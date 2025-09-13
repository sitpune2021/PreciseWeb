<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinerecord;
use App\Models\WorkOrder;
use App\Models\Machine;
use App\Models\Operator;
use App\Models\Setting;
use App\Models\SetupSheet;

class MachinerecordController extends Controller
{

    public function AddMachinerecord()
{
    $workorders = WorkOrder::with('customer')
        ->whereHas('customer', function($q){
            $q->where('status', 1);  // Active customers फक्त
        })
        ->latest()
        ->get();

    $machines   = Machine::all();
    $operators  = Operator::all();
    $settings   = Setting::all();

    return view('Machinerecord.add', compact('workorders', 'machines', 'operators', 'settings'));
}


    public function StoreMachinerecord(Request $request)
    {
        $validated = $request->validate([
            'part_no'     => 'required|string|max:100',
            'code'        => 'required|string|max:100',
            'work_order'  => 'required|string|max:100',
            'first_set'   => 'required|string|max:100',
            'qty'         => 'required|integer|min:1',
            'machine'     => 'required|string|max:100',
            'operator'    => 'required|string|max:100',
            'setting_no'  => 'required|string|max:100',
            'est_time'    => 'required|string|max:100',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
            'hrs'         => 'required|numeric|min:0',
            'time_taken'  => 'required|numeric|min:0',
            'actual_hrs'  => 'required|numeric|min:0',
            'invoice_no'  => 'required|string|max:100',
        ]);

        Machinerecord::create($validated);

        return redirect()->route('ViewMachinerecord')->with('success', 'Machine Record Added Successfully');
    }

    public function ViewMachinerecord()
    {
        $record = Machinerecord::latest()->get();

        $workorders = WorkOrder::with('customer')->latest()->get();

        return view('Machinerecord.view', compact('record', 'workorders'));
    }

    public function edit(string $encryptedId)
{
    $id = base64_decode($encryptedId);
    $record = Machinerecord::findOrFail($id);

    $workorders = WorkOrder::with('customer')
        ->whereHas('customer', function($q) use ($record){
            $q->where('status', 1)
              ->orWhere('id', $record->customer_id); // sadhya record chi customer included
        })
        ->latest()
        ->get();

    $machines   = Machine::all();
    $operators  = Operator::all();
    $settings   = Setting::all();

    return view('Machinerecord.add', compact('record', 'workorders', 'machines', 'operators', 'settings'));
}


    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $record = Machinerecord::findOrFail($id);

        $validated = $request->validate([
            'part_no'     => 'required|string|max:100',
            'code'        => 'required|string|max:100',
            'work_order'  => 'required|string|max:100',
            'first_set'   => 'required|string|max:100',
            'qty'         => 'required|integer|min:1',
            'machine'     => 'required|string|max:100',
            'operator'    => 'required|string|max:100',
            'setting_no'  => 'required|string|max:100',
            'est_time'    => 'required|string|max:100',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
            'hrs'         => 'required|numeric|min:0',
            'time_taken'  => 'required|numeric|min:0',
            'actual_hrs'  => 'required|numeric|min:0',
            'invoice_no'  => 'required|string|max:100',
        ]);

        $record->update($validated);

        return redirect()->route('ViewMachinerecord')->with('success', 'Machine Record Updated Successfully');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $record = Machinerecord::findOrFail($id);
        $record->delete();
        return redirect()->route('ViewMachinerecord')->with('success', 'Branch deleted successfully.');
    }

    public function fetchData($part_code)
    {
        $data = SetupSheet::where('part_code', $part_code)->first();

        if ($data) {
            return response()->json([
                'work_order_no' => $data->customer_id,
                'description' => $data->description,
                'qty' => $data->qty,
                'est_time' => $data->est_time,
            ]);
        }
        return response()->json([]);
    }
}
