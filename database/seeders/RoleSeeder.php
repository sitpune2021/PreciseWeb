<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.  
     */
    public function run(): void
    {
        $roles = [
                  [
                'name' => 'SuperAdmin',
                'guard_name' => 'web',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programmer',
                'guard_name' => 'web',
            
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'guard_name' => 'web',
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance',
                'guard_name' => 'web',
                 
                'created_at' => now(),
                'updated_at' => now(),
            ],
         
        ];

        DB::table('roles')->insert($roles);
    }
}
