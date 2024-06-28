<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
            'name' => 'OZ Tech',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => '1',
            'status' => '1',
            'email_verified_at'=> now()
            ]
         ];

        foreach ($records as $key => $record)
        {
            $module = User::where('email',$record['email'])->first();
            if ($module)
            {
                unset($records[$key]);
            }
        if(!empty($record)) {
            $user = User::create($record);
            $role = Role::where('name' , 'Admin')->first();
            $permissions = Permission::pluck('id','id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
        }
		}

    }
}
