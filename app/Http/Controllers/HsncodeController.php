<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Hsncode;
use Illuminate\Support\Facades\Auth;

class HsncodeController extends Controller
{
    // Show add form + list
    public function addHsn()
    {
        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id()) // filter by logged-in admin
            ->latest()
            ->get();

        return view('Hsncode.add', compact('hsncodes'));
    }

    // Store record
    public function store(Request $request)
    {
        $request->validate([
            'hsn_code' => [
                'required',
                Rule::unique('hsncodes', 'hsn_code')
                    ->where(fn($query) => $query->where('admin_id', Auth::id()))
                    ->whereNull('deleted_at')
            ],
            'sgst'         => 'required|numeric',
            'cgst'         => 'required|numeric',
            'igst'         => 'required|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        Hsncode::create([
            'hsn_code'     => $request->hsn_code,
            'sgst'         => $request->sgst,
            'cgst'         => $request->cgst,
            'igst'         => $request->igst,
            'invoice_desc' => $request->invoice_desc,
            'admin_id'     => Auth::id(),
            'is_active'    => 1,
            'status'       => 1,
        ]);

        return redirect()->back()->with('success', 'HSN Code added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $id  = base64_decode($id);

        $hsn = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail(); // removed is_active filter

        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Hsncode.add', compact('hsn', 'hsncodes'));
    }


    // Update record
    public function update(Request $request, $id)
    {
        $id  = base64_decode($id);
        $hsn = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'hsn_code'     => 'required|unique:hsncodes,hsn_code,' . $hsn->id,
            'sgst'         => 'required|numeric',
            'cgst'         => 'required|numeric',
            'igst'         => 'required|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        $hsn->update([
            'hsn_code'     => $request->hsn_code,
            'sgst'         => $request->sgst,
            'cgst'         => $request->cgst,
            'igst'         => $request->igst,
            'invoice_desc' => $request->invoice_desc,
            'is_active'    => 1, // ✅ make active on update
        ]);

        return redirect()->route('addHsn')->with('success', 'HSN Code updated successfully!');
    }


    // Status change
    public function updateStatus(Request $request)
    {
        $hsn = Hsncode::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $hsn->status = $hsn->status ? 0 : 1;
        $hsn->save();

        return back();
    }

    // Delete record
    public function destroy($id)
    {
        $id  = base64_decode($id);

        $hsn = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $hsn->is_active = 0;
        $hsn->save();

        $hsn->delete();

        return back()->with('success', 'HSN Code deleted successfully!');
    }

    // Show Trash
    public function trash()
    {

        $trashedhsn = Hsncode::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('deleted_at', 'desc') // latest deleted top
            ->get();

        // Active HSN list for duplicate check
        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc') // newest top
            ->get();
        return view('Hsncode.trash', compact('trashedhsn', 'hsncodes'));
    }

    // Restore
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);

        $hsncode = Hsncode::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        // Check if same HSN already exists for same admin
        $exists = Hsncode::where('hsn_code', $hsncode->hsn_code)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            // Restore but keep inactive
            $hsncode->is_active = 0;
            $hsncode->restore();
            $hsncode->save();

            return redirect()->route('editHsn', base64_encode($hsncode->id))
                ->with('success', "HSN Code '{$hsncode->hsn_code}' already exists. Redirected to Edit Page.");
        }

        // Normal restore
        $hsncode->is_active = 1;
        $hsncode->restore();
        $hsncode->save();

        return redirect()->route('addHsn')
            ->with('success', "HSN Code '{$hsncode->hsn_code}' restored successfully.");
    }
}
