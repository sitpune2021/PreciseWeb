<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);
        $request->validate([
            'name'              => 'required|string|max:255|unique:customers,name',
            'contact_person' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z.\s]+$/'],
            'phone_no' => ['required','digits:10','regex:/^[0-9]{10}$/','unique:customers,phone_no',],
            'email_id'          => 'nullable|email|max:40',
            'gst_no' => ['required','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/','unique:customers,gst_no',],
            'address'           => 'required|string',    
            
        ]);

        $customer_name_words = explode(' ', trim($request->input('name')));
        $code = '';

        if (count($customer_name_words) == 1) {

            $code = strtoupper(Str::substr($customer_name_words[0], 0, 3));
        } elseif (count($customer_name_words) == 2) {

            $code = strtoupper(Str::substr($customer_name_words[0], 0, 2) . Str::substr($customer_name_words[1], 0, 1));
        } else {

            $firstLetter = Str::substr($customer_name_words[0], 0, 1);
            $secondLetter = Str::substr($customer_name_words[1], 0, 1);
            $thirdLetter = Str::substr($customer_name_words[2], 0, 1);
            $code = strtoupper($firstLetter . $secondLetter . $thirdLetter);
        }

        $request->merge([
            'code' => $code,
        ]);


        Customer::create([
            'login_id'       => 0,
            'name'           => is_array($request->input('name')) ? $request->input('name')[0] : $request->input('name'),
            // 'code' => Str::substr($request->input('name'), 0, 3),
            'code'           => $request->input('code'),
            'email_id'       => $request->input('email_id'),
            'contact_person' => is_array($request->input('contact_person')) ? $request->input('contact_person')[0] : $request->input('contact_person'),
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
        'name' => ['required','string','max:255',
            Rule::unique('customers', 'name')->ignore($id),
        ],
        'code'           => 'nullable',
        'contact_person' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z.\s]+$/'],
        'phone_no' => ['required','digits:10','regex:/^[0-9]{10}$/','unique:customers,phone_no',],
        'email_id'       => 'nullable|email|max:40',
        'gst_no' => ['required','regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/','unique:customers,gst_no',],
        'address'        => 'required|string',
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

    public function updateCustomerStatus(Request $request)
    {
        $customer = Customer::findOrFail($request->id);
        $customer->status = $request->has('status') ? 1 : 0;
        $customer->save();

        return back()->with('success', 'Status updated!');
    }
}
