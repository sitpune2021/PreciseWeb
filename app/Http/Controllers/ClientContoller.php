<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientContoller extends Controller
{
    public function AddClient()
    {
        return view('Client.add');
    }
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/', 'unique:clients,name',],
            'phone_no' => ['required', 'numeric', 'digits:10'],
            'email_id' => ['required', 'email', 'max:30', 'unique:users,email'],
            'gst_no' => ['required', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', 'unique:customers,gst_no',],
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

        $users = User::create([
            'admin_id'  => Auth::id(),
            'name'      => $request->input('name'),
            'mobile'    => $request->input('phone_no'),
            'email'     => $request->input('email_id'),
            'username'  => $request->input('phone_no'),
            'password'  => Hash::make($request->input('password')),
            'org_pass'  => $request->input('password'),
            'user_type' => 2,
        ]);

        $today = Carbon::today();
        $expiry = $today->copy()->addDays(7);

        Client::create([
            'name'         => $request->input('name'),
            'email_id'     => $request->input('email_id'),
            'phone_no'     => $request->input('phone_no'),
            'gst_no'       => $request->input('gst_no'),
            'logo'         => $logoPath ?? null,
            'address'      => $request->input('address'),
            'login_id'     => $users->id,
            'plan_type' => 1,
            'plan_expiry'  => $expiry,
            'trial_start'  => $today,
            'trial_end'    => $expiry,
            'status'       => 1,
        ]);

        return redirect()->route('ViewClient')->with('success', 'Client created with 7-day trial.');
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
            'email_id'    => 'required|email|max:30',
            'gst_no'       => ['required', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',],
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

    // public function destroy(string $encryptedId)
    // {
    //     $id = base64_decode($encryptedId);
    //     $client = Client::findOrFail($id);
    //     $client->delete();
    //     return redirect()->route('ViewClient')->with('success', 'Branch deleted successfully.');
    // }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $client = Client::findOrFail($id);
        // Delete related user
        if ($client->login_id) {
            User::where('id', $client->login_id)->delete();
        }
        // Delete logo file
        if ($client->logo && file_exists(public_path($client->logo))) {
            unlink(public_path($client->logo));
        }
        // Delete client
        $client->delete();

        return redirect()->route('ViewClient')
            ->with('success', 'Client and related user deleted successfully.');
    }

    public function updateClientStatus(Request $request)
    {
        $client = Client::findOrFail($request->id);

        $client->status = $request->has('status') ? 1 : 0;
        $client->save();
        return back()->with('success', 'Status updated!');
    }

    public function updateClientPlan(Request $request)
    {
        $client = Client::findOrFail($request->id);
        $plan = $request->plan_type;

        $expiry = match ($plan) {
            '1month' => now()->addMonth(),
            '3month' => now()->addMonths(3),
            '1year' => now()->addYear(),
            default => now()->addDays(7),
        };

        $client->plan_type = $plan;
        $client->plan_expiry = $expiry;
        $client->trial_start = null;
        $client->trial_end = null;
        $client->save();

        return back()->with('success', 'Plan updated successfully!');
    }

    public function renewPlan(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'plan_type' => 'required|string',
            'trial_start' => 'required|date',
            'trial_end' => 'required|date|after_or_equal:trial_start',
        ]);

        $client = Client::find($request->client_id);

        $client->update([
            'plan_type' => $request->plan_type,
            'trial_start' => $request->trial_start,
            'trial_end' => $request->trial_end,
            'plan_expiry' => $request->trial_end,
        ]);

        return redirect()->back()->with('success', 'Client plan renewed successfully!');
    }
}
