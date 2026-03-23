<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    //direct customer page
    public function customerPage()
    {
        $customerTypes = CustomerType::get()->toArray();
        $cities = City::where('is_discontinued', 0)
            ->get()
            ->toArray();
        $customers = Customer::select('*', 'customers.other_name as customer_other_name', 'customers.is_discontinued as customer_is_discontinued')
            ->join('customer_types', 'customers.customer_type_id', 'customer_types.customer_type_id')
            ->join('cities', 'customers.city_id', 'cities.city_id')
            ->join('townships', 'customers.township_id', 'townships.township_id')
            ->get()
            ->toArray();

        return view('admin.customer.customer', compact('customerTypes', 'cities', 'customers'));
    }

    //Customer create
    public function createCutomer(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addCustomerData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Customer::create($data);
        return redirect()->route('customer#customerPage')->with('success', 'Customer created successfully.');
    }

    // customer update page
    public function customerUpdatePage($customer_id)
    {
        $customerTypes = CustomerType::get()->toArray();
        $cities = City::get()->toArray();
        $customers = Customer::select('*', 'customers.other_name as customer_other_name', 'customers.is_discontinued as customer_is_discontinued')
            ->where('customer_id', $customer_id)
            ->join('customer_types', 'customers.customer_type_id', 'customer_types.customer_type_id')
            ->join('cities', 'customers.city_id', 'cities.city_id')
            ->join('townships', 'customers.township_id', 'townships.township_id')
            ->get()
            ->toArray();

        return view('admin.customer.customer_update', compact('customerTypes', 'cities', 'customers'));
    }

    //customer update
    public function updateCutomer(Request $req)
    {
        $customerID = $req->edit_customer_id;
        $this->updateValidationCheck($req);
        $data = $this->addUpdateCustomerData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Customer::where('customer_id', $customerID)->update($data);
        return redirect()->route('customer#customerPage')->with('update', 'Customer updated successfully.');
    }

    //customer delete
    public function deleteCutomer(Request $req)
    {

        $customerID = $req->delete_customer_id;

        Customer::where('customer_id', $customerID)->delete();
        return redirect()->route('customer#customerPage')->with('delete', 'Customer deleted successfully.');
    }

    //Private Functions
    //add Customer data
    private function addCustomerData($req)
    {
        $data = [
            'customer_name' => $req->customer_name,
            'other_name' => $req->other_name,
            'customer_code' => $req->customer_code,
            'customer_type_id' => $req->customer_type,
            'gender' => $req->gender,
            'date_of_birth' => $req->date_of_birth,
            'phone_number' => $req->phone_number,
            'email' => $req->email,
            'city_id' => $req->city,
            'township_id' => $req->township,
            'address' => $req->address,
            'remark' => $req->remark,
            'location_id' => "1",
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add update Customer data
    private function addUpdateCustomerData($req)
    {
        $data = [
            'customer_name' => $req->edit_customer_name,
            'other_name' => $req->edit_other_name,
            'customer_code' => $req->edit_customer_code,
            'customer_type_id' => $req->edit_customer_type,
            'gender' => $req->edit_gender,
            'date_of_birth' => $req->edit_date_of_birth,
            'phone_number' => $req->edit_phone_number,
            'email' => $req->edit_email,
            'city_id' => $req->edit_city,
            'township_id' => $req->edit_township,
            'address' => $req->edit_address,
            'remark' => $req->edit_remark,
            'location_id' => "1",
            'is_discontinued' => $req->edit_customer_is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,

        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'customer_name' => 'required',
            'customer_code' => 'required|unique:customers,customer_code',
            'customer_type' => 'required|not_in:0',
            'gender' => 'required|not_in:0',
            'phone_number' => 'required|numeric|digits_between:7,11',
            'email' => 'nullable|email',
            'city' => 'required|not_in:0',
            'township' => 'required',
            'address' => 'required',
        ];

        $validationMessages = [
            'customer_name.required' => 'Customer Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'customer_code.required' => 'Customer Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'customer_type.required' => 'Customer Type ရွေးရန်လိုအပ်ပါသည်',
            'customer_type.not_in' => 'Customer Type ရွေးရန်လိုအပ်ပါသည်',
            'gender.required' => 'Gender ရွေးရန်လိုအပ်ပါသည်',
            'gender.not_in' => 'Gender ရွေးရန်လိုအပ်ပါသည်',
            'phone_number.required' => 'Phone Number ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone_number.numeric' => "Phone Number သည် ကိန်းဂဏန်းများဖြစ်ရမည်",
            'phone_number.digits_between' => 'Phone Number မှားယွင်းနေပါသည်',
            'email.email' => 'Email Format ဖြည့်ရန်လိုအပ်ပါသည်',
            'city.required' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'city.not_in' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'township.required' => 'Township ရွေးရန်လိုအပ်ပါသည်',
            'address.required' => 'Address ဖြည့်ရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }

    private function updateValidationCheck($req)
    {
        $validationRules = [
            'edit_customer_name' => 'required',
            'edit_customer_code' => 'required',
            'edit_customer_type' => 'required|not_in:0',
            'edit_gender' => 'required|not_in:0',
            'edit_phone_number' => 'required|numeric|digits_between:7,11',
            'edit_email' => 'email',
            'edit_city' => 'required|not_in:0',
            'edit_township' => 'required',
            'edit_address' => 'required',
        ];

        $validationMessages = [
            'edit_customer_name.required' => 'Customer Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'edit_customer_code.required' => 'Customer Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'edit_customer_type.required' => 'Customer Type ရွေးရန်လိုအပ်ပါသည်',
            'edit_customer_type.not_in' => 'Customer Type ရွေးရန်လိုအပ်ပါသည်',
            'edit_gender.required' => 'Gender ရွေးရန်လိုအပ်ပါသည်',
            'edit_gender.not_in' => 'Gender ရွေးရန်လိုအပ်ပါသည်',
            'edit_phone_number.required' => 'Phone Number ဖြည့်ရန်လိုအပ်ပါသည်',
            'edit_phone_number.numeric' => "Phone Number သည် ကိန်းဂဏန်းများဖြစ်ရမည်",
            'edit_phone_number.digits_between' => "Phone Number မှားယွင်းနေပါသည်",
            'edit_email.email' => 'Email Format ဖြည့်ရန်လိုအပ်ပါသည်',
            'edit_city.required' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'edit_city.not_in' => 'City ရွေးရန်လိုအပ်ပါသည်',
            'edit_township.required' => 'Township ရွေးရန်လိုအပ်ပါသည်',
            'edit_address.required' => 'Address ဖြည့်ရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
