<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function AddOperator()
    {
        $operators = Operator::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Operator.add', compact('operators'));
    }

    public function storeOperator(Request $request)
    {
        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
            'phone_no' => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('operators', 'phone_no')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
        ]);

        Operator::create([
            'operator_name' => $request->operator_name,
            'phone_no'      => $request->phone_no,
            'is_active'     => 1,
            'admin_id'      => Auth::id(),
        ]);

        return redirect()->route('AddOperator')->with('success', 'Operator added successfully');
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $operators = Operator::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Operator.add', compact('operator', 'operators'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'operator_name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
                Rule::unique('operators', 'operator_name')
                    ->ignore($operator->id)
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
            'phone_no' => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('operators', 'phone_no')
                    ->ignore($operator->id)
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
        ]);
        $operator->update([
            'operator_name' => $request->operator_name,
            'phone_no'      => $request->phone_no,
            'is_active'     => 1,
            'admin_id'      => Auth::id(),
        ]);

        return redirect()->route('AddOperator')
            ->with('success', "Operator '{$operator->operator_name}' updated and activated successfully.");
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $operator->is_active = 0;
        $operator->save();
        $operator->delete();

        return redirect()->route('AddOperator')->with('success', 'Operator deleted successfully.');
    }

    public function updateOperatorStatus(Request $request)
    {
        $operator = Operator::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $operator->status = $request->has('status') ? 1 : 0;
        $operator->save();

        return back()->with('success', 'Status updated!');
    }

    public function trash()
    {
        $trashedOperators = Operator::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $Operators = Operator::where('admin_id', Auth::id())->get();

        return view('Operator.trash', compact('trashedOperators', 'Operators'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $operator = Operator::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $exists = Operator::where('operator_name', $operator->operator_name)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            $operator->is_active = 0;
            $operator->restore();
            $operator->touch();
            $operator->save();

            return redirect()->route('editOperator', base64_encode($operator->id))
                ->with('success', "Operator '{$operator->operator_name}' already exists. Redirected to Edit Page.");
        }

        $operator->is_active = 1;
        $operator->restore();
        $operator->touch();
        $operator->save();

        return redirect()->route('AddOperator')
            ->with('success', "Operator '{$operator->operator_name}' restored successfully.");
    }
}
