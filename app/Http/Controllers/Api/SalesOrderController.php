<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StockIssueController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\PaymentType;
use App\Models\OrderDetails;
use App\Models\MemberCard;
use App\Models\Coupon;
use App\Models\ItemSellingPrice;
use App\Models\MenuItem;
use App\Models\SalesDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function salesOrder(Request $req)
    {

        $sale = Sales::latest()->first();
        $saleLastID = $sale ? $sale->sale_id : 1;

        $userID = "$req->userID";

        $tableID = "$req->orderTableID";
        $tableOrderNumber = $req->orderTableOrderNumber;

        $table = Table::where('table_id', $tableID)->first();
        $customers = Customer::all();
        $employees = Employee::all();
        $paymentTypes = PaymentType::all();
        $orders = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();

        if (!$orders) {
        return response()->json(['message' => 'Order not found'], 404);
        }

        $orderID = $orders->order_id;
        $waiters = User::where('user_role_id', 4)->get();
        $cashier = User::where('id', $userID)->first();
        $orderDetails = OrderDetails::select('*', 'ISP.item_selling_price as item_price')
            ->where('order_id', $orderID)
            ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
            ->get()
            ->toArray();

        return response()->json([
            'table' => $table,
            'table_order_number' => $tableOrderNumber,
            'customers' => $customers,
            'waiters' => $waiters,
            'cashier' => $cashier,
            'order_details' => $orderDetails,
            'sale_last_id' => $saleLastID,
            'payment_types' => $paymentTypes,
        ]);
    }

    public function getMemberCardByMemberCardCode(Request $req)
    {
        $req->validate([
            'memberCard' => 'required|string'
        ]);

        $memberCardCode = $req->query('memberCard');
        $currentDateTime = now(); // Cleaner than date()

        $memberCard = MemberCard::where('member_card_code', $memberCardCode)
            ->join('member_card_types', 'member_cards.member_card_type_id', '=', 'member_card_types.member_card_type_id')
            ->where('expire_date', '>=', $currentDateTime)
            ->first();

        if (!$memberCard) {
            return response()->json([
                'success' => false,
                'message' => 'Member card not found or expired.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $memberCard
        ]);
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
            // return redirect()->route('store#dineInPage');
            // return response()->json($saleID);
            return response()->json([
            'message' => 'Checkout was successful.',
        ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout failed',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

       //Private Functions
    //add sale data
    private function addSaleData($req, $orderDate, $invoiceNumber)
    {
        $data = [
            'sale_voucher_number' => $invoiceNumber,
            'table_id' => $req->table_id,
            'table_order_number' => $req->table_order_number,
            'customer_id' => $req->customer_id,
            'waiter_id' => $req->waiter_id,
            'cashier_id' => $req->cashier_id,
            'order_date' => $orderDate,
            'total_amount' => $req->total_amount,
            'total_item_promo_amount' => $req->item_discount_amt,
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
    public function prePrint(Request $req)
    {
        $tableID = $req->query('tableID');
        $tableOrderNumber = $req->query('tableOrderNumber');

        $orderMaster = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();

        if (!$orderMaster) {
            return response()->json([
                'message' => 'No order found for this table and order number.',
            ], 404);
        }

        $orderID = $orderMaster->order_id;

        return response()->json([
            'order_id' => $orderID,
        ]);
    }

    public function preOrderPrint(Request $req)
    {
        $order = Order::select('orders.*')
            ->where('orders.order_id', $req->order_id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        $orderDetails = OrderDetails::selectRaw('IFNULL(SUM(order_details.quantity), 0) as quantity')
            ->selectRaw('items1.item_name, order_details.is_foc, order_details.item_id, ISP.item_selling_price as item_price, items1.unit_id')
            ->where('order_id', $req->order_id)
            ->whereNull('promotion_price')
            ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', '=', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', '=', 'ISP.item_id')
            ->groupBy([
                'items1.item_name',
                'order_details.is_foc',
                'order_details.order_id',
                'order_details.item_id',
                'ISP.item_selling_price',
                'items1.unit_id'
            ])
            ->get();

        $promotionOrderDetails = OrderDetails::select('order_details.*', 'ISP.item_selling_price as item_price')
            ->where('order_id', $req->order_id)
            ->whereNotNull('promotion_price')
            ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', '=', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', '=', 'ISP.item_id')
            ->get();

        return response()->json([
            'invoice_number' => $req->invoiceNumber,
            'order' => $order,
            'order_details' => $orderDetails,
            'promotion_order_details' => $promotionOrderDetails
        ]);
    }
}
