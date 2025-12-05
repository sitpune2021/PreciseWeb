<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
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


            if ($module == 'Dashboard') {
                $allPermissions[$module] = ['view', 'view_work_orders', 'view_project', 'view_machinerecord'];
            } else {
                $allPermissions[$module] = ['view', 'add', 'edit', 'delete'];
            }
        }
        $json = json_encode($allPermissions);
        $escaped = json_encode($json);
        DB::table('role_permissions')->insert([
            'role_id' => 2,
            'admin_id' => 2,
            'permissions' => $escaped,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
