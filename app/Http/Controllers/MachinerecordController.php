<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MachineRecord;
use App\Models\WorkOrder;
use App\Models\Machine;
use App\Models\Operator;
use App\Models\Setting;
use App\Models\SetupSheet;
use App\Models\Customer;
use App\Models\MaterialType;
use Illuminate\Support\Facades\Auth;

class MachinerecordController extends Controller
{
    public function AddMachinerecord()
    {
        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id()) // Only current admin
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $materialtype = MaterialType::where('admin_id', Auth::id())->get(); 
        $workorders = WorkOrder::with('customer')
            ->where('admin_id', Auth::id()) // Only current admin
            ->whereHas('customer', function ($q) {
                $q->where('status', 1)
                    ->where('admin_id', Auth::id());
            })
            ->latest()
            ->get();

        $machines  = Machine::where('admin_id', Auth::id())->get(); // Only current admin
        $operators = Operator::where('admin_id', Auth::id())->get(); // Only current admin
        $settings  = Setting::where('admin_id', Auth::id())->get(); // Only current admin

        return view('Machinerecord.add', compact('workorders', 'machines', 'operators', 'settings', 'codes', 'materialtype'));
    }

    public function StoreMachinerecord(Request $request)
    {
        $validated = $request->validate([
            'part_no'     => 'required|string|max:100',
            'code'        => 'required|string|max:100',
            'work_order'  => 'required|string|max:100',
            'first_set'   => 'nullable|string|max:100',
            'qty'         => 'required|integer|min:1',
            'machine'     => 'required|string|max:100',
            'operator'    => 'required|string|max:100',
            'setting_no'  => 'required|string|max:100',
            'est_time'    => 'required|string|max:100',
            'material'    => 'required|string|max:200',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
            'minute'      => 'required|numeric|min:0',
            'hrs'         => 'required|numeric|min:0',
            'time_taken'  => 'required|numeric|min:0',
            'actual_hrs'  => 'required|numeric|min:0',
            'invoice_no'  => 'nullable|string|max:100',
        ]);

        $validated['admin_id'] = Auth::id(); // Assign admin_id
        MachineRecord::create($validated);

        return redirect()->route('ViewMachinerecord')->with('success', 'Machine Record Added Successfully');
    }

    public function ViewMachinerecord()
    {
        $record = MachineRecord::where('admin_id', Auth::id())->latest()->get(); 
        $workorders = WorkOrder::with('customer')->where('admin_id', Auth::id())->latest()->get(); // Only current admin

        return view('Machinerecord.view', compact('record', 'workorders'));
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $record = MachineRecord::where('admin_id', Auth::id())->findOrFail($id);  
        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id())
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $materialtype = MaterialType::where('admin_id', Auth::id())->get(); // Only current admin

        $workorders = WorkOrder::with('customer')
            ->where('admin_id', Auth::id())
            ->whereHas('customer', function ($q) use ($record) {
                $q->where('status', 1)
                    ->where('admin_id', Auth::id())
                    ->orWhere('id', $record->customer_id); // include current record's customer
            })
            ->latest()
            ->get();

        $machines  = Machine::where('admin_id', Auth::id())->get();
        $operators = Operator::where('admin_id', Auth::id())->get();
        $settings  = Setting::where('admin_id', Auth::id())->get();

        return view('Machinerecord.add', compact('record', 'workorders', 'machines', 'operators', 'settings', 'materialtype', 'codes'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $record = MachineRecord::where('admin_id', Auth::id())->findOrFail($id); // Only current admin

        $validated = $request->validate([
            'part_no'     => 'required|string|max:100',
            'code'        => 'required|string|max:100',
            'work_order'  => 'required|string|max:100',
            'first_set'   => 'nullable|string|max:100',
            'qty'         => 'required|integer|min:1',
            'machine'     => 'required|string|max:100',
            'operator'    => 'required|string|max:100',
            'setting_no'  => 'required|string|max:100',
            'material'    => 'required|string|max:200',
            'est_time'    => 'required|string|max:100',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
            'minute'      => 'required|numeric|min:0',
            'hrs'         => 'required|numeric|min:0',
            'time_taken'  => 'required|numeric|min:0',
            'actual_hrs'  => 'required|numeric|min:0',
            'invoice_no'  => 'nullable|string|max:100',
        ]);

        $validated['admin_id'] = Auth::id(); // Ensure admin_id stays current admin
        $record->update($validated);

        return redirect()->route('ViewMachinerecord')->with('success', 'Machine Record Updated Successfully');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $record = MachineRecord::where('admin_id', Auth::id())->findOrFail($id); // Only current admin
        $record->delete();

        return redirect()->route('ViewMachinerecord')->with('success', 'Machine Record deleted successfully.');
    }

    public function fetchData($part_code)
    {
        $data = SetupSheet::where('part_code', $part_code)
            ->where('admin_id', Auth::id()) // Only current admin
            ->first();

        if ($data) {
            return response()->json([
                'work_order_no' => $data->customer_id,
                'description'   => $data->description,
                'qty'           => $data->qty,
                'exp_time'      => $data->exp_time,
            ]);
        }

        return response()->json([]);
    }


    public function trash()
    {
        $trashedMachines = MachineRecord::onlyTrashed()
            ->orderBy('id', 'desc')
            ->get();

        $machines = MachineRecord::all();

        return view('Machinerecord.trash', compact('trashedMachines', 'machines'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $machine = MachineRecord::withTrashed()->findOrFail($id);

        // Duplicate check using part_no + work_order
        $exists = MachineRecord::where('part_no', $machine->part_no)
            ->where('work_order', $machine->work_order)
            ->whereNull('deleted_at')
            ->exists();

        // Restore the record
        $machine->restore();

        if ($exists) {
            // Duplicate exists → redirect to edit page
            return redirect()->route('EditMachinerecord', base64_encode($machine->id))
                ->with('success', "Machine record with Part No '{$machine->part_no}' and Work Order '{$machine->work_order}' already exists. You will be redirected to the Edit Page.");
        }

        // No duplicate → redirect to main list
        return redirect()->route('ViewMachinerecord')
            ->with('success', "Machine record with Part No '{$machine->part_no}' restored successfully.");
    }
}
