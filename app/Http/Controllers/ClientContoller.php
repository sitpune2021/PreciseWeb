<?php
 
namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
 
class ClientContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddClient()
    {
        return view('Client.add');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeClient(Request $request)
     {
 
    $request->validate([
        'name'        => 'required|string|max:255',
        'phone_no'    => 'required|string|max:20',
        'email_id'    => 'nullable|email|max:30',
        'gst_no'      => 'required|string|max:20',
        'logo'        => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        'address'     => 'required|string',
    ]);
 
 
    if ($request->hasFile('logo')) 
        {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('client_logo'); 
            // Make sure the folder exists
            if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            $logoPath = 'client_logo/' . $filename;
        }
 
    Client::create([
        'name'        => $request->input('name'),
        'email_id'    => $request->input('email_id'),
        'phone_no'    => $request->input('phone_no'),
        'gst_no'      => $request->input('gst_no'),
        'logo'        => $logoPath,
        'address'     => $request->input('address'),
    ]);
 
     return redirect()->route('ViewClient')->with('success', 'Client created successfully.');
   
}
/**
     * Display the specified resource.
     */
    //public function ViewClient(string $id)
    public function ViewClient()
    {
        return view('Client.view');
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
 