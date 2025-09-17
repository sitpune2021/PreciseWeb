<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;

class FinancialYearController extends Controller
{
    public function AddFinancialYear()
    {
        $years = FinancialYear::orderBy('id', 'desc')->get();
        return view('FinancialYear.add', compact('years'));
    }

    public function storeFinancialYear(Request $request)
    {
        $request->validate([
            'year' => 'required|string|unique:financial_years,year|max:255',
        ]);

        FinancialYear::create([
            'year' => $request->year,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('AddFinancialYear')->with('success', 'Financial Year added successfully');
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $year = FinancialYear::findOrFail($id);
        $years = FinancialYear::orderBy('id', 'desc')->get();
        return view('FinancialYear.add', compact('year', 'years'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'year' => 'required|string|unique:financial_years,year,' . $id . '|max:255',
        ]);

        $year = FinancialYear::findOrFail($id);
        $year->update([
            'year' => $request->year,
            'status' => $request->status ?? 0,
        ]);

        return redirect()->route('AddFinancialYear')->with('success', 'Financial Year updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $year = FinancialYear::findOrFail($id);
        $year->delete();
        return redirect()->route('AddFinancialYear')->with('success', 'Financial Year deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $year = FinancialYear::findOrFail($request->id);
        $year->status = $request->has('status') ? 1 : 0;
        $year->save();

        return back()->with('success', 'Status updated!');
    }
}
