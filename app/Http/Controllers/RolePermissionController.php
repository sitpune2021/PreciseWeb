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

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'role_id' => 'required|exists:roles,id',
    //         'permissions' => 'required|array'
    //     ]);

    //     $cleanPermissions = [];

    //     foreach ($request->permissions as $module => $actions) {
    //         $actions = array_filter($actions);
    //         if (!empty($actions)) {
    //             $cleanPermissions[$module] = array_values($actions);
    //         }
    //     }

    //     RolePermission::updateOrCreate(
    //         [
    //             'admin_id' => auth()->id(), // 🔥 VERY IMPORTANT
    //             'role_id'  => $request->role_id,
    //         ],
    //         [
    //             'permissions' => $cleanPermissions
    //         ]
    //     );

    //     return back()->with('success', 'Permissions saved successfully!');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'role_id'     => 'required|exists:roles,id',
            'permissions' => 'required|array'
        ]);

        $cleanPermissions = [];

        foreach ($request->permissions as $module => $actions) {
            $actions = array_filter($actions); // remove unchecked
            if (!empty($actions)) {
                $cleanPermissions[$module] = array_values($actions);
            }
        }

        if (empty($cleanPermissions)) {
            return back()->withErrors([
                'permissions' => 'Please select at least one permission'
            ]);
        }

        RolePermission::updateOrCreate(
            [
                'role_id' => $request->role_id,
                'admin_id' => Auth::id(), // ⚠️ important
            ],
            [
                'permissions' => $cleanPermissions
            ]
        );

        return back()->with('success', 'Permissions saved successfully!');
    }


    public function getRolePermissions($role_id)
    {
        $rolePermission = RolePermission::where('role_id', $role_id)
            ->where('admin_id', Auth::id())
            ->first();

        if ($rolePermission) {
            return response()->json([
                'status' => true,
                'permissions' => $rolePermission->permissions ?? []
            ]);
        }

        return response()->json([
            'status' => false,
            'permissions' => []
        ]);
    }
}
