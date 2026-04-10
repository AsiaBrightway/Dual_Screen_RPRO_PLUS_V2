<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\MenuItem;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\DeletedOrder;
use Exception;
use Illuminate\Http\Request;
use App\Models\ItemDiscount;
use App\Http\Controllers\StockIssueController;
use Carbon\Carbon;
use App\Models\MenuItemItem;
use App\Models\Table;
use Response;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
    public function getcategories()
    {
        // $mainCategories = MainCategory::where('is_deleted', 0)->where('is_discontinued', 0)->get();
        $menuCategories = MenuCategory::where('is_deleted', 0)->where('is_discontinued', 0)->get();
        return response()->json(['menuCategories' => $menuCategories]);
    }

    public function searchitems(Request $request)
    {
        $items = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
            ->where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->when($request->searchKey, function ($query) use ($request) {
                $query->where('item_name', 'like', "%{$request->searchKey}%")
                    ->orWhere('other_name', 'like', "%{$request->searchKey}%");
            })
            ->get();

        return response()->json($items);
    }

    public function getitemsbycategory(string $id)
    {
        // $categoryId = $id;
        // $items = MenuItem::where('is_deleted', 0)
        //     ->where('is_discontinued', 0)
        //     ->when($categoryId, function ($query) use ($categoryId) {
        //         $query->where('main_category_id', $categoryId)->orWhere('sub_category_id', $categoryId);
        //     })
        //     ->get();

        // return response()->json($items);

        $selectedSubCategoryID = $id;

        // Base query
        if ($selectedSubCategoryID == 0 || $selectedSubCategoryID == "0") {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->get()->toArray();

            $items = $this->addStoreQtyToItems($dbItems);
        } else {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->where('sub_category_id', $selectedSubCategoryID)
                ->get()->toArray();

            $items = $this->addStoreQtyToItems($dbItems);
        }

        // Return as JSON response
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
    private function addStoreQtyToItems($dbItems)
    {
        $today = Carbon::today();
        $items = [];
        for ($i = 0; $i < count($dbItems); ++$i) {
            $storeQty = StockIssueController::getStockBalance($dbItems[$i]['item_id'], $dbItems[$i]['unit_id'])[0];
            // $storeQty = max(0, StockIssueController::getStockBalance($dbItems[$i]['item_id'], $dbItems[$i]['unit_id'])[0]);
            $discountItem = ItemDiscount::select('*')
                ->where('item_id', $dbItems[$i]['item_id'])
                ->where('start_date', '<=', date($today))
                ->where('end_date', '>=', date($today))
                ->first();

            $my_detail = [
                'item_id' => $dbItems[$i]['item_id'],
                'main_category_id' => $dbItems[$i]['main_category_id'],
                'sub_category_id' => $dbItems[$i]['sub_category_id'],
                'item_type_id' => $dbItems[$i]['item_type_id'],
                'item_code' => $dbItems[$i]['item_code'],
                'bar_code' => $dbItems[$i]['bar_code'],
                'item_name' => $dbItems[$i]['item_name'],
                'other_name' => $dbItems[$i]['other_name'],
                'unit_id' => $dbItems[$i]['unit_id'],
                'item_price' => $dbItems[$i]['item_price'],
                'item_image' => $dbItems[$i]['item_image'],
                'location_id' => $dbItems[$i]['location_id'],
                'is_discontinued' => $dbItems[$i]['is_discontinued'],
                'is_deleted' => $dbItems[$i]['is_deleted'],
                'modified_by' => $dbItems[$i]['modified_by'],
                'created_at' => $dbItems[$i]['created_at'],
                'updated_at' => $dbItems[$i]['updated_at'],
                'store_qty' => intval($storeQty),
            ];
            array_push($items, $my_detail);
        }

        return $items;
    }

    public function getItemsWithStock()
    {
        $today = Carbon::today()->toDateString();
        $dbItems = MenuItem::all(); // Fetch all items from the database
        $items = [];

        foreach ($dbItems as $dbItem) {
            $storeQty = StockIssueController::getStockBalance($dbItem->item_id, $dbItem->unit_id)[0] ?? 0;

            $discountItem = ItemDiscount::where('item_id', $dbItem->item_id)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            $items[] = [
                'item_id' => $dbItem->item_id,
                'main_category_id' => $dbItem->main_category_id,
                'sub_category_id' => $dbItem->sub_category_id,
                'item_type_id' => $dbItem->item_type_id,
                'item_code' => $dbItem->item_code,
                'bar_code' => $dbItem->bar_code,
                'item_name' => $dbItem->item_name,
                'other_name' => $dbItem->other_name,
                'unit_id' => $dbItem->unit_id,
                'item_price' => $dbItem->item_price,
                'item_image' => $dbItem->item_image,
                'location_id' => $dbItem->location_id,
                'is_discontinued' => $dbItem->is_discontinued,
                'is_deleted' => $dbItem->is_deleted,
                'modified_by' => $dbItem->modified_by,
                'created_at' => $dbItem->created_at,
                'updated_at' => $dbItem->updated_at,
                'store_qty' => intval($storeQty),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function getItemBySearchKey(string $name)
    {
        $searchKey = $name;

        $dbItems = MenuItem::select('menu_items.*', 'item_selling_prices.item_selling_price as item_price')
            ->join('item_selling_prices', 'menu_items.item_id', '=', 'item_selling_prices.item_id')
            ->where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where(function ($subQuery) use ($searchKey) {
                    $subQuery->where('item_name', 'like', "%$searchKey%")
                        ->orWhere('other_name', 'like', "%$searchKey%");
                });
            })
            ->get();

        $items = $dbItems;

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function addorderitem(Request $request)
    {


        try {
            DB::beginTransaction();

            $orderDetailsList = $request->input('unOrderItems');
            $tableID = $request->input('tableID');
            $tableOrderNumber = $request->input('tableOrderNumber');
            $user_id = $request->input('user_id');
            $today = Carbon::today();

            // Remove reservation if exists
            Reservation::where('table_id', $tableID)->delete();


            // Retrieve or create order master
            $orderMaster = Order::firstOrCreate([
                'table_id' => $tableID,
                'table_order_number' => $tableOrderNumber,
            ], $this->addOrderMasterData($request));

            $orderID = !empty($orderMaster->id) ? $orderMaster->id : $orderMaster->order_id;

            foreach ($orderDetailsList as $detail) {
                if ($detail['is_ordered'] != 1) {
                    $discountItem = ItemDiscount::where('item_id', $detail['orderItemID'])
                        ->whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->first();

                    if ($discountItem) {
                        $weekDays = [
                            'Monday' => $discountItem->monday,
                            'Tuesday' => $discountItem->tuesday,
                            'Wednesday' => $discountItem->wednesday,
                            'Thursday' => $discountItem->thursday,
                            'Friday' => $discountItem->friday,
                            'Saturday' => $discountItem->saturday,
                            'Sunday' => $discountItem->sunday,
                        ];

                        $dayOfWeek = $today->format('l');
                        if (!array_filter($weekDays) || $weekDays[$dayOfWeek]) {
                            $detailData = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price, $user_id);
                        } else {
                            $detailData = $this->addOrderDetailsData($detail, $orderID, $user_id);
                        }
                    } else {
                        $detailData = $this->addOrderDetailsData($detail, $orderID, $user_id);
                    }

                    $detailData['remark'] = $detailData['remark'] ?? '';
                    $detailData['is_ordered'] = 1;
                    OrderDetails::create($detailData);
                }
            }

            DB::commit();
            return response()->json(['orderID' => $orderID]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //add order master data
    private function addOrderMasterData($request)
    {
        $data = [
            'table_id' => $request->input('tableID'),
            'table_order_number' => $request->input('tableOrderNumber'),
            'ordered_by' => $request->input('user_id'),

        ];
        return $data;
    }

    private function addOrderDetailsData($detail, $orderID, $user_id)
    {
        $data = [

            'order_id' => $orderID,
            'item_id' => $detail['orderItemID'],
            'quantity' => $detail['orderItemQuantity'],
            'remark' => $detail['orderItemRemark'],
            'is_ordered' => $detail['is_ordered'],
            'is_foc' => $detail['is_foc'],
            'ordered_by' => $user_id,
        ];
        return $data;
    }

    //add order details promotion data
    private function addOrderDetailsPromotionData($detail, $orderID, $promotionPrice, $user_id)
    {
        $data = [
            'order_id' => $orderID,
            'item_id' => $detail['orderItemID'],
            'promotion_price' => $promotionPrice,
            'quantity' => $detail['orderItemQuantity'],
            'remark' => $detail['orderItemRemark'],
            'is_ordered' => $detail['is_ordered'],
            'is_foc' => $detail['is_foc'],
            'ordered_by' => $user_id,
        ];
        return $data;
    }
    public function deleteorderitem(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $orderDetail = OrderDetails::where('order_detail_id', $id)->first();
            $user_id = $request->user_id;

            // $orderDetail = DB::table('order_details')->where('order_detail_id', $id)->first();
            if (!$orderDetail) {
                return response()->json(['message' => 'Order detail not found'], 404);
            }
            $tableid = Order::where('order_id', $orderDetail->order_id)->first()->table_id;

            $detailData = $this->addDeletedOrderData($orderDetail, $tableid, $user_id);
            $detailData['is_ordered'] = 1;
            DeletedOrder::create($detailData);

            // $orderDetail->delete();
            //fix order delete
            $orderID = $orderDetail->order_id;
            OrderDetails::where('order_detail_id', $id)->delete();

            if (!OrderDetails::where('order_id', $orderID)->exists()) {
                Order::where('order_id', $orderID)->delete();
            }



            DB::commit();
            return response()->json(['message' => 'Order item deleted successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function addDeletedOrderData($request, $tableid, $user_id)
    {
        $data = [

            'order_id' => $request['order_id'],
            'table_id' => $tableid,
            'item_id' => $request['item_id'],
            'quantity' => $request['quantity'],
            'remark' => $request['remark'],
            'is_ordered' => $request['is_ordered'],
            'is_foc' => $request['is_foc'],
            'ordered_by' => $request['ordered_by'],
            'deleted_by' => $user_id,
        ];
        return $data;
    }

    public function updateorderitem(Request $request)
    {
        $orderDetailID = $request->orderDetailID;
        $foc = $request->foc == 'checked' ? 1 : 0;

        OrderDetails::where('order_detail_id', $orderDetailID)->update(['is_foc' => $foc]);
        return response()->json(['message' => 'Order updated successfully']);
    }

    public function getorder(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'tableID' => 'required|integer',
                'tableOrderValue' => 'required|integer',
            ]);

            $tableID = $request->input('tableID');
            $tableOrderValue = $request->input('tableOrderValue');

            // Fetch categories
            $mainCategories = MainCategory::where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->get();

            $menuCategories = MenuCategory::where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->get();

            // Fetch menu items with pricing
            $dbItems = MenuItem::select('menu_items.*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', '=', 'item_selling_prices.item_id')
                ->where('menu_items.is_deleted', 0)
                ->where('menu_items.is_discontinued', 0)
                ->get();

            // Fetch table details
            $table = Table::where('table_id', $tableID)->first();

            // Fetch order details
            $orderMaster = Order::where('table_id', $tableID)
                ->where('table_order_number', $tableOrderValue)
                ->first();

            $orderID = $orderMaster ? $orderMaster->order_id : 0;

            $orderDetails = OrderDetails::select('order_details.*', 'ISP.item_selling_price as item_price', 'MI.item_name as item_name', 'MI.item_image as item_image', 'MI.main_category_id as main_category_id')
                ->where('order_details.order_id', $orderID)
                ->join('menu_items as MI', 'order_details.item_id', '=', 'MI.item_id')
                ->join('item_selling_prices as ISP', 'MI.item_id', '=', 'ISP.item_id')
                ->get();

            return response()->json([
                'tableOrderValue' => $tableOrderValue,
                'table' => $table,
                // 'mainCategories' => $mainCategories,
                // 'menuCategories' => $menuCategories,
                // 'items' => $dbItems,
                'orderID' => $orderID,
                'orderDetails' => $orderDetails,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
