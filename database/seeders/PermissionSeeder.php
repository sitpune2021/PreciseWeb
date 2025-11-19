<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard Access
            ['name' => 'view_dashboard', 'description' => 'View Dashboard', 'guard_name' => 'web'],
 
            // Accounts Management
            ['name' => 'Add', 'description' => 'Create ', 'guard_name' => 'web'],
            ['name' => 'View', 'description' => 'View ','guard_name' => 'web'],
            ['name' => 'Edit', 'description' => 'Edit ','guard_name' => 'web'],
            ['name' => 'Delete', 'description' => 'Delete','guard_name' => 'web'],
  
        ];

        DB::table('permissions')->insert($permissions);
        
    }
}
