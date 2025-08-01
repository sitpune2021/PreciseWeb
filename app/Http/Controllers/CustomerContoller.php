<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Crypt;

class CustomerContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddCustomer()
    {
        return view('Customer.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'code'              => 'required|string|max:50',
            'contact_person'    => 'required|string|max:255',
            'phone_no'          => 'required|string|max:20',
            'email_id'          => 'nullable|email|max:30',
            'gst_no'            => 'required|string|max:20',
            'address'           => 'required|string',
        ]);

        Customer::create([
            'login_id'       => 0,
            'name'           => $request->input('name'),
            'code'           => $request->input('code'),
            'email_id'       => $request->input('email_id'),
            'contact_person' => $request->input('contact_person'),
            'phone_no'       => $request->input('phone_no'),
            'gst_no'         => $request->input('gst_no'),
            'address'        => $request->input('address'),
        ]);

        return redirect()->route('ViewCustomer')->with('success', 'Customer created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function ViewCustomer()
    {
        $customer = Customer::orderBy('id', 'desc')->get();
        return view('Customer.view', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {
       try {
        $id = base64_decode($encryptedId);
        $customer = Customer::findOrFail($id);
        return view('Customer.add', compact('customer'));
        } catch (\Exception $e) {
            abort(404); // If decryption fails or ID is invalid
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'code'              => 'required|string|max:50',
            'contact_person'    => 'required|string|max:255',
            'phone_no'          => 'required|string|max:20',
            'email_id'          => 'nullable|email|max:30',
            'gst_no'            => 'required|string|max:20',
            'address'           => 'required|string',
        ]);

        $customer = Customer::findOrFail($id);

        $customer->update($validated);

        return redirect()->route('ViewCustomer')->with('success', 'Customer updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $customer = Customer::findOrFail($id);
        $customer->delete(); 
        return redirect()->route('ViewCustomer')->with('success', 'Branch deleted successfully.');
    }
}
