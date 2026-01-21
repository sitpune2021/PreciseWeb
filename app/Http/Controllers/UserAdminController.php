<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->user_type == 1) {
            $users = User::latest()->get(); // all users
        } else {
            $users = User::where('admin_id', auth()->id())->latest()->get();
        }

        return view('useradmin.view', compact('users'));
    }



    public function AddUserAdmin()
    {
        $mode = 'add';
        $user = new User(); // empty model
        return view('useradmin.create', compact('mode', 'user'));
    }

    public function StoreUser(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'mobile'        => 'required|digits:10|unique:users,mobile',
            'user_type'     => 'required|in:1,2,3,4,5',
            'status'        => 'required|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photoName = null;

        if ($request->hasFile('profile_photo')) {
            $photoName = $request->file('profile_photo')
                ->store('users/profile', 'public'); // SAME as update
        }

        User::create([
            'admin_id' => Auth::id(), // logged-in admin
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
            'user_type' => $request->user_type,
            'status'    => $request->status,
            'profile_photo' => $photoName,
            'password'  => Hash::make('123'),
            'org_pass'  => '123',
        ]);

        return redirect()->route('ListUserAdmin')
            ->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $mode = 'edit';
        $user = User::findOrFail(base64_decode($id));
        return view('useradmin.create', compact('mode', 'user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // ✅ Validation
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email'     => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'mobile'    => [
                'required',
                'digits:10',
                Rule::unique('users', 'mobile')->ignore($user->id),
            ],
            'user_type' => ['required', Rule::in([1, 2, 3, 4, 5])],
            'status'    => ['required', Rule::in([0, 1])],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ✅ Profile Photo Update
        if ($request->hasFile('profile_photo')) {

            // delete old photo
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // store new photo
            $path = $request->file('profile_photo')
                ->store('users/profile', 'public');

            $validated['profile_photo'] = $path;
        }

        // ✅ Update User
        $user->update($validated);

        // ✅ Redirect
        return redirect()
            ->route('ListUserAdmin')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('ListUserAdmin')
            ->with('success', 'User deleted successfully');
    }

    public function RolePermission()
    {
        $roles = Role::all();
        return view('useradmin.rolepermission', compact('roles'));
    }

    public function userupdateStatus(Request $request)
    {
        $user = User::findOrFail($request->id);

        $user->status = (int) $request->status;
        $user->save();

        return back()->with('success', 'Status updated!');
    }

    public function getRolePermissions($role_id)
    {
        $rolePermission = RolePermission::where('role_id', $role_id)->first();

        return response()->json([
            'status' => (bool) $rolePermission,
            'permissions' => $rolePermission ? $rolePermission->permissions : []
        ]);
    }
}
