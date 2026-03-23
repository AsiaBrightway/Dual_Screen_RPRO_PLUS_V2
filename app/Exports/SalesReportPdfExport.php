<?php

namespace App\Exports;

use App\Models\Sales;
use App\Models\SalesDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class SalesReportPdfExport
{
    protected $startDate;
    protected $endDate;
    protected $isCheckedItemSummary;
    protected $searchCategoryID;
    protected $searchStockID;
    protected $isFOCSummary;
    protected $isDiscountSummary;
    protected $isKPaySummary;
    protected $isDeletedSummary;

    public function __construct(
        $startDate,
        $endDate,
        $isCheckedItemSummary,
        $searchCategoryID,
        $searchStockID,
        $isFOCSummary,
        $isDiscountSummary,
        $isKPaySummary,
        $isDeletedSummary
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->isCheckedItemSummary = $isCheckedItemSummary;
        $this->searchCategoryID = $searchCategoryID;
        $this->searchStockID = $searchStockID;
        $this->isFOCSummary = $isFOCSummary;
        $this->isDiscountSummary = $isDiscountSummary;
        $this->isKPaySummary = $isKPaySummary;
        $this->isDeletedSummary = $isDeletedSummary;
    }

    public function download()
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
                    'R'  => 'NotoSansMyanmar-Regular.ttf',
                    'B'  => 'NotoSansMyanmar-Bold.ttf',
                ],
                'dejavusans' => [
                    'R' => 'dejavu-sans.book.ttf',
                    'B' => 'dejavu-sans.bold.ttf',
                ],
            ],
            'default_font' => 'dejavusans',
        ]);

        $html = view('exports.sales_report_pdf', $data)->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('sales_report_' . Carbon::now()->format('Ymd_His') . '.pdf', 'D');
    }

    private function getReportData()
    {
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        // Default values sent to Blade
        $base = [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'isItemSummary' => false,
            'isFOCSummary' => $this->isFOCSummary,
            'isDiscountSummary' => $this->isDiscountSummary,
            'isKPaySummary' => $this->isKPaySummary,
            'isDeletedSummary' => $this->isDeletedSummary,
            'rows' => collect(),
            'totalAmount' => 0,
            'totalOnline' => 0,
            'totalPromo' => 0,
            'totalServiceCharges' => 0,
            'totalTax' => 0,
        ];

        // --------------------------------------------------
        // 1) ITEM SUMMARY (all / by category / by stock item)
        // --------------------------------------------------
        if ($this->isCheckedItemSummary == 1) {

            $query = SalesDetail::select(
                'S.sale_voucher_number',
                'items1.item_name',
                'MC.menu_category_name',
                'units.unit_name',
                'ISP.item_selling_price',
                'ISP.unit_cost',
                'sales_details.quantity',
                'sales_details.is_foc',
                'sales_details.order_time'
            )
                ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
                ->join('units', 'units.unit_id', '=', 'items1.unit_id')
                ->join('menu_categories as MC', 'items1.sub_category_id', '=', 'MC.category_id')
                ->join('sales as S', 'sales_details.sale_id', '=', 'S.sale_id')
                ->join('item_selling_prices as ISP', 'items1.item_id', '=', 'ISP.item_id')
                ->whereBetween('sales_details.created_at', [$startDate, $endDate]);

            // Filter 1: By Category
            if ($this->searchCategoryID != 0) {
                $query->where('items1.sub_category_id', $this->searchCategoryID);
            }

            // Filter 2: By Stock Item
            if ($this->searchStockID != 0) {
                $query->where('items1.item_id', $this->searchStockID);
            }

            $rows = $query->get();

            $totalAmount = $rows->sum(fn($x) => (float)$x->item_selling_price * (float)$x->quantity);
            $totalCost = $rows->sum(fn($x) => (float)$x->unit_cost * (float)$x->quantity);
            return array_merge($base, [
                'isItemSummary' => true,
                'rows' => $rows,
                'totalAmount' => $totalAmount,
                'totalCost' => $totalCost,
            ]);
        } else {
            $query = Sales::select(
                'sale_voucher_number',
                'floors.floor_name',
                'tables.table_name',
                'table_order_number',
                'customers.customer_name',
                'waiter.name as waiter_name',
                'cashier.name as cashier_name',
                'order_date',
                'total_amount',
                'total_item_promo_amount',
                'voucher_discount_amount',
                'service_charges_amount',
                'tax_amount',
                'net_amount',
                'paid_amount',
                'balance_amount',
                'change_amount',
                'delivery_charges',
                'online_paid',
                'sales.created_at',
                'sales.voucher_discount_percent',
                'sales.payment_type_id',
                'sales.is_delete',
                'sales.sale_id'
            )
                ->join('tables', 'sales.table_id', 'tables.table_id')
                ->join('floors', 'tables.floor_id', 'floors.floor_id')
                ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
                ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
                ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
                ->whereBetween('sales.created_at', [$startDate, $endDate]);

            // FOC 100%
            if ($this->isFOCSummary) {
                $query->where('sales.is_delete', 0)
                    ->where('sales.voucher_foc', '=', 1);
            }
            // Discount 1 to 99%
            elseif ($this->isDiscountSummary) {
                $query->where('sales.is_delete', 0)
                    ->whereBetween('sales.voucher_discount_percent', [1, 99]);
            }
            // K-Pay or Online
            elseif ($this->isKPaySummary) {
                $query->where('sales.is_delete', 0)
                    ->where('sales.payment_type_id', '!=', 1);
            }
            // Deleted vouchers
            elseif ($this->isDeletedSummary) {
                $query->where('sales.is_delete', 1);
            }
            // Default
            else {
                $query->where('sales.is_delete', 0);
            }

            $rows = $query->orderByDesc('sale_id')->get();
            $onlinePaid = $rows->sum('online_paid');
            $cashPayment = $rows->sum('paid_amount');
            $totalNetAmount = $rows->sum('net_amount');
            $saleIds = $rows->pluck('sale_id')->all();
            $totalItemPromo = $rows->sum('total_item_promo_amount');
            $totalVoucher   = $rows->sum('voucher_discount_amount');
            $totalCost = SalesDetail::select(
                DB::raw('SUM(ISP.unit_cost * sales_details.quantity) AS total_cost')
            )
                ->join('item_selling_prices as ISP', 'sales_details.item_id', '=', 'ISP.item_id')
                ->whereIn('sales_details.sale_id', $saleIds)
                ->value('total_cost') ?? 0;
                
            $totalDiscount = $totalVoucher + $totalItemPromo;
            $totalNetProfit = $totalNetAmount - $totalCost;
            
            return [
                'isItemSummary' => false,
                'rows' => $rows,
                'totalAmount' => number_format($rows->sum('total_amount'), 0),
                'totalOnline' => number_format($onlinePaid, 0),
                'totalDiscount' => number_format($totalDiscount, 0),
                'totalServiceCharges' => number_format($rows->sum('service_charges_amount'), 0),
                'totalTax' => number_format($rows->sum('tax_amount'), 0),
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'cashPayment' => number_format($cashPayment, 0),
                'totalCost' => number_format($totalCost, 0),
                'totalNetAmount' => number_format($totalNetAmount, 0),
                'totalNetProfit' => number_format($totalNetProfit, 0),

            ];
        }
    }
}
