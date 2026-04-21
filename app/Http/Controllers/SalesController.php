<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Table;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\MenuItem;
use App\Models\MemberCard;
use App\Models\PaymentType;
use App\Models\SalesDetail;
use App\Models\ItemDiscount;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\ItemSellingPrice;
use App\Models\MenuCategory;
use App\Models\StockReceiveDetail;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

class SalesController extends Controller
{
    //direct sale list page
    public function saleListPage(Request $req)
    {
        $dailyPrintDate = $req->query('dailyPrintDate')
            ? Carbon::parse($req->query('dailyPrintDate'))->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');

        // dd($dailyPrintDate);

        $todayDate = Carbon::now()->format('Y-m-d');
        $sales = Sales::select('*', 'waiter.name as waiter_name', 'cashier.name as cashier_name', 'PT.payment_type_name as payment_type_name')
            ->join('tables', 'sales.table_id', 'tables.table_id')
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
            ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
            ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
            ->join('payment_types as PT', 'sales.payment_type_id', 'PT.payment_type_id')
            ->where('sales.is_delete', '0')
            ->whereDate('sales.created_at', $dailyPrintDate)
            ->get()
            ->sortByDesc('sale_id')
            ->toArray();

        // dd($sales);

        // return view('admin.store.sale.sale_list', compact('sales'));
        return view('admin.store.sale.sale_list', compact('sales', 'dailyPrintDate'));
    }

    public function orderInvoice(Request $req)
    {
        $sale = Sales::get()->last();

        if ($sale == null || $sale == "null") {
            $saleLastID = 1;
        } else {
            $saleLastID = $sale->sale_id + 1;
        }

        $tableID = $req->orderTableID;
        $tableOrderNumber = $req->orderTableOrderNumber;

        $table = Table::where('table_id', $tableID)->get()->toArray();
        $customers = Customer::get()->toArray();
        $employees = Employee::get()->toArray();
        $paymentTypes = PaymentType::get()->toArray();
        $orders = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();

        $orderID = $orders->order_id;
        // $waiter = User::where('id', $orders->ordered_by)->first();
        $waiters = User::where('user_role_id', 4)
            ->where('is_discontinued', 0)
            ->get()
            ->toArray();
        $cashier = User::where('id', Auth::user()->id)->first();
        $orderDetails = OrderDetails::select(
            '*',
            'order_details.created_at as order_detail_created_at',
            'ISP.item_selling_price as item_price'
        )
            ->where('order_id', $orderID)
            ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
            ->get()
            ->toArray();
        // dd($orderDetails);

        return view('admin.store.sale.order_invoice', compact('table', 'tableOrderNumber', 'customers', 'waiters', 'cashier', 'orderDetails', 'saleLastID', 'paymentTypes'));
    }

    public function saleOrderDetails($sale_id, Request $req)
    {
        $date = $req->query('date');

        $sale = Sales::select('sales.*', 'PT.payment_type_name as payment_type_name')
            ->join('payment_types as PT', 'sales.payment_type_id', 'PT.payment_type_id')
            ->where('sales.sale_id', $sale_id)->first();
        // dd($sale);

        $saleID = $sale->sale_id;
        $tableID = $sale->table_id;
        $tableOrderNumber = $sale->table_order_number;
        $saleVoucherNumber = $sale->sale_voucher_number;
        $customerID = $sale->customer_id;
        $waiterID = $sale->waiter_id;
        $cashierID = $sale->cashier_id;

        $table = Table::where('table_id', $tableID)->get()->toArray();

        $customer = Customer::where('customer_id', $customerID)->first();
        $waiter = User::where('id', $waiterID)->first();
        $cashier = User::where('id', $cashierID)->first();


        $employees = Employee::get()->toArray();
        $saleDetails = SalesDetail::where('sale_id', $saleID)
            ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'sales_details.ordered_by', 'U.id')
            ->get()
            ->toArray();

