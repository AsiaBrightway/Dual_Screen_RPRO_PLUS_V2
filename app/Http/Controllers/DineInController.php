<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Floor;
use App\Models\Order;
use App\Models\Table;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use App\Models\DeletedOrder;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class DineInController extends Controller
{
    //direct dine in page
    public function dineInPage()
    {

        $floors = Floor::where('is_discontinued', 0)
            ->where('is_deleted', 0)
            ->get();

        $firstFloorID = $floors->first()?->floor_id;

        $tables = Table::where('tables.is_discontinued', 0)
            ->where('tables.is_deleted', 0)
            ->where('tables.floor_id', $firstFloorID)
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->get();

        $todayDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->addMinutes(120)->format('H:i:s');

        $occupiedTables = Order::get();

        $reservationTables = Reservation::where('reservation_date', $todayDate)
            ->where('reservation_time', '<=', $currentTime)
            ->get();

        return view('admin.store.dine_in', compact('floors', 'tables', 'occupiedTables', 'reservationTables'));
    }

    public function getTableByFloorID(Request $req)
    {
        $floorID = $req->query('selectedFloorID');
        $occupiedTables = Order::pluck('table_id')->toArray(); // Assuming 'table_id' is the column in the Order model representing occupied tables
        $tables = Table::where('tables.floor_id', $floorID)
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->where('tables.is_discontinued', 0)
            ->where('tables.is_deleted', 0)
            ->get();

        $todayDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->addMinutes(120)->format('H:i:s');
        $reservationTables = Reservation::
                where('reservation_date', $todayDate)
            ->where('reservation_time', '<=', $currentTime)
            ->pluck('table_id')->toArray();

        return response()->json(['tables' => $tables, 'occupiedTables' => $occupiedTables, 'reservationTables' => $reservationTables]);
    }

    public function getTableByFloorIDOnly(Request $req)
    {
        $floorID = $req->query('floorID');

        $tables = Table::where('tables.floor_id', $floorID)
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->where('tables.is_discontinued', 0)
            ->where('tables.is_deleted', 0)
            ->get();

        return response()->json($tables);
    }

    public function getOrderByTableIDAndOrderNumber(Request $req)
    {
        $tableID = $req->query('tableID');
        $tableOrderNumber = $req->query('tableOrderNumber');
        $table = Table::where('table_id', $tableID)->get();

        $orderMaster = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();

        if ($orderMaster != null) {
            $orderID = $orderMaster->order_id;
        } else {
            $orderID = 0;
        }

        $orderDetails = OrderDetails::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->where('order_id', $orderID)
            ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
            ->join('item_selling_prices', 'order_details.item_id', 'item_selling_prices.item_id')
            ->get()
            ->toArray();

        return response()->json($orderDetails);
    }

    public function getOrderSummaryByTableID(Request $req)
    {
        $tableID = $req->query('tableID');
        $tableOrderNumber = $req->query('tableOrderNumber');

        $orderMaster = Order::where('table_id', $tableID)
            ->where('table_order_number', $tableOrderNumber)
            ->first();

        if ($orderMaster != null) {
            $orderID = $orderMaster->order_id;
        } else {
            $orderID = 0;
        }

        $orderDetails = OrderDetails::select(
            'order_details.item_id',
            'menu_items.item_name',
            DB::raw('SUM(item_selling_prices.item_selling_price * order_details.quantity) as total_price'),
            DB::raw('SUM(order_details.quantity) as total_quantity')
        )
        ->where('order_id', $orderID)
        ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
        ->join('item_selling_prices', 'order_details.item_id', 'item_selling_prices.item_id')
        ->groupBy('order_details.item_id', 'menu_items.item_name')
        ->get()
        ->toArray();
        // dd($orderDetails);

        return response()->json($orderDetails);
    }

    public function tableMerge(Request $req)
    {
        try {
            DB::beginTransaction();

            $fromFloorID = $req->from_floor_ID;
            $fromTableID = $req->from_table_ID;
            $fromTableOrderID = $req->from_table_order;

            $toFloorID = $req->to_floor_id;
            $toTableID = $req->to_table_id;
            $toTableOrderID = $req->to_table_order_id;

            $toOrderMaster = Order::where('table_id', $toTableID)
                ->where('table_order_number', $toTableOrderID)
                ->first();

            $fromOrderMaster = Order::where('table_id', $fromTableID)
                ->where('table_order_number', $fromTableOrderID)
                ->first();


            if ($toOrderMaster != null) {
                $toOrderID = $toOrderMaster->order_id;
            } else {
                $data = $this->addOrderMasterData($toTableID, $toTableOrderID);
                $result = Order::create($data);
                $toOrderID = $result->id;
            }

            if ($fromOrderMaster != null) {
                $fromOrderID = $fromOrderMaster->order_id;
                $order_detaillist = OrderDetails::where('order_id', $fromOrderID)->get();

                foreach ($order_detaillist as $detail) {
                    $detail_data = $this->addOrderDetailsData($detail, $toOrderID);
                    OrderDetails::create($detail_data);
                }
                OrderDetails::where('order_id', $fromOrderID)->delete();
                Order::where('table_id', $fromTableID)
                    ->where('table_order_number', $fromTableOrderID)
                    ->delete();
            }
            DB::commit();

            return redirect()->route('store#dineInPage')->with(['success' => 'Table Merge completed
            successfully!']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('store#dineInPage')->with(['failed' => 'Table Merge was failed.
            Please try again!']);
        }
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

        $orderDetails = OrderDetails::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->where('order_id', $orderID)
            ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
            ->join('item_selling_prices', 'order_details.item_id', 'item_selling_prices.item_id')
            ->get()
            ->toArray();

        return response()->json($orderDetails);
    }

    public function updateOrderItemQty(Request $req)
    {
        $orderDetailID = $req->orderDetailID;
        $updatedQuantity = $req->updatedQuantity;

        $orderDetailsTemp = OrderDetails::where('order_detail_id', $orderDetailID)->first();

        $oldQty = $orderDetailsTemp->quantity;

        $data = $this->updateOrderItemQtyData($updatedQuantity);
        OrderDetails::where('order_detail_id', $orderDetailID)->update($data);

        $orderID = $orderDetailsTemp->order_id;

        if ($updatedQuantity < $oldQty) {
            $focQty = $oldQty - $updatedQuantity;
            $detail_data = $this->addOrderDetailsFOCData($orderDetailsTemp, $focQty);
            OrderDetails::create($detail_data);
        }

        $orderDetails = OrderDetails::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->where('order_id', $orderID)
            ->join('menu_items', 'order_details.item_id', 'menu_items.item_id')
            ->join('item_selling_prices', 'order_details.item_id', 'item_selling_prices.item_id')
            ->get()
            ->toArray();

        return response()->json($orderDetails);
    }

    private function addDeletedOrderData($request, $orderID, $tableid, $removedQty)
    {
        $data = [
            'order_id' => $orderID,
            'item_id' => $request['item_id'],
            'quantity' => $removedQty,
            'remark' => $request['remark'],
            'is_ordered' => $request['is_ordered'],
            'is_foc' => $request['is_foc'],
            'order_time' => $request['created_at'],
            'ordered_by' => $request['ordered_by'],
            'table_id' => $tableid,
            'deleted_by' => Auth::user()->id,
        ];
        return $data;
    }

    public function deleteOrderItem(Request $req)
    {
        try {

            DB::beginTransaction();
            $orderDetailID = $req->orderDetailID;
            $orderDetails = [];
            $orderDetailsTemp = OrderDetails::where('order_detail_id', $orderDetailID)->first();
            // $orderDeleteData = OrderDetails::where('order_detail_id', $orderDetailID)->get();
            $deleteQty = $req->deleteQty;

            $tableid = Order::where('order_id', $orderDetailsTemp->order_id)->first()->table_id;

            // if($orderDetailsTemp!=null){
            //     $detail_data = $this->addDeletedOrderData($orderDetailsTemp, $orderDetailsTemp->order_id,$tableid);
            //     $detail_data['is_ordered'] = 0;
            //     DeletedOrder::create($detail_data);
            // }

            if ($orderDetailsTemp != null) {
                $orderID = $orderDetailsTemp->order_id;

                if ($deleteQty == "") {
                    $detail_data = $this->addDeletedOrderData($orderDetailsTemp, $orderDetailsTemp->order_id, $tableid, $orderDetailsTemp->quantity);
                    $detail_data['is_ordered'] = 0;
                    $orderDeleteData = DeletedOrder::create($detail_data);
                    OrderDetails::where('order_detail_id', $orderDetailID)->delete();
                } else {
                    $detail_data = $this->addDeletedOrderData($orderDetailsTemp, $orderDetailsTemp->order_id, $tableid, $deleteQty);
                    $orderDeleteData = DeletedOrder::create($detail_data);


                    if ($orderDetailsTemp->quantity == $deleteQty) {
                        OrderDetails::where('order_detail_id', $orderDetailID)->delete();
                    } else {
                        OrderDetails::where('order_detail_id', $orderDetailID)->decrement('quantity', $deleteQty);
                    }
                }

                // OrderDetails::where('order_detail_id', $orderDetailID)->delete();
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

                $order = Order::select('*', 'tbl.table_name as table_name', 'fl.floor_name as floor_name')
                    ->join('tables as tbl', 'orders.table_id', 'tbl.table_id')
                    ->join('floors as fl', 'tbl.floor_id', 'fl.floor_id')
                    ->where('orders.order_id', $orderID)->first();

                if ($orderDetails == null) {
                    Order::where('order_id', $orderID)->delete();
                }
            }

            DB::commit();
            return response()->json([
                "orderDetails" => $orderDetails,
                "orderDetailDelete" => $orderDeleteData,
                "orderID" => $orderID,
                "order" => $order
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    //Private Functions
    //add order master data
    private function addOrderMasterData($toTableID, $toTableOrderID)
    {
        $data = [
            'table_id' => $toTableID,
            'table_order_number' => $toTableOrderID,
            'ordered_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add order details data
    private function addOrderDetailsData($detail, $toOrderID)
    {
        $data = [
            'order_id' => $toOrderID,
            'item_id' => $detail['item_id'],
            'quantity' => $detail['quantity'],
            'remark' => $detail['remark'],
            'is_ordered' => $detail['is_ordered'],
            'is_foc' => $detail['is_foc'],
            'ordered_by' => $detail['ordered_by'],
        ];
        return $data;
    }
    private function addOrderDetailsFOCData($orderDetailsTemp, $focQty)
    {
        $data = [

            'order_id' => $orderDetailsTemp->order_id,
            'item_id' => $orderDetailsTemp->item_id,
            'batch_number' => $orderDetailsTemp->batch_number,
            'promotion_price' => $orderDetailsTemp->promotion_price,
            'quantity' => $focQty,
            'remark' => $orderDetailsTemp->remark,
            'is_ordered' => $orderDetailsTemp->is_ordered,
            'is_foc' => 1,
            'order_type' => $orderDetailsTemp->order_type,
            'ordered_by' => $orderDetailsTemp->ordered_by,
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

    private function updateOrderItemQtyData($updatedQuantity)
    {
        $data = [
            'quantity' => $updatedQuantity
        ];
        return $data;
    }
}
