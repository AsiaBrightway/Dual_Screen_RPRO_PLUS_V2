<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FloorController extends Controller
{
    //direct config floor page
    public function floorPage()
    {
        $floors = Floor::where('is_deleted', 0)->get()->toArray();
        return view('admin.configuration.floor.floor', compact('floors'));
    }

    //floor create
    public function createFloor(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addFloorData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Floor::create($data);
        return redirect()->route('config#floorPage')->with('success', 'Floor created successfully.');
    }

    //floor update
    public function updateFloor(Request $req)
    {

        $floorID = $req->edit_floor_id;
        $data = $this->addFloorUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Floor::where('floor_id', $floorID)->update($data);
        return redirect()->route('config#floorPage')->with('update', 'Floor updated successfully.');
    }

    //floor delete
    public function deleteFloor(Request $req)
    {

        $floorID = $req->delete_floor_id;

        Floor::where('floor_id', $floorID)->update([
            'is_deleted' => 1,
            'modified_by' => Auth::id(),
        ]);
        return redirect()->route('config#floorPage')->with('delete', 'Floor deleted successfully.');
    }

    //Private Functions
    //add fllor data
    private function addFloorData($req)
    {
        $data = [
            'floor_name' => $req->floor_name,
            'other_name' => $req->other_name,
            'floor_code' => $req->floor_code,
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'location_id' => "1",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add floor update data
    private function addFloorUpdateData($req)
    {
        $data = [
            'floor_name' => $req->edit_floor_name,
            'other_name' => $req->edit_other_name,
            'floor_code' => $req->edit_floor_code,
            'is_discontinued' => $req->edit_is_discontinued,
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
            'floor_name' => 'required|unique:floors,floor_name',
            'floor_code' => 'required|unique:floors,floor_code'
        ];
        $validationMessages = [
            'floor_name.required' => 'Floor Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'floor_name.unique' => 'Floor Name တူနေပါသည်',
            'floor_code.required' => 'Floor Code ဖြည့်ရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
