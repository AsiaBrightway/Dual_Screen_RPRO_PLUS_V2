<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\ItemType;
use App\Models\MenuItem;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\ItemSellingPrice;
use App\Models\StockReceiveDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    public function itemPage()
    {
        $mainCategories = MainCategory::where('is_deleted', 0)->get()->toArray();
        $subCategories = [];
        $itemTypes = ItemType::get()->toArray();
        $units  = Unit::where('is_discontinued', 0)->get()->toArray();

        $items = MenuItem::select(
            'menu_items.*',
            'menu_items.other_name as item_other_name',
            'menu_items.is_discontinued as item_is_discontinued',
            'main_categories.main_category_name',
            'menu_categories.menu_category_name',
            'units.unit_name',
            'item_types.item_type_name',
            DB::raw("
                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM order_details
                        WHERE order_details.item_id = menu_items.item_id
                    )
                    THEN 1 ELSE 0
                END AS has_orders
            ")
        )
            ->join('main_categories', 'menu_items.main_category_id', 'main_categories.main_category_id')
            ->leftJoin('menu_categories', 'menu_items.sub_category_id', 'menu_categories.category_id')
            ->join('item_types', 'menu_items.item_type_id', 'item_types.item_type_id')
            ->join('units', 'menu_items.unit_id', 'units.unit_id')
            ->where('menu_items.is_deleted', 0)
            ->orderBy('menu_items.item_id')
            ->get()
            ->toArray();
        // dd($items);
        return view(
            'admin.configuration.item.item.item',
            compact('items', 'mainCategories', 'subCategories', 'itemTypes', 'units')
        );
    }

    //direct config item page
    // public function itemPage()
    // {
    //     $mainCategories = MainCategory::where('is_deleted', 0)->get()->toArray();
    //     $subCategories = [];
    //     $itemTypes = ItemType::get()->toArray();
    //     $units  = Unit::where('is_discontinued', 0)
    //         ->get()->toArray();
    //     $items = MenuItem::select('*', 'menu_items.other_name as item_other_name', 'menu_items.is_discontinued as item_is_discontinued')
    //         ->join('main_categories', 'menu_items.main_category_id', 'main_categories.main_category_id')
    //         ->leftjoin('menu_categories', 'menu_items.sub_category_id', 'menu_categories.category_id')
    //         ->join('item_types', 'menu_items.item_type_id', 'item_types.item_type_id')
    //         ->join('units', 'menu_items.unit_id', 'units.unit_id')
    //         ->where('menu_items.is_deleted', 0)
    //         ->orderBy('menu_items.item_id')
    //         ->get()
    //         ->sortBy('item_id')
    //         ->toArray();

    //     return view('admin.configuration.item.item.item', compact('items', 'mainCategories', 'subCategories', 'itemTypes', 'units'));
    // }

    //Get Sub Category By Main Category (dropdown)
    public function getSubCategoryByMainCategory(Request $req)
    {
        $mainCategory_id = $req->query('mainCategoryID');
        $subCategory = MenuCategory::where('main_category_id', $mainCategory_id)->where('is_deleted', 0)->get();
        return response()->json($subCategory);
    }

    //Get Item Details by Item (dropdown)
    public function getItemDetailsByItem(Request $req)
    {
        $item_id = $req->query('itemID');
        if ($item_id != 0) {
            $itemDetail = MenuItem::select('*', 'main_categories.main_category_name as category_name', 'menu_categories.menu_category_name as sub_category_name', 'units.unit_name as unit_name')
                ->join('main_categories', 'menu_items.main_category_id', 'main_categories.main_category_id')
                ->join('menu_categories', 'menu_items.sub_category_id', 'menu_categories.category_id')
                ->join('units', 'menu_items.unit_id', 'units.unit_id')
                ->where('item_id', $item_id)
                ->where('menu_items.is_deleted', 0)
                ->first();
            $mainCategoryName = $itemDetail['category_name'];
            $subCategoryName = $itemDetail['sub_category_name'];
            $unitName = $itemDetail['unit_name'];
            $unitID = $itemDetail['unit_id'];

            if ($itemDetail['item_type_id'] != 1) {
                $receiveDetailList = StockReceiveDetail::query()
                    ->where(['item_id' => $item_id, 'unit_id' => $unitID, 'is_deleted' => 0])
                    ->selectRaw('batch_number')
                    ->selectRaw('unit_cost')
                    ->selectRaw('created_at')
                    ->groupBy(['batch_number', 'unit_cost', 'created_at'])->get();

                $purchaseDetailList = PurchaseDetail::query()
                    ->where(['item_id' => $item_id, 'unit_id' => $unitID, 'is_deleted' => 0])
                    ->selectRaw('batch_number')
                    ->selectRaw('unit_cost')
                    ->selectRaw('created_at')
                    ->groupBy(['batch_number', 'unit_cost',  'created_at'])->get();

                $stockInLists = collect(array_merge($receiveDetailList->toArray(), $purchaseDetailList->toArray()))
                    ->sortBy('batch_number')
                    ->values();
                $lastBatchNumberItem = $stockInLists->last();
                $unitCost = $lastBatchNumberItem['unit_cost'];
            } else {
                $unitCost = 0;
            }

            // $purchaseDetails = PurchaseDetail::select('*')
            //     ->where('item_id', $item_id)
            //     ->where('unit_id', $unitID)->get()->toArray();

            // if ($purchaseDetails != null) {
            //     $purchaseDetails = collect($purchaseDetails);
            //     $totalPurchaseQty = $purchaseDetails->sum('quantity');
            //     $totalPurchaseCost = $purchaseDetails->sum(function ($detail) {
            //         return $detail['quantity'] * $detail['unit_cost'];
            //     });
            // } else {
            //     $totalPurchaseQty = 0;
            //     $totalPurchaseCost = 0;
            // }

            // $stockReceiveDetails = StockReceiveDetail::select('*')
            //     ->where('item_id', $item_id)
            //     ->where('unit_id', $unitID)->get()->toArray();
            // if ($stockReceiveDetails != null) {
            //     $stockReceiveDetails = collect($stockReceiveDetails);
            //     $totalReceiveQty = $stockReceiveDetails->sum('quantity');
            //     $totalReceiveCost = $stockReceiveDetails->sum(function ($detail) {
            //         return $detail['quantity'] * $detail['unit_cost'];
            //     });
            // } else {
            //     $totalReceiveQty = 0;
            //     $totalReceiveCost = 0;
            // }

            // $stockTotalQty = $totalPurchaseQty + $totalReceiveQty;
            // $stockTotalCost = $totalPurchaseCost + $totalReceiveCost;

            // if ($stockTotalQty != 0 && $stockTotalCost != 0) {
            //     $avgCost = round($stockTotalCost / $stockTotalQty);
            // } else {
            //     $avgCost = 0;
            // }

            return response()->json(['mainCategoryName' => $mainCategoryName, 'subCategoryName' => $subCategoryName, 'unitName' => $unitName, 'unitID' => $unitID, 'unitCost' => $unitCost]);
        }
    }

    //Create Item
    public function createItem(Request $req)
    {
        $this->validationCheck($req);

        $data = $this->addItemData($req);
        $data['is_discontinued'] = ($data['is_discontinued'] === "on") ? 1 : 0;

        if ($req->hasFile('item_image')) {
            $fileName = uniqid() . '_' . $req->file('item_image')->getClientOriginalName();
            $req->file('item_image')->storeAs('public/Images/', $fileName);
            $data['item_image'] = $fileName;
        }
        try {
            DB::beginTransaction();

            //  Insert MenuItem first
            $menuItem = MenuItem::create($data);

            try {

                $sellingPriceData = $this->addPriceControlData($req);
                $sellingPriceData['item_id'] = $menuItem->id;
                ItemSellingPrice::create($sellingPriceData);
            } catch (\Exception $e) {
                // log error but don’t rollback MenuItem
                Log::error("ItemSellingPrice insert failed: " . $e->getMessage());
            }
            DB::commit();
            return redirect()->route('config#item#itemPage')->with('success', 'Item created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    //update item
    public function updateItem(Request $req)
    {
        $item_id = $req->edit_item_id;
        $data = $this->addUpdateItemData($req);
        try {
            DB::beginTransaction();
            if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
                $data['is_discontinued'] = 0;
            }
            if ($data['is_discontinued'] == "on") {
                $data['is_discontinued'] = 1;
            }

            if ($data['item_image'] != null) {
                if ($req->hasFile('edit_item_image')) {

                    $oldImageName = MenuItem::select('item_image')->where('item_id', $item_id)->first()->toArray();
                    $oldImageName = $oldImageName['item_image'];

                    if ($oldImageName != null) {
                        Storage::delete('public/Images/' . $oldImageName);
                    }

                    $fileName = uniqid() . '_' . $req->file('edit_item_image')->getClientOriginalName();
                    $req->file('edit_item_image')->storeAs('public/Images', $fileName);
                    $data['item_image'] = $fileName;
                }
            } else {
                $oldImageName = MenuItem::select('item_image')->where('item_id', $item_id)->first()->toArray();
                $data['item_image'] = $oldImageName['item_image'];;
            }

            MenuItem::where('item_id', $item_id)->update($data);
            ItemSellingPrice::where('item_id', $item_id)->update([
                'unit_id' => $data['unit_id'],
                'modified_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()->route('config#item#itemPage')->with('update', 'Item Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    //delete item
    public function deleteItem(Request $req)
    {
        $item_id = $req->delete_item_id;

        $usedInOrders = DB::table('order_details')
            ->where('item_id', $item_id)
            ->exists();

        if ($usedInOrders) {
            return redirect()
                ->route('config#item#itemPage')
                ->with('delete_error', 'This item has orders. Please remove order records first.');
        }

        try {
            DB::beginTransaction();

            $item = MenuItem::where('item_id', $item_id)->first();

            if ($item && $item->item_image) {
                Storage::delete('public/Images/' . $item->item_image);
            }

            MenuItem::where('item_id', $item_id)->update([
                'is_deleted'  => 1,
                'modified_by' => Auth::user()->id,
            ]);

            DB::commit();

            return redirect()
                ->route('config#item#itemPage')
                ->with('delete', 'Item Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('config#item#itemPage')
                ->with('delete_error', 'Something went wrong');
        }
    }



    // public function deleteItem(Request $req)
    // {

    //     $item_id = $req->delete_item_id;
    //     try {
    //         DB::beginTransaction();
    //         $oldImageName = MenuItem::select('item_image')->where('item_id', $item_id)->first()->toArray();
    //         $oldImageName = $oldImageName['item_image'];

    //         if ($oldImageName != null) {
    //             Storage::delete('public/Images/' . $oldImageName);
    //         }
    //         MenuItem::where('item_id', $item_id)->update([
    //             'is_deleted' => 1,
    //             'modified_by' => Auth::user()->id,
    //         ]);

    //         DB::commit();
    //         return redirect()->route('config#item#itemPage')->with('delete', 'Item Deleted Successfully');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['errors' => $e->getMessage()]);
    //     }
    // }

    //Item Name validation
    public function checkUniqueItemName(Request $request)
    {
        $itemName = $request->item_name;

        // Check if item code exists in the menu_items table
        $exists = MenuItem::where('item_name', $itemName)->where('is_deleted', 0)
            ->exists();

        // Return JSON response to jQuery validation
        return response()->json(!$exists);
    }

    //Item code validation
    public function checkUniqueItemCode(Request $request)
    {
        $itemCode = $request->item_code;

        // Check if item code exists in the menu_items table
        $exists = MenuItem::where('item_code', $itemCode)->where('is_deleted', 0)
            ->exists();

        // Return JSON response to jQuery validation
        return response()->json(!$exists);
    }
    //Bar code validation
    public function checkUniqueBarCode(Request $request)
    {
        $barCode = $request->bar_code;

        // Check if item code exists in the menu_items table
        $exists = MenuItem::where('bar_code', $barCode)->where('is_deleted', 0)
            ->exists();

        // Return JSON response to jQuery validation
        return response()->json(!$exists);
    }

    // Get next available number for an item code prefix
    public function getNextItemCodeNumber(Request $request)
    {
        $prefix = $request->prefix;
        $excludeId = $request->exclude_id;

        $query = MenuItem::where('item_code', 'LIKE', $prefix . '%')
            ->where('is_deleted', 0);

        // Exclude the current item when editing
        if ($excludeId) {
            $query->where('item_id', '!=', $excludeId);
        }

        // Find the maximum number suffix among existing codes
        $maxNumber = 0;
        $codes = $query->pluck('item_code');
        foreach ($codes as $code) {
            $suffix = substr($code, strlen($prefix));
            if (is_numeric($suffix) && (int)$suffix > $maxNumber) {
                $maxNumber = (int)$suffix;
            }
        }

        return response()->json(['next_number' => $maxNumber + 1]);
    }

    //private
    //Add Item Data
    private function addItemData($req)
    {
        $data = [
            'main_category_id' => $req->main_category,
            'sub_category_id' => $req->sub_category,
            'item_type_id' => $req->item_type,
            'item_code' => $req->item_code,
            'bar_code' => $req->item_code,
            'item_name' => $req->item_name,
            'other_name' => $req->other_name,
            'unit_id' => $req->item_unit,
            'item_image' => $req->item_image,
            'location_id' => "1",
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add item selling price data
    private function addPriceControlData($req)
    {
        $data = [
            'unit_id' => $req->item_unit,
            'currency_id' => 1,
            'unit_cost' => $req->unit_cost,
            'item_selling_price' => $req->selling_price,
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add update item data
    private function addUpdateItemData($req)
    {
        $data = [
            'main_category_id' => $req->edit_main_category,
            'sub_category_id' => $req->edit_sub_category,
            'item_type_id' => $req->edit_item_type,
            'item_code' => $req->edit_item_code,
            'bar_code' => $req->edit_bar_code,
            'item_name' => $req->edit_item_name,
            'other_name' => $req->edit_other_name,
            'unit_id' => $req->edit_item_unit,
            'item_image' => $req->edit_item_image,
            'location_id' => "1",
            'is_discontinued' => $req->edit_is_discontinued,
            'is_deleted' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }


    //validation check
    private function validationCheck($req)
    {
        $validationRules = [
            'main_category' => 'required|not_in:0',
            'sub_category' => 'required',
            'item_code'     => [ 'required',Rule::unique('menu_items', 'item_code')->where(fn ($q) => $q->where('is_deleted', 0))],
            // 'bar_code' => 'required|unique:menu_items,bar_code',
            'item_name' => 'required',
            'item_image' => 'mimes:jpg,jpeg,png',
            'selling_price' => [
                'required',
                'numeric',
                'gte:' . $req->unit_cost, // Ensure selling price is greater than or equal to avgcost
            ]
        ];

        $validationMessages = [
            'main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
            'item_code.required' => 'Item Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'item_code.unique' => 'Item Code တူနေပါသည်',
            // 'bar_code.required' => 'Bar Code ဖြည့်ရန်လိုအပ်ပါသည်',
            // 'bar_code.unique' => 'Bar Code တူနေပါသည်',
            'item_name.required' => 'Item Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'item_image.mimes' => 'Image သည် JPG, JPEG, PNG Format သာဖြစ်ရပါမည်',
            'selling_price.required' => 'Selling Price ဖြည့်ရန်လိုအပ်ပါသည်',
            'selling_price.numeric' => 'Selling Price သည် Number ဖြစ်ရပါမည်',
            'selling_price.gte' => 'Selling Price သည် အနည်းဆုံး ' . $req->unit_cost . ' ရှိရပါမည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
