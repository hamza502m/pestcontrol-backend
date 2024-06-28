<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => "user-list",
                'guard_name' => "sanctum",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "user-create",
                'guard_name' => "sanctum",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "user-edit",
                'guard_name' => "sanctum",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "user-delete",
                'guard_name' => "sanctum",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "user-admin",
                'guard_name' => "sanctum",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        foreach ($permissions as $key => $record) {
            $module = Permission::where('name', $record['name'])->first();
            if ($module) {
                unset($permissions[$key]);
            }
        }
        if (!empty($permissions)) {
            DB::table('permissions')->insert($permissions);
        }
    }
}