        // dd($saleDetails);
        return view('admin.store.sale.sale_order_details', compact('table', 'tableOrderNumber', 'customer', 'waiter', 'cashier', 'sale', 'saleDetails', 'saleVoucherNumber', 'date'));
    }

    public function getMemberCardByMemberCardCode(Request $req)
    {
        $memberCardCode = $req->query('memberCard');
        $currentDateTime = date('Y-m-d H:i:s');

        $memberCard = MemberCard::where('member_card_code', $memberCardCode)
            ->join('member_card_types', 'member_cards.member_card_type_id', 'member_card_types.member_card_type_id')
            ->where('expire_date', '>=', $currentDateTime)
            ->get();

        return response()->json($memberCard);
    }

    public function getCouponCardByCouponCardCode(Request $req)
    {
        $couponCardCode = $req->query('couponCard');
        $currentDateTime = date('Y-m-d H:i:s');

        $couponCard = Coupon::where('coupon_code', $couponCardCode)
            ->where('expire_date', '>=', $currentDateTime)
            ->get();

        return response()->json($couponCard);
    }

    public function checkOut(Request $req)
    {
        try {
            // dd($req->all());
            DB::beginTransaction();

            $tableID = $req->table_id;
            $tableOrderNumber = $req->table_order_number;

            $orderMaster = Order::where('table_id', $tableID)
                ->where('table_order_number', $tableOrderNumber)
                ->first();

            if ($orderMaster != null) {
                $orderDate = $orderMaster->created_at->format('Y-m-d H:i:s');
                $orderID = $orderMaster->order_id;
                $orderDetails = OrderDetails::where('order_id', $orderID)->get();

                $totalPromoPrice = 0;
                $totalSellingPrice = 0;
                foreach ($orderDetails as $detail) {
                    if ($detail['promotion_price'] != null) {
                        $totalPromoPrice += $detail['promotion_price'] * $detail['quantity'];
                    }
                    $sellingPrice = ItemSellingPrice::where('item_id', $detail['item_id'])->first();
                    $totalSellingPrice += $sellingPrice->item_selling_price *  $detail['quantity'];
                }
                if ($totalPromoPrice > 0) {
                    $totalPromo = $totalSellingPrice - $totalPromoPrice;
                } else {
                    $totalPromo = 0;
                }
            } else {
                $orderDate = date('Y-m-d H:i:s');
            }

            $lastSale = Sales::orderByDesc('sale_id')->first();
            $lastVoucherNumber = $lastSale?->sale_voucher_number;

            if ($lastVoucherNumber && $req->invoice_number == $lastVoucherNumber) {
                $lastInvoice = $lastVoucherNumber;
                $numericPart = (int) str_replace('INV-', '', $lastInvoice);
                $invoiceNumber = 'INV-' . ($numericPart + 1);
            } else {
                $invoiceNumber = $req->invoice_number;
            }


            $data = $this->addSaleData($req, $orderDate, $invoiceNumber); // not use totalPromo
            $result = Sales::create($data);
            // dd($result);
            $saleID = $result->id;

            $orderDetails = OrderDetails::where('order_id', $orderID)->get();

            foreach ($orderDetails as $detail) {

                $item_id = $detail['item_id'];
                $item_info = MenuItem::select('*', 'item_selling_prices.unit_cost as unit_cost', 'item_selling_prices.item_selling_price as item_selling_price')
                    ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                    ->where('menu_items.item_id', $item_id)
                    ->where('menu_items.is_discontinued', 0)
                    ->where('menu_items.is_deleted', 0)
                    ->first();

                $resultList = StockIssueController::getStockBalance($item_id, $item_info->unit_id);

                $storeQty = $resultList[0];

                $stockItemList = $resultList[1];
                $sale_qty = (float)$detail['quantity']; // Ensure this is a float
                if ($storeQty > 0) {
                    foreach ($stockItemList as $key => $stockItem) {
                        $balanceQty = (float)$stockItem->receiveQty + (float)$stockItem->purchaseQty; // Convert to float
                        if ($balanceQty >= $sale_qty) {
                            // Insert remaining sale quantity in one row if balanceQty can fulfill it
                            $sale_detail_entry = [
                                'sale_id' => $saleID,
                                'item_id' => $detail['item_id'],
                                'batch_number' => $stockItem->batch_number,
                                'quantity' => $sale_qty, // Full sale quantity
                                'unit_cost' => $item_info->unit_cost,
                                'sale_price' => $item_info->item_selling_price,
                                'promotion_price' => $detail['promotion_price'],
                                'unit_id' => $item_info->unit_id,
                                'remark' => $detail['remark'],
                                'expire_date' => $stockItem->expire_date,
                                'is_foc' => $detail['is_foc'],
                                'sale_type' => $stockItem->type,
                                'ordered_by' => $detail['ordered_by'],
                                'order_time' => $detail['created_at'],
                            ];
                            SalesDetail::create($sale_detail_entry);
                            $sale_qty = 0;
                            break; // Sale fulfilled, exit loop
                        } else {
                            // Insert first row with available balance quantity
                            $sale_detail_entry = [
                                'sale_id' => $saleID,
                                'item_id' => $detail['item_id'],
                                'batch_number' => $stockItem->batch_number,
                                'quantity' => $balanceQty, // Use available stock quantity
                                'unit_cost' => $item_info->unit_cost,
                                'sale_price' => $item_info->item_selling_price,
                                'promotion_price' => $detail['promotion_price'],
                                'unit_id' => $item_info->unit_id,
                                'remark' => $detail['remark'],
                                'expire_date' => $stockItem->expire_date,
                                'is_foc' => $detail['is_foc'],
                                'sale_type' => $stockItem->type,
                                'ordered_by' => $detail['ordered_by'],
                                'order_time' => $detail['created_at'],
                            ];
                            SalesDetail::create($sale_detail_entry);

                            // Update sale quantity after subtracting balanceQty
                            $sale_qty -= $balanceQty;
                        }
                    }
                    if ($sale_qty > 0) {
                        $sale_detail_entry = [
                            'sale_id' => $saleID,
                            'item_id' => $detail['item_id'],
                            'batch_number' => 0,
                            'quantity' => $sale_qty, // Use available stock quantity
                            'unit_cost' => $item_info->unit_cost,
                            'sale_price' => $item_info->item_selling_price,
                            'promotion_price' => $detail['promotion_price'],
                            'unit_id' => $item_info->unit_id,
                            'remark' => $detail['remark'],
                            'expire_date' => null,
                            'is_foc' => $detail['is_foc'],
                            'sale_type' => null,
                            'ordered_by' => $detail['ordered_by'],
                            'order_time' => $detail['created_at'],
                        ];
                        SalesDetail::create($sale_detail_entry);
                    }
                } else {
                    $sale_detail_entry = [
                        'sale_id' => $saleID,
                        'item_id' => $detail['item_id'],
                        'batch_number' => 0,
                        'quantity' => $sale_qty, // Use available stock quantity
                        'unit_cost' => $item_info->unit_cost,
                        'sale_price' => $item_info->item_selling_price,
                        'promotion_price' => $detail['promotion_price'],
                        'unit_id' => $item_info->unit_id,
                        'remark' => $detail['remark'],
                        'expire_date' => null,
                        'is_foc' => $detail['is_foc'],
                        'sale_type' => null,
                        'ordered_by' => $detail['ordered_by'],
                        'order_time' => $detail['created_at'],
                    ];
                    SalesDetail::create($sale_detail_entry);
                }
            }

            Order::where('table_id', $tableID)
                ->where('table_order_number', $tableOrderNumber)
                ->delete();
            OrderDetails::where('order_id', $orderID)->delete();

            DB::commit();
            //return redirect()->route('store#dineInPage');
            return response()->json($saleID);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function prePrint(Request $req)
    {
        $tableID = $req->query('tableID');
        $tableOrderNumber = $req->query('tableOrderNumber');

        $orderMaster = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();
        $orderID = $orderMaster->order_id;

        return response()->json($orderID);
    }

    // daily sale print
    public function dailyPrint(Request $req)
    {
        $dailyPrintDate = Carbon::parse($req->query('dailyPrintDate'))->format('Y-m-d');

        $saleDetails = SalesDetail::selectRaw('IFNULL(SUM(sales_details.quantity), 0) as quantity')
            ->selectRaw('IFNULL(SUM(sales_details.quantity * sales_details.sale_price), 0) as sale_price')
            ->selectRaw('MI.item_name as item_name')
            ->selectRaw('MC.menu_category_name as menu_category_name')
            ->selectRaw('MC.category_id as menu_category_id')
            ->selectRaw('MNC.main_category_id as main_category_id')
            ->selectRaw('MNC.main_category_name as main_category_name')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->leftJoin('menu_items as MI', 'sales_details.item_id', '=', 'MI.item_id')
            ->leftJoin('menu_categories as MC', 'MI.sub_category_id', '=', 'MC.category_id')
            ->leftJoin('main_categories as MNC', 'MI.main_category_id', '=', 'MNC.main_category_id')
            ->where('sales.is_delete', 0)
            ->whereDate('sales_details.created_at', $dailyPrintDate)
            ->where('sales_details.is_foc', 0)
            ->groupBy(['MI.item_name', 'MC.menu_category_name', 'MC.category_id', 'MNC.main_category_id', 'MNC.main_category_name'])
            ->orderBy('MC.category_id') // Add this line to order by menu_category_id
            ->get()
            ->toArray();

        // Restructure the result
        $saleDetailsNestedDatas = [];

        foreach ($saleDetails as $detail) {
            $menuCategoryId = $detail['menu_category_id'];
            $menuCategoryName = $detail['menu_category_name'];

            if (!isset($saleDetailsNestedDatas[$menuCategoryId])) {
                $saleDetailsNestedDatas[$menuCategoryId] = [
                    'menu_category_name' => $menuCategoryName,
                    'menu_category_id' => $menuCategoryId,
                    'main_category_id' => $detail['main_category_id'],
                    'main_category_name' => $detail['main_category_name'],
                    'items' => [],
                ];
            }

            $saleDetailsNestedDatas[$menuCategoryId]['items'][] = [
                'quantity' => $detail['quantity'],
                'sale_price' => $detail['sale_price'],
                'item_name' => $detail['item_name'],
            ];
        }

        // Reset array keys for clean output
        $saleDetailsNestedDatas = array_values($saleDetailsNestedDatas);

        $focSaleDetails = SalesDetail::selectRaw('IFNULL(SUM(sales_details.quantity), 0) as quantity')
            ->selectRaw('IFNULL(SUM(sales_details.quantity * sales_details.sale_price), 0) as sale_price')
            ->selectRaw('MI.item_name as item_name')
            ->selectRaw('MC.menu_category_name as menu_category_name')
            ->selectRaw('MC.category_id as menu_category_id')
            ->selectRaw('MNC.main_category_id as main_category_id')
            ->selectRaw('MNC.main_category_name as main_category_name')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->join('menu_items as MI', 'sales_details.item_id', '=', 'MI.item_id')
            ->join('menu_categories as MC', 'MI.sub_category_id', '=', 'MC.category_id')
            ->leftJoin('main_categories as MNC', 'MI.main_category_id', '=', 'MNC.main_category_id')
            ->where('sales.is_delete', 0)
            ->whereDate('sales_details.created_at', $dailyPrintDate)
            ->where('sales_details.is_foc', 1)
            ->groupBy(['MI.item_name', 'MC.menu_category_name', 'MC.category_id', 'MNC.main_category_id', 'MNC.main_category_name'])
            ->orderBy('MC.category_id') // Add this line to order by menu_category_id
            ->get()
            ->toArray();

        return response(view('prints.sale.daily_sale_print', compact('saleDetailsNestedDatas', 'dailyPrintDate', 'focSaleDetails')))
            ->header('Content-Type', 'text/html');
    }

    //Sale delete
    public function deleteSale(Request $req)
    {

        $saleID = $req->delete_sale_id;
        $data = $this->deleteSaleData($req);

        Sales::where('sale_id', $saleID)->update($data);
        return redirect()->route('sale#saleListPage');
    }

    //Private Functions
    //add sale data
    private function addSaleData($req, $orderDate, $invoiceNumber)
    {
        // $voucher_foc = 0;
        // if($req->voucher_foc) {
        //     $voucher_foc = $req->voucher_foc;
        // }

        $data = [
            'sale_voucher_number' => $invoiceNumber,
            'table_id' => $req->table_id,
            'table_order_number' => $req->table_order_number,
            'customer_id' => $req->customer_id === '' ? null : $req->customer_id,
            'waiter_id' => $req->waiter_id,
            'cashier_id' => $req->cashier_id,
            'order_date' => $orderDate,
            'total_amount' => $req->total_amount,
            'total_item_promo_amount' => $req->item_discount_amt, // instead of $totalPromo
            'service_charges_amount' => $req->service_charges_amount,
            'service_charges_percent' => $req->service_charges_percent,
            'tax_amount' => $req->tax_amount,
            'tax_percent' => $req->tax_percent,
            'voucher_discount_amount' => $req->voucher_discount_amount,
            'voucher_discount_percent' => $req->voucher_discount_percent,
            'member_card_code' => $req->member_card,
            'member_card_amount' => $req->member_card_discount_amount,
            'member_card_percent' => $req->member_card_discount_percent,
            'coupon_card_code' => $req->coupon_card,
            'coupon_card_amount' => $req->coupon_card_discount_amount,
            'coupon_card_percent' => $req->coupon_card_discount_percent,
            'net_amount' => $req->net_amount,
            'payment_type_id' => $req->payment_type,
            'online_paid' => $req->online_paid,
            'paid_amount' => $req->paid_amount,
            'balance_amount' => $req->balance,
            'change_amount' => $req->change,
            'delivery_charges' => 0,
            'voucher_foc' => $req->voucher_foc,
            'is_delete' => 0,
        ];
        return $data;
    }

    //add sale details data
    private function addSaleDetailsData($detail, $saleID, $item_info,  $expireDate)
    {
        $data = [
            'sale_id' => $saleID,
            'item_id' => $detail->item_id,
            // 'batch_number' => $batch_number,
            'quantity' => $detail->quantity,
            'average_cost' => $item_info->average_cost,
            'sale_price' => $item_info->item_selling_price,
            'unit_id' => $item_info->unit_id,
            'remark' => $detail->remark,
            'expire_date' => $expireDate,
            'is_foc' => $detail->is_foc,
            'sale_type' => '',
            'ordered_by' => $detail->ordered_by,
            'order_time' => $detail->created_at,
        ];
        return $data;
    }
    //delete sale data
    private function deleteSaleData($req)
    {
        $data = [
            'delete_reason' => $req->sale_delete_reason,
            'is_delete' => "1",
            'deleted_by' => Auth::user()->id,
        ];
        return $data;
    }
}
