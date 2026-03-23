<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Order;
use App\Models\Table;
use App\Models\OrderDetails;
use App\Models\Floor;
use Exception;
use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DineInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $floors = Floor::where('is_discontinued', 0)->where('is_deleted', 0)->get();
        return response()->json($floors);
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
    public function getfloors()
    {
        $floors = Floor::where('is_discontinued', 0)->where('is_deleted', 0)->get();
        return response()->json($floors);
    }

    public function gettablesbyfloor(Request $req)
    {
        $floorID = $req->query('floorID');
        $tables = Table::where('floor_id', $floorID)->where('is_discontinued', 0)->where('is_deleted', 0)->get();
        return response()->json($tables);
    }

    public function getoccupiedtables()
    {
        $occupiedTables = Order::pluck('table_id');
        return response()->json($occupiedTables);
    }

    public function getreservations()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->addMinutes(120)->format('H:i:s');
        $reservations = Reservation::where('reservation_date', $todayDate)->where('reservation_time', '<=', $currentTime)->get();
        return response()->json($reservations);
    }

    public function getorderdetails(Request $req)
    {
        $tableID = $req->query('tableID');
        $tableOrderNumber = $req->query('tableOrderNumber');
        $order = Order::where('table_id', $tableID)->where('table_order_number', $tableOrderNumber)->first();

        if (!$order) {
            return response()->json([]);
        }

        $orderDetails = OrderDetails::where('order_id', $order->order_id)
            ->join('menu_items', 'order_details.item_id', '=', 'menu_items.item_id')
            ->join('item_selling_prices', 'order_details.item_id', '=', 'item_selling_prices.item_id')
            ->select('order_details.*', 'item_selling_prices.item_selling_price as item_price')
            ->get();
        
        return response()->json($orderDetails);
    }

    public function mergetables(Request $req)
    {
        try {
            DB::beginTransaction();
            
            $fromTableID = $req->from_table_ID;
            $fromTableOrderID = $req->from_table_order;
            $toTableID = $req->to_table_id;
            $toTableOrderID = $req->to_table_order_id;
            
            $toOrder = Order::firstOrCreate([
                'table_id' => $toTableID,
                'table_order_number' => $toTableOrderID,
            ], [
                'ordered_by' => Auth::id()
            ]);
            
            $fromOrder = Order::where('table_id', $fromTableID)->where('table_order_number', $fromTableOrderID)->first();
            
            if ($fromOrder) {
                OrderDetails::where('order_id', $fromOrder->order_id)->update(['order_id' => $toOrder->order_id]);
                $fromOrder->delete();
            }
            
            DB::commit();
            return response()->json(['success' => 'Table merge completed successfully!']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Table merge failed. Please try again!'], 500);
        }
    }

    public function updateorderitem(Request $req)
    {
        $orderDetailID = $req->orderDetailID;
        $isFoc = $req->foc == "checked" ? 1 : 0;
        
        OrderDetails::where('order_detail_id', $orderDetailID)->update(['is_foc' => $isFoc]);
        return response()->json(['message' => 'Order item updated successfully']);
    }

    public function updateorderitemqty(Request $req)
    {
        $orderDetailID = $req->orderDetailID;
        $updatedQuantity = $req->updatedQuantity;
        
        OrderDetails::where('order_detail_id', $orderDetailID)->update(['quantity' => $updatedQuantity]);
        return response()->json(['message' => 'Order item quantity updated successfully']);
    }

    public function deleteorderitem(Request $req)
    {
        $orderDetailID = $req->orderDetailID;
        $orderDetail = OrderDetails::find($orderDetailID);

        if ($orderDetail) {
            $orderID = $orderDetail->order_id;
            $orderDetail->delete();
            
            $remainingItems = OrderDetails::where('order_id', $orderID)->exists();
            if (!$remainingItems) {
                Order::where('order_id', $orderID)->delete();
            }
        }

        return response()->json(['message' => 'Order item deleted successfully']);
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
            $userID = $req->user_id;

            $toOrderMaster = Order::where('table_id', $toTableID)
                ->where('table_order_number', $toTableOrderID)
                ->first();

            $fromOrderMaster = Order::where('table_id', $fromTableID)
                ->where('table_order_number', $fromTableOrderID)
                ->first();


            if ($toOrderMaster != null) {
                $toOrderID = $toOrderMaster->order_id;
            } else {
                $data = $this->addOrderMasterData($toTableID, $toTableOrderID,$userID);
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

             return response()->json(['message' => 'Table Merge successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

        //Private Functions
    //add order master data
    public function addOrderMasterData($toTableID, $toTableOrderID, $userID)
    {
        $data = [
            'table_id' => $toTableID,
            'table_order_number' => $toTableOrderID,
            'ordered_by' => $userID,

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
}
