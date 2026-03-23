<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function checkCityName(Request $request)
    {
        $cityName = $request->input('city_name');
        $exists = City::where('city_name', $cityName)->exists();

        return response()->json(['exists' => $exists]);
    }
    //city create
    public function createCity(Request $req)
    {

        $data = $this->addCityData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        City::create($data);
        return redirect()->route('config#locationPage')->with('success', 'City created successfully.');
    }

    //city update
    public function updateCity(Request $req)
    {

        $cityID = $req->edit_city_id;
        $data = $this->addCityUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        City::where('city_id', $cityID)->update($data);
        return redirect()->route('config#locationPage')->with('update', 'City updated successfully.');
    }

    //city delete
    public function deleteCity(Request $req)
    {
        $cityID = $req->delete_city_id;

        City::where('city_id', $cityID)->delete();
        return redirect()->route('config#locationPage')->with('delete', 'City deleted successfully.');
    }

    //Private Functions
    //add location data
    private function addCityData($req)
    {
        $data = [
            'city_name' => $req->city_name,
            'is_discontinued' => $req->is_discontinued,
            'is_updated' => "0",
        ];
        return $data;
    }

    //add unit update data
    private function addCityUpdateData($req)
    {
        $data = [
            'city_name' => $req->edit_city_name,
            'is_discontinued' => $req->edit_is_discontinued,
            'is_updated' => "0",
        ];
        return $data;
    }
}
