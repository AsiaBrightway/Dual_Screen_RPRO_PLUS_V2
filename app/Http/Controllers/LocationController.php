<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Township;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //direct config location page
    public function locationPage()
    {
        $cities = City::get()->toArray();
        $availableCities = City::where('is_discontinued', 0)->get()->toArray();
        $townships = Township::select('*', 'townships.is_discontinued as township_is_discontinued')
            ->join('cities', 'townships.city_id', 'cities.city_id')
            ->get()
            ->toArray();
        return view('admin.configuration.location.location', compact('cities', 'townships', 'availableCities'));
    }
}
