<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
{
    $modules = [
        'Client','Operator','Machine','Setting','Hsncode','MaterialType',
        'FinancialYear','UserAdmin','Customer','Vendors','Projects',
        'WorkOrders','SetupSheet','MachineRecord','MaterialReq',
        'MaterialOrder','Invoice','Subscription'
    ];

    $allPermissions = [];

    foreach ($modules as $module) {
        $allPermissions[$module] = ['view','add','edit','delete'];
    }

     $json = json_encode($allPermissions);

    $escaped = json_encode($json);

    DB::table('role_permissions')->insert([
        'role_id' => 2,
        'permissions' => $escaped,  
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

}
