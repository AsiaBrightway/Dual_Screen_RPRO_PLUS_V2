<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sales;
use App\Models\MenuItem;
use App\Models\SalesDetail;
use App\Models\OrderDetails;
use App\Models\MainCategory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController extends Controller
{
    //
    public function saleOrderPrint($sale_id)
    {
        $shop_name = config('shop_name.shop_name');
        $phone = config('shop_name.phone');
        $sale = Sales::select('sales.*', 'PT.payment_type_name as payment_type_name', 'tbl.table_name as table_name',)
            ->join('payment_types as PT', 'sales.payment_type_id', 'PT.payment_type_id')
            ->join('tables as tbl', 'sales.table_id', 'tbl.table_id')
            ->where('sales.sale_id', $sale_id)->first();
        //     $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
        //         ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
        //         ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
        //         ->where('orders.order_id', $order_id)->first();
        $saleDetails = SalesDetail::selectRaw('IFNULL(SUM(sales_details.quantity), 0) as quantity')
            ->selectRaw('items1.item_name,sales_details.is_foc, sales_details.item_id, sales_details.sale_price as item_price, items1.unit_id')
            ->where('sale_id', $sale_id)
            ->whereNull('promotion_price')
            ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'sales_details.ordered_by', '=', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', '=', 'ISP.item_id')
            ->groupBy(['items1.item_name', 'sales_details.is_foc', 'sales_details.sale_id', 'sales_details.item_id', 'sales_details.sale_price', 'items1.unit_id'])
            ->get()
            ->toArray();

        // $promotionSaleDetails = SalesDetail::select('*', 'ISP.item_selling_price as item_price')
        //     ->where('sale_id', $sale_id)
        //     ->where('promotion_price', '!=', null)
        //     ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
        //     ->join('units', 'units.unit_id', '=', 'items1.unit_id')
        //     ->join('users as U', 'sales_details.ordered_by', 'U.id')
        //     ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
        //     ->get()
        //     ->toArray();

        $promotionSaleDetails = SalesDetail::selectRaw('IFNULL(SUM(sales_details.quantity), 0) as quantity')
            ->selectRaw('items1.item_name, sales_details.is_foc, sales_details.item_id, sales_details.promotion_price, items1.unit_id')
            ->where('sale_id', $sale_id)
            ->whereNotNull('promotion_price')
            ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'sales_details.ordered_by', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
            ->groupBy([
                'items1.item_name',
                'sales_details.is_foc',
                'sales_details.sale_id',
                'sales_details.item_id',
                'sales_details.promotion_price',
                'items1.unit_id'
            ])
            ->get()
            ->toArray();

        // dd($promotionSaleDetails);
        // return response()->json([
        //     'sale' => $sale,
        //     'saleDetails' => $saleDetails,
        //     'promotionSaleDetails' => $promotionSaleDetails
        // ]);

        // Normal Print
        // return view('prints.sale.sale_order_print', compact('sale', 'saleDetails', 'promotionSaleDetails', 'shop_name'));

        // Print for Dual Screen
        return view('prints.sale.dual_sale_order_print', compact('sale', 'saleDetails', 'promotionSaleDetails', 'shop_name', 'phone'));
    }

    public function preOrderPrint($order_id, $invoiceNumber)
    {
        $shop_name = config('shop_name.shop_name');
        $phone = config('shop_name.phone');
        // $order = Order::select('orders.*')
        //     ->where('orders.order_id', $order_id)->first();
        $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
            ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
            ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
            ->where('orders.order_id', $order_id)->first();

        $orderDetails = OrderDetails::selectRaw('IFNULL(SUM(order_details.quantity), 0) as quantity')
            ->selectRaw('items1.item_name,order_details.is_foc, order_details.item_id, ISP.item_selling_price as item_price, items1.unit_id')
            ->where('order_id', $order_id)
            ->whereNull('promotion_price')
            ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', '=', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', '=', 'ISP.item_id')
            ->groupBy(['items1.item_name', 'order_details.is_foc', 'order_details.order_id', 'order_details.item_id', 'ISP.item_selling_price', 'items1.unit_id'])
            ->get()
            ->toArray();

        // $promotionOrderDetails = OrderDetails::select('*', 'ISP.item_selling_price as item_price')
        //     ->where('order_id', $order_id)
        //     ->where('promotion_price', '!=', null)
        //     ->join('menu_items as items1', 'order_details.item_id', '=', 'items1.item_id')
        //     ->join('units', 'units.unit_id', '=', 'items1.unit_id')
        //     ->join('users as U', 'order_details.ordered_by', 'U.id')
        //     ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
        //     ->get()
        //     ->toArray();

        $promotionOrderDetails = OrderDetails::selectRaw('IFNULL(SUM(order_details.quantity), 0) as quantity')
            ->selectRaw('items1.item_name, order_details.is_foc, order_details.item_id, order_details.promotion_price as promotion_price, items1.unit_id')
            ->where('order_id', $order_id)
            ->whereNotNull('promotion_price')
            ->join('menu_items as items1', 'order_details.item_id', '=', "items1.item_id")
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('users as U', 'order_details.ordered_by', 'U.id')
            ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
            ->groupBy([
                'items1.item_name',
                'order_details.is_foc',
                'order_details.order_id',
                'order_details.item_id',
                'order_details.promotion_price',
                'items1.unit_id'
            ])->get()
            ->toArray(); //preOrderPrint

        return view('prints.sale.pre_order_print', compact('order', 'orderDetails', 'promotionOrderDetails', 'invoiceNumber', 'shop_name', 'phone'));
    }
    public function checkOutPrint($table_id, $table_order_number)
    {
        $order = Order::where('table_id', $table_id)
            ->where('table_order_number', $table_order_number)->first();
        $orderID = $order['order_id'];


        return view('prints.sale.sale_order_print', compact('sale', 'saleDetails'));
    }

    // public function orderPrint($orderID, $filteredOrderItemsEncoded)
    // {
    //     $orderItems = json_decode(urldecode($filteredOrderItemsEncoded), true);
    //     $order_id = $orderID;
    //     $orderPrintItems = [];
    //     foreach ($orderItems as $key => $orderItem) {
    //         $item_id = $orderItem['orderItemID'];
    //         $item = MenuItem::select('*', 'U.unit_name as unit_name')
    //             ->join('units as U', 'menu_items.unit_id', 'U.unit_id')
    //             ->where('item_id', $item_id)
    //             ->first();
    //         $data = [
    //             'item_name' => $item['item_name'],
    //             'unit_name' => $item['unit_name'],
    //             'quantity' => $orderItem['orderItemQuantity'],
    //             'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //         ];
    //         array_push($orderPrintItems, $data);
    //     }

    //     $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
    //         ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
    //         ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
    //         ->where('orders.order_id', $order_id)->first();

    //     $user_id = Auth::user()->id;
    //     $user = User::select('*')
    //         ->where('id', $user_id)->first();
    //     $orderedBy = $user['name'];
    //     return view('prints.order.order_print', compact('order', 'orderPrintItems', 'orderedBy'));
    // }

    // public function samplePrint(Request $request)
    // {
    //     $orderItems = json_decode($request->input('filteredOrder'), true);
    //     $order_id = $request->input('orderID');
    //     $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
    //         ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
    //         ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
    //         ->where('orders.order_id', $order_id)->first();

    //     $user_id = Auth::user()->id;
    //     $user = User::select('*')
    //         ->where('id', $user_id)->first();
    //     $orderedBy = $user['name'];

    //     $kitchenOrderPrintItems = [];
    //     $bbqOrderPrintItems = [];
    //     $beerOrderPrintItems = [];
    //     $otherOrderPrintItems = [];
    //     $noodleOrderPrintItems = [];
    //     $cuisineOrderPrintItems = [];

    //     foreach ($orderItems as $key => $orderItem) {
    //         $item_id = $orderItem['orderItemID'];
    //         $item = MenuItem::select('*', 'U.unit_name as unit_name')
    //             ->join('units as U', 'menu_items.unit_id', 'U.unit_id')
    //             ->where('item_id', $item_id)
    //             ->first();
    //         if ($item['main_category_id'] == 1) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($kitchenOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 2) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($bbqOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 3) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($beerOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 4) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($otherOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 5) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($noodleOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 6) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $orderItem['orderItemQuantity'],
    //                 'remark' => $orderItem['orderItemRemark'] ? $orderItem['orderItemRemark'] : '',
    //             ];
    //             array_push($cuisineOrderPrintItems, $data);
    //         }
    //     }
    //     return view('prints.order.order_print', compact('order', 'kitchenOrderPrintItems', 'bbqOrderPrintItems', 'beerOrderPrintItems', 'otherOrderPrintItems', 'noodleOrderPrintItems', 'cuisineOrderPrintItems', 'orderedBy'));
    // }

    // public function sampleDeletePrint(Request $request)
    // {
    //     try {

    //         $orderItem = json_decode($request->input('orderDetailDelete'), true);

    //         $order_id = $request->input('orderID');

    //         $order = $request->input('order');

    //         $user_id = Auth::user()->id;
    //         $user = User::select('*')
    //             ->where('id', $user_id)->first();
    //         $orderedBy = $user['name'];

    //         $kitchenOrderPrintItems = [];
    //         $bbqOrderPrintItems = [];
    //         $beerOrderPrintItems = [];
    //         $otherOrderPrintItems = [];
    //         $noodleOrderPrintItems = [];
    //         $cuisineOrderPrintItems = [];

    //         // foreach ($orderItems as $key => $orderItem) {

    //         $item_id = $orderItem['item_id'];

    //         $item = MenuItem::select('*', 'U.unit_name as unit_name')
    //             ->join('units as U', 'menu_items.unit_id', 'U.unit_id')
    //             ->where('item_id', $item_id)
    //             ->first();

    //         $quantity = $orderItem['quantity'];
    //         $remark = $orderItem['remark'];
    //         if ($item['main_category_id'] == 1) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($kitchenOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 2) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($bbqOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 3) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($beerOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 4) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($otherOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 5) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($noodleOrderPrintItems, $data);
    //         } else if ($item['main_category_id'] == 6) {
    //             $data = [
    //                 'item_name' => $item['item_name'],
    //                 'unit_name' => $item['unit_name'],
    //                 'quantity' => $quantity,
    //                 'remark' => $remark ? $remark : '',
    //             ];
    //             array_push($cuisineOrderPrintItems, $data);
    //         }
    //         // }

    //         return view('prints.order.delete_order_print', compact('order', 'kitchenOrderPrintItems', 'bbqOrderPrintItems', 'beerOrderPrintItems', 'otherOrderPrintItems', 'noodleOrderPrintItems', 'cuisineOrderPrintItems', 'orderedBy'));
    //     } catch (Exception $e) {
    //         Log::error('Error during sampleDeletePrint: ' . $e->getMessage());
    //         return response()->json(['error' => 'An error occurred while printing the delete order.'], 500);
    //     }
    // }

    public function samplePrint(Request $request)
    {
        $orderItems = json_decode($request->input('filteredOrder'), true);
        $order_id = $request->input('orderID');

        $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
            ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
            ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
            ->where('orders.order_id', $order_id)->first();

        $user_id = Auth::user()->id;
        $orderedBy = User::where('id', $user_id)->value('name');

        // Get all active main categories
        $mainCategories = MainCategory::where('is_deleted', 0)
            ->orderBy('main_category_id')
            ->get();

        // Initialize grouped items array
        $groupedItems = [];
        foreach ($mainCategories as $category) {
            $groupedItems[$category->main_category_id] = [
                'name' => $category->main_category_name,
                'items' => []
            ];
        }

        // Loop through order items and assign to main category
        foreach ($orderItems as $orderItem) {
            $item = MenuItem::select('menu_items.*', 'U.unit_name', 'menu_items.main_category_id')
                ->leftjoin('units as U', 'menu_items.unit_id', 'U.unit_id')
                ->where('item_id', $orderItem['orderItemID'])
                ->first();

            if (!$item) continue;

            $data = [
                'item_name' => $item->item_name,
                'unit_name' => $item->unit_name,
                'quantity' => $orderItem['orderItemQuantity'],
                'remark' => $orderItem['orderItemRemark'] ?? '',
            ];

            $groupedItems[$item->main_category_id]['items'][] = $data;
        }
        // dd($groupedItems);
        return view('prints.order.order_print', compact('order', 'groupedItems', 'orderedBy'));
    }

    public function sampleDeletePrint(Request $request)
    {
        try {

            $orderItem = json_decode($request->input('orderDetailDelete'), true);
            $order_id = $request->input('orderID');
            $order = $request->input('order');
            $orderedBy = User::where('id', Auth::id())->value('name');

            // Load all active categories
            $mainCategories = MainCategory::where('is_deleted', 0)
                ->orderBy('main_category_id')
                ->get();

            $groupedItems = [];
            foreach ($mainCategories as $mc) {
                $groupedItems[$mc->main_category_id] = [
                    'name' => $mc->main_category_name,
                    'items' => []
                ];
            }
            $item_id = $orderItem['item_id'];

            $item = MenuItem::select('menu_items.*', 'U.unit_name')
                ->join('units as U', 'menu_items.unit_id', 'U.unit_id')
                ->where('item_id', $item_id)
                ->first();

            if ($item) {

                $data = [
                    'item_name' => $item->item_name,
                    'unit_name' => $item->unit_name,
                    'quantity' => $orderItem['quantity'],
                    'remark' => $orderItem['remark'] ?? '',
                ];

                // Push into correct category
                $groupedItems[$item->main_category_id]['items'][] = $data;
            }
            return view(
                'prints.order.delete_order_print',
                compact('order', 'groupedItems', 'orderedBy')
            );
        } catch (Exception $e) {
            Log::error('Error during sampleDeletePrint: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while printing the delete order.'
            ], 500);
        }
    }

    /**
     * Return the QZ Tray public certificate for silent printing.
     */
    public function qzCertificate()
    {
        $certPath = storage_path('app/qz-tray/digital-certificate.txt');

        if (!file_exists($certPath)) {
            return response('Certificate not found. Run: php artisan qz:setup', 500);
        }

        return response(file_get_contents($certPath))
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Sign a QZ Tray request using the private key for silent printing.
     */
    public function qzSign(Request $request)
    {
        $toSign = $request->input('request');
        $keyPath = storage_path('app/qz-tray/private-key.pem');

        if (!file_exists($keyPath)) {
            return response('Private key not found. Run: php artisan qz:setup', 500);
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($keyPath));

        if (!$privateKey) {
            return response('Failed to load private key.', 500);
        }

        $signature = '';
        openssl_sign($toSign, $signature, $privateKey, OPENSSL_ALGO_SHA512);

        return response(base64_encode($signature))
            ->header('Content-Type', 'text/plain');
    }

    public function salesReportPrint(Request $req)
    {
        // Get date range - default to today if no dates provided
        $startDate = $req->filled('startDate')
            ? Carbon::parse($req->input('startDate'))->startOfDay()
            : Carbon::now()->startOfDay();
        $endDate = $req->filled('endDate')
            ? Carbon::parse($req->input('endDate'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Get filter parameters
        $searchCategoryID = (int) $req->input('searchCategoryID', 0);
        $searchStockID = (int) $req->input('searchStockID', 0);
        $isCheckedItemSummary = (int) $req->input('isCheckedItemSummary', 0);
        $isFOCSummary = (int) $req->input('isFOCSummary', 0);
        $isDiscountSummary = (int) $req->input('isDiscountSummary', 0);
        $isKPaySummary = (int) $req->input('isKPaySummary', 0);
        $isDeletedSummary = (int) $req->input('isDeletedSummary', 0);

        // dd($isDeletedSummary);

        // Check if this is an item summary report
        if ($isCheckedItemSummary == 1) {
            $sales = SalesDetail::select(
                'S.sale_voucher_number',
                'items1.item_name',
                'MC.menu_category_name',
                'units.unit_name',
                'ISP.item_selling_price',
                'ISP.unit_cost',
                'promotion_price',
                'quantity',
                'is_foc',
                'order_time',
                'total_amount',
                'online_paid',
                'total_item_promo_amount',
                'voucher_discount_amount'
            )
                ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
                ->join('units', 'units.unit_id', '=', 'items1.unit_id')
                ->join('menu_categories as MC', 'items1.sub_category_id', 'MC.category_id')
                ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
                ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
                ->whereBetween('sales_details.created_at', [$startDate, $endDate])
                ->when($searchCategoryID != 0, function ($query) use ($searchCategoryID) {
                    return $query->where('items1.sub_category_id', $searchCategoryID);
                })
                ->when($searchStockID != 0, function ($query) use ($searchStockID) {
                    return $query->where('items1.item_id', $searchStockID);
                })
                ->get()
                ->toArray();
            // dd($sales);
        } else {
            // Default sales report
            $sales = Sales::select(
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
                'is_delete'
            )
                ->join('tables', 'sales.table_id', 'tables.table_id')
                ->join('floors', 'tables.floor_id', 'floors.floor_id')
                ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
                ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
                ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
                ->whereBetween('sales.created_at', [$startDate, $endDate])
                ->when($isFOCSummary != 0, function ($query) {
                    return $query->where('sales.is_delete', '0')
                        ->where('sales.voucher_foc', '=', 1);
                })
                ->when($isDiscountSummary != 0, function ($query) {
                    return $query->where('sales.is_delete', '0')
                        ->whereBetween('sales.voucher_discount_percent', [1, 99]);
                })
                ->when($isKPaySummary != 0, function ($query) {
                    return $query->where('sales.is_delete', '0')
                        ->where('sales.payment_type_id', '!=', 1);
                })
                ->when($isDeletedSummary != 0, function ($query) {
                    return $query->where('sales.is_delete', '1');
                })
                ->when($isFOCSummary == 0 && $isDiscountSummary == 0 && $isKPaySummary == 0 && $isDeletedSummary == 0, function ($query) {
                    return $query->where('sales.is_delete', '0');
                })
                ->orderByDesc('sale_id')
                ->get()
                ->toArray();
        }

        $query = SalesDetail::join('menu_items as item', 'item.item_id', 'sales_details.item_id')
            ->join('item_selling_prices as ISP', 'item.item_id', 'ISP.item_id')
            ->join('sales as S', 'S.sale_id', 'sales_details.sale_id')
            ->whereBetween('sales_details.created_at', [$startDate, $endDate])
            ->when($searchCategoryID != 0, function ($query) use ($searchCategoryID) {
                return $query->where('item.sub_category_id', $searchCategoryID);
            })
            ->when($searchStockID != 0, function ($query) use ($searchStockID) {
                return $query->where('item.item_id', $searchStockID);
            })
            ->when($isFOCSummary != 0, function ($query) {
                return $query->where('S.voucher_foc', '=', 1);
            })
            ->when($isDiscountSummary != 0, function ($query) {
                return $query->whereBetween('S.voucher_discount_percent', [1, 99]);
            })
            ->when($isKPaySummary != 0, function ($query) {
                return $query->where('S.payment_type_id', '!=', 1);
            })
            ->when($isDeletedSummary != 0, function ($query) {
                return $query->where('S.is_delete', '1');
            })
            ->when($isFOCSummary == 0 && $isDiscountSummary == 0 && $isKPaySummary == 0 && $isDeletedSummary == 0, function ($query) {
                return $query->where('S.is_delete', '0');
            });

        // dd($query->get()->toArray());

        $totalSalesCost = $query->sum(DB::raw('sales_details.quantity * ISP.unit_cost'));
        // dd($totalSalesCost);

        $totalAmount = 0;
        $totalOnlinePayment = 0;
        $totalCashPayment = 0;
        $totalPromo = 0;
        $totalServiceCharges = 0;
        $totalTax = 0;
        $totalNetAmount = 0;

        foreach ($sales as $sale) {
            $totalAmount += $sale['total_amount'];
            $totalOnlinePayment += $sale['online_paid'] ?? 0;
            $totalCashPayment += $sale['paid_amount'] ?? 0;
            $totalPromo += abs($sale['total_item_promo_amount'] ?? 0) + abs($sale['voucher_discount_amount'] ?? 0);
            $totalServiceCharges += $sale['service_charges_amount'] ?? 0;
            $totalTax += $sale['tax_amount'] ?? 0;
            $totalNetAmount += $sale['net_amount'] ?? 0;
        }

        // Return view or JSON based on request type
        if ($req->ajax() || $req->expectsJson()) {
            return response()->json($sales);
        }

        return view('prints.sale.sales_report_print', compact('sales', 'totalAmount', 'totalOnlinePayment', 'totalCashPayment', 'totalPromo', 'totalServiceCharges', 'totalTax', 'totalNetAmount', 'totalSalesCost', 'isCheckedItemSummary'));
    }
}
