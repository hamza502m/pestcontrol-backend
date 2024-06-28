<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
                    [
                        'id' => 1,
                        'name' => "Admin",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 2,
                        'name' => "HR-Manager",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 3,
                        'name' => "Operation-Manager",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 4,
                        'name' => "Sales-Manager",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 5,
                        'name' => "Client",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 6,
                        'name' => "Vendor",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'id' => 7,
                        'name' => "Employee",
                        'guard_name' => "sanctum",
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ];
        foreach ($records as $key => $record)
        {
            $module = Role::where('id',$record['id'])->first();
            if ($module)
            {
                unset($records[$key]);
            }
        }
        if(!empty($records)) {
            DB::table('roles')->insert($records);
        }
    }
}
