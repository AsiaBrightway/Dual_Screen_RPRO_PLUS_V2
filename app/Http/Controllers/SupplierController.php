<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Supplier;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    //direct config supplier page
    public function supplierPage()
    {
        $cities = City::where('is_discontinued', 0)->get()->toArray();
        $townships = Township::where('is_discontinued')->get()->toArray();
        $suppliers = Supplier::select('*', 'suppliers.other_name as supplier_other_name', 'suppliers.is_discontinued as supplier_is_discontinued')
            ->join('cities', 'suppliers.city_id', 'cities.city_id')
            ->join('townships', 'suppliers.township_id', 'townships.township_id')
            ->get()
            ->toArray();
        return view('admin.supplier.supplier', compact('cities', 'townships', 'suppliers'));
    }

    //direct config supplier list page
    public function supplierListPage()
    {
        $cities = City::where('is_discontinued', 0)->get()->toArray();
        $townships = Township::where('is_discontinued', 0)->get()->toArray();
        $suppliers = Supplier::select('*', 'suppliers.other_name as supplier_other_name', 'suppliers.is_discontinued as supplier_is_discontinued')
            ->join('cities', 'suppliers.city_id', 'cities.city_id')
            ->join('townships', 'suppliers.township_id', 'townships.township_id')
            ->get()
            ->toArray();
        return view('admin.supplier.supplier_list', compact('cities', 'townships', 'suppliers'));
    }

    //table create
    public function createSupplier(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addSupplierData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Supplier::create($data);
        return redirect()->route('supplier#supplierPage')->with(['success' => 'Supplier Added Successfully']);
    }

    //supplier update
    public function updateSupplier(Request $req)
    {

        $supplierID = $req->edit_supplier_id;
        $data = $this->addSupplierUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Supplier::where('supplier_id', $supplierID)->update($data);
        return back()->with(['update' => 'Supplier Updated Successfully']);
    }

    //supplier delete
    public function deleteSupplier(Request $req)
    {

        $supplierID = $req->delete_supplier_id;

        Supplier::where('supplier_id', $supplierID)->delete();
        return back()->with(['delete' => 'Supplier Deleted Successfully']);
    }

    //Private Functions
    //add Supplier data
    private function addSupplierData($req)
    {
        $data = [
            'supplier_name' => $req->supplier_name,
            'other_name' => $req->other_name,
            'supplier_code' => $req->supplier_code,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
            'city_id' => $req->city,
            'township_id' => $req->township,
            'address' => $req->address,
            'remark' => $req->remark,
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'location_id' => "1",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add update supplier data
    private function addSupplierUpdateData($req)
    {
        $data = [
            'supplier_name' => $req->edit_supplier_name,
            'other_name' => $req->edit_other_name,
            'supplier_code' => $req->edit_supplier_code,
            'phone_number' => $req->edit_phone_number,
            'email' => $req->edit_email,
            'city_id' => $req->edit_city,
            'township_id' => $req->edit_township,
            'address' => $req->edit_address,
            'remark' => $req->edit_remark,
            'is_discontinued' => $req->edit_supplier_is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'location_id' => "1",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'supplier_name' => 'required',
            'supplier_code' => 'required|unique:suppliers,supplier_code',
            'phone_number' => 'required|numeric|digits_between:7,11',
            'city' => 'required|not_in:0',
            'township' => 'required',
            'address' => 'required',
        ];

        $validationMessages = [
            'supplier_name.required' => 'Supplier Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'supplier_code.required' => 'Supplier Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone_number.required' => 'Phone Number ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone_number.numeric' => 'Phone Number သည် ကိန်းဂဏန်းများဖြစ်ရမည်',
            'phone_number.digits_between' => 'Phone Number မှားယွင်းနေပါသည်',
            'city.required' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'city.not_in' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'township.required' => 'Township ရွေးရန်လိုအပ်ပါသည်',
            'address.required' => 'Address ဖြည့်ရန်လိုအပ်ပါသည်',

        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
