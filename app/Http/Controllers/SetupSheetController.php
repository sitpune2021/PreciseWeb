<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\SetupSheet;
use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class SetupSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddSetupSheet()
    {

        $codes = Customer::select('id', 'code', 'name')->get();

        return view('SetupSheet.add', compact('codes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeSetupSheet(Request $request)
    {
        $request->validate([
            // Basic Info
            'part_code'     => 'required|string|max:255',
            'work_order_no' => 'required|string|max:255',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',

            // Size Fields
            'size_in_x' => 'required|numeric|min:0',
            'size_in_y' => 'required|numeric|min:0',
            'size_in_z' => 'required|numeric|min:0',

            // Setup Info
            'setting'   => 'required|string|max:255',
            'e_time'    => 'required|string|max:255',

            // Reference
            'x_refer'   => 'required|string|max:255',
            'y_refer'   => 'required|string|max:255',
            'z_refer'   => 'required|string|max:255',
            'clamping'  => 'required|string|max:255',

            // Thickness & Quantity
            'thickness' => 'required|numeric|min:1',
            'qty'       => 'required|integer|min:1',

            // Hole Details
            'holes'      => 'required|string|max:255',
            'hole_x'     => 'required|numeric|min:0',
            'hole_y'     => 'required|numeric|min:0',
            'hole_dia'   => 'required|numeric|min:0',
            'hole_depth' => 'required|numeric|min:0',
        ]);

        SetupSheet::create($request->all());

        return redirect()->route('ViewSetupSheet')->with('success', 'SetupSheet created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function ViewSetupSheet()
    {
        $sheets = SetupSheet::all();
        return view('SetupSheet.view', compact('sheets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editSetupSheet(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $setup = SetupSheet::findOrFail($id);
            $method = "PUT";
            $codes = Customer::select('id', 'code', 'name')->get();
            return view('SetupSheet.add', compact('setup', 'method', 'codes'));
        } catch (\Exception $setup) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
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
            ->select('id', 'part', 'customer_id')
            ->with('customer:id,code')
            ->get();

        $formatted = $parts->map(function ($wo) {
            return [
                'id' => $wo->id,
                'part' => $wo->part,
                'part_code' => ($wo->customer->code ?? '') . '_' . $wo->customer_id . '_' . $wo->part
            ];
        });

        return response()->json($formatted);
    }
}
