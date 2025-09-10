<?php

namespace App\Http\Controllers;

use App\Models\Machine;


use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddMachine()
    {
        $machines = Machine::orderBy('id', 'desc')->get();
        // dd($machines);
        return view('Machine.add', compact('machines'));
    }

    public function storeMachine(Request $request)
    {
        $request->validate([
            'machine_name' => [
                'required',
                'unique:machines,machine_name',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
            ],
        ]);

        Machine::create([
            'machine_name' => $request->machine_name,
        ]);

        return redirect()->route('AddMachine')->with('success', 'Machine added successfully');
    }

    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $machine = Machine::findOrFail($id);
            $machines = Machine::orderBy('id', 'desc')->get();
            return view('Machine.add', compact('machine', 'machines'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'machine_name' => ['required','unique:machines,machine_name,' . $id,'regex:/^[A-Za-z\s]+$/','max:255',],
        ]);
            $machine = Machine::findOrFail($id);
            $machine->machine_name = $request->machine_name;
            $machine->save();

            return redirect()->route('AddMachine')->with('success', 'Machine updated successfully.');
          
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $machine = Machine::findOrFail($id);
        $machine->delete();
        return redirect()->route('AddMachine')->with('success', 'Branch deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $machine = Machine::findOrFail($request->id);
        $machine->status = $request->has('status') ? 1 : 0;
        $machine->save();

        return back()->with('success', 'Status updated!');
    }
}
