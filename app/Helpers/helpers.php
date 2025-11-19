<?php

use Illuminate\Support\Facades\Auth;

function hasPermission($module, $action = null)
{
    $user = Auth::user();
    if (!$user) {
        return false; // No authenticated user
    }

    // Get permissions JSON from DB
    $permissionsJson = $user->rolePermission->permissions ?? '{}';

    // Decode JSON to array
    $permissions = json_decode($permissionsJson, true);

    if (!is_array($permissions) || empty($permissions)) {
        return false; // No permissions
    }

    // Check if module exists
    if (!isset($permissions[$module])) {
        return false; // Module not allowed
    }

    // If no action specified, module access is enough
    if ($action === null) {
        return true;
    }

    // Check if action exists in module permissions
    return is_array($permissions[$module]) && in_array($action, $permissions[$module]);
}




