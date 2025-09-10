<?php
 
namespace App\Http\Controllers;
 
use App\Models\Operator;
 
 
use Illuminate\Http\Request;
 
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
            'operator_name' => ['required','unique:operators,operator_name','regex:/^[A-Za-z\s]+$/','max:255',
            ],
        ]);
 
        Operator::create([
            'operator_name' => $request->operator_name,
        ]);
 
        return redirect()->route('AddOperator')->with('success', 'Operator added successfully');
    }

    public function edit(string $encryptedId)
    {
            $id = base64_decode($encryptedId);
            $operator = Operator::findOrFail($id);
            $operators = Operator::orderBy('id', 'desc')->get();
            return view('Operator.add', compact('operator', 'operators'));        
    }
 
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
 
        $request->validate([
            'operator_name' => ['required','unique:operators,operator_name,' . $id, 'regex:/^[A-Za-z\s]+$/','max:255',
            ],
        ]);
 
      
            $operator = Operator::findOrFail($id);
            $operator->operator_name = $request->operator_name;
            $operator->save();
 
            return redirect()->route('AddOperator')->with('success', 'Operator updated successfully.');     
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
}