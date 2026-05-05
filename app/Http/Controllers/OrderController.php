<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Table;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\ItemDiscount;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use App\Models\OrderDetails;
use App\Models\DeletedOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StockIssueController;

class OrderController extends Controller
{

    //direct dine in page
    public function orderPage(Request $req)
    {
        $tableID = $req->tableID;
        $tableOrderValue = $req->tableOrderValue;
        // logger($req);
        $mainCategories = MainCategory::where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->get()->toArray();
        $menuCategories = MenuCategory::where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->get()->toArray();
        $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
            ->where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->whereIn('item_type_id', [1, 3])
            ->get()->toArray();

        // dd($dbItems);

        // $items = $this->addStoreQtyToItems($dbItems);

        $table = Table::where('table_id', $tableID)->get();

        $orderMaster = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderValue)
            ->first();

        if ($orderMaster != null) {
            $orderID = $orderMaster->order_id;
        } else {
            $orderID = 0;
        }

        $orderDetails = OrderDetails::select('*', 'ISP.item_selling_price as item_price')
            ->where('order_id', $orderID)
            ->join('menu_items as MI', 'order_details.item_id', 'MI.item_id')
            ->join('item_selling_prices as ISP', 'MI.item_id', 'ISP.item_id')
            ->get()
            ->toArray();

