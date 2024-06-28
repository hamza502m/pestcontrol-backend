<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

        /*
            Code By: Umair Shaukat
            Code On: 15-05-2024
            Details:Add Permission
            Version: V1.0.0.0

        */
    public function store(Request $request)
    {

      try{
            $validate = $this->validate($request, [
                'name' => 'required|unique:permissions,name'
            ]);
            $image=$request->icon;
            $folder='icons';
            $name = $image->getClientOriginalName();
            $name = strtolower(str_replace(' ', '-', $name));
            $destinationPath = public_path() . '/upload/'.$folder.'/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            $fileName = $folder.'_' . rand(0, 999) . '.png';
            $image->move($destinationPath, $fileName);
            $permission = new Permission();
            $permission->name=$request->name;
            $permission->icon=$fileName;
            $permission->save();

            activity()->causedBy(auth()->user())->log($request->name.' added');
            return response()->json([
                'message' => 'Permission Added',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
                return response()->json([
                    'error' => $e->validator->errors()->first(),
                ], 422);
        }

    }

        /*
            Code By: Umair Shaukat
            Code On: 15-05-2024
            Details:Add Permission
            Version: V1.0.0.0

        */
    public function updatePermission(Request $request,$id)
    {
      try{
            $validate = $this->validate($request, [
                'name' => 'required:permissions'
            ]);
            $image=$request->icon;
            $folder='icons';
            $name = $image->getClientOriginalName();
            $name = strtolower(str_replace(' ', '-', $name));
            $destinationPath = public_path() . '/upload/'.$folder.'/';
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            $fileName = $folder.'_' . rand(0, 999) . '.png';
            $image->move($destinationPath, $fileName);
            $permission = Permission::find($id);
            $permission->name=$request->name;
            $permission->icon=$fileName;
            $permission->save();
            return response()->json([
                'message' => 'Permission updated',
            ]);
        }catch(\Illuminate\Validation\ValidationException $e){
                return response()->json([
                    'error' => $e->validator->errors()->first(),
                ], 422);
        }

    }


        /*
            Code By: Umair Shaukat
            Code On: 15-05-2024
            Details:Add Role
            Version: V1.0.0.0

        */
    public function addRole(Request $request)
    {
    try{
        $validate = $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
            // $message = 'New Role id:' . $role->id . ' is Created ' . AuthUser() . '';
            // Log::info($message);

        $permissions = Permission::whereIn('id',$request->input('permission'))->get();
        $role->syncPermissions($permissions);
        return response()->json([
                'message' => 'Role created successfully',
            ]);
      }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
        'error' => $e->validator->errors()->first(),
        ], 422);
        }
    }

        /*
            Code By: Umair Shaukat
            Code On: 15-05-2024
            Details:Udate Role
            Version: V1.0.0.0

        */
    public function updateRole(Request $request,$id)
    {
    try{
        $validate = $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        
        // $message = 'Role id:' . $id . ' role name: ' . $role->name . ' is Updated ' . AuthUser() . '';
        // Log::notice($message);
        

        $permissions = Permission::whereIn('id',$request->input('permission'))->get();
        $role->syncPermissions($permissions);
        return response()->json([
                'message' => 'Role and permissions has been updated',
            ]);
      }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
        'error' => $e->validator->errors()->first(),
        ], 422);
        }
    }


    public function updatePermissionbyuser(Request $request,$id)
    {
    try{

        $role = Role::find($id);        
        // $message = 'Role id:' . $id . ' role name: ' . $role->name . ' is Updated ' . AuthUser() . '';
        // Log::notice($message);
        

        $permissions = Permission::whereIn('id',$request->input('permission'))->get();
        $role->syncPermissions($permissions);
        return response()->json([
                'message' => 'Permissions has been updated',
            ]);
      }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
        'error' => $e->validator->errors()->first(),
        ], 422);
        }
    }



}
