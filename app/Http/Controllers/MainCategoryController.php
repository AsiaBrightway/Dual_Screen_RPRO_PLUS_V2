<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MainCategoryController extends Controller
{
    // =======================
    // MAIN CATEGORY LIST PAGE
    // =======================
    public function index()
    {
        $mainCategories = MainCategory::where('is_deleted', 0)
            ->get();

        return view('admin.configuration.item.category.category', compact('mainCategories'));
    }


    // =======================
    // CREATE MAIN CATEGORY
    // =======================
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'main_category_name' => [
                'required',
                Rule::unique('main_categories', 'main_category_name')
                    ->where(fn($q) => $q->where('is_deleted', 0)),
            ],
        ], [
            'main_category_name.required' => 'Main Category Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'main_category_name.unique' => 'Main Category Name တူနေပါသည်'
        ]);

        $validator->validate();

        MainCategory::create([
            'main_category_name' => $req->main_category_name,
            'store_location_id' => '1',
            'is_discontinued' => $req->is_discontinued == "on" ? 1 : 0,
            'is_deleted' => 0,
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('menuCategoryPage')->with('success', 'Main Category Created Successfully');
    }


    // =======================
    // GET CATEGORY BY ID (AJAX)
    // =======================
    public function getById(Request $req)
    {
        $id = $req->id;
        $data = MainCategory::where('main_category_id', $id)->first();

        return response()->json($data);
    }


    public function update(Request $req)
    {
        $id = $req->edit_main_category_id;
        $validator = Validator::make($req->all(), [
            'main_category_name' => 'required',
            Rule::unique('main_categories', 'main_category_name')
                ->ignore($id, 'main_category_id')
        ], [
            'main_category_name.required' => 'Category Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'main_category_name.unique'   => 'Category Name သည်ရှိပြီးသားဖြစ်နေပါသည်',
        ]);

        $validator->validate();


        MainCategory::where('main_category_id', $id)->update([
            'main_category_name' => $req->main_category_name,
            'store_location_id' => '1',
            'is_discontinued' => $req->is_discontinued == "on" ? 1 : 0,
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('menuCategoryPage')->with('update', 'Main Category Updated Successfully');
    }


    // =======================
    // DELETE (SOFT DELETE)
    // =======================
    public function delete(Request $req)
    {
        $id = $req->delete_main_category_id;

        MainCategory::where('main_category_id', $id)->update([
            'is_deleted' => 1,
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('menuCategoryPage')->with('delete', 'Main Category Deleted Successfully');
    }
}
