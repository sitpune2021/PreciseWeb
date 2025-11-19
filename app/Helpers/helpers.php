<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {

    function hasPermission($module, $action = null)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        $permissionsJson = $user->rolePermission->permissions ?? '{}';

        $permissions = json_decode($permissionsJson, true);

        if (!is_array($permissions) || empty($permissions)) {
            return false;
        }

        if (!isset($permissions[$module])) {
            return false;
        }

        if ($action === null) {
            return true;
        }

        return in_array($action, $permissions[$module]);
    }
}
