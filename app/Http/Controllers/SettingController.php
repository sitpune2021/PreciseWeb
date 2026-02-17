<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function AddSetting()
    {
        $settings = Setting::where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Setting.add', compact('settings'));
    }
    public function storeSetting(Request $request)
    {
        $request->validate([
            'setting_name' => [
                'required',
                'regex:/^[A-Za-z0-9\s]+$/',
                'max:255',
                Rule::unique('settings', 'setting_name')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
        ], [
            'setting_name.unique' => 'This settings name already exists for your account.',
            'setting_name.regex'  => 'The settings name may only contain letters, numbers and spaces.',
        ]);

        Setting::create([
            'setting_name' => $request->setting_name,
            'is_active'    => 1,
            'admin_id'     => Auth::id(),
        ]);

        return redirect()->route('AddSetting')->with('success', 'Setting added successfully');
    }
    public function editSetting(string $encryptedId)
    {

        $id = base64_decode($encryptedId);

        $setting = Setting::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $settings = Setting::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
        return view('Setting.add', compact('setting', 'settings'));
    }
    public function updateSetting(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'setting_name' => [
                'required',
                'unique:settings,setting_name,' . $id,
                'regex:/^[A-Za-z0-9\s]+$/',
                'max:255',
            ],
        ]);

        $setting = Setting::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $setting->setting_name = $request->setting_name;
        $setting->save();

        return redirect()->route('AddSetting')->with('success', 'Setting updated successfully.');
    }
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $setting = Setting::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $settingName = $setting->name;

        $setting->delete();

        return redirect()->route('AddSetting')
            ->with('success', $settingName . ' deleted successfully.');
    }
    public function updateSettingStatus(Request $request)
    {
        $setting = Setting::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $setting->status = $request->has('status') ? 1 : 0;
        $setting->save();

        return back()->with('success', 'Status updated!');
    }
    public function trash()
    {

        $trashedSetting = Setting::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $Setting = Setting::where('admin_id', Auth::id())->get();

        return view('Setting.trash', compact('trashedSetting', 'Setting'));
    }
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $Setting = Setting::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $exists = Setting::where('setting_name', $Setting->setting_name)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            $Setting->is_active = 0;
            $Setting->restore();
            $Setting->touch();
            $Setting->save();

            return redirect()->route('editSetting', base64_encode($Setting->id))
                ->with('success', "Setting '{$Setting->setting_name}' already exists. Redirected to Edit Page.");
        }

        $Setting->is_active = 1;
        $Setting->restore();
        $Setting->touch();
        $Setting->save();

        return redirect()->route('AddSetting')
            ->with('success', "Setting '{$Setting->setting_name}' restored successfully.");
    }
}
