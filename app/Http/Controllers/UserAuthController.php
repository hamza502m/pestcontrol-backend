<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\QueryException;

class UserAuthController extends Controller
{

        /*
                  Code By: Umair Shaukat
                  Code On: 15-05-2024
                  Details:For registering user
                  Version: V1.0.0.0

        */
    public function register(Request $request){
	    try {
	        $registerUserData = $request->validate([
	            'name' => 'required|string',
	            'email' => 'required|string|email|unique:users',
	            'password' => 'required|min:8'
	        ]);

	        $user = User::create([
	            'name' => $registerUserData['name'],
	            'email' => $registerUserData['email'],
	            'role' => 2,
	            'password' => Hash::make($registerUserData['password']),
	        ]);

            activity()->causedBy(auth()->user())->log($request->full_name.' as a User');
	        return response()->json([
	            'message' => 'User Created',
	            'data' => $user,
	            'token' =>$user->createToken($user->name.'-AuthToken')->plainTextToken
	        ]);
	    } catch (\Illuminate\Validation\ValidationException $e) {
	        // Validation error occurred
	        return response()->json([
	            'error' => $e->validator->errors()->first(),
	        ], 422);
	    } catch (\Exception $e) {
	        // Other unexpected errors
	        return response()->json([
	            'error' => 'Something went wrong.',
	        ], 500);
	    }
	}


        /*
                  Code By: Umair Shaukat
                  Code On: 15-05-2024
                  Details:For Login user
                  Version: V1.0.0.0

        */
	 public function login(Request $request){
	        $loginUserData = $request->validate([
	            'email'=>'required|string|email',
	            'password'=>'required|min:8'
	        ]);
	        $user = User::where('email',$loginUserData['email'])->first();
	        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
	            return response()->json([
	                'message' => 'Invalid Credentials'
	            ],401);
	        }
	        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $user->role)
            ->get();
            $permiss=[];
            $i=0;
			foreach ($rolePermissions as $rolePermission) {
			    $data = Permission::where('id', $rolePermission->permission_id)->first();
			    $permiss['permission'][$i] = [
			        "name" => $data->name,
			        "url" => $data->url,
			        "icon" => env('APP_URL').'/upload/icons/'.$data->icon
			    ];
			    $i++;
			}
            activity()->causedBy(auth()->user())->log($user->full_name.' Login');
	        return response()->json([
	            'access_token' => $token,
	            'user' => $user,
	            'permission' => $permiss,
	        ]);
    }
  
        /*
                  Code By: Umair Shaukat
                  Code On: 15-05-2024
                  Details:For Logout user
                  Version: V1.0.0.0

        */
    public function logout(){
	    auth()->user()->tokens()->delete();

	    return response()->json([
	      "message"=>"logged out"
	    ]);
	}
	public function allPermissionbyuser($id,Request $request){
          	$user = User::where('id', $id)->first();
			$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $user->role)->get();
			$permiss = [];
			$i = 1; // Start from 1 to match the required array format with numbered keys
			foreach ($rolePermissions as $rolePermission) {
			    $data = Permission::where('id', $rolePermission->permission_id)->first();
			    $permiss['permission'][$i] = [
			        "name" => $data->name,
			        "url" => $data->url,
			        "icon" => env('APP_URL').'/upload/icons/'.$data->icon
			    ];
			    $i++;
			}
			return response()->json($permiss);
    }
}
