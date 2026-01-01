<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 🔥 Admin user fetch (user_type = 1)
        $admin = User::where('user_type', 1)->first();

        if (!$admin) {
            throw new \Exception('Admin user not found. Please seed users first.');
        }

        $modules = [
            'Dashboard',
            'Client',
            'Operator',
            'Machine',
            'Setting',
            'Hsncode',
            'MaterialType',
            'FinancialYear',
            'UserAdmin',
            'Customer',
            'Vendors',
            'Projects',
            'WorkOrders',
            'SetupSheet',
            'MachineRecord',
            'MaterialReq',
            'MaterialOrder',
            'Invoice',
            'Subscription'
        ];

        $allPermissions = [];

        foreach ($modules as $module) {
            if ($module === 'Dashboard') {
                $allPermissions[$module] = [
                    'view',
                    'view_work_orders',
                    'view_project',
                    'view_machinerecord'
                ];
            } else {
                $allPermissions[$module] = [
                    'view',
                    'add',
                    'edit',
                    'delete'
                ];
            }
        }

        DB::table('role_permissions')->insert([
            'admin_id'    => $admin->id,     // ✅ HERE
            'role_id'     => 2,
            'permissions' => json_encode($allPermissions),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
