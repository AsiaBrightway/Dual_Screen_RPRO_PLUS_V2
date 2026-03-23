<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    //direct user role page
    public function userRolePage()
    {
        $userRoles = UserRole::where('user_role_id', '!=', 1)->get()->toArray();
        // $userRoles = UserRole::get()->toArray();
        return view('admin.user.user.user_role', compact('userRoles'));
    }

    //user role create
    public function createUserRole(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addUserRoleData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        UserRole::create($data);
        return redirect()->route('user#users#userRolePage')->with('success', 'User Role Created Successfully');
    }

    //User Role update
    public function updateUserRole(Request $req)
    {

        $userRoleID = $req->edit_user_role_id;
        $data = $this->addUserRoleUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        UserRole::where('user_role_id', $userRoleID)->update($data);
        return redirect()->route('user#users#userRolePage')->with('update', 'User Role Updated Successfully');
    }

    //User Role delete
    public function deleteUserRole(Request $req)
    {

        $userRoleID = $req->delete_user_role_id;

        UserRole::where('user_role_id', $userRoleID)->delete();
        return redirect()->route('user#users#userRolePage')->with('delete', 'User Role Deleted Successfully');
    }

    //Private Functions
    //add user role data
    private function addUserRoleData($req)
    {
        $data = [
            'user_role_name' => $req->user_role_name,
            'other_name' => $req->other_name,
            'location_id' => "1",
            'is_discontinued' => $req->is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add user role update data
    private function addUserRoleUpdateData($req)
    {
        $data = [
            'user_role_name' => $req->edit_user_role_name,
            'other_name' => $req->edit_other_name,
            'location_id' => "1",
            'is_discontinued' => $req->edit_is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'user_role_name' => 'required|unique:user_roles,user_role_name',
        ];

        $validationMessages = [
            'user_role_name.required' => 'User Role Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'user_role_name.unique' => 'User Role Name တူနေပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
