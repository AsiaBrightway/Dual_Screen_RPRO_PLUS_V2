<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    //direct config table page
    public function tablePage()
    {
        $floors = Floor::get()->where('is_deleted', 0)->where('is_discontinued', 0)->toArray();
        $tables = Table::select(
            '*',
            'tables.other_name as table_other_name',
            'tables.is_discontinued as table_is_discontinued'
        )
            ->join('floors', 'tables.floor_id', '=', 'floors.floor_id')
            ->where('floors.is_discontinued', 0)
            ->get()
            ->toArray();

        return view('admin.configuration.table.table', compact('floors', 'tables'));
    }

    //table create
    public function createTable(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addFloorData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        Table::create($data);
        return redirect()->route('config#tablePage')->with('success', 'Table created successfully.');
    }

    //table update
    public function updateTable(Request $req)
    {

        $tableID = $req->edit_table_id;
        $data = $this->addTableUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Table::where('table_id', $tableID)->update($data);
        return redirect()->route('config#tablePage')->with('update', 'Table updated successfully.');
    }

    //table delete
    public function deleteTable(Request $req)
    {

        $tableID = $req->delete_table_id;

        Table::where('table_id', $tableID)->delete();
        return redirect()->route('config#tablePage')->with('delete', 'Table deleted successfully.');
    }

    //Private Functions
    //add Floor data
    private function addFloorData($req)
    {
        $data = [
            'table_name' => $req->table_name,
            'other_name' => $req->other_name,
            'floor_id' => $req->floor,
            'is_open' => "1",
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'is_updated' => "0",
            'location_id' => "1",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add table update data
    private function addTableUpdateData($req)
    {
        $data = [
            'table_name' => $req->edit_table_name,
            'other_name' => $req->edit_other_name,
            'floor_id' => $req->edit_floor,
            'is_open' => "1",
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
            'table_name' => [
                'required',
                Rule::unique('tables')->where(function ($query) {
                    return $query->where('floor_id', request('floor'));
                }),
            ],
            'floor' => 'required|not_in:0',
        ];

        $validationMessages = [
            'table_name.required' => 'Table Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'table_name.unique' => 'Table Name တူနေပါသည်',
            'floor.required' => 'Floor ရွေးရန်လိုအပ်ပါသည်',
            'floor.not_in' => 'Floor ရွေးရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
