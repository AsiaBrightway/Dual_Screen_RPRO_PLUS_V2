<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeePosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeePositionController extends Controller
{
    //direct employee position page
    public function employeePositionPage()
    {
        $employeePositions = EmployeePosition::get()->toArray();
        return view('admin.user.employee.employee_position', compact('employeePositions'));
    }

    //Employee Position create
    public function createEmployeePosition(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addEmployeePositionData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        EmployeePosition::create($data);
        return redirect()->route('user#employee#employeePositionPage')->with('success', 'Employee Position Created Successfully');
    }

    //Employee Position update
    public function updateEmployeePosition(Request $req)
    {

        $employeePositionID = $req->edit_employee_position_id;
        $data = $this->addEmployeePositionUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        EmployeePosition::where('employee_position_id', $employeePositionID)->update($data);
        return redirect()->route('user#employee#employeePositionPage')->with('update', 'Employee Position Updated Successfully');
    }

    //Employee Position delete
    public function deleteEmployeePosition(Request $req)
    {

        $employeePositionID = $req->delete_employee_position_id;

        EmployeePosition::where('employee_position_id', $employeePositionID)->delete();
        return redirect()->route('user#employee#employeePositionPage')->with('delete', 'Employee Position Deleted Successfully');
    }

    //Private Functions
    //add employee position data
    private function addEmployeePositionData($req)
    {
        $data = [
            'position_name' => $req->position_name,
            'other_name' => $req->other_name,
            'is_discontinued' => $req->is_discontinued,
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add employee position update data
    private function addEmployeePositionUpdateData($req)
    {
        $data = [
            'position_name' => $req->edit_position_name,
            'other_name' => $req->edit_other_name,
            'is_discontinued' => $req->edit_employee_position_is_discontinued,
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'position_name' => 'required|unique:employee_positions,position_name',
        ];

        $validationMessages = [
            'position_name.required' => 'Position Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'position_name.unique' => 'Position Name တူနေပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
