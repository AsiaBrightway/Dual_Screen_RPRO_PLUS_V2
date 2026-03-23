<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //direct user page
    public function userPage()
    {

        $employees = Employee::where('is_terminate', 0)->get()->toArray();
        $userRoles = UserRole::where('user_role_id', '!=', 1)->get()->toArray();
        $users = User::select('*', 'users.is_discontinued as user_is_discontinued', 'users.modified_by as user_modified_by', 'users.created_at as user_created_at', 'users.updated_at as user_updated_at')
            ->where('users.user_role_id', '!=', 1)
            ->join('user_roles', 'users.user_role_id', 'user_roles.user_role_id')
            ->join('employees', 'users.employee_id', 'employees.employee_id')
            ->get()
            ->toArray();

        return view('admin.user.user.user', compact('employees', 'userRoles', 'users'));
    }

    //Get Employee Code by Employee ID  (dropdown)
    public function getEmpoyeeCodeByEmployeeID(Request $req)
    {
        $employee_id = $req->query('employee_id');
        $employee = Employee::where('employee_id', $employee_id)->get();

        return response()->json($employee);
    }

    //user create
    public function createUser(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addUserData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        User::create($data);
        return redirect()->route('user#users#userPage')->with('success', 'User Created Successfully');
    }

    //user update
    public function updateUser(Request $req)
    {

        $userID = $req->edit_user_id;
        $data = $this->addUserUpdateData($req);

        if ($req->edit_password != null || $req->edit_password != "null") {
            $data['password'] = Hash::make($req->edit_password);
        }

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        User::where('id', $userID)->update($data);
        return redirect()->route('user#users#userPage')->with('update', 'User Updated Successfully');
    }

    //user delete
    public function deleteUser(Request $req)
    {

        $userID = $req->delete_user_id;

        User::where('id', $userID)->delete();
        return redirect()->route('user#users#userPage')->with('delete', 'User Deleted Successfully');
    }

    //Private Functions
    //add User data
    private function addUserData($req)
    {
        $data = [
            'name' => $req->employee_name_txt,
            'username' => $req->user_name,
            'user_role_id' => $req->user_role,
            'employee_id' => $req->employee_name,
            'login_status' => "1",
            'location_id' => "1",
            'is_discontinued' => $req->is_discontinued,
            'modified_by' => Auth::user()->id,
            'password' => Hash::make($req->password),
        ];
        return $data;
    }

    //add user update data
    private function addUserUpdateData($req)
    {
        $data = [

            'user_role_id' => $req->edit_user_role,
            'is_discontinued' => $req->edit_user_is_discontinued,
            // 'password' => Hash::make($req->edit_password),
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'employee_name' => 'required|not_in:0',
            'user_role' => 'required|not_in:0',
            'user_name' =>  'required|unique:users,username',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ];

        $validationMessages = [
            'employee_name.required' => 'Employee Name ရွေးရန်လိုအပ်ပါသည်',
            'employee_name.not_in' => 'Employee ရွေးရန်လိုအပ်ပါသည်',
            'user_role.required' => 'User Role ရွေးရန်လိုအပ်ပါသည်',
            'user_role.not_in' => 'User Role ရွေးရန်လိုအပ်ပါသည်',
            'user_name.required' => 'User Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'user_name.unique' => 'User Name တူနေပါသည်',
            'password.required' => 'Password ဖြည့်ရန်လိုအပ်ပါသည်',
            'password.min' => 'Password သည် အနည်းဆုံး ၆လုံး ရှိရပါမည်',
            'confirm_password.required' => 'Confirm Password ဖြည့်ရန်လိုအပ်ပါသည်',
            'confirm_password.min' => 'Password သည် အနည်းဆုံး ၆လုံး ရှိရပါမည်',
            'confirm_password.same' => 'Confirm Password သည် Password နှင့် တူရပါမည်',
        ];

        $validator = Validator::make($req->all(), $validationRules, $validationMessages);

        // Custom rule to check the unique combination of employee_id and user_role_id
        $validator->after(function ($validator) use ($req) {
            $existingUser = DB::table('users')
                ->where('employee_id', $req->employee_name)
                ->where('user_role_id', $req->user_role)
                ->exists();

            if ($existingUser) {
                $validator->errors()->add('employee_name', 'Employee Name နှင့် User Role အတွက်သည် ရှိပီးသားဖြစ်ပါသည်');
                $validator->errors()->add('user_role', 'Employee Name နှင့် User Role အတွက်သည် ရှိပီးသားဖြစ်ပါသည်');
            }
        });

        $validator->validate();
    }
}
