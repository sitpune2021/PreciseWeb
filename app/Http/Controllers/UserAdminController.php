<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserAdmin;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserAdminController extends Controller
{
    // Show create form
    public function AddUserAdmin()
    {
        $roles = Role::all();
        return view('useradmin.create', compact('roles'));
    }


    // Store user
    public function StoreUser(Request $request)
    {

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:useradmins,email',
            'user_name' => 'required|string|unique:useradmins,user_name',
            'mobile' => ['required', 'digits:10'],
            'role' => 'required|exists:roles,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 1️⃣ Create UserAdmin
        $admin = UserAdmin::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 'Active'
        ]);

        // Create user in users table
        User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'username' => $request->user_name,
            'user_type' => $request->role,
            'org_pass' => $request->password,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('ListUserAdmin')->with('success', 'User created successfully in both tables.');
    }

    // Show edit form
    public function edit($id)
    {
        $id = base64_decode($id);
        $user = UserAdmin::findOrFail($id);
        $roles = Role::all();
        return view('useradmin.create', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = UserAdmin::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'email|unique:useradmins,email,' . $id,
            'user_name' => 'required|string|unique:useradmins,user_name,' . $id,
            'mobile' => ['nullable', 'digits:10'],
            'role' => 'required|integer|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->user_name = $request->user_name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('ListUserAdmin')->with('success', 'User updated successfully.');
    }


    // List users
    public function index()
{
    $adminId = Auth::id();
    $users = UserAdmin::where('admin_id', $adminId)->get();

    return view('useradmin.view', compact('users'));
}
    public function RolePermission()
    {
        $roles = Role::all();
        return view('useradmin.rolepermission', compact('roles'));
    }

    public function userupdateStatus(Request $request)
    {
        $user = UserAdmin::findOrFail($request->id);
        $user->status = $request->has('status') ? 'Active' : 'Inactive';
        $user->save();

        return back()->with('success', 'Status updated!');
    }


    public function getRolePermissions($role_id)
    {
        $rolePermission = RolePermission::where('role_id', $role_id)->first();

        if ($rolePermission) {
            $permissions = json_decode($rolePermission->permissions, true);
            return response()->json(['status' => true, 'permissions' => $permissions['permissions'] ?? []]);
        }

        return response()->json(['status' => false, 'permissions' => []]);
    }
}
