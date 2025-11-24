<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Hsncode;
use Illuminate\Support\Facades\Auth;

class HsncodeController extends Controller
{
    public function addHsn()
    {
        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Hsncode.add', compact('hsncodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hsn_code' => [
                'required',
                Rule::unique('hsncodes', 'hsn_code')
                    ->where(fn($query) => $query->where('admin_id', Auth::id()))
                    ->whereNull('deleted_at')
            ],
            'sgst'         => 'nullable|numeric',
            'cgst'         => 'nullable|numeric',
            'igst'         => 'nullable|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        Hsncode::create([
            'hsn_code'     => $request->hsn_code,
            'sgst'         => $request->filled('sgst') ? $request->sgst : 0,
            'cgst'         => $request->filled('cgst') ? $request->cgst : 0,
            'igst'         => $request->filled('igst') ? $request->igst : 0,
            'invoice_desc' => $request->invoice_desc,
            'admin_id'     => Auth::id(),
            'is_active'    => 1,
            'status'       => 1,
        ]);

        return redirect()->back()->with('success', 'HSN Code added successfully!');
    }

    public function edit($id)
    {
        $id  = base64_decode($id);

        $hsn = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Hsncode.add', compact('hsn', 'hsncodes'));
    }

    public function update(Request $request, $id)
    {
        $id  = base64_decode($id);
        $hsn = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'hsn_code'     => 'required|unique:hsncodes,hsn_code,' . $hsn->id,
            'sgst'         => 'nullable|numeric',
            'cgst'         => 'nullable|numeric',
            'igst'         => 'nullable|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        $hsn->update([
            'hsn_code'     => $request->hsn_code,
            'sgst'         => $request->filled('sgst') ? $request->sgst : 0,
            'cgst'         => $request->filled('cgst') ? $request->cgst : 0,
            'igst'         => $request->filled('igst') ? $request->igst : 0,
            'invoice_desc' => $request->invoice_desc,
            'is_active'    => 1,
        ]);

        return redirect()->route('addHsn')->with('success', 'HSN Code updated successfully!');
    }

    public function updateStatus(Request $request)
    {
        $hsn = Hsncode::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $hsn->status = $hsn->status ? 0 : 1;
        $hsn->save();

        return back();
    }

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

    public function trash()
    {

        $trashedhsn = Hsncode::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('deleted_at', 'desc')
            ->get();

        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
        return view('Hsncode.trash', compact('trashedhsn', 'hsncodes'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);

        $hsncode = Hsncode::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $exists = Hsncode::where('hsn_code', $hsncode->hsn_code)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            $hsncode->is_active = 0;
            $hsncode->restore();
            $hsncode->touch();
            $hsncode->save();

            return redirect()->route('editHsn', base64_encode($hsncode->id))
                ->with('success', "HSN Code '{$hsncode->hsn_code}' already exists. Redirected to Edit Page.");
        }

        $hsncode->is_active = 1;
        $hsncode->restore();
        $hsncode->touch();
        $hsncode->save();

        return redirect()->route('addHsn')
            ->with('success', "HSN Code '{$hsncode->hsn_code}' restored successfully.");
    }
}
