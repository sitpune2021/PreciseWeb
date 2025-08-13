<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ClientContoller extends Controller
{

    public function AddClient()
    {
        return view('Client.add');
    }


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


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('client_logo');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            $logoPath = 'client_logo/' . $filename;
        }

        //print_r($request->all());die;

        $users = User::create([
            'name'      => $request->input('name'),
            'mobile'    => $request->input('phone_no'),
            'email'     => $request->input('email_id'),
            'username'  => $request->input('phone_no'),
            'password'  => Hash::make($request->input('password')),
            'org_pass'  => $request->input('password'),
            'user_type' => 2,
        ]);
        //echo '<pre>';print_r( $users->id);die;
        Client::create([
            'name'      => $request->input('name'),
            'email_id'  => $request->input('email_id'),
            'phone_no'  => $request->input('phone_no'),
            'gst_no'    => $request->input('gst_no'),
            'logo'      => $logoPath,
            'address'   => $request->input('address'),
            'login_id'  => $users->id,
        ]);

        return redirect()->route('ViewClient')->with('success', 'Client created successfully.');
    }

    public function ViewClient()
    {
        $client = Client::orderBy('id', 'desc')->get();
        return view('Client.view', compact('client'));
    }


    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $client = Client::findOrFail($id);
            return view('Client.add', compact('client'));
        } catch (\Exception $e) {
            abort(404);
        }
    }


    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'name'        => 'required|string|max:255',
            'phone_no'    => 'required|string|max:20',
            'email_id'    => 'nullable|email|max:30',
            'gst_no'      => 'required|string|max:20',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'address'     => 'required|string',
        ]);

        $client = Client::findOrFail($id);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('client_logo');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $filename);
            $logoPath = 'client_logo/' . $filename;

            if ($client->logo && file_exists(public_path($client->logo))) {
                unlink(public_path($client->logo));
            }

            $client->logo = $logoPath;
        }

        $client->name        = $request->input('name');
        $client->email_id    = $request->input('email_id');
        $client->phone_no    = $request->input('phone_no');
        $client->gst_no      = $request->input('gst_no');
        $client->address     = $request->input('address');

        $client->save();

        return redirect()->route('ViewClient')->with('success', 'Client updated successfully.');
    }



    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('ViewClient')->with('success', 'Branch deleted successfully.');
    }
}
