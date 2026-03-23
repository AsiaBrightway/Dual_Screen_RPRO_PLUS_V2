<?php

namespace App\Exports;

use App\Models\Sales;
use App\Models\SalesDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private int $rowIndex = 0;

    private float $totalAmount = 0.0;
    private float $totalCashPayment = 0.0;
    private float $totalOnlinePayment = 0.0;
    private float $totalPromo = 0.0;
    private float $totalServiceCharge = 0.0;
    private float $totalTax = 0.0;

    private float $totalNetAmount = 0.0;
    private float $totalCost = 0.0;

    private float $totalAmountByItemSummary = 0.0;

    /**
    * @var string|null
    */
    protected $startDate;
    protected $endDate;

    protected $isCheckedItemSummary;
    protected $searchCategoryID;
    protected $searchStockID;
    protected $isFOCSummary;
    protected $isDiscountSummary;
    protected $isKPaySummary;
    protected $isDeletedSummary;

    /**
     * Store the dates from the controller.
     * We receive $startDate and $endDate from the form.
     */
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
    )
    {
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

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $totalSalesCost = SalesDetail::join('menu_items as item', 'item.item_id', 'sales_details.item_id')
            ->join('item_selling_prices as ISP', 'item.item_id', 'ISP.item_id')
            ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
            ->whereBetween('sales_details.created_at', [$startDate, $endDate])
            ->when($this->searchCategoryID != 0, function ($query) {
                return $query->where('item.sub_category_id', $this->searchCategoryID);
            })
            ->when($this->searchStockID != 0, function ($query) {
                return $query->where('item.item_id', $this->searchStockID);
            })
            ->when($this->isFOCSummary != 0, function ($query) {
                return $query->where('S.is_delete', '0')->where('S.voucher_foc', '=', 1);
            })
            ->when($this->isDiscountSummary != 0, function ($query) {
                return $query->where('S.is_delete', '0')->whereBetween('S.voucher_discount_percent', [1, 99]);
            })
            ->when($this->isKPaySummary != 0, function ($query) {
                return $query->where('S.is_delete', '0')->where('S.payment_type_id', '!=', 1);
            })
            ->when($this->isDeletedSummary != 0, function ($query) {
                return $query->where('S.is_delete', '1');
            })
            ->when($this->isFOCSummary == 0 && $this->isDiscountSummary == 0 && $this->isKPaySummary == 0 && $this->isDeletedSummary == 0, function ($query) {
                return $query->where('S.is_delete', '0');
            })
            ->sum(DB::raw('ISP.unit_cost * sales_details.quantity'));

        $this->totalCost = $totalSalesCost;
        
        if ($this->isCheckedItemSummary == 1) {
            
            // This query is for the "Item Summary"
            $query = SalesDetail::select('S.sale_voucher_number', 'items1.item_name', 'MC.menu_category_name', 'units.unit_name',
                'ISP.item_selling_price', 'ISP.unit_cost', 'sales_details.promotion_price', // <-- Use table prefix
                'sales_details.quantity', 'sales_details.is_foc', 'sales_details.order_time') // <-- Use table prefix
                ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
                ->join('units', 'units.unit_id', '=', 'items1.unit_id')
                ->join('menu_categories as MC', 'items1.sub_category_id', 'MC.category_id')
                ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
                ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
                ->whereBetween('sales_details.created_at', [$startDate, $endDate]);

            if ($this->searchCategoryID != 0) {
                $query->where('items1.sub_category_id', $this->searchCategoryID);
            }

            if ($this->searchStockID != 0) {
                $query->where('items1.item_id', $this->searchStockID);
            }

            return $query->get();

        } else {
            // This query is for the "Sales Report"
            $query = Sales::select('sale_voucher_number', 'floors.floor_name', 'tables.table_name', 'table_order_number',
                'customers.customer_name','waiter.name as waiter_name', 'cashier.name as cashier_name', 'order_date', 'total_amount',
                'total_item_promo_amount', 'service_charges_amount', 'tax_amount', 'voucher_discount_amount', 'net_amount', 'paid_amount', 'balance_amount', 'change_amount', 'delivery_charges', 'online_paid', 'sales.created_at')
                ->join('tables', 'sales.table_id', 'tables.table_id')
                ->join('floors', 'tables.floor_id', 'floors.floor_id')
                ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
                ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
                ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
                ->whereBetween('sales.created_at', [$startDate, $endDate]);

            if ($this->isFOCSummary != 0) {
                $query->where('sales.is_delete', '0')
                      ->where('sales.voucher_foc', '=', 1);
            } elseif ($this->isDiscountSummary != 0) {
                $query->where('sales.is_delete', '0')
                      ->whereBetween('sales.voucher_discount_percent', [1, 99]);
            } elseif ($this->isKPaySummary != 0) {
                $query->where('sales.is_delete', '0')
                      ->where('sales.payment_type_id', '!=', 1);
            } elseif ($this->isDeletedSummary != 0) {
                $query->where('sales.is_delete', '1');
            } else {
                $query->where('sales.is_delete', '0');
            }

            return $query->orderByDesc('sale_id')->get();
        }
    }

    public function map($sale): array
    {
        $no = ++$this->rowIndex;

        // $this->totalAmountByItemSummary += (float) ( ((float)$sale->item_selling_price * (float)$sale->quantity) ?? 0 );

        if ($this->isCheckedItemSummary == 1) {
            // Note: $row is a SalesDetail result
            
            // Calculate amount: (price * qty)
            $amount = $sale->promotion_price != null && $sale->promotion_price > 0
                ? ($sale->promotion_price * $sale->quantity)
                : ($sale->item_selling_price * $sale->quantity);
            $this->totalAmountByItemSummary += $amount;
            
            return [
                $no,
                $sale->sale_voucher_number,
                $sale->item_name,
                $sale->menu_category_name,
                $sale->unit_name,
                $sale->unit_cost,
                $sale->item_selling_price,
                $sale->promotion_price, // this could be $sale->item_selling_price - $sale->promotion_price
                $sale->quantity,
                $amount,
                $sale->is_foc == 1 ? 'Yes' : 'No', // Format FOC
                Carbon::parse($sale->order_time)->format('d-M-y'), // Use order_time
            ];
        }

        $this->totalAmount += ($sale['total_amount'] ?? 0);
        $this->totalCashPayment += ($sale['paid_amount'] ?? 0);
        $this->totalOnlinePayment += ($sale['online_paid'] ?? 0);
        $this->totalServiceCharge += ($sale['service_charges_amount'] ?? 0);
        $this->totalTax += ($sale['tax_amount'] ?? 0);
        $this->totalNetAmount += ($sale['net_amount'] ?? 0);
        
        if ($sale['total_item_promo_amount'] != null && $sale['total_item_promo_amount'] != 0) {
            $this->totalPromo += abs($sale['total_item_promo_amount']);
        } else {
            $this->totalPromo += 0;
        }

        if ($sale['voucher_discount_amount'] != null && $sale['voucher_discount_amount'] != 0) {
            $this->totalPromo += $sale['voucher_discount_amount'];
        } else {
            $this->totalPromo += 0;
        }

        // Map for "Sales Report" (original map)
        return [
            $no,
            $sale['sale_voucher_number'],
            $sale['floor_name'],
            $sale['table_name'],
            $sale['table_order_number'],
            $sale['customer_name'],
            $sale['waiter_name'],
            $sale['cashier_name'],
            Carbon::parse($sale['created_at'])->format('d-M-y'),
            $sale['total_amount'],
            abs($sale['total_item_promo_amount']),
            $sale['voucher_discount_amount'],
            $sale['service_charges_amount'],
            $sale['tax_amount'],
            $sale['net_amount'],
            $sale['paid_amount'],
            $sale['balance_amount'],
            $sale['change_amount'],
            $sale['delivery_charges']
        ];
        
    }

    public function headings(): array
    {
        if ($this->isCheckedItemSummary == 1) {
            // Headings for "Item Summary"
            return [
                'No',
                'Voucher No',
                'Item Name',
                'Category',
                'Unit',
                'Unit Cost',
                'Sale Price',
                'Item Promo', // promotion_price
                'Qty',
                'Amount',
                'FOC',
                'Order Date',
            ];
        }

        // Headings for "Sales Report" (original headings)
        return [
            'No',
            'Voucher No',
            'Floor Name',
            'Table Name',
            'Table Order No',
            'Customer Name',
            'Waiter Name',
            'Cashier Name',
            'Order Date',
            'Total Amount',
            'Item Promo',
            'Voucher Promo',
            'Service',
            'Tax',
            'Net Amount',
            'Paid Amount',
            'Balance',
            'Charge',
            'Delivery Charge'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                if ($this->isCheckedItemSummary == 1) {
                    
                    // --- LOGIC FOR ITEM SUMMARY ---
                    $sheet->getStyle('A1:L1')->getFont()->setBold(true); // 12 columns (A-L)
                    
                    // Add one total row
                    $totalAmountRow = $this->rowIndex + 3;
                    $totalCostRow = $this->rowIndex + 4;
                    $totalNetProfitRow = $this->rowIndex + 5;

                    // Merge cells up to 'Amount' column (J). Label in A-I, Value in J.
                    $sheet->mergeCells("A{$totalAmountRow}:K{$totalAmountRow}");
                    $sheet->mergeCells("A{$totalCostRow}:K{$totalCostRow}");
                    $sheet->mergeCells("A{$totalNetProfitRow}:K{$totalNetProfitRow}");

                    $sheet->setCellValue("A{$totalAmountRow}", 'Total Amount');
                    $sheet->setCellValue("L{$totalAmountRow}", $this->totalAmountByItemSummary);

                    $sheet->setCellValue("A{$totalCostRow}", 'Total Cost');
                    $sheet->setCellValue("L{$totalCostRow}", $this->totalCost);

                    $sheet->setCellValue("A{$totalCostRow}", 'Total Cost');
                    $sheet->setCellValue("L{$totalCostRow}", $this->totalCost);

                    $sheet->setCellValue("A{$totalNetProfitRow}", 'Total Net Profit');
                    $sheet->setCellValue("L{$totalNetProfitRow}", ($this->totalAmountByItemSummary - $this->totalCost));

                    // Style the total row
                    $sheet->getStyle("A{$totalAmountRow}:L{$totalAmountRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalCostRow}:L{$totalCostRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalNetProfitRow}:L{$totalNetProfitRow}")->getFont()->setBold(true);

                    $sheet->getStyle("A{$totalAmountRow}:K{$totalAmountRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalCostRow}:K{$totalCostRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalNetProfitRow}:K{$totalNetProfitRow}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("L{$totalAmountRow}")
                          ->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("L{$totalCostRow}")
                          ->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("L{$totalNetProfitRow}")
                          ->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    
                    // Auto-size columns A-L
                    foreach (range('A', 'L') as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }

                } else {
                    
                    // --- LOGIC FOR SALES REPORT (original code) ---
                    $sheet->getStyle('A1:S1')->getFont()->setBold(true); // 19 columns (A-S)

                    // Add three total rows
                    $totalAmountRow = $this->rowIndex + 3;
                    $totalCashRow = $this->rowIndex + 4;
                    $totalOnlineRow = $this->rowIndex + 5;
                    $totalServiceChargeRow = $this->rowIndex + 6;
                    $totalTaxRow = $this->rowIndex + 7;
                    $totalPromoRow = $this->rowIndex + 8;

                    $sheet->mergeCells("O{$totalAmountRow}:R{$totalAmountRow}");
                    $sheet->mergeCells("O{$totalCashRow}:R{$totalCashRow}");
                    $sheet->mergeCells("O{$totalOnlineRow}:R{$totalOnlineRow}");
                    $sheet->mergeCells("O{$totalServiceChargeRow}:R{$totalServiceChargeRow}");
                    $sheet->mergeCells("O{$totalTaxRow}:R{$totalTaxRow}");
                    $sheet->mergeCells("O{$totalPromoRow}:R{$totalPromoRow}");

                    $sheet->mergeCells("A{$totalAmountRow}:M{$totalAmountRow}");
                    $sheet->mergeCells("A{$totalCashRow}:M{$totalCashRow}");
                    $sheet->mergeCells("A{$totalOnlineRow}:M{$totalOnlineRow}");
                    $sheet->mergeCells("A{$totalPromoRow}:M{$totalPromoRow}");
                    $sheet->mergeCells("A{$totalServiceChargeRow}:M{$totalServiceChargeRow}");
                    $sheet->mergeCells("A{$totalTaxRow}:M{$totalTaxRow}");


                    $sheet->setCellValue("A{$totalAmountRow}", 'Total Amount');
                    $sheet->setCellValue("N{$totalAmountRow}", $this->totalAmount);

                    $sheet->setCellValue("A{$totalCashRow}", 'Total Cash Payment');
                    $sheet->setCellValue("N{$totalCashRow}", $this->totalCashPayment);

                    $sheet->setCellValue("A{$totalOnlineRow}", 'Total Online Payment');
                    $sheet->setCellValue("N{$totalOnlineRow}", $this->totalOnlinePayment);

                    $sheet->setCellValue("A{$totalPromoRow}", 'Total Discount');
                    $sheet->setCellValue("N{$totalPromoRow}", $this->totalPromo);

                    $sheet->setCellValue("A{$totalServiceChargeRow}", 'Total Service Charge');
                    $sheet->setCellValue("N{$totalServiceChargeRow}", $this->totalServiceCharge);

                    $sheet->setCellValue("A{$totalTaxRow}", 'Total Tax');
                    $sheet->setCellValue("N{$totalTaxRow}", $this->totalTax);


                    $sheet->setCellValue("O{$totalServiceChargeRow}", 'Total Net Amount');
                    $sheet->setCellValue("S{$totalServiceChargeRow}", $this->totalNetAmount);

                    $sheet->setCellValue("O{$totalTaxRow}", 'Total Cost');
                    $sheet->setCellValue("S{$totalTaxRow}", $this->totalCost);

                    $sheet->setCellValue("O{$totalPromoRow}", 'Net Profit');
                    $sheet->setCellValue("S{$totalPromoRow}", ($this->totalNetAmount - $this->totalCost));

                    $sheet->getStyle("A{$totalAmountRow}:S{$totalAmountRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalCashRow}:S{$totalCashRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalOnlineRow}:S{$totalOnlineRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalPromoRow}:S{$totalPromoRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalServiceChargeRow}:S{$totalServiceChargeRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalTaxRow}:S{$totalTaxRow}")->getFont()->setBold(true);

                    $sheet->getStyle("A{$totalAmountRow}:P{$totalAmountRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalCashRow}:P{$totalCashRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalOnlineRow}:P{$totalOnlineRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalPromoRow}:P{$totalPromoRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalServiceChargeRow}:P{$totalServiceChargeRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("A{$totalTaxRow}:P{$totalTaxRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("N{$totalAmountRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("N{$totalCashRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("N{$totalOnlineRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("N{$totalPromoRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("N{$totalServiceChargeRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle("N{$totalTaxRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

                    // Auto-size columns A-S
                    foreach (range('A', 'S') as $col) {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                    }
                }
                
            },
        ];
    }
}
