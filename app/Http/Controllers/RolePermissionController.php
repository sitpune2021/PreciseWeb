<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    public function RolePermission()
    {
        $roles = Role::all();
        return view('useradmin.rolepermission', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array'
        ]);

        RolePermission::updateOrCreate(
            [
                'role_id'  => $request->role_id,
                'admin_id' => Auth::id(),
            ],
            [
                'permissions' => $request->permissions,
            ]
        );

        return back()->with('success', 'Permissions saved successfully!');
    }

    public function getRolePermissions($role_id)
    {
        $rolePermission = RolePermission::where('role_id', $role_id)
            ->where('admin_id', Auth::id())
            ->first();

        return response()->json([
            'status' => (bool) $rolePermission,
            'permissions' => $rolePermission
                ? $rolePermission->permissions // ✅ cast already gives array
                : []
        ]);
    }
}
