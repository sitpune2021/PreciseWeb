<?php

namespace App\Http\Controllers;

use App\Models\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddVendor()
    {
        return view('Vendor.add');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function storeVendor(Request $request)
    {
        $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);
        $request->validate([
            'vendor_name'     => 'required|string|max:255',
          
            'contact_person'  => 'required|string|max:255',
            'gst_no'          => [
                'required',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ],
            'status'          => 'required|in:Active,Inactive',
            'phone_no'        => 'required|digits_between:10,15',
            'email_id'        => 'nullable|email|max:255',
            'address'         => 'required|string|max:500',
        ]);
 
        $vendor_name_words = explode(' ', $request->input('vendor_name'));
        $firstLetter = Str::substr($vendor_name_words[0], 0, 1);
        $secondLetter = isset($vendor_name_words[1]) ? Str::substr($vendor_name_words[1], 0, 1) : '';
        $vendor_code = strtoupper($firstLetter . $secondLetter);
        $request->merge([
        'vendor_code' => $vendor_code, 
        ]);

        Vendor::create([
            'vendor_name'    => $request->vendor_name,
            'vendor_code'    => $request->vendor_code,
             
            'contact_person' => $request->contact_person,
            'gst_no'         => $request->gst_no,
            'status'         => $request->status,
            'phone_no'       => $request->phone_no,
            'email_id'       => $request->email_id,
            'address'        => $request->address,
        ]);

        return redirect()->route('ViewVendor')->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function ViewVendor()
    {
        $vendors = Vendor::all();
        return view('Vendor.view', compact('vendors'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {

        try {
            $id = base64_decode($encryptedId);
            $vendor = Vendor::findOrFail($id);
            $method = "PUT";
            return view('Vendor.add', compact('vendor', 'method'));
        } catch (\Exception $vendor) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    

     public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
            $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);

        $validated = $request->validate([
            'vendor_name'     => 'required|string|max:255',
                'vendor_code'     => 'nullable',
                'contact_person'  => 'required|string|max:255',
                    'gst_no'          => [
                'required',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            ],
                'status'          => 'required|in:Active,Inactive',
                'phone_no'        => 'required|digits_between:10,15',
                'email_id'        => 'nullable|email|max:255',
                'address'         => 'required|string|max:500',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update($validated);

         return redirect()->route('ViewVendor')->with('success', 'Vendor updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
 public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $vendor = Vendor::findOrFail($id);
        $vendor->delete(); 
        return redirect()->route('ViewVendor')->with('success', 'Branch deleted successfully.');
    }
}
