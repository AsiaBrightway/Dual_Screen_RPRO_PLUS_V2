<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    //direct config unit page
    public function unitPage()
    {
        // $units = Unit::get()->toArray();
        $units = DB::table('units as u')
            ->leftJoin('menu_items as mi', function($join) {
                $join->on('mi.unit_id', '=', 'u.unit_id')
                ->where(function ($q) {
                    $q->whereNull('mi.is_deleted')
                    ->orWhere('mi.is_deleted', 0);
                });
            })
            ->select(
                'u.unit_id',
                'u.unit_name',
                'u.other_name',
                'u.is_discontinued',
                DB::raw('COUNT(mi.item_id) as menu_item_count')
            )
            ->groupBy('u.unit_id', 'u.unit_name', 'u.other_name', 'u.is_discontinued')
            ->get()
            ->toArray();
            
        // dd($units);

        return view('admin.configuration.item.unit.unit', compact('units'));
    }

    //menu create
    public function createUnit(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addUnitData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Unit::create($data);
        return redirect()->route('config#item#unitPage')->with('success', 'Unit created successfully.');
    }

    //unit update
    public function updateUnit(Request $req)
    {


        $unitID = $req->edit_unit_id;
        $data = $this->addUnitUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Unit::where('unit_id', $unitID)->update($data);
        return redirect()->route('config#item#unitPage')->with('update', 'Unit updated successfully.');
    }

    //unit delete
    public function deleteUnit(Request $req)
    {

        $unitID = $req->delete_unit_id;

        Unit::where('unit_id', $unitID)->delete();
        return redirect()->route('config#item#unitPage')->with('delete', 'Unit deleted successfully.');
    }

    //Private Functions
    //add unit data
    private function addUnitData($req)
    {
        $data = [
            'unit_name' => $req->unit_name,
            'other_name' => $req->other_name,
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'location_id' => "1",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add unit update data
    private function addUnitUpdateData($req)
    {
        $data = [
            'unit_name' => $req->edit_unit_name,
            'other_name' => $req->edit_other_name,
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
            'unit_name' => 'required|unique:units,unit_name',
            'other_name' => 'required'
        ];

        $validationMessages = [
            'unit_name.required' => 'Unit Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'unit_name.unique' => 'Unit Name တူနေပါသည်',
            'other_name.required' => 'Other Name ဖြည့်ရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
