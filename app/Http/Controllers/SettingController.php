<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\UserRolePermission;
use Exception;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    //direct setting page
    public function settingPage(){
        $userRoles = UserRole::where('user_role_id','!=',1)
                                ->get()->toArray();
        return view('admin.setting.setting',compact('userRoles'));
    }

    //Add User Role Permissions
    public function addUserRolePermission(Request $req){
        try{
            DB::beginTransaction();
            $user_role_id = $req->query('user_role_id');
            $form_menu_permissions = $req->query('form_menu_permissions');

            $user_role_permission = UserRolePermission::where('role_id',$user_role_id)->first();
            if($user_role_permission != null)
            {
                UserRolePermission::where('role_id',$user_role_id)->delete();
            }
            foreach ($form_menu_permissions as $form_menu_permission) {
                if($form_menu_permission['is_used'] !=0)
                {
                    $data = $this->addUserRolePermissionData($form_menu_permission);
                    UserRolePermission::create($data);
                }
            }
            DB::commit();
            return redirect()->route('setting#settingPage');
        }
        catch (Exception $e) {
            DB::rollBack();
        }
    }

    //Get User Role Forms By User Role ID
    public function getUserRoleForms(Request $req)
    {
        $user_role = $req->query('user_role');
        $userRoleForms = UserRolePermission::where('role_id',$user_role)->get();
        return response()->json($userRoleForms);
    }

    private function addUserRolePermissionData($form_menu_permission){
        $data =[
            'role_id' => $form_menu_permission['role_id'],
            'form_menu_id' => $form_menu_permission['form_menu_id'],
            'is_updated'=> 0,
            'is_deleted'=>0,
        ];
        return $data;
    }

}
