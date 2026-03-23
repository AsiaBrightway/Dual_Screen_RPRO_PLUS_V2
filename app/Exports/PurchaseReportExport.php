<?php

namespace App\Exports;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PurchaseReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private int $rowIndex = 0;
    private float $totalNetAmount = 0;
    private float $totalAmount = 0;
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
        $this->isCheckedItemSummary = (int) $isCheckedItemSummary;
        $this->searchCategoryID = (int) $searchCategoryID;
        $this->searchStockID = (int) $searchStockID;
        $this->searchSupplierID = (int) $searchSupplierID;
    }

    /* =====================================================
       COLLECTION
    ====================================================== */
    public function collection()
    {
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate   = Carbon::parse($this->endDate)->endOfDay();

        if ($this->isCheckedItemSummary == 1) {
            return PurchaseDetail::select(
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
                ->where('P.is_delete', 0)
                ->when(
                    $this->searchCategoryID !== 0,
                    fn($q) =>
                    $q->where('items.sub_category_id', $this->searchCategoryID)
                )
                ->when(
                    $this->searchStockID !== 0,
                    fn($q) =>
                    $q->where('items.item_id', $this->searchStockID)
                )
                ->when(
                    $this->searchSupplierID !== 0,
                    fn($q) =>
                    $q->where('P.supplier_id', $this->searchSupplierID)
                )
                ->get();
        }

        /* ================= PURCHASE SUMMARY (FULL GROUP SAFE) ================= */
        return Purchase::select(
            'purchases.purchase_date',
            'purchases.purchase_voucher_number',
            'purchases.due_date',
            'purchases.remark',
            'purchases.total_amount',
            'S.supplier_name'
        )
            ->selectRaw('IFNULL(SUM(PL.voucher_discount),0) + purchases.total_item_discount AS discount_amount')
            ->selectRaw('IFNULL(SUM(PL.transport_charges),0) AS transport_charges')
            ->selectRaw('IFNULL(SUM(PL.other_charges),0) AS other_charges')
            ->selectRaw('IFNULL(SUM(PL.tax),0) AS tax')
            ->selectRaw('IFNULL(SUM(PL.paid_amount),0) AS paid_amount')
            ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase_payment_logs as PL', 'PL.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchases.is_delete', 0)
            ->whereBetween('purchases.purchase_date', [$startDate, $endDate])
            ->when(
                $this->searchSupplierID !== 0,
                fn($q) =>
                $q->where('purchases.supplier_id', $this->searchSupplierID)
            )
            ->groupBy(
                'purchases.purchase_voucher_number',
                'purchases.purchase_date',
                'purchases.due_date',
                'purchases.remark',
                'purchases.total_amount',
                'purchases.total_item_discount',
                'S.supplier_name'
            )
            ->get();
    }

    public function map($row): array
    {
        $no = ++$this->rowIndex;

        if ($this->isCheckedItemSummary === 1) {

            $amount = $row->unit_cost * $row->quantity;
            $this->totalAmount += $amount;
            return [
                $no,
                $row->purchase_voucher_number,
                $row->supplier_name,
                Carbon::parse($row->purchase_date)->format('d-M-y'),
                Carbon::parse($row->expire_date)->format('d-M-y'),
                $row->item_name,
                $row->menu_category_name,
                $row->unit_name,
                $row->quantity,
                $row->unit_cost,
                $amount,
            ];
        }

        $net = $row->total_amount
            + $row->transport_charges
            + $row->other_charges
            + $row->tax
            - $row->discount_amount;
        $this->totalNetAmount += $net;

        return [
            $no,
            $row->purchase_voucher_number,
            $row->supplier_name,
            Carbon::parse($row->purchase_date)->format('d-M-y'),
            Carbon::parse($row->due_date)->format('d-M-y'),
            $row->total_amount,
            $row->transport_charges,
            $row->other_charges,
            $row->tax,
            $row->discount_amount,
            $row->paid_amount,
            $net - $row->paid_amount,
            $net,
            $row->remark,
        ];
    }

    /* =====================================================
       HEADINGS
    ====================================================== */
    public function headings(): array
    {
        return $this->isCheckedItemSummary === 1
            ? [
                'No',
                'Voucher No',
                'Supplier Name',
                'Purchase Date',
                'Expire Date',
                'Item Name',
                'Category',
                'Unit',
                'Qty',
                'Unit Cost',
                'Amount'
            ]
            : [
                'No',
                'Voucher No',
                'Supplier Name',
                'Purchase Date',
                'Due Date',
                'Total Amount',
                'Transport Charges',
                'Other Charges',
                'Tax',
                'Discount',
                'Paid Amount',
                'Balance',
                'Net Amount',
                'Remark'
            ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $totalRow = $this->rowIndex + 3;

                /* ================= HEADER BOLD ================= */
                $sheet->getStyle('A1:N1')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('A1:N1')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /* ================= TOTAL ROW ================= */
                if ($this->isCheckedItemSummary === 1) {
                    $sheet->mergeCells("A{$totalRow}:J{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", 'Total Amount');
                    $sheet->setCellValue("K{$totalRow}", $this->totalAmount);
                } else {
                    $sheet->mergeCells("A{$totalRow}:L{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", 'Total Net Amount');
                    $sheet->setCellValue("M{$totalRow}", $this->totalNetAmount);
                }

                $sheet->getStyle("A{$totalRow}:M{$totalRow}")
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle("A{$totalRow}:M{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
        ];
    }
}
