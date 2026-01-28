<?php

use Illuminate\Support\Facades\Auth;
use App\Models\RolePermission;
if (!function_exists('hasPermission')) {

   function hasPermission($module, $action)
{
    $user = auth()->user();
    if (!$user) return false;

    // 🔥 SUPER ADMIN = ALL ACCESS
    if ($user->user_type == 2) {
        return true;
    }

    $rolePermission = RolePermission::where('role_id', $user->user_type)
        ->where('admin_id', $user->admin_id)
        ->first();

    if (!$rolePermission) return false;

    return isset($rolePermission->permissions[$module]) &&
           in_array($action, $rolePermission->permissions[$module]);
}

    
}
