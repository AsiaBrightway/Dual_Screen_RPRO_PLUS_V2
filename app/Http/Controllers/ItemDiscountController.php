<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\ItemDiscount;
use App\Models\ItemSellingPrice;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemDiscountController extends Controller
{
    public function discountPage()
    {
        $mainCategories = MainCategory::where('is_deleted', 0)->where('is_discontinued', 0)->get()->toArray();
        $subCategories = [];
        $discounts = ItemDiscount::select('*')
            ->join('menu_items', 'item_discounts.item_id', 'menu_items.item_id')
            ->get()
            ->sortBy('item_discount_id')
            ->toArray();
        return view('admin.configuration.item.discount.discount', compact('mainCategories', 'subCategories', 'discounts'));
    }

    //Get Sub Category By Main Category (dropdown)
    public function getSubCategoryByMainCategory(Request $req)
    {
        $mainCategory_id = $req->query('mainCategoryID');
        $subCategory = MenuCategory::where('main_category_id', $mainCategory_id)->where('is_deleted', 0)->get();
        return response()->json($subCategory);
    }

    //Get Item by Main Category and Sub Category  (dropdown)
    public function getItemByMainCategoryAndSubCategory(Request $req)
    {
        $mainCategoryID = $req->query('mainCategoryID');
        $subCategoryID = $req->query('subCategoryID');

        $items = MenuItem::where('main_category_id', $mainCategoryID)->where('sub_category_id', $subCategoryID)->get();

        return response()->json($items);
    }

    //Get Item Price by Item ID  (dropdown)
    public function getItemPriceByItemID(Request $req)
    {
        $itemID = $req->query('itemID');
        $items = ItemSellingPrice::where('item_id', $itemID)->get();

        return response()->json($items);
    }

    //discount create
    public function createDiscount(Request $req)
    {
        $this->validationCheck($req);
        $data = $this->addDiscountData($req);
        ItemDiscount::create($data);
        return redirect()->route('config#item#discountPage')->with('success', 'Item Discount Created Successfully');
    }

    //discount update page
    public function discountUpdatePage($item_discount_id)
    {
        $itemDiscount = ItemDiscount::where('item_discount_id', $item_discount_id)->first();
        $itemId = $itemDiscount->item_id;
        $item = MenuItem::where('item_id', $itemId)->first();
        $mainCategories = MainCategory::where('main_category_id', $item->main_category_id)->get()->toArray();
        $subCategories = MenuCategory::where('category_id', $item->sub_category_id)->get()->toArray();
        $items = MenuItem::where('item_id', $itemId)->get()->toArray();

        // $mainCategories = MainCategory::get()->toArray();
        // $subCategories = [];
        $discounts = ItemDiscount::select('*')
            ->join('menu_items', 'item_discounts.item_id', 'menu_items.item_id')
            ->get()
            ->sortBy('item_discount_id')
            ->toArray();

        $updateData = ItemDiscount::where('item_discount_id', $item_discount_id)
            ->join('menu_items', 'item_discounts.item_id', 'menu_items.item_id')
            ->get()->toArray();

        return view('admin.configuration.item.discount.discount_update', compact('mainCategories', 'subCategories', 'discounts', 'updateData', 'items'));
    }

    //discount update
    public function updateDiscount(Request $req)
    {

        $discountID = $req->edit_discount_id;
        $this->updateValidationCheck($req);
        $data = $this->addDiscountData($req);
        ItemDiscount::where('item_discount_id', $discountID)->update($data);
        return redirect()->route('config#item#discountPage')->with('update', 'Item Discount Updated Successfully');
    }

    //discount delete
    public function deleteDiscount(Request $req)
    {

        $discountID = $req->delete_discount_id;

        ItemDiscount::where('item_discount_id', $discountID)->delete();
        return redirect()->route('config#item#discountPage')->with('delete', 'Item Discount Deleted Successfully');
    }

    //Private Functions
    //add discount data
    private function addDiscountData($req)
    {
        $data = [
            'item_id' => $req->items,
            'description' => $req->description,
            'other_description' => $req->other_description,
            'item_price' => $req->item_price,
            'buy_quantity' => $req->buy_quantity,
            'discount_type' => $req->radio_discount_type,
            'amount_discount' => $req->amount_discount,
            'percent_discount' => $req->percent_discount,
            'promotion_price' => $req->promotion_price,
            'monday' => $req->monday,
            'tuesday' => $req->tuesday,
            'wednesday' => $req->wednesday,
            'thursday' => $req->thursday,
            'friday' => $req->friday,
            'saturday' => $req->saturday,
            'sunday' => $req->sunday,
            'start_date' => $req->start_date,
            'end_date' => $req->end_date,
            'start_happy_hour' => $req->start_happy_hour,
            'end_happy_hour' => $req->end_happy_hour,
            'location_id' => "1",
            'is_updated' => "0",
            'is_deleted' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {

        $validationRules = [
            'main_category' => 'required|not_in:0',
            'sub_category' => 'required',
            'items' => 'required|unique:item_discounts,item_id',
            'description' => 'required',
            'buy_quantity' => 'required|numeric',
            'amount_discount' => 'required|numeric',
            'percent_discount' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $validationMessages = [
            'main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
            'items.required' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
            'items.unique' => 'Item Name တူနေပါသည်',
            'description.required' => 'Description ဖြည့်ရန်လိုအပ်ပါသည်',
            'buy_quantity.required' => 'Buy Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
            'buy_quantity.numeric' => 'Buy Quantity သည် Number ဖြစ်ရပါမည်',
            'amount_discount.required' => 'Amount Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'amount_discount.numeric' => 'Amount Discount သည် Number ဖြစ်ရပါမည်',
            'percent_discount.required' => 'Percent Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'percent_discount.numeric' => 'Perceont Discount သည် Number ဖြစ်ရပါမည်',
            'start_date.required' => 'Start Date ရွေးရန်လိုအပ်ပါသည်',
            'end_date.required' => 'End Date ရွေးရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
    private function updateValidationCheck($req)
    {

        $validationRules = [
            'main_category' => 'required|not_in:0',
            'sub_category' => 'required',
            'items' => 'required',
            'description' => 'required',
            'buy_quantity' => 'required|numeric',
            'amount_discount' => 'required|numeric',
            'percent_discount' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $validationMessages = [
            'main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
            'items.required' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
            'items.unique' => 'Item Name တူနေပါသည်',
            'description.required' => 'Description ဖြည့်ရန်လိုအပ်ပါသည်',
            'buy_quantity.required' => 'Buy Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
            'buy_quantity.numeric' => 'Buy Quantity သည် Number ဖြစ်ရပါမည်',
            'amount_discount.required' => 'Amount Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'amount_discount.numeric' => 'Amount Discount သည် Number ဖြစ်ရပါမည်',
            'percent_discount.required' => 'Percent Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'percent_discount.numeric' => 'Perceont Discount သည် Number ဖြစ်ရပါမည်',
            'start_date.required' => 'Start Date ရွေးရန်လိုအပ်ပါသည်',
            'end_date.required' => 'End Date ရွေးရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
