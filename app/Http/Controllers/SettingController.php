<?php
 
namespace App\Http\Controllers;
 
use App\Models\Setting;
 
 
use Illuminate\Http\Request;
 
class SettingController extends Controller
{
 
    public function AddSetting()
    {
        $settings = Setting::latest()->get();
        
        return view('Setting.add', compact('settings'));
    }

    public function storeSetting(Request $request)
{
    $request->validate([
        'setting_name' => [
            'required',
            'unique:settings,setting_name',
            'regex:/^[A-Za-z0-9\s]+$/', // alphabets + digits + space
            'max:255',
        ],
    ]);
 
    Setting::create([
        'setting_name' => $request->setting_name,
    ]);
 
    return redirect()->route('AddSetting')->with('success', 'Setting added successfully');
}

    public function editSetting(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $setting = Setting::findOrFail($id);
             $settings = Setting::orderBy('id', 'desc')->get();
            return view('Setting.add', compact('setting', 'settings'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

     public function updateSetting (Request $request, string $encryptedId)
{
    $id = base64_decode($encryptedId);
 
    $request->validate([
        'setting_name' => [
            'required',
            'unique:settings,setting_name,' . $id,
            'regex:/^[A-Za-z0-9\s]+$/', // alphabets + digits + space
            'max:255',
        ],
    ]);
 
  
        $setting = Setting::findOrFail($id);
        $setting->setting_name = $request->setting_name;
        $setting->save();
 
        return redirect()->route('AddSetting')->with('success', 'Setting updated successfully.');
    
}

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return redirect()->route('AddSetting')->with('success', 'Branch deleted successfully.');
    }

    public function updateSettingStatus(Request $request)
    {

        $setting = Setting::findOrFail($request->id);
 
        $setting->status = $request->has('status') ? 1 : 0;
        $setting->save();
 
        return back()->with('success', 'Status updated!');
    }
}