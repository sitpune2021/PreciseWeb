<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RateController extends Controller
{
    // Show all active rates
    public function Addrate()
    {
        $rates = Rate::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Rate.add', compact('rates'));
    }

    // Store new rate
    public function storeRate(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rates', 'name')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],
            'hour' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        Rate::create([
            'name'      => $request->name,
            'rate'      => $request->rate,
            'hour'      => $request->hour,
            'is_active' => 1,
            'admin_id'  => Auth::id(),
        ]);

        return redirect()->route('Addrate')->with('success', 'Rate added successfully');
    }

    // Edit rate
    public function editRate(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $rate = Rate::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $rates = Rate::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Rate.add', compact('rates', 'rate'));
    }

    // Update rate
    public function updateRate(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $rate = Rate::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rates', 'name')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    })
                    ->ignore($rate->id),
            ],
            'rate' => [
                'required',
                'numeric',
                'min:0',
            ],
            'hour' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $rate->update([
            'name'      => $request->name,
            'rate'      => $request->rate,
            'hour'      => $request->hour,
            'is_active' => 1,
            'admin_id'  => Auth::id(),
        ]);

        return redirect()->route('Addrate')->with('success', 'Rate updated successfully');
    }

    // Update status (active/inactive)
    public function updateratestatus(Request $request)
    {
        $rate = Rate::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $rate->status = $request->input('status', 0);
        $rate->save();

        return back()->with('success', 'Status updated!');
    }

    // Soft delete rate
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $rate = Rate::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $rate->is_active = 0;
        $rate->save();
        $rate->delete();

        return redirect()->route('Addrate')->with('success', 'Rate deleted successfully.');
    }

    // Show trashed rates
    public function trash()
    {
        $trashedRates = Rate::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $rates = Rate::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Rate.trash', compact('trashedRates', 'rates'));
    }

    // Restore trashed rate
    public function restore(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $rate = Rate::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        // Check for duplicates
        $exists = Rate::where('name', $rate->name)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            $rate->restore();
            $rate->is_active = 0;
            $rate->touch();
            $rate->save();

            return redirect()->route('editRate', base64_encode($rate->id))
                ->with('success', "Rate '{$rate->name}' already exists. Redirected to Edit Page.");
        }

        $rate->restore();
        $rate->is_active = 1;
        $rate->touch();
        $rate->save();

        return redirect()->route('Addrate')
            ->with('success', "Rate '{$rate->name}' restored successfully.");
    }
}
