<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MenuItem;
use App\Models\ItemDiscount;
use App\Models\MainCategory;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCatalogueController extends Controller
{
    //direct menu catalogue page
    public function menuCataloguePage()
    {
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
            ->get()->toArray();

        $items = $this->addStoreQtyToItems($dbItems);
        return view('catalogue.menu_catalogue', compact('mainCategories', 'menuCategories', 'items',));
    }

    public function getItemBySearchKey()
    {
        $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
            ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
            ->where('is_deleted', 0)
            ->where('is_discontinued', 0)
            ->when(request('searchKey'), function ($query) {
                $key = request('searchKey');
                $query->where(function ($subQuery) use ($key) {
                    $subQuery->where('item_name', 'like', '%' . $key . '%')
                        ->orWhere('other_name', 'like', '%' . $key . '%');
                });
            })
            ->get();

        $items = $this->addStoreQtyToItems($dbItems);

        return response()->json($items);
    }
    public function getItemBySubCategory(Request $req)
    {
        $selectedSubCategoryID = $req->query('selectedSubCategoryID');
        if (
            $selectedSubCategoryID == 0 || $selectedSubCategoryID == "0"
        ) {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where(
                    'is_deleted',
                    0
                )
                ->where('is_discontinued', 0)
                ->get()->toArray();

            $items = $this->addStoreQtyToItems($dbItems);
        } else {
            $dbItems = MenuItem::select('*', 'item_selling_prices.item_selling_price as item_price')
                ->join('item_selling_prices', 'menu_items.item_id', 'item_selling_prices.item_id')
                ->where(
                    'is_deleted',
                    0
                )
                ->where('is_discontinued', 0)
                ->where('sub_category_id', $selectedSubCategoryID)
                ->get()->toArray();

            $items = $this->addStoreQtyToItems($dbItems);
        }
        // dd($items);

        return response()->json($items);
    }

    private function addStoreQtyToItems($dbItems)
    {
        $today = Carbon::today();
        $items = [];
        for ($i = 0; $i < count($dbItems); ++$i) {
            $storeQty = StockIssueController::getStockBalance($dbItems[$i]['item_id'], $dbItems[$i]['unit_id'])[0];
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
}
