<?php

namespace App\Exports;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PurchaseReportPdfExport
{
    protected $startDate;
    protected $endDate;
    protected $isCheckedItemSummary;
    protected $searchCategoryID;
    protected $searchStockID;
    protected $searchSupplierID;

    public function __construct(
        $startDate,
        $endDate,
        $isCheckedItemSummary,
        $searchCategoryID,
        $searchStockID,
        $searchSupplierID
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->isCheckedItemSummary = $isCheckedItemSummary;
        $this->searchCategoryID = $searchCategoryID;
        $this->searchStockID = $searchStockID;
        $this->searchSupplierID = $searchSupplierID;
    }

    /* =====================================================
       PDF DOWNLOAD
    ====================================================== */
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

        $html = view('exports.purchase_report_pdf', $data)->render();
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'purchase_report_' . Carbon::now()->format('Ymd_His') . '.pdf',
            'D'
        );
    }

    /* =====================================================
       REPORT DATA
    ====================================================== */
    private function getReportData()
    {
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate   = Carbon::parse($this->endDate)->endOfDay();

        /* =====================================================
           1️ ITEM SUMMARY
        ====================================================== */
        if ($this->isCheckedItemSummary == 1) {
            $query = PurchaseDetail::select(
                'P.purchase_voucher_number',
                'items.item_name',
                'MC.menu_category_name',
                'units.unit_name',
                'purchase_details.unit_cost',
                'purchase_details.quantity',
                'purchase_details.expire_date',
                'P.purchase_date',
                'S.supplier_name'
            )
                ->join('purchases as P', 'purchase_details.purchase_id', '=', 'P.purchase_id')
                ->join('menu_items as items', 'purchase_details.item_id', '=', 'items.item_id')
                ->join('units', 'units.unit_id', '=', 'items.unit_id')
                ->join('menu_categories as MC', 'items.sub_category_id', '=', 'MC.category_id')
                ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                ->whereBetween('P.purchase_date', [$startDate, $endDate])
                ->where('P.is_delete', 0);

            if ($this->searchCategoryID != 0) {
                $query->where('items.sub_category_id', $this->searchCategoryID);
            }

            if ($this->searchStockID != 0) {
                $query->where('items.item_id', $this->searchStockID);
            }

            if ($this->searchSupplierID != 0) {
                $query->where('P.supplier_id', $this->searchSupplierID);
            }

            return [
                'isItemSummary' => true,
                'rows' => $query->get(),
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ];
        }

        /* =====================================================
           2️ PURCHASE SUMMARY (DEFAULT)
        ====================================================== */
        $rows = Purchase::select('purchase_voucher_number', 'purchase_date', 'due_date', 'purchases.remark', 'purchases.total_amount', 'S.supplier_name')
            ->selectRaw('IFNULL(SUM(PL.voucher_discount),0)+purchases.total_item_discount as discount_amount')
            ->selectRaw('IFNULL(SUM(PL.transport_charges), 0) as transport_charges')
            ->selectRaw('IFNULL(SUM(PL.other_charges), 0) as other_charges')
            ->selectRaw('IFNULL(SUM(PL.tax), 0) as tax')
            ->selectRaw('IFNULL(SUM(PL.paid_amount), 0) as paid_amount')
            ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase_payment_logs as PL', 'PL.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchases.is_delete', 0)
            ->where('purchase_date', '>=', date($startDate))
            ->where('purchase_date', '<=', date($endDate))
            ->groupBy(['purchase_voucher_number', 'purchase_date', 'due_date', 'remark', 'total_amount', 'total_item_discount', 'supplier_name'])
            ->get();

        /* ---------- TOTALS ---------- */
        $totalAmount = $rows->sum('total_amount');

        $totalNetAmount = $rows->sum(function ($r) {
            return
                $r->total_amount +
                $r->transport_charges +
                $r->other_charges +
                $r->tax -
                $r->discount_amount;
        });

        $totalPaid = $rows->sum('paid_amount');
        $totalBalance = $totalNetAmount - $totalPaid;

        return [
            'isItemSummary' => false,
            'rows' => $rows,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'totalAmount' => number_format($totalAmount, 0),
            'totalNetAmount' => number_format($totalNetAmount, 0),
            'totalPaid' => number_format($totalPaid, 0),
            'totalBalance' => number_format($totalBalance, 0),
        ];
    }
}
