<?php
 
namespace App\Http\Controllers;
 
use App\Models\Operator;
 
 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
 
 
class OperatorController extends Controller
{
 
    public function AddOperator()
    {
        $operators = Operator::latest()->get();
        return view('Operator.add', compact('operators'));
    }
 
    public function storeOperator(Request $request)
    {
        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')->whereNull('deleted_at'),
            ],
        ]);
 
 
 
        Operator::create([
            'operator_name' => $request->operator_name,
        ]);
 
        return redirect()->route('AddOperator')->with('success', 'Operator added successfully');
    }
 
 
    public function show(string $id) {}
 
 
    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $operator = Operator::findOrFail($id);
            $operators = Operator::orderBy('id', 'desc')->get();
            return view('Operator.add', compact('operator', 'operators'));
        } catch (\Exception $e) {
            abort(404);
        }
    }
 
 
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
 
        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
        ]);
 
        try {
            $operator = Operator::findOrFail($id);
            $operator->operator_name = $request->operator_name;
            $operator->save();
 
            return redirect()->route('AddOperator')
                ->with('success', 'Operator updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }
 
 
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $Operator = Operator::findOrFail($id);
        $Operator->delete();
        return redirect()->route('AddOperator')->with('success', 'Branch deleted successfully.');
    }
 
 
    public function updateOperatorStatus(Request $request)
    {
        $operator = Operator::findOrFail($request->id);
 
        $operator->status = $request->has('status') ? 1 : 0;
        $operator->save();
 
        return back()->with('success', 'Status updated!');
    }
 
 
    public function trash()
    {
        $trashedOperators = Operator::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('Operator.trash', compact('trashedOperators'));
    }
 
   
 
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::withTrashed()->findOrFail($id);
 
        $exists = Operator::where('operator_name', $operator->operator_name)
            ->whereNull('deleted_at')
            ->exists();
 
        if ($exists) {
            $newName = $operator->operator_name . ' (1)';
            $counter = 1;
 
            while (Operator::where('operator_name', $newName)
                ->whereNull('deleted_at')
                ->exists()
            ) {
                $counter++;
                $newName = $operator->operator_name . ' (' . $counter . ')';
            }
 
            $operator->operator_name = $newName;
            $operator->save();
            $operator->restore();
 
            return redirect()->route('trashOperator')
                ->with('success', "This operator already exists. Restored as '$newName'.");
        }
 
        $operator->restore();
 
        return redirect()->route('trashOperator')->with('success', 'Operator restored successfully.');
    }
}
 