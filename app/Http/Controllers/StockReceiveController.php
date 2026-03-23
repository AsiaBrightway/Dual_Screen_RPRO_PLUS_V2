<?php

namespace App\Http\Controllers;

use App\Models\ItemSellingPrice;
use Exception;
use App\Models\Unit;
use App\Models\ItemType;
use App\Models\MenuItem;
use App\Models\SalesDetail;
use App\Models\MainCategory;
use App\Models\StockReceive;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\StockReceiveDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StockReceiveController extends Controller
{
    //direct stock receive page
    public function receivePage()
    {
        $voucherNumber = 'RV-' . date('y') . '-' . StockReceive::whereYear('receive_date', date('Y'))->count() + 1;

        // $menu_item = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
        // ->whereIn('IT.item_type_id',[1,3])
        // ->select('menu_items.*', 'U.unit_name', 'ISP.unit_cost')
        // ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
        // ->join('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
        // ->join('item_types as IT', 'IT.item_type_id', '=', 'menu_items.item_type_id')
        // // ->where('IT.item_type_id', '!=', 1)
        // ->get();

        $menu_item = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->whereIn('I.item_type_id', [2, 3])
            ->select('menu_items.*', 'unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->get();

        $mainCategories = MainCategory::where('is_deleted', 0)->get()->toArray();
        $subCategories = [];
        $itemTypes = ItemType::get()->toArray();
        $units  = Unit::where('is_discontinued', 0)
            ->get()->toArray();
        
        // dd($menu_item->toArray());
        return view('admin.stock_control.stock_receive.receive', compact('menu_item',  'voucherNumber', 'mainCategories', 'subCategories', 'itemTypes', 'units'));
    }

    //direct stock receive List page
    public function receiveListPage(Request $req)
    {
        $dailyReceiveDate = $req->query('dailyReceiveDate');
        
        $stock_receive_list = StockReceive::select('stock_receives.stock_receive_id', 'receive_voucher_number', 'receive_date', 'remark')
            ->selectRaw('SUM(amount) as total_amount')
            ->join('stock_receive_details as RD', 'RD.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->groupBy(['stock_receive_id', 'receive_voucher_number', 'receive_date', 'remark'])
            ->where('stock_receives.is_delete', 0)
            ->when($req->has('dailyReceiveDate'), function($query) use($req) {
                $query->whereDate('stock_receives.receive_date', $req->dailyReceiveDate);
            }, function($query) {
                // $query->orderBy('stock_receives.stock_receive_id', 'DESC')->limit(10);
                $query->whereDate('stock_receives.receive_date', '>=', now()->subDays(30))
                  ->orderBy('stock_receives.stock_receive_id', 'DESC');
            })
            ->get();
        // dd($stock_receive_list->toArray());
        return view('admin.stock_control.stock_receive.receive_list', compact('stock_receive_list'));
    }

    public function createReceiveItem(Request $req)
    {
        // Validate manually to return JSON errors
        $validator = Validator::make($req->all(), [
            'create_main_category' => 'required|not_in:0',
            'create_sub_category' => 'required|not_in:0',
            'create_item_code' => 'required|unique:menu_items,item_code',
            // 'create_bar_code' => 'required|unique:menu_items,bar_code',
            'create_item_name' => 'required',
            'create_item_image' => 'mimes:jpg,jpeg,png',
            'create_unit_cost' => 'required|numeric',
            'create_item_selling_price' => [
                'required',
                'numeric',
                'gt:' . $req->create_unit_cost,
            ]
        ], [
            'create_main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'create_main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'create_sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
            'create_item_code.required' => 'Item Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_code.unique' => 'Item Code တူနေပါသည်',
            // 'create_bar_code.required' => 'Bar Code ဖြည့်ရန်လိုအပ်ပါသည်',
            // 'create_bar_code.unique' => 'Bar Code တူနေပါသည်',
            'create_item_name.required' => 'Item Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_image.mimes' => 'Image သည် JPG, JPEG, PNG Format သာဖြစ်ရပါမည်',
            'create_item_selling_price.required' => 'Selling Price ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_selling_price.numeric' => 'Selling Price သည် Number ဖြစ်ရပါမည်',
            'create_unit_cost.required' => 'Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_selling_price.gt' => 'Selling Price သည် Unit Cost ထက်များရမည်',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $this->addItemData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
                    $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        if ($req->hasFile('create_item_image')) {
            $fileName = uniqid() . '_' . $req->file('create_item_image')->getClientOriginalName();
            $req->file('create_item_image')->storeAs('public/Images/', $fileName);
            $data['item_image'] = $fileName;
        }

        try {
            DB::beginTransaction();
            $menuItem = MenuItem::create($data);

            try {
                $sellingPriceData = $this->addPriceControlData($req);
                $sellingPriceData['item_id'] = $menuItem->id;
                ItemSellingPrice::create($sellingPriceData);
            } catch (\Exception $e) {
                Log::error('ItemSellingPrice insert failed: ' . $e->getMessage());
            }
            
            DB::commit();

            // Return Success JSON instead of Redirect
            return response()->json(['success' => 'Item created successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    // Add Price Control Data
    private function addPriceControlData($req)
    {
        $data = [
            'unit_id' => $req->create_item_unit,
            'currency_id' => 1,
            'unit_cost' => $req->create_unit_cost,
            'item_selling_price' => $req->create_item_selling_price,
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    // Validation Check
    // private function validationCheck($req)
    // {
    //     $validationRules = [
    //         'create_main_category' => 'required|not_in:0',
    //         'create_sub_category' => 'required',
    //         'create_item_code' => 'required|unique:menu_items,item_code',
    //         'create_bar_code' => 'required|unique:menu_items,bar_code',
    //         'create_item_name' => 'required',
    //         'create_item_image' => 'mimes:jpg,jpeg,png',
    //         'create_item_selling_price' => [
    //             'required',
    //             'numeric',
    //             'gte:' . $req->create_unit_cost,
    //         ]
    //     ];

    //     $validationMessages = [
    //         'create_main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
    //         'create_main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
    //         'create_sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
    //         'create_item_code.required' => 'Item Code ဖြည့်ရန်လိုအပ်ပါသည်',
    //         'create_item_code.unique' => 'Item Code တူနေပါသည်',
    //         'create_bar_code.required' => 'Bar Code ဖြည့်ရန်လိုအပ်ပါသည်',
    //         'create_bar_code.unique' => 'Bar Code တူနေပါသည်',
    //         'create_item_name.required' => 'Item Name ဖြည့်ရန်လိုအပ်ပါသည်',
    //         'create_item_image.mimes' => 'Image သည် JPG, JPEG, PNG Format သာဖြစ်ရပါမည်',
    //         'create_item_selling_price.required' => 'Selling Price ဖြည့်ရန်လိုအပ်ပါသည်',
    //         'create_item_selling_price.numeric' => 'Selling Price သည် Number ဖြစ်ရပါမည်',
    //         'create_item_selling_price.gte' => 'Selling Price သည် Unit Cost ထပ်များရမည်',
    //     ];
        
    //     Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    // }

    //create
    public function createStockReceive(Request $request)
    {
        try {
            DB::beginTransaction();
            // store_receive_detaillist = $request->-> AJax request ထဲက Key Name ကိုယူ
            $detailList = $request->detailList;
            $voucherNumber = 'RV-' . date('y') . '-' . StockReceive::whereYear('receive_date', date('Y'))->count() + 1;
            $master_data = $this->addStockReceiveMasterData($request, $voucherNumber);
            $result =  StockReceive::create($master_data);
            //dd($result);
            foreach ($detailList as $detail) {
                $receiveID = $result->id;
                $detail_data = $this->addStockReceiveDetailData($detail, $receiveID);
                $receiveDetail = StockReceiveDetail::create($detail_data);
                if ((int)$detail['store_qty'] < 0) {
                    $data = [
                        'batch_number' => $receiveDetail->batch_number,
                        'expire_date' => $receiveDetail->expire_date,
                        'sale_type' => "Receive"
                    ];
                    SalesDetail::where('item_id', $receiveDetail->item_id)
                        ->where('batch_number', 0)
                        ->update($data);
                }
            }
            DB::commit();
            return response()->json(['success' => "Successful!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    private function addStockReceiveMasterData($request, $voucherNumber)
    {
        $data = [
            'receive_voucher_number' =>  $voucherNumber,
            'receive_date' => $request->receiveDate,
            'remark' => $request->remark,
            'is_delete' => 0,
            'is_updated' => false,
            'modified_by' => $request->modifiedBy
        ];
        return $data;
    }

    private function addStockReceiveDetailData($detail, $receiveID)
    {
        $data = [
            'stock_receive_id' => $receiveID,
            'item_id' => $detail['item_id'],
            'unit_id' => $detail['unit_id'],
            'quantity' => $detail['quantity'],
            'unit_cost' => $detail['unit_cost'],
            'amount' => $detail['amount'],
            'expire_date' => $detail['expire_date'],
            'batch_number' => $receiveID,
            'is_updated' => false,
            'is_deleted' => 0
        ];
        return $data;
    }

    //update
    public function updateStockReceivePage($id)
    {

        $menu_item = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->whereIn('I.item_type_id', [2, 3])
            ->select('menu_items.*', 'unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->get();

        $selectedReceive = StockReceive::where('stock_receive_id', "=", $id)->get();
        $selectedReceiveDetail = StockReceiveDetail::where('stock_receive_id', "=", $id)
            ->select('stock_receive_details.item_id', 'stock_receive_details.unit_id', 'I.item_code', 'I.bar_code', 'I.item_name', 'U.unit_name', 'quantity', 'stock_receive_details.unit_cost', 'expire_date', 'amount')
            ->join('units as U', 'U.unit_id', '=', 'stock_receive_details.unit_id')
            ->join('menu_items as I', 'I.item_id', '=', 'stock_receive_details.item_id')
            ->get();

        return view('admin.stock_control.stock_receive.update_receive', compact('menu_item',  'selectedReceive', 'selectedReceiveDetail'));
    }

    public function updateStockReceive(Request $request)
    {
        try {
            DB::beginTransaction();
            $receiveID = $request->receiveID;
            $detailList = $request->detailList;
            $master_data = $this->addStockReceiveMasterData($request, $request->voucherNo);
            StockReceive::where('stock_receive_id', '=', $receiveID)->update($master_data);
            StockReceiveDetail::where('stock_receive_id', '=', $receiveID)->delete();
            foreach ($detailList as $detail) {
                $detail_data = $this->addStockReceiveDetailData($detail, $receiveID);
                $detail_data['is_updated'] = true;
                StockReceiveDetail::create($detail_data);
            }
            DB::commit();
            return response()->json(['success' => "Update successful!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function deleteStoreReceive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delete_reason' => 'required'
        ], [
            'delete_reason' => 'Delete Reason ဖြည့်ရန်လိုအပ်ပါသည်!'
        ]);
        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $receiveID = $request->stockReceive_deleteID;
                StockReceive::where('stock_receive_id', $receiveID)->update([
                    'is_delete' => true,
                    'delete_reason' => $request->delete_reason,
                    'modified_by' => $request->loginUserID,
                ]);
                StockReceiveDetail::where('stock_receive_id', $receiveID)->update([
                    'is_deleted' => true
                ]);
                DB::commit();
                return response()->json(['success' => "Delete Successful!"]);
                // return redirect()->route('stockControl#stock_receive#receiveListPage')->with(['deleteSuccess' => 'Delete Successfully!']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    //private
    //Add Item Data
    private function addItemData($req)
    {
        $data = [
            'main_category_id' => $req->create_main_category,
            'sub_category_id' => $req->create_sub_category,
            'item_type_id' => $req->create_item_type,
            'item_code' => $req->create_item_code,
            'bar_code' => $req->create_item_code,
            'item_name' => $req->create_item_name,
            'other_name' => $req->create_other_name,
            'unit_id' => $req->create_item_unit,
            'item_image' => $req->create_item_image,
            'location_id' => "1",
            'is_discontinued' => $req->create_is_discontinued,
            'is_deleted' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    public function receiveDetailsPage($id, Request $request)
    {
        $dailyReceiveDate = $request->query('dailyReceiveDate');

        $menu_item = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->whereIn('I.item_type_id', [2, 3])
            ->select('menu_items.*', 'unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->get();

        $selectedReceive = StockReceive::where('stock_receive_id', "=", $id)->get();
        $selectedReceiveDetail = StockReceiveDetail::where('stock_receive_id', "=", $id)
            ->select('stock_receive_details.item_id', 'stock_receive_details.unit_id', 'I.item_code', 'I.bar_code', 'I.item_name', 'U.unit_name', 'quantity', 'stock_receive_details.unit_cost', 'expire_date', 'amount')
            ->join('units as U', 'U.unit_id', '=', 'stock_receive_details.unit_id')
            ->join('menu_items as I', 'I.item_id', '=', 'stock_receive_details.item_id')
            ->get();
        // dd($selectedReceiveDetail->toArray());
        // dd($menu_item->toArray(), $selectedReceive->toArray(), $selectedReceiveDetail->toArray());

        return view('admin.stock_control.stock_receive.receive_details', compact('menu_item',  'selectedReceive', 'selectedReceiveDetail', 'dailyReceiveDate'));
    }
}
