<?php

namespace App\Http\Controllers;

use App\Models\Township;
use Illuminate\Http\Request;

class TownshipController extends Controller
{
    public function checkTownshipName(Request $request)
    {
        $townshipName = $request->input('township_name');
        $exists = Township::where('township_name', $townshipName)->exists();

        return response()->json(['exists' => $exists]);
    }
    //township create
    public function createTownship(Request $req)
    {

        $data = $this->addTownshipData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Township::create($data);
        return redirect()->route('config#locationPage')->with('success', 'Township created successfully.');
    }

    //township update
    public function updateTownship(Request $req)
    {
        $townshipID = $req->edit_township_id;
        $data = $this->addTownshipUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Township::where('township_id', $townshipID)->update($data);
        return redirect()->route('config#locationPage')->with('update', 'Township updated successfully.');
    }

    //township delete
    public function deleteTownship(Request $req)
    {
        $townshipID = $req->delete_township_id;

        Township::where('township_id', $townshipID)->delete();
        return redirect()->route('config#locationPage')->with('deleted', 'Township deleted successfully.');
    }

    //Private Functions
    //add location data
    private function addTownshipData($req)
    {
        $data = [
            'city_id' => $req->city,
            'township_name' => $req->township_name,
            'is_discontinued' => $req->is_discontinued,
            'is_updated' => "0",
        ];
        return $data;
    }

    //add unit update data
    private function addTownshipUpdateData($req)
    {
        $data = [
            'city_id' => $req->city,
            'township_name' => $req->edit_township_name,
            'is_discontinued' => $req->edit_township_is_discontinued,
            'is_updated' => "0",
        ];
        return $data;
    }
}