        // dd($table);
        // dd($orderDetails);
        return view('admin.store.order', compact('mainCategories', 'menuCategories', 'dbItems', 'table', 'tableOrderValue', 'orderDetails'));
    }

    public function canceledOrders(Request $request)
    {
        $filterDate = $request->query('filterDate', Carbon::now()->format('Y-m-d'));

        $canceledOrders = DeletedOrder::select(
            'deleted_orders.*',
            'menu_items.item_name',
            'tables.table_name',
            'users.name as ordered_by_name',
            'deleted_by_users.name as deleted_by_name',
        )
            ->leftJoin('menu_items', 'deleted_orders.item_id', '=', 'menu_items.item_id')
            ->leftJoin('tables', 'deleted_orders.table_id', '=', 'tables.table_id')
            ->leftJoin('users', 'deleted_orders.ordered_by', '=', 'users.id')
            ->leftJoin('users as deleted_by_users', 'deleted_orders.deleted_by', '=', 'deleted_by_users.id')
            ->whereDate('deleted_orders.created_at', $filterDate)
            ->orderBy('deleted_orders.created_at', 'desc')
            ->get();

        return view('admin.store.canceled_orders', compact('canceledOrders', 'filterDate'));
    }

    public function getItemBySearchKey()
    {
        $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
            ->where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->whereIn('item_type_id', [1, 3])
            ->when(request('searchKey'), function ($query) {
                $key = request('searchKey');
                $query->where(function ($subQuery) use ($key) {
                    $subQuery->where('item_name', 'like', '%' . $key . '%')
                        ->orWhere('other_name', 'like', '%' . $key . '%');
                });
            })
            ->get();

        // $items = $this->addStoreQtyToItems($dbItems);
        // dd($items);

        return response()->json($dbItems);
    }


    public function getSubCategoryByMainCategory(Request $req)
    {
        $selectedMainCategoryID = $req->query('selectedMainCategoryID');
        if ($selectedMainCategoryID == 0 || $selectedMainCategoryID == "0") {
            $subCategories = MenuCategory::where('is_deleted', 0)->get();
        } else {
            $subCategories = MenuCategory::where('main_category_id', $selectedMainCategoryID)->where('is_deleted', 0)->get();
        }

        // dd($subCategories->toArray());

        return response()->json($subCategories);
    }

    public function getItemBySubCategory(Request $req)
    {
        $selectedSubCategoryID = $req->query('selectedSubCategoryID');
        if ($selectedSubCategoryID == 0 || $selectedSubCategoryID == "0") {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->get()->toArray();

            // $items = $this->addStoreQtyToItems($dbItems);
        } else {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->where('sub_category_id', $selectedSubCategoryID)
                ->get()->toArray();

            // $items = $this->addStoreQtyToItems($dbItems);
        }
        // dd($items);
        return response()->json($dbItems);
    }

    public function getItemByMainCategory(Request $req)
    {
        $selectedMainCategoryID = $req->query('selectedMainCategoryID');
        if ($selectedMainCategoryID == 0 || $selectedMainCategoryID == "0") {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->get()->toArray();

            // $items = $this->addStoreQtyToItems($dbItems);
        } else {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where('is_deleted', 0)
                ->where('is_discontinued', 0)
                ->whereIn('item_type_id', [1, 3])
                ->where('main_category_id', $selectedMainCategoryID)
                ->get()->toArray();

            // $items = $this->addStoreQtyToItems($dbItems);
        }

        return response()->json($dbItems);
    }

    public function addOrderItem(Request $req)
    {
        // logger($req->all());
        // $order_detaillist = $req->orderItems;


        try {
            DB::beginTransaction();
            $order_detaillist = json_decode($req->input('unOrderItems'), true);
            $tableID = $req->input('tableID');
            $tableOrderNumber = $req->input('tableOrderNumber');


            $reservationTable = Reservation::where('table_id', $tableID)->first();
            if ($reservationTable != null) {
                Reservation::where('table_id', $tableID)->delete();
            }

            $orderMaster = Order::where('table_id', $tableID)
                ->where('table_order_number', $tableOrderNumber)
                ->first();

            if ($orderMaster != null) {
                $orderID = $orderMaster->order_id;
            } else {
                $data = $this->addOrderMasterData($req);
                $result = Order::create($data);
                $orderID = $result->id;
            }
            $today = Carbon::today();
            foreach ($order_detaillist as $detail) {

                if ($detail['is_ordered'] != 1 || $detail['is_ordered'] != "1") {
                    $discountItem = ItemDiscount::select('*')
                        ->where('item_id', $detail['orderItemID'])
                        ->where('start_date', '<=', date($today))
                        ->where('end_date', '>=', date($today))
                        ->first();
                    if ($discountItem != null) {
                        if ($discountItem->monday == null && $discountItem->tuesday == null && $discountItem->wednesday == null && $discountItem->thursday == null && $discountItem->friday == null && $discountItem->saturday == null && $discountItem->sunday == null) {
                            $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                        } else {
                            $dayOfWeek = $today->dayName;
                            if ($discountItem->monday != null || $discountItem->monday != 0) {
                                $checkMonday = "Monday";
                            }
                            if ($discountItem->tuesday != null || $discountItem->tuesday != 0) {
                                $checkTueday = "Tuesday";
                            }
                            if ($discountItem->wednesday != null || $discountItem->wednesday != 0) {
                                $checkWednesday = "Wednesday";
                            }
                            if ($discountItem->thursday != null || $discountItem->thursday != 0) {
                                $checkTursday = "Thursday";
                            }
                            if ($discountItem->friday != null || $discountItem->friday != 0) {
                                $checkFriday = "Friday";
                            }
                            if ($discountItem->saturday != null || $discountItem->saturday != 0) {
                                $checkSaturday = "Saturday";
                            }
                            if ($discountItem->sunday != null || $discountItem->sunday != 0) {
                                $checkSunday = "Sunday";
                            }
                            if ($dayOfWeek == "Monday") {
                                if ($dayOfWeek == $checkMonday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Tuesday") {
                                if ($dayOfWeek == $checkTueday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Wednesday") {
                                if ($dayOfWeek == $checkWednesday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Thursday") {
                                if ($dayOfWeek == $checkTursday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Friday") {
                                if ($dayOfWeek == $checkFriday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Saturday") {
                                if ($dayOfWeek == $checkSaturday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else if ($dayOfWeek == "Sunday") {
                                if ($dayOfWeek == $checkSunday) {
                                    $detail_data = $this->addOrderDetailsPromotionData($detail, $orderID, $discountItem->promotion_price);
                                }
                            } else {
                                $detail_data = $this->addOrderDetailsData($detail, $orderID);
                            }
                        }
                    } else {
                        $detail_data = $this->addOrderDetailsData($detail, $orderID);
                    }
                    if ($detail_data['remark'] == null) {
                        $detail_data['remark'] = "";
                    }
                    $detail_data['is_ordered'] = 1;
                    OrderDetails::create($detail_data);
                }
            }
            DB::commit();
            return response()->json($orderID);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function deleteOrderItem(Request $req)
    {
        try {
            DB::beginTransaction();
            $orderDetailID = $req->orderDetailID;
            $orderDetails = [];
            $orderDetailsTemp = OrderDetails::where('order_detail_id', $orderDetailID)->first();
            if ($orderDetailsTemp != null) {
                $tableid = Order::where('order_id', $orderDetailsTemp->order_id)->first()->table_id;
                $detail_data = $this->addDeletedOrderData($orderDetailsTemp, $tableid);
                $detail_data['is_ordered'] = 1;
                DeletedOrder::create($detail_data);

                $orderID = $orderDetailsTemp->order_id;
                OrderDetails::where('order_detail_id', $orderDetailID)->delete();
                $orderDetails = OrderDetails::select('*', 'item_selling_prices.item_selling_price as item_price')
                    ->where('order_id', $orderID)
                    ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
                    ->join(
                        'item_selling_prices',
                        'order_details.item_id',
                        'item_selling_prices.item_id'
                    )
                    ->get()
                    ->toArray();
                if ($orderDetails == null) {
                    Order::where('order_id', $orderID)->delete();
                }
            }
            DB::commit();
            return response()->json($orderDetailID);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    private function addDeletedOrderData($orderDetailsTemp, $tableid)
    {
        $data = [

            'order_id' => $orderDetailsTemp->order_id,
            'table_id' => $tableid,
            'item_id' => $orderDetailsTemp->item_id,
            'quantity' => $orderDetailsTemp->quantity,
            'remark' => $orderDetailsTemp->remark,
            'is_ordered' => $orderDetailsTemp->is_ordered,
            'is_foc' => $orderDetailsTemp->is_foc,
            'ordered_by' => Auth::id(),
            'deleted_by' => Auth::id(),
        ];
        return $data;
    }

    public function updateOrderItem(Request $req)
    {
        $orderDetailID = $req->orderDetailID;
        $foc = $req->foc;

        if ($foc == "checked") {
            $data = $this->updateOrderDetailsData(1);
            OrderDetails::where('order_detail_id', $orderDetailID)->update($data);
        } else {
            $data = $this->updateOrderDetailsData(0);
            OrderDetails::where('order_detail_id', $orderDetailID)->update($data);
        }

        $orderDetailsTemp = OrderDetails::where('order_detail_id', $orderDetailID)->first();

        $orderID = $orderDetailsTemp->order_id;

        $orderDetails = OrderDetails::where('order_id', $orderID)
            ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
            ->get()
            ->toArray();

        return response()->json($orderDetails);
    }

    //Private Functions

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
    //add order master data
    private function addOrderMasterData($req)
    {
        $data = [
            'table_id' => $req->tableID,
            'table_order_number' => $req->tableOrderNumber,
            'ordered_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add order details data
    private function addOrderDetailsData($detail, $orderID)
    {
        $data = [

            'order_id' => $orderID,
            'item_id' => $detail['orderItemID'],
            'quantity' => $detail['orderItemQuantity'],
            'remark' => $detail['orderItemRemark'],
            'is_ordered' => $detail['is_ordered'],
            'is_foc' => $detail['is_foc'],
            'ordered_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add order details promotion data
    private function addOrderDetailsPromotionData($detail, $orderID, $promotionPrice)
    {
        $data = [
            'order_id' => $orderID,
            'item_id' => $detail['orderItemID'],
            'promotion_price' => $promotionPrice,
            'quantity' => $detail['orderItemQuantity'],
            'remark' => $detail['orderItemRemark'],
            'is_ordered' => $detail['is_ordered'],
            'is_foc' => $detail['is_foc'],
            'ordered_by' => Auth::user()->id,
        ];
        return $data;
    }

    private function updateOrderDetailsData($foc)
    {
        $data = [
            'is_foc' => $foc
        ];
        return $data;
    }
}
