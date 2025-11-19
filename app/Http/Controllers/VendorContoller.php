<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class VendorContoller extends Controller
{

    public function AddVendor()
    {
        return view('Vendor.add');
    }

    public function storeVendor(Request $request)
    {
        $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);

        $request->validate([
            'vendor_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
                Rule::unique('vendors', 'vendor_name')
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'contact_person' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z.\s]+$/'
            ],
            'gst_no' => [
                'required',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('vendors', 'gst_no')
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'status' => 'required|in:Active,Inactive',
            'phone_no' => [
                'required',
                'digits:10',
                Rule::unique('vendors', 'phone_no')
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'email_id' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        $vendor_name_words = explode(' ', trim($request->input('vendor_name')));
        $vendor_code = '';

        if (count($vendor_name_words) == 1) {
            $vendor_code = strtoupper(Str::substr($vendor_name_words[0], 0, 3));
        } elseif (count($vendor_name_words) == 2) {
            $vendor_code = strtoupper(
                Str::substr($vendor_name_words[0], 0, 2) .
                    Str::substr($vendor_name_words[1], 0, 1)
            );
        } else {
            $vendor_code = strtoupper(
                Str::substr($vendor_name_words[0], 0, 1) .
                    Str::substr($vendor_name_words[1], 0, 1) .
                    Str::substr($vendor_name_words[2], 0, 1)
            );
        }

        Vendor::create([
            'admin_id'       => Auth::id(),   
            'vendor_name'    => $request->vendor_name,
            'vendor_code'    => $vendor_code,
            'contact_person' => $request->contact_person,
            'gst_no'         => $request->gst_no,
            'status'         => $request->status,
            'phone_no'       => $request->phone_no,
            'email_id'       => $request->email_id,
            'address'        => $request->address,
        ]);

        return redirect()->route('ViewVendor')->with('success', 'Vendor created successfully.');
    }

    public function ViewVendor()
    {
        $vendors = Vendor::where('admin_id', Auth::id())  
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Vendor.view', compact('vendors'));
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $vendor = Vendor::where('admin_id', Auth::id())->findOrFail($id); 
        $method = "PUT";
        return view('Vendor.add', compact('vendor', 'method'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $vendor = Vendor::where('admin_id', Auth::id())->findOrFail($id);

        $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);

        $validated = $request->validate([
            'vendor_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vendors', 'vendor_name')
                    ->ignore($vendor->id)
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'vendor_code' => 'nullable',
            'contact_person' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z.\s]+$/'
            ],
            'gst_no' => [
                'required',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('vendors', 'gst_no')
                    ->ignore($vendor->id)
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'status' => 'required|in:Active,Inactive',
            'phone_no' => [
                'nullable',
                'digits:10',
                Rule::unique('vendors', 'phone_no')
                    ->ignore($vendor->id)
                    ->where(function ($query) {
                        return $query->where('admin_id', Auth::id());
                    })
                    ->whereNull('deleted_at'),
            ],
            'email_id' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        $validated['admin_id'] = Auth::id(); 

        $vendor->update($validated);

        return redirect()->route('ViewVendor')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $vendor = Vendor::where('admin_id', Auth::id())->findOrFail($id);
        $vendor->delete();
        return redirect()->route('ViewVendor')->with('success', 'Vendor deleted successfully.');
    }

    public function trash()
    {
        $trashedVendors = Vendor::where('admin_id', Auth::id())
            ->onlyTrashed()
            ->orderBy('id', 'desc')
            ->get();

        $vendors = Vendor::where('admin_id', Auth::id())->get();

        return view('Vendor.trash', compact('trashedVendors', 'vendors'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $vendor = Vendor::withTrashed()
            ->where('admin_id', Auth::id())
            ->findOrFail($id);

        $exists = Vendor::where('vendor_name', $vendor->vendor_name)
            ->where('admin_id', Auth::id())
            ->where('id', '!=', $vendor->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            $existingVendor = Vendor::where('vendor_name', $vendor->vendor_name)
                ->where('admin_id', Auth::id())
                ->whereNull('deleted_at')
                ->first();

            return redirect()->route('editVendor', base64_encode($existingVendor->id))
                ->with('success', "Vendor '{$vendor->vendor_name}' already exists. Redirected to Edit Page.");
        }

        $vendor->restore();

        return redirect()->route('ViewVendor')
            ->with('success', "Vendor '{$vendor->vendor_name}' restored successfully.");
    }
}
