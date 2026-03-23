<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\MOdels\UserRolePermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Str;
use Illuminate\Validation\ValidationException;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $users = User::select('*', 'users.is_discontinued as user_is_discontinued', 'users.modified_by as user_modified_by', 'users.created_at as user_created_at', 'users.updated_at as user_updated_at')
    //     ->where('users.user_role_id', '!=', 1)
    //     ->join('user_roles', 'users.user_role_id', 'user_roles.user_role_id')
    //     ->join('employees', 'users.employee_id', 'employees.employee_id')
    //     ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $users,
    //     ]);
    // }

    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the admin by username
        $users = User::where('username', $request->username)->first();

        // Check if admin exists and the password matches
        if (!$users || !Hash::check($request->password, $users->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $accessToken = $users->createToken('app-token')->plainTextToken;

        // Return response with tokens and admin details
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $accessToken,
            'user' => [
                'id' => $users->id,
                'name' => $users-> name,
                'username'=> $users -> username,
                'user_role_id'=> $users ->user_role_id,
                'employee_id'=> $users ->employee_id,
                'login_status'=> $users ->login_status,
                'location_id'=> $users ->location_id,
                'is_discontinued'=> $users ->is_discontinued,
                'modified_by'=> $users ->modified_by
                // 'password'=> $users ->password

            ],
        ], 200);
    }

    // public function login(Request $req)
    // {
    //     $this->validationCheck($req);
    //     $credentials = $req->only('username', 'password');

    //     // if (Auth::attempt($credentials)) {
    //         $userRoleID = Auth::user()->user_role_id;
    //         $userRolePermissions = UserRolePermission::where('role_id', $userRoleID)->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Login successful',
    //             'user_role_id' => $userRoleID,
    //             'permissions' => $userRolePermissions
    //         ], 200);
    //     // } else {
    //     //     return response()->json([
    //     //         'status' => 'error',
    //     //         'message' => 'Username or Password is incorrect!'
    //     //     ], 401);
    //     // }
    // }

    // private function validationCheck($req)
    // {
    //     $validator = Validator::make($req->all(), [
    //         'username' => 'required|string',
    //         'password' => 'required|string'
    //     ]);

    //     if ($validator->fails()) {
    //         response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 400)->send();
    //         exit;
    //     }
    // }

    public function getEmployeeCodeByEmployeeID(Request $request)
    {
        $employee_id = $request->query('employee_id');
        $employee = Employee::where('employee_id', $employee_id)->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //logout
    public function logout(Request $request)
    {
        $user = User::find($request->user_id);
        $user->tokens()->where('id', $request->bearerToken())->delete();

        return Response([
            'message' => 'Logout successfully!'
        ], 200);
    }
}
