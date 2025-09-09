<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hsncode;

class HsncodeController extends Controller
{
    // Show add form + list
    public function addHsn()
    {
        $hsncodes = Hsncode::orderBy('id','desc')->get();
        return view('Hsncode.add', compact('hsncodes'));
    }

    // Store record
    public function store(Request $request)
    {
        $request->validate([
            'hsn_code'     => 'required|unique:hsncodes,hsn_code,' . ($id ?? 'NULL') . ',id',
            'sgst'         => 'required|numeric',
            'cgst'         => 'required|numeric',
            'igst'         => 'required|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        Hsncode::create($request->all());
        return redirect()->back()->with('success', 'HSN Code added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $hsn = Hsncode::findOrFail(base64_decode($id));
        $hsncodes = Hsncode::orderBy('id','desc')->get();
        return view('Hsncode.add', compact('hsn','hsncodes'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $hsn = Hsncode::findOrFail(base64_decode($id));

        $request->validate([
            'hsn_code' => 'required|unique:hsncodes,hsn_code,'.$hsn->id,
            'sgst' => 'required|numeric',
            'cgst' => 'required|numeric',
            'igst' => 'required|numeric',
            'invoice_desc' => 'required|string|max:255',
        ]);

        $hsn->update($request->all());
        return redirect()->route('addHsn')->with('success', 'HSN Code updated successfully!');
    }

    // Status change
    public function updateStatus(Request $request)
    {
        $hsn = Hsncode::find($request->id);
        $hsn->status = $hsn->status ? 0 : 1;
        $hsn->save();
        return back();
    }

    // Delete record
    public function destroy($id)
    {
        $hsn = Hsncode::findOrFail(base64_decode($id));
        $hsn->delete();
        return back()->with('success', 'HSN Code deleted successfully!');
    }
}
