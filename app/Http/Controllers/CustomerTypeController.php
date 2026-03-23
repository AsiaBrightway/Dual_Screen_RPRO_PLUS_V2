<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerTypeController extends Controller
{
    //direct customer type page
    public function customerTypePage()
    {
        $customerTypes = CustomerType::get()->toArray();
        return view('admin.customer.customer_type', compact('customerTypes'));
    }

    //customer type create
    public function createCutomerType(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addCustomerTypeData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        CustomerType::create($data);
        return redirect()->route('customer#customerTypePage')->with('success', 'Customer Type created successfully.');
    }

    //Customer type update
    public function updateCutomerType(Request $req)
    {

        $customerTypeID = $req->edit_customer_type_id;
        $data = $this->addCustomerTypeUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        CustomerType::where('customer_type_id', $customerTypeID)->update($data);
        return redirect()->route('customer#customerTypePage');
    }

    //Customer Type delete
    public function deleteCutomerType(Request $req)
    {

        $customerTypeID = $req->delete_customer_type_id;

        CustomerType::where('customer_type_id', $customerTypeID)->delete();
        return redirect()->route('customer#customerTypePage');
    }

    //Private Functions
    //add customer Type data
    private function addCustomerTypeData($req)
    {
        $data = [
            'customer_type_name' => $req->customer_type_name,
            'other_name' => $req->other_name,
            'customer_type_code' => $req->customer_type_code,
            'is_discontinued' => $req->is_discontinued,
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add customer type update data
    private function addCustomerTypeUpdateData($req)
    {
        $data = [
            'customer_type_name' => $req->edit_customer_type_name,
            'other_name' => $req->edit_other_name,
            'customer_type_code' => $req->edit_customer_type_code,
            'is_discontinued' => $req->edit_customer_type_is_discontinued,
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'customer_type_name' => 'required|unique:customer_types,customer_type_name',
            'customer_type_code' => 'required|unique:customer_types,customer_type_code'
        ];

        $validationMessages = [
            'customer_type_name.required' => 'Customer Type Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'customer_type_name.unique' => 'Customer Type Name တူနေပါသည်',
            'customer_type_code.required' => 'Customer Type Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'customer_type_code.unique' => 'Customer Type Code တူနေပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
