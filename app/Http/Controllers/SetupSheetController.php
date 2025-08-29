<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\SetupSheet;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Setting;
use Illuminate\Http\Request;

class SetupSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddSetupSheet()
    {

        $codes = Customer::select('id', 'code', 'name')->orderBy('id', 'desc')->get();
        $settings   = Setting::all();
        return view('SetupSheet.add', compact('codes', 'settings'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function storeSetupSheet(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'part_code'     => 'required|string|max:255',
            'work_order_no' => 'required|string|max:255',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',

            'size_in_x' => 'required|numeric|min:0',
            'size_in_y' => 'required|numeric|min:0',
            'size_in_z' => 'required|numeric|min:0',

            'setting'   => 'required|string|max:255',
            'e_time'    => 'required|string|max:255',

            'x_refer'  => 'required|string|max:255',
            'y_refer'  => 'required|string|max:255',
            'z_refer'  => 'required|string|max:255',
            'clamping' => 'required|string|max:255',

            'thickness' => 'required|string|min:1',
            'qty'       => 'required|integer|min:1',

            // Hole Details (arrays)
            'holes'      => 'required|array',
            'holes.*'    => 'required|numeric|min:0',
            'hole_x'     => 'required|array',
            'hole_x.*'   => 'required|numeric|min:0',
            'hole_y'     => 'required|array',
            'hole_y.*'   => 'required|numeric|min:0',
            'hole_dia'   => 'required|array',
            'hole_dia.*' => 'required|numeric|min:0',
            'hole_depth' => 'required|array',
            'hole_depth.*' => 'required|numeric|min:0',
        ]);

        SetupSheet::create($validated);

        return redirect()->route('ViewSetupSheet')->with('success', 'SetupSheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function ViewSetupSheet()
    {
        $sheets = SetupSheet::orderBy('id', 'desc')->get();
        return view('SetupSheet.view', compact('sheets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSetupSheet(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            
            $setupSheet = SetupSheet::findOrFail($id);

            // dd($setupSheet);
            $settings   = Setting::all();
            $codes = Customer::select('id', 'code', 'name')->get();
            return view('SetupSheet.add', compact('setupSheet', 'codes', 'settings'));
        } catch (\Exception $setup) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setupSheet = SetupSheet::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'part_code'     => 'required|string|max:255',
            'work_order_no' => 'required|string|max:255',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',

            'size_in_x' => 'required|numeric|min:0',
            'size_in_y' => 'required|numeric|min:0',
            'size_in_z' => 'required|numeric|min:0',

            'setting'   => 'required|string|max:255',
            'e_time'    => 'required|string|max:255',

            'x_refer'  => 'required|string|max:255',
            'y_refer'  => 'required|string|max:255',
            'z_refer'  => 'required|string|max:255',
            'clamping' => 'required|string|max:255',

            'thickness' => 'required|string|min:1',
            'qty'       => 'required|integer|min:1',

            'holes'        => 'required|array',
            'holes.*'      => 'required|numeric|min:0',
            'hole_x'       => 'required|array',
            'hole_x.*'     => 'required|numeric|min:0',
            'hole_y'       => 'required|array',
            'hole_y.*'     => 'required|numeric|min:0',
            'hole_dia'     => 'required|array',
            'hole_dia.*'   => 'required|numeric|min:0',
            'hole_depth'   => 'required|array',
            'hole_depth.*' => 'required|numeric|min:0',
        ]);


        $setupSheet->update($validated);

        return redirect()->route('ViewSetupSheet')->with('success', 'Setup Sheet updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setup = SetupSheet::findOrFail($id);
        $setup->delete();
        return redirect()->route('ViewSetupSheet')->with('success', 'Branch deleted successfully.');
    }


    public function getCustomerParts($customerId)
    {
        $parts = WorkOrder::where('customer_id', $customerId)
            ->select('id', 'part', 'part_description', 'customer_id') // ✅ add part_description
            ->with('customer:id,code')
            ->get();

        $formatted = $parts->map(function ($wo) {
            return [
                'id' => $wo->id,
                'part' => $wo->part,
                'part_code' => ($wo->customer->code ?? '') . '_' . $wo->customer_id . '_' . $wo->part,
                'part_description' => $wo->part_description, // ✅ send description
            ];
        });

        return response()->json($formatted);
    }
}
