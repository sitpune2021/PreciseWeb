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
        $machines = Machine::latest()->get(); // Operator list
        return view('Machine.add', compact('machines'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function storeMachine(Request $request)
    {
        $request->validate([
            'machine_name' => 'required|string|max:255',
        ]);
 
        Machine::create([
            'machine_name' => $request->machine_name,
        ]);
 
        return redirect()->route('AddMachine')->with('success', 'Machine added successfully');
    }
 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $machine = Machine::findOrFail($id);
            $machines = Machine::all();
            return view('Machine.add', compact('machine', 'machines'));
        } catch (\Exception $e) {
            abort(404);
        }
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        $request->validate([
            'machine_name' => 'required|string|max:255',
        ]);
 
        try {
            $id = base64_decode($encryptedId);
            $machine = Machine::findOrFail($id);
 
            $machine->machine_name = $request->machine_name;
            $machine->save();
 
            return redirect()->route('AddMachine')->with('success', 'Machine updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }
 
 
    /**
     * Remove the specified resource from storage.
     */
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