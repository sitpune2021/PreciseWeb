<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
  
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'mobile' => 1234567890,
            'password' => Hash::make('sup@2025'),
            'org_pass' => 'sup@2025',
            'user_type' => 1
        ]);

       
        $this->call([ RoleSeeder::class, RolePermissionSeeder::class,
            PaymentPlanSeeder::class,
        ]);
    }
}
