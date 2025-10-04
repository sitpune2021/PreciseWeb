<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddMachine()
    {
        $machines = Machine::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();
 
        return view('Machine.add', compact('machines'));
    }
    public function storeMachine(Request $request)
    {
        $request->validate([
            'machine_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('machines', 'machine_name')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())   // प्रत्येक admin साठी वेगळं
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
        ], [
            'machine_name.unique' => 'This machine name already exists for your account.', // ✅ custom message
            'machine_name.regex'  => 'The machine name may only contain letters and spaces.',
        ]);

        Machine::create([
            'machine_name' => $request->machine_name,
            'is_active'    => 1,
            'admin_id'     => Auth::id(),
        ]);

        return redirect()->route('AddMachine')->with('success', 'Machine added successfully');
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $machine = Machine::where('id', $id)
            ->where('admin_id', Auth::id())                 //admin_id
            ->firstOrFail();

        $machines = Machine::where('is_active', 1)
            ->where('admin_id', Auth::id())                 //admin_id
            ->orderBy('id', 'desc')
            ->get();
        return view('Machine.add', compact('machine', 'machines'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'machine_name' => ['required', 'unique:machines,machine_name,' . $id, 'regex:/^[A-Za-z\s]+$/', 'max:255',],
        ]);
        $machine = Machine::where('id', $id)
            ->where('admin_id', Auth::id())                 //admin_id
            ->firstOrFail();

        $machine->machine_name = $request->machine_name;
        $machine->is_active = 1;
        $machine->restore();
        $machine->created_at = now(); // Make it appear on top
        $machine->save();
        $machine->save();

        return redirect()->route('AddMachine')->with('success', 'Machine updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $machine = Machine::where('id', $id)
            ->where('admin_id', Auth::id())                 //admin_id
            ->firstOrFail();

        $machine->delete();
        return redirect()->route('AddMachine')->with('success', 'Branch deleted successfully.');
    }

    public function updateStatus(Request $request)
    {

        $machine = Machine::where('id', $request->id)
            ->where('admin_id', Auth::id())         //admin_id
            ->firstOrFail();

        $machine->status = $request->has('status') ? 1 : 0;
        $machine->save();

        return back()->with('success', 'Status updated!');
    }


    public function trash()
    {
        // Get soft deleted operators
        $trashedmachine = Machine::onlyTrashed()
            ->where('admin_id', Auth::id())         //admin_id
            ->orderBy('id', 'desc')
            ->get();

        // Get active operators
        $Machine = Machine::where('admin_id', Auth::id())->get();

        return view('Machine.trash', compact('trashedmachine', 'Machine'));
    }


    // Restore machine
   public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $machine = Machine::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();
 
        $exists = Machine::where('machine_name', $machine->machine_name)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();
 
        $machine->is_active = 1;
        $machine->restore();
        $machine->touch();
        $machine->save();
 
        if ($exists) {
            return redirect()->route('editMachine', base64_encode($machine->id))
                ->with('success', "Machine '{$machine->machine_name}' already exists. Redirected to Edit Page.");
        }
 
        return redirect()->route('AddMachine')
            ->with('success', "Machine '{$machine->machine_name}' restored successfully.");
    }
}
