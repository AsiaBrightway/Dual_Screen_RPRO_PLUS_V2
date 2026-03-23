<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Delivery;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    //direct config delivery page
    public function deliveryPage()
    {
        $cities = City::where('is_discontinued', 0)->get()->toArray();
        $townships = Township::where('is_discontinued', 0)->get()->toArray();
        $deliveries = Delivery::select('*', 'deliveries.is_discontinued as delivery_is_discontinued')
            ->join('cities', 'deliveries.city_id', 'cities.city_id')
            ->join('townships', 'deliveries.township_id', 'townships.township_id')
            ->get()
            ->toArray();
        return view('admin.configuration.delivery.delivery', compact('cities', 'townships', 'deliveries'));
    }

    //Get Township By City (dropdown)
    public function getTownshipByCity(Request $req)
    {
        $cityID = $req->query('cityID');
        $township = Township::where('is_discontinued', 0)->where('city_id', $cityID)->get();
        return response()->json($township);
    }

    //Delivery create
    public function createDelivery(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addDeliveryData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Delivery::create($data);
        return redirect()->route('config#deliveryPage')->with('success', 'Delivery created successfully.');
    }

    //Delivery update
    public function updateDelivery(Request $req)
    {

        $deliveryID = $req->edit_delivery_id;
        $data = $this->addDeliveryUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Delivery::where('delivery_id', $deliveryID)->update($data);
        return redirect()->route('config#deliveryPage')->with('update', 'Delivery updated successfully.');
    }

    //delivery delete
    public function deleteDelivery(Request $req)
    {

        $deliveryID = $req->delete_delivery_id;

        Delivery::where('delivery_id', $deliveryID)->delete();
        return redirect()->route('config#deliveryPage')->with('delete', 'Delivery deleted successfully.');
    }

    //Private Functions
    //add delivery data
    private function addDeliveryData($req)
    {
        $data = [
            'company_name' => $req->company_name,
            'phone_number' => $req->phone_number,
            'city_id' => $req->city,
            'township_id' => $req->township,
            'address' => $req->address,
            'remark' => $req->remark,
            'is_discontinued' => $req->is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add delivery update data
    private function addDeliveryUpdateData($req)
    {
        $data = [
            'company_name' => $req->edit_company_name,
            'phone_number' => $req->edit_phone_number,
            'city_id' => $req->edit_city,
            'township_id' => $req->edit_township,
            'address' => $req->edit_address,
            'remark' => $req->edit_remark,
            'is_discontinued' => $req->edit_delivery_is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'company_name' => 'required|unique:deliveries,company_name',
            'phone_number' => 'required|numeric|digits_between:7,11',
            'city' => 'required|not_in:0',
            'township' => 'required',
            'address' => 'required',
        ];
        $validationMessages = [
            'company_name.required' => 'Company Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'company_name.unique' => 'Company Name တူနေပါသည်',
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
