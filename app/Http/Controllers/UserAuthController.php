<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class UserAuthController extends Controller
{
    public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string',
			'email' => 'required|string|email|unique:users',
			'password' => 'required|min:8'
		]);
	
		if($validator->fails()) {
			return response()->json(['status' => 'error','message' => $validator->errors()->first()],422);
		}

	    try{
            DB::beginTransaction();
	        $user = User::create([
	            'name' => $request->name,
	            'email' => $request->email,
	            'role' => 2,
	            'password' => Hash::make($request->password),
	        ]);

            activity()->causedBy(auth()->user())->log($request->name.' as a HR Manager Created');
			return response()->json([
                'status' => 'success',
                'message' => 'HR Manager Created Successfully.',
				'data' => $user,
				'token' =>$user->createToken($user->name.'-AuthToken')->plainTextToken
            ], 201); 

            DB::commit();
	        return response()->json([
	            'message' => 'User Created',
	            'data' => $user,
	            'token' =>$user->createToken($user->name.'-AuthToken')->plainTextToken
	        ]);
	    }catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['status' => 'error','message' => 'Failed to Add Purchase Order. ' .$e->getMessage()],500);
	    }
	}

	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email'=>'required|string|email',
			'password'=>'required|min:8'
		]);
	
		if($validator->fails()) {
			return response()->json(['status' => 'error','message' => $validator->errors()->first()],422);
		}

		$user = User::where('email',$request->email)->first();
		if(!$user || !Hash::check($request->password,$user->password)){
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid Credentials'
			],401);
		}
		$token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
		$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $user->role)->get();
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
		activity()->causedBy(auth()->user())->log($user->name.' Login');
		return response()->json([
			'status' => 'success',
			'token' => $token,
			'data' => $user,
			'permission' => $permiss,
		]);
    }
  

    public function logout(){
	    auth()->user()->tokens()->delete();
	    return response()->json([
		  'status' => 'success',
	      "message"=>"Logged Out Successfully"
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
