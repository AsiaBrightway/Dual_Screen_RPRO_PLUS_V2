<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\StockIssueType;
use App\Models\Supplier;

class ReportSelectBindingController extends Controller
{
    public function bindingMenuCategory(){
    try {
            $menuCategoryList = MenuCategory::query()->where(['is_discontinued' => 0])->select('category_id as id','menu_category_name as name')->get();
            return response()->json(['success' => $menuCategoryList]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function bindingStockItem(){
        try {
                $menuItemList = MenuItem::query()->where(['is_discontinued' => 0,'is_deleted' => 0])->whereIn('item_type_id',[1,3])
                                ->select('item_id as id','item_name as name')->get();
                return response()->json(['success' => $menuItemList]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
    }

    public function bindingStockIssueType(){
        try {
                $issueTypeList = StockIssueType::query()->where(['is_discontinued' => 0])
                                ->select('issue_type_id  as id','issue_type_name_1 as name')->get();
                return response()->json(['success' => $issueTypeList]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
    }

    public function bindingSupplier(){
        try {
                $supplierList = Supplier::query()->where(['is_discontinued' => 0])
                                ->select('supplier_id  as id','supplier_name as name')->get();
                return response()->json(['success' => $supplierList]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
    }
}
