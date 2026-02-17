<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FinancialYearController extends Controller
{
    public function AddFinancialYear()
    {
        $adminId = Auth::id();
        $years = FinancialYear::where('admin_id', $adminId)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('FinancialYear.add', compact('years'));
    }
    public function storeFinancialYear(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            'year' => [
                'required',
                'string',
                'max:255',
                Rule::unique('financial_years', 'year')
                    ->whereNull('deleted_at')
                    ->where('admin_id', $adminId),
            ],
        ]);

        FinancialYear::create([
            'year' => $request->year,
            'status' => $request->status ?? 1,
            'admin_id' => $adminId,
        ]);

        return redirect()->route('AddFinancialYear')
            ->with('success', 'Financial Year added successfully');
    }
    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $year = FinancialYear::findOrFail($id);
        $adminId = Auth::id();
        $years = FinancialYear::where('admin_id', $adminId)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('FinancialYear.add', compact('year', 'years'));
    }
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $adminId = Auth::id();

        $request->validate([
            'year' => [
                'required',
                'string',
                'max:255',
                Rule::unique('financial_years', 'year')
                    ->whereNull('deleted_at')
                    ->where('admin_id', $adminId)
                    ->ignore($id),
            ],
        ]);

        $year = FinancialYear::findOrFail($id);
        $year->update([
            'year' => $request->year,
            'status' => 1,
            'admin_id' => $adminId,
        ]);

        return redirect()->route('AddFinancialYear')
            ->with('success', 'Financial Year updated successfully.');
    }
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $year = FinancialYear::findOrFail($id);
        $year->delete();

        return redirect()->route('AddFinancialYear')
            ->with('success', 'Financial Year deleted successfully.');
    }
    public function updateStatus(Request $request)
    {
        $year = FinancialYear::findOrFail($request->id);
        $year->status = $request->has('status') ? 1 : 0;
        $year->save();

        return back()->with('success', 'Status updated!');
    }
    public function trash()
    {
        $adminId = Auth::id();

        $trashedFinancialYears = FinancialYear::onlyTrashed()
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $financialYears = FinancialYear::whereNull('deleted_at')
            ->where('status', 1)
            ->where('admin_id', $adminId)
            ->get();

        return view('FinancialYear.trash', compact('trashedFinancialYears', 'financialYears'));
    }
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $adminId = Auth::id();
        $year = FinancialYear::withTrashed()->findOrFail($id);

        // Check if the same year already exists for the admin
        $exists = FinancialYear::where('year', $year->year)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->where('admin_id', $adminId)
            ->where('id', '!=', $year->id)
            ->exists();

        $year->restore();
        $year->status = 1;
        $year->admin_id = $adminId;
        $year->touch();
        $year->save();

        if ($exists) {
            return redirect()->route('EditFinancialYear', base64_encode($year->id))
                ->with('success', "Financial Year '{$year->year}' already exists for you. Redirected to Edit Page.");
        }

        return redirect()->route('AddFinancialYear')
            ->with('success', "Financial Year '{$year->year}' restored successfully.");
    }
}
