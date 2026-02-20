<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentPlan;

class PaymentPlanController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->user_type, [1, 2])) {
            abort(403);
        }

        $plans = PaymentPlan::orderBy('id', 'desc')->get();
        return view('admin.payment.plans.index', compact('plans'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'days'  => 'required|integer|min:1',
            'gst'   => 'required|numeric|min:0|max:100',
            'short_text' => 'required|string|max:255'
        ]);
        PaymentPlan::create([
            'title'      => $request->title,
            'price'      => $request->price,
            'short_text' => $request->short_text,
            'days'       => $request->days,
            'gst'        => $request->gst,
            'is_active'  => $request->is_active ?? 1
        ]);
        return redirect()->route('admin.plans')
            ->with('success', 'Plan Created Successfully');
    }
    public function edit($id)
    {
        $plans = PaymentPlan::orderBy('id', 'desc')->get();
        $plan  = PaymentPlan::findOrFail($id);

        return view('admin.payment.plans.index', compact('plans', 'plan'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'short_text' => 'nullable|string|max:255',
            'days'  => 'required|integer|min:1',
            'gst'   => 'required|numeric|min:0|max:100'
        ]);

        $plan = PaymentPlan::findOrFail($id);

        $plan->update([
            'title'     => $request->title,
            'price'     => $request->price,
            'short_text' => $request->short_text,
            'days'      => $request->days,
            'gst'       => $request->gst,
            'is_active' => $request->is_active ?? 0
        ]);

        return redirect()->route('admin.plans')
            ->with('success', 'Plan Updated Successfully');
    }
    public function destroy($id)
    {
        PaymentPlan::findOrFail($id)->delete();

        return redirect()->route('admin.plans')
            ->with('success', 'Plan Deleted Successfully');
    }
    public function toggleStatus(Request $request)
    {
        $plan = PaymentPlan::findOrFail($request->id);

        $plan->is_active = $request->has('status') ? 1 : 0;
        $plan->save();

        return redirect()->back()->with('success', 'Plan status updated successfully');
    }
}
