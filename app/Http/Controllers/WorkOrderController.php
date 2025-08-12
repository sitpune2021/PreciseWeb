<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Customer;


use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddWorkOrder()
    {
        $codes = Customer::select('id', 'code', 'name')->get();
        return view('WorkOrder.add', compact('codes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ViewWorkOrder()
    {
        $workorders = WorkOrder::with('customer')->get();
        return view('WorkOrder.view', compact('workorders'));
    }


    /**
     * Store a newly created resource in storage.
     */
    private function validateWorkOrder(Request $request)
    {
        return $request->validate([
            'work_order_no'     => 'required|string|max:100',
            'customer_id'       => '|exists:customers,id',
            'part'              => 'required|string|max:100',
            'date'              => 'required|date',
            'part_description'  => 'required|string|max:1000',
            'diameter'           => 'required|string|max:50',
            'length'            => 'required|string|max:50',
            'width'             => 'required|string|max:50',
            'height'            => 'required|string|max:50',
            'exp_time'          => 'required|date_format:H:i',
            'quantity'          => 'required|integer|min:1',
        ]);
    }

    public function storeWorkEntry(Request $request)
    {

        $validated = $this->validateWorkOrder($request);

        // Create work order
        $work_order = WorkOrder::create($validated);

        // Load the customer relation
        $work_order->load('customer');

        // Generate and update the part code
        $work_order->update([
            'part_code' => $work_order->id . $work_order->customer->code . $work_order->part,
        ]);

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $workorder = WorkOrder::findOrFail($id);
            $codes = Customer::select('id', 'code', 'name')->get();
            return view('WorkOrder.add', compact('workorder', 'codes'));
        } catch (\Exception $wo) {
            abort(404);
        }
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'work_order_no'      => 'required|string|max:100',
            'customer_id'       => 'required|exists:customers,id',
            'part'               => 'required|string|max:100',
            'date'               => 'required|date',
            'part_description'   => 'required|string|max:1000',
            'diameter'            => 'required|string|max:50',
            'length'             => 'required|string|max:50',
            'width'              => 'required|string|max:50',
            'height'             => 'required|string|max:50',
            'exp_time'           => 'required|date_format:H:i',
            'quantity'           => 'required|integer|min:1',
        ]);

        $workOrder = WorkOrder::findOrFail($id);

        $workOrder->work_order_no     = $request->work_order_no;
        // $workOrder->entry_code        = $request->entry_code;
        $workOrder->customer_id       = $request->customer_id;
        $workOrder->part              = $request->part;
        $workOrder->date              = $request->date;
        $workOrder->part_description  = $request->part_description;
        $workOrder->diameter           = $request->diameter;
        $workOrder->length            = $request->length;
        $workOrder->width             = $request->width;
        $workOrder->height            = $request->height;
        $workOrder->exp_time          = $request->exp_time;
        $workOrder->quantity          = $request->quantity;

        $workOrder->save();

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();
        return redirect()->route('ViewWorkOrder')->with('success', 'Branch deleted successfully.');
    }
}
