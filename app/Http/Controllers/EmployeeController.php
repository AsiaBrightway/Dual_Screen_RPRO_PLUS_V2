<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeePosition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //direct employee page
    public function employeePage()
    {
        $employeePositions = EmployeePosition::get()->toArray();
        $employees = Employee::select('*', 'employees.other_name as employee_other_name')
            ->join('employee_positions', 'employees.employee_position_id', 'employee_positions.employee_position_id')
            ->get()
            ->toArray();
        return view('admin.user.employee.employee', compact('employeePositions', 'employees'));
    }

    //employee create
    public function createEmployee(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addEmployeeData($req);
        if ($data['is_terminate'] == null || $data['is_terminate'] == "null") {
            $data['is_terminate'] = 0;
        }
        if ($data['is_terminate'] == "on") {
            $data['is_terminate'] = 1;
        }
        Employee::create($data);
        return redirect()->route('user#employee#employeePage')->with(['success' => 'Employee Created Successfully']);
    }

    //employee update
    public function updateEmployee(Request $req)
    {

        $employeeID = $req->edit_employee_id;
        $data = $this->addEmployeeUpdateData($req);

        if ($data['is_terminate'] == null || $data['is_terminate'] == "null") {
            $data['is_terminate'] = 0;
        }
        if ($data['is_terminate'] == "on") {
            $data['is_terminate'] = 1;
        }

        Employee::where('employee_id', $employeeID)->update($data);
        return redirect()->route('user#employee#employeePage')->with('update', 'Employee Updated Successfully');
    }

    //employee delete
    public function deleteEmployee(Request $req)
    {

        $employeeID = $req->delete_employee_id;
        try {
            DB::beginTransaction();
            User::where('employee_id', $employeeID)->delete();
            Employee::where('employee_id', $employeeID)->delete();
            DB::commit();
            return redirect()->route('user#employee#employeePage')->with('delete', 'Employee Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    //Private Functions
    //add Employee data
    private function addEmployeeData($req)
    {
        $data = [
            'employee_name' => $req->employee_name,
            'other_name' => $req->other_name,
            'employee_code' => $req->employee_code,
            'employee_position_id' => $req->employee_position,
            'is_terminate' => $req->is_terminate,
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add employee update data
    private function addEmployeeUpdateData($req)
    {
        $data = [
            'employee_name' => $req->edit_employee_name,
            'other_name' => $req->edit_other_name,
            'employee_code' => $req->edit_employee_code,
            'employee_position_id' => $req->edit_employee_position,
            'is_terminate' => $req->edit_is_terminate,
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
            'employee_name' => 'required',
            'employee_code' => 'required|unique:employees,employee_code',
            'employee_position' => 'required|not_in:0'
        ];

        $validationMessages = [
            'employee_name.required' => 'Employee Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'employee_code.required' => 'Employee Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'employee_code.unique' => 'Employee Code တူနေပါသည်',
            'employee_position.required' => 'Employee Position ရွေးရန်လိုအပ်ပါသည်',
            'employee_position.not_in' => 'Employee Position ရွေးရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
