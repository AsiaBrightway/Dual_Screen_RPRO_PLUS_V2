<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    //Public Functions
    //direct config category page
    public function categoryPage()
    {
        $mainCategories = DB::table('main_categories')
            ->where('is_deleted', 0)
            ->orderBy('main_category_name', 'ASC')
            ->get();

        $menuCategories = DB::table('menu_categories as mc')
            ->leftJoin('menu_items as mi', function ($join) {
                $join->on('mi.sub_category_id', '=', 'mc.category_id')
                    ->where(function ($q) {
                        $q->whereNull('mi.is_deleted')
                            ->orWhere('mi.is_deleted', 0);
                    });
            })
            ->where('mc.is_deleted', 0)
            ->select(
                'mc.category_id',
                'mc.main_category_id',
                'mc.menu_category_name',
                'mc.menu_category_image',
                'mc.is_discontinued',
                DB::raw('COUNT(mi.item_id) as menu_item_count')
            )
            ->groupBy(
                'mc.category_id',
                'mc.main_category_id',
                'mc.menu_category_name',
                'mc.menu_category_image',
                'mc.is_discontinued'
            )
            ->orderBy('mc.menu_category_name', 'ASC')
            ->get()
            ->groupBy('main_category_id');

        return view(
            'admin.configuration.item.category.category',
            compact('mainCategories', 'menuCategories')
        );
    }


    private function addMenuCategoryData($req)
    {
        return [
            'menu_category_name' => $req->menu_category_name,
            'is_discontinued'    => $req->is_discontinued == "on" ? 1 : 0,
            'menu_category_image' => null, // File will be handled separately
        ];
    }

    public function createMenuCategory(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'menu_category_name' => [
                'required',
                Rule::unique('menu_categories', 'menu_category_name')
                    ->where(fn($q) => $q->where('is_deleted', 0)),
            ],
        ], [
            'menu_category_name.required' => 'Menu Category Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'menu_category_name.unique' => 'Menu Category Name တူနေပါသည်',
        ]);

        if ($validator->fails()) {
            // AJAX request: return JSON errors
            if ($req->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Normal request: redirect back
            // dd("here");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Save the menu category
        $data = $this->addMenuCategoryData($req);
        $data['main_category_id'] = $req->main_category_id;
        $data['store_location_id'] = 1;
        $data['is_deleted'] = 0;
        $data['modified_by'] = Auth::id();

        if ($req->hasFile('menu_category_image')) {
            $fileName = uniqid() . '_' . $req->file('menu_category_image')->getClientOriginalName();
            $req->file('menu_category_image')->storeAs('public/Images/', $fileName);
            $data['menu_category_image'] = $fileName;
        }

        MenuCategory::create($data);

        // Return success for AJAX
        if ($req->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('menuCategoryPage')->with('success', 'Menu Category created successfully.');
    }


    public function updateMenuCategory(Request $req)
    {
        $id = $req->edit_category_id;

        $validator = Validator::make($req->all(), [
            'edit_category_name' => 'required|unique:menu_categories,menu_category_name,' . $id . ',category_id',
        ], [
            'edit_category_name.required' => 'Category Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'edit_category_name.unique' => 'Category Name တူနေပါသည်',
        ]);

        $validator->validate();

        $data = [
            'menu_category_name' => $req->edit_category_name,
            'is_discontinued' => $req->is_discontinued == "on" ? 1 : 0,
            'modified_by' => Auth::id(),
        ];

        if ($req->hasFile('edit_menu_category_image')) {
            $oldImage = MenuCategory::where('category_id', $id)->value('menu_category_image');
            if ($oldImage) {
                Storage::delete('public/Images/' . $oldImage);
            }
            $fileName = uniqid() . '_' . $req->file('edit_menu_category_image')->getClientOriginalName();
            $req->file('edit_menu_category_image')->storeAs('public/Images', $fileName);
            $data['menu_category_image'] = $fileName;
        }

        MenuCategory::where('category_id', $id)->update($data);

        return redirect()->route('menuCategoryPage')->with('update', 'Menu Category Updated Successfully');
    }

    public function deleteMenuCategory(Request $req)
    {
        $id = $req->delete_category_id;

        $hasItems = MenuItem::where('sub_category_id', $id)
            ->whereIn('is_deleted', [0, null])
            ->exists();

        if ($hasItems) {
            return redirect()
                ->route('menuCategoryPage')
                ->withErrors('This category has menu items and cannot be deleted.');
        }

        MenuCategory::where('category_id', $id)->update([
            'is_deleted'  => 1,
            'modified_by' => Auth::id(),
        ]);

        return redirect()
            ->route('menuCategoryPage')
            ->with('delete', 'Menu Category Deleted Successfully');
    }





    // //bar category create
    // public function createBarCategory(Request $req)
    // {

    //     // $this->validationCheck($req);
    //     $data = $this->addBarCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('bar_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('bar_menu_category_image')->getClientOriginalName();
    //         $req->file('bar_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }

    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //bar category update
    // public function updateBarCategory(Request $req)
    // {

    //     $categoryID = $req->edit_bar_category_id;
    //     $data = $this->addBarCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }

    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_bar_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_bar_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_bar_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }


    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //bar category delete
    // public function deleteBarCategory(Request $req)
    // {

    //     $categoryID = $req->delete_bar_category_id;

    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }

    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //kitchen category create
    // public function createKitchenCategory(Request $req)
    // {

    //     $data = $this->addKitchenCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('kitchen_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('kitchen_menu_category_image')->getClientOriginalName();
    //         $req->file('kitchen_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }
    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //kitchen category update
    // public function updateKitchenCategory(Request $req)
    // {

    //     $categoryID = $req->edit_kitchen_category_id;
    //     $data = $this->addKitchenCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }

    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_kitchen_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_kitchen_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_kitchen_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }


    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //kitchen category delete
    // public function deleteKitchenCategory(Request $req)
    // {

    //     $categoryID = $req->delete_kitchen_category_id;
    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }

    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Refrigerator category create
    // public function createRefrigeratorCategory(Request $req)
    // {

    //     $data = $this->addRefrigeratorCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('refrigerator_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('refrigerator_menu_category_image')->getClientOriginalName();
    //         $req->file('refrigerator_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }
    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Refrigerator category update
    // public function updateRefrigeratorCategory(Request $req)
    // {

    //     $categoryID = $req->edit_refrigerator_category_id;
    //     $data = $this->addRefrigeratorCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }

    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_refrigerator_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_refrigerator_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_refrigerator_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }

    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Refrigerator category delete
    // public function deleteRefrigeratorCategory(Request $req)
    // {

    //     $categoryID = $req->delete_refrigerator_category_id;
    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }
    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Service category create
    // public function createServiceCategory(Request $req)
    // {

    //     $data = $this->addServiceCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('service_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('service_menu_category_image')->getClientOriginalName();
    //         $req->file('service_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }
    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Service category update
    // public function updateServiceCategory(Request $req)
    // {

    //     $categoryID = $req->edit_service_category_id;
    //     $data = $this->addServiceCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_service_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_service_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_service_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }

    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Service category delete
    // public function deleteServiceCategory(Request $req)
    // {

    //     $categoryID = $req->delete_service_category_id;
    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }
    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //noodle category create
    // public function createNoodleCategory(Request $req)
    // {

    //     // $this->validationCheck($req);
    //     $data = $this->addNoodleCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('noodle_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('noodle_menu_category_image')->getClientOriginalName();
    //         $req->file('noodle_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }

    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //noodle category update
    // public function updateNoodleCategory(Request $req)
    // {

    //     $categoryID = $req->edit_noodle_category_id;
    //     $data = $this->addNoodleCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }

    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_noodle_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_noodle_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_noodle_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }


    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //noodle category delete
    // public function deleteNoodleCategory(Request $req)
    // {

    //     $categoryID = $req->delete_noodle_category_id;

    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }

    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //cuisine category create
    // public function createCuisineCategory(Request $req)
    // {

    //     // $this->validationCheck($req);
    //     $data = $this->addCuisineCategoryData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }
    //     if ($req->hasFile('cuisine_menu_category_image')) {
    //         $fileName = uniqid() . '_' . $req->file('cuisine_menu_category_image')->getClientOriginalName();
    //         $req->file('cuisine_menu_category_image')->storeAs('public/Images/', $fileName);
    //         $data['menu_category_image'] = $fileName;
    //     }

    //     MenuCategory::create($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //cuisine category update
    // public function updateCuisineCategory(Request $req)
    // {

    //     $categoryID = $req->edit_cuisine_category_id;
    //     $data = $this->addCuisineCategoryUpdateData($req);

    //     if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
    //         $data['is_discontinued'] = 0;
    //     }
    //     if ($data['is_discontinued'] == "on") {
    //         $data['is_discontinued'] = 1;
    //     }

    //     if ($data['menu_category_image'] != null) {
    //         if ($req->hasFile('edit_cuisine_menu_category_image')) {

    //             $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //             $oldImageName = $oldImageName['menu_category_image'];

    //             if ($oldImageName != null) {
    //                 Storage::delete('public/Images/' . $oldImageName);
    //             }

    //             $fileName = uniqid() . '_' . $req->file('edit_cuisine_menu_category_image')->getClientOriginalName();
    //             $req->file('edit_cuisine_menu_category_image')->storeAs('public/Images', $fileName);
    //             $data['menu_category_image'] = $fileName;
    //         }
    //     } else {
    //         $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //         $data['menu_category_image'] = $oldImageName['menu_category_image'];;
    //     }


    //     MenuCategory::where('category_id', $categoryID)->update($data);
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //cuisine category delete
    // public function deleteCuisineCategory(Request $req)
    // {

    //     $categoryID = $req->delete_cuisine_category_id;

    //     $oldImageName = MenuCategory::select('menu_category_image')->where('category_id', $categoryID)->first()->toArray();
    //     $oldImageName = $oldImageName['menu_category_image'];

    //     if ($oldImageName != null) {
    //         Storage::delete('public/Images/' . $oldImageName);
    //     }

    //     MenuCategory::where('category_id', $categoryID)->delete();
    //     return redirect()->route('config#item#categoryPage');
    // }

    // //Private Functions
    // //add bar category data
    // private function addBarCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->bar_category_name,
    //         'main_category_id' => "1",
    //         'menu_category_image' => $req->bar_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->bar_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add bar category update data
    // private function addBarCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_bar_category_name,
    //         'main_category_id' => "1",
    //         'menu_category_image' => $req->edit_bar_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_bar_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add kitchen category data
    // private function addKitchenCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->kitchen_category_name,
    //         'main_category_id' => "2",
    //         'menu_category_image' => $req->kitchen_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->kitchen_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add kitchen category update data
    // private function addKitchenCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_kitchen_category_name,
    //         'main_category_id' => "2",
    //         'menu_category_image' => $req->edit_kitchen_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_kitchen_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add Refrigerator category data
    // private function addRefrigeratorCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->refrigerator_category_name,
    //         'main_category_id' => "3",
    //         'menu_category_image' => $req->refrigerator_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->refrigerator_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add Refrigerator category update data
    // private function addRefrigeratorCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_refrigerator_category_name,
    //         'main_category_id' => "3",
    //         'menu_category_image' => $req->edit_refrigerator_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_refrigerator_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add Service category data
    // private function addServiceCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->service_category_name,
    //         'main_category_id' => "4",
    //         'menu_category_image' => $req->service_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->service_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add Service category update data
    // private function addServiceCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_service_category_name,
    //         'main_category_id' => "4",
    //         'menu_category_image' => $req->edit_service_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_service_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add noodle category data
    // private function addNoodleCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->noodle_category_name,
    //         'main_category_id' => "5",
    //         'menu_category_image' => $req->noodle_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->noodle_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add noodle category update data
    // private function addNoodleCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_noodle_category_name,
    //         'main_category_id' => "5",
    //         'menu_category_image' => $req->edit_noodle_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_noodle_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add cuisine category data
    // private function addCuisineCategoryData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->cuisine_category_name,
    //         'main_category_id' => "6",
    //         'menu_category_image' => $req->cuisine_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->cuisine_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //add cuisine category update data
    // private function addCuisineCategoryUpdateData($req)
    // {
    //     $data = [
    //         'menu_category_name' => $req->edit_cuisine_category_name,
    //         'main_category_id' => "6",
    //         'menu_category_image' => $req->edit_cuisine_menu_category_image,
    //         'store_location_id' => "1",
    //         'is_discontinued' => $req->edit_cuisine_is_discontinued,
    //         'is_deleted' => "0",
    //         'modified_by' => Auth::user()->id,
    //     ];
    //     return $data;
    // }

    // //Validation
    // private function validationCheck($req)
    // {
    //     $validationRules = [
    //         'edit_category_name' => 'required|min:5|unique:MenuCategory,menu_category_name',
    //     ];

    //     $validationMessages = [
    //         'edit_category_name.required' => 'Category Name ဖြည့်ရန်လိုအပ်ပါသည်',
    //         'edit_category_name.min' => 'အနည်းဆုံး၅လုံးရှိရပါမည်',
    //         'edit_category_name.unique' => 'Category Name တူနေပါသည်'
    //     ];

    //     Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    // }
}
