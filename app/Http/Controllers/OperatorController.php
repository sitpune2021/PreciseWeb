<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    // Show operator add/list page
    public function AddOperator()
    {
        $operators = Operator::where('is_active', 1)->latest()->get(); // only active
        return view('Operator.add', compact('operators'));
    }

    // Store new operator
    public function storeOperator(Request $request)
    {
        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')
                    ->where(function ($query) {
                        $query->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
        ]);

        Operator::create([
            'operator_name' => $request->operator_name,
            'is_active' => 1, // default active
        ]);

        return redirect()->route('AddOperator')->with('success', 'Operator added successfully');
    }

    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $operator = Operator::findOrFail($id);
            $operators = Operator::where('is_active', 1)->orderBy('id', 'desc')->get();
            return view('Operator.add', compact('operator', 'operators'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::findOrFail($id);

        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')
                    ->ignore($operator->id)
                    ->whereNull('deleted_at')
                    ->where('is_active', 1),
            ],
        ]);

        // Update existing operator
        $operator->operator_name = $request->operator_name;
        $operator->is_active = 1; // make active
        $operator->save();

        return redirect()->route('AddOperator')
            ->with('success', "Operator '{$operator->operator_name}' updated and activated successfully.");
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::findOrFail($id);

        $operator->is_active = 0; // make inactive
        $operator->save();

        $operator->delete(); // soft delete

        return redirect()->route('AddOperator')->with('success', 'Operator deleted successfully.');
    }


    public function updateOperatorStatus(Request $request)
    {
        $operator = Operator::findOrFail($request->id);
        $operator->status = $request->has('status') ? 1 : 0;
        $operator->save();
        return back()->with('success', 'Status updated!');
    }

    // Show trashed operators
    public function trash()
    {
        $trashedOperators = Operator::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('Operator.trash', compact('trashedOperators'));
    }

    // Restore operator
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::withTrashed()->findOrFail($id);

        // Check if active operator with same name exists
        $exists = Operator::where('operator_name', $operator->operator_name)
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            // Redirect to edit page if active operator with same name exists
            $operator->is_active = 0; // keep inactive
            $operator->restore();
            $operator->save();

            return redirect()->route('editOperator', base64_encode($operator->id))
                ->with('success', "Operator '{$operator->operator_name}' restored. Edit to add to active list.");
        }

        // No active operator with same name → direct restore & activate
        $operator->is_active = 1; // make active
        $operator->restore();
        $operator->save();

        return redirect()->route('AddOperator')
            ->with('success', "Operator '{$operator->operator_name}' restored successfully.");
    }
}
