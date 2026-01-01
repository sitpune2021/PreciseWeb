<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {
    function hasPermission($module, $action = null)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // ✅ Direct array access
        $permissions = $user->rolePermission->permissions ?? [];

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
