<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
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
            'Subscription',
            'Quotation'
        ];

        /**
         * ==========================
         * SUPER ADMIN (role_id = 1)
         * ==========================
         */
        $superPermissions = [];

        foreach ($modules as $module) {
            if ($module === 'Dashboard') {
                $superPermissions[$module] = [
                    'view',
                    'view_work_orders',
                    'view_project',
                    'view_machinerecord'
                ];
            } else {
                $superPermissions[$module] = [
                    'add', 'view', 'edit', 'delete'
                ];
            }
        }

        RolePermission::updateOrCreate(
            [
                'admin_id' => 1,   // 🔥 Super Admin user id
                'role_id'  => 1,
            ],
            [
                'permissions' => $superPermissions
            ]
        );

        /**
         * ==========================
         * ADMIN (role_id = 2)
         * ==========================
         */
        RolePermission::updateOrCreate(
            [
                'admin_id' => 1,   // 🔥 Admin belongs to Super Admin
                'role_id'  => 2,
            ],
            [
                'permissions' => $superPermissions
            ]
        );
    }
}


