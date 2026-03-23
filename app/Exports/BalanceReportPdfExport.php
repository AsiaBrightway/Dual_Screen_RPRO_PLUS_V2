<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class BalanceReportPdfExport
{
    protected string $searchDate;
    protected int $searchCategoryID;

    public function __construct($searchDate, $searchCategoryID = 0)
    {
        $this->searchDate = $searchDate
            ? Carbon::parse($searchDate)->toDateString()
            : Carbon::now()->toDateString();
        $this->searchCategoryID = (int) $searchCategoryID;
    }
    public function download(string $mode = 'D')
    {
        $data = $this->getReportData();
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'notosansmyanmar' => [
                    'R' => 'NotoSansMyanmar-Regular.ttf',
                    'B' => 'NotoSansMyanmar-Bold.ttf',
                ],
                'dejavusans' => [
                    'R' => 'dejavu-sans.book.ttf',
                    'B' => 'dejavu-sans.bold.ttf',
                ],
            ],
            'default_font' => 'dejavusans',
        ]);

        $html = view('exports.balance_report_pdf', $data)->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'balance_report_' . Carbon::now()->format('Ymd_His') . '.pdf',
            $mode
        );
    }

    /* =====================================================
        MAIN REPORT DATA
    ====================================================== */
    private function getReportData(): array
    {
        $itemIds = $this->getActiveItemIds($this->searchDate);
        $rows = collect();

        foreach ($itemIds as $itemId) {
            $row = $this->calculateItemFIFOBalance($itemId, $this->searchDate);

            if (!$row) continue;

            if (
                $this->searchCategoryID > 0 &&
                $row['sub_category_id'] != $this->searchCategoryID
            ) {
                continue;
            }

            $rows->push((object) $row);
        }

        return [
            'rows' => $rows,
            'searchDate' => $this->searchDate,
        ];
    }

    /* =====================================================
        ACTIVE ITEM IDS (ANY ACTIVITY <= DATE)
    ====================================================== */
    private function getActiveItemIds(string $date): array
    {
        $purchaseIds = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->whereDate('purchases.purchase_date', '=', $date)
            ->where(fn($q) => $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0))
            ->pluck('purchase_details.item_id');

        $receiveIds = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->whereDate('stock_receives.receive_date', '=', $date)
            ->where(fn($q) => $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0))
            ->pluck('stock_receive_details.item_id');

        $soldIds = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->whereDate('sales.order_date', '=', $date)
            ->where(fn($q) => $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0))
            ->pluck('sales_details.item_id');

        $issuedIds = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->whereDate('stock_issues.issue_date', '=', $date)
            ->where(fn($q) => $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0))
            ->pluck('stock_issue_details.item_id');

        return array_values(array_unique(array_merge(
            $purchaseIds->toArray(),
            $receiveIds->toArray(),
            $soldIds->toArray(),
            $issuedIds->toArray()
        )));
    }

    /* =====================================================
        FIFO BALANCE CALCULATION (AS OF DATE)
    ====================================================== */
    private function calculateItemFIFOBalance(int $itemId, string $date): ?array
    {
        $item = DB::table('menu_items')
            ->leftJoin('menu_categories as MC', 'menu_items.sub_category_id', '=', 'MC.category_id')
            ->leftJoin('units', 'units.unit_id', '=', 'menu_items.unit_id')
            ->leftJoin('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', $itemId)
            ->select(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_items.sub_category_id',
                'MC.menu_category_name',
                'units.unit_name',
                DB::raw('MAX(ISP.unit_cost) as isp_unit_cost'),
                DB::raw('MAX(ISP.item_selling_price) as sale_price')
            )
            ->groupBy(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_items.sub_category_id',
                'MC.menu_category_name',
                'units.unit_name'
            )
            ->first();

        if (!$item) return null;

        $incoming = $this->getIncomingBatches($itemId, $date);
        $outQty = $this->getOutgoingQuantity($itemId, $date);
        $remaining = $this->applyFIFOConsumption($incoming, $outQty);
        $activity = $this->getDateActivity($itemId, $date);

        $balanceQty = array_sum(array_column($remaining, 'remaining_qty'));
        $balanceValue = array_sum(array_map(
            fn($b) => $b['remaining_qty'] * $b['unit_cost'],
            $remaining
        ));

        $weightedCost = $balanceQty > 0
            ? $balanceValue / $balanceQty
            : ($item->isp_unit_cost ?? 0);

        return [
            'item_id' => $item->item_id,
            'item_name' => $item->item_name,
            'sub_category_id' => $item->sub_category_id,
            'menu_category_name' => $item->menu_category_name,
            'unit_name' => $item->unit_name,

            'purchased_qty' => $activity['purchased_qty'],
            'purchased_value' => $activity['purchased_value'],
            'received_qty' => $activity['received_qty'],
            'received_value' => $activity['received_value'],
            'sold_qty' => $activity['sold_qty'],
            'issued_qty' => $activity['issued_qty'],

            'total_in_qty' => $activity['purchased_qty'] + $activity['received_qty'],
            'total_out_qty' => $activity['sold_qty'] + $activity['issued_qty'],

            'balance_qty' => $balanceQty,
            'weighted_unit_cost' => round($weightedCost, 2),
            'amount' => round($balanceValue, 2),
            'isp_unit_cost' => floor($item->isp_unit_cost ?? 0),
            'sale_price' => floor($item->sale_price ?? 0),
        ];
    }

    /* =====================================================
        HELPERS
    ====================================================== */

    private function getIncomingBatches(int $itemId, string $date): array
    {
        $purchases = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchase_details.item_id', $itemId)
            ->whereDate('purchases.purchase_date', '<=', $date)
            ->where(fn($q) => $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0))
            ->select(
                'purchase_details.batch_number',
                'purchase_details.quantity',
                'purchase_details.unit_cost',
                'purchases.purchase_date as tx_date'
            )
            ->get();

        $receives = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->where('stock_receive_details.item_id', $itemId)
            ->whereDate('stock_receives.receive_date', '<=', $date)
            ->where(fn($q) => $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0))
            ->select(
                'stock_receive_details.batch_number',
                'stock_receive_details.quantity',
                'stock_receive_details.unit_cost',
                'stock_receives.receive_date as tx_date'
            )
            ->get();

        return $purchases->concat($receives)
            ->sortBy([
                ['tx_date', 'asc'],
                ['batch_number', 'asc'],
            ])
            ->map(fn($b) => [
                'batch_number' => $b->batch_number,
                'quantity' => $b->quantity,
                'unit_cost' => $b->unit_cost,
            ])
            ->values()
            ->toArray();
    }

    private function getOutgoingQuantity(int $itemId, string $date): int
    {
        $sold = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->where('sales_details.item_id', $itemId)
            ->whereDate('sales.order_date', '=', $date)
            ->where(fn($q) => $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0))
            ->sum('sales_details.quantity');

        $issued = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->where('stock_issue_details.item_id', $itemId)
            ->whereDate('stock_issues.issue_date', '=', $date)
            ->where(fn($q) => $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0))
            ->sum('stock_issue_details.quantity');

        return $sold + $issued;
    }

    private function applyFIFOConsumption(array $incoming, int $outQty): array
    {
        $remaining = [];

        foreach ($incoming as $batch) {
            if ($outQty <= 0) {
                $remaining[] = [
                    'remaining_qty' => $batch['quantity'],
                    'unit_cost' => $batch['unit_cost'],
                ];
            } elseif ($outQty >= $batch['quantity']) {
                $outQty -= $batch['quantity'];
            } else {
                $remaining[] = [
                    'remaining_qty' => $batch['quantity'] - $outQty,
                    'unit_cost' => $batch['unit_cost'],
                ];
                $outQty = 0;
            }
        }

        return $remaining;
    }

    private function getDateActivity(int $itemId, string $date): array
    {
        $purchase = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchase_details.item_id', $itemId)
            ->whereDate('purchases.purchase_date', $date)
            ->where(fn($q) => $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0))
            ->selectRaw('COALESCE(SUM(quantity),0) qty, COALESCE(SUM(quantity * unit_cost),0) value')
            ->first();

        $receive = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->where('stock_receive_details.item_id', $itemId)
            ->whereDate('stock_receives.receive_date', $date)
            ->where(fn($q) => $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0))
            ->selectRaw('COALESCE(SUM(quantity),0) qty, COALESCE(SUM(quantity * unit_cost),0) value')
            ->first();

        $sold = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->where('sales_details.item_id', $itemId)
            ->whereDate('sales.order_date', $date)
            ->where(fn($q) => $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0))
            ->sum('quantity');

        $issued = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->where('stock_issue_details.item_id', $itemId)
            ->whereDate('stock_issues.issue_date', $date)
            ->where(fn($q) => $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0))
            ->sum('quantity');

        return [
            'purchased_qty' => $purchase->qty ?? 0,
            'purchased_value' => $purchase->value ?? 0,
            'received_qty' => $receive->qty ?? 0,
            'received_value' => $receive->value ?? 0,
            'sold_qty' => $sold ?? 0,
            'issued_qty' => $issued ?? 0,
        ];
    }
}
