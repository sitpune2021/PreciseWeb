<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use Illuminate\Validation\Rule;

class AdminSettingController extends Controller
{
    public function EditSetting()
    {
        $data = AdminSetting::where('admin_id', Auth::id())->first();

        if (!$data) {
            $data = AdminSetting::create([
                'admin_id' => Auth::id(),
                'gst_no' => null,
                'date' => null,
                'udyam_no' => null,
                'bank_details' => null,
                'declaration' => null,
                'note' => null,
                'footer_note' => null,
                'logo' => null,
                'stamp' => null,
            ]);
        }
        return view('admin.setting', compact('data'));
    }
    public function UpdateAdminSetting(Request $request)
    {
        $setting = AdminSetting::where('admin_id', Auth::id())->first();

        if (!$setting) {
            $setting = AdminSetting::create([
                'admin_id' => Auth::id()
            ]);
        }
        $request->validate([
            'gst_no' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}Z[0-9]{1}$/',
            ],
            'date' => 'nullable|date',

            'udyam_no' => [
                'nullable',
                'string',
                'regex:/^UDYAM-[A-Z]{2}-\d{2}-\d{7}$/',
                Rule::unique('admin_settings', 'udyam_no')
                    ->ignore(optional($setting)->id)
                    ->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],

            'bank_details' => 'nullable|string',
            'declaration' => 'nullable|string',
            'note' => 'nullable|string',
            'footer_note' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'stamp' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        $setting->fill($request->except(['logo', 'stamp', 'remove_logo', 'remove_stamp']));

        if ($request->has('remove_logo')) $setting->logo = null;
        if ($request->has('remove_stamp')) $setting->stamp = null;

        if ($request->hasFile('logo')) {
            $logoName = time() . '_logo.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/settings'), $logoName);
            $setting->logo = $logoName;
        }

        if ($request->hasFile('stamp')) {
            $stampName = time() . '_stamp.' . $request->stamp->extension();
            $request->stamp->move(public_path('uploads/settings'), $stampName);
            $setting->stamp = $stampName;
        }

        $setting->save();
        return back()->with('success', 'Admin Settings Updated Successfully');
    }
}
