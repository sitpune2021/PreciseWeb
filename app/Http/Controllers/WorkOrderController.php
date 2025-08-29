<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Customer;
use Carbon\Carbon;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddWorkOrder()
    {

        $codes = Customer::select('id', 'code', 'name')->orderBy('id', 'desc')->get();
        $workorders = WorkOrder::with('customer')->orderBy('created_at', 'desc')->get();

        return view('WorkOrder.add', compact('codes', 'workorders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ViewWorkOrder()
    {
        $workorders = WorkOrder::with('customer')->orderBy('id', 'desc')->get();
        return view('WorkOrder.view', compact('workorders'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeWorkEntry(Request $request)
    {
        Log::info('Incoming Work Entry Request:', $request->all());

        // Validate first
        $validatedData = $request->validate([
            'rows'               => 'required|array|min:1',
            'rows.*.customer_id' => 'required|exists:customers,id',
            'rows.*.part'        => 'required|string|max:100',
            'rows.*.date'        => 'required|date',
            'rows.*.part_description' => 'required|string|max:1000',
            'rows.*.dimeter'     => 'required|numeric',
            'rows.*.length'      => 'required|numeric',
            'rows.*.width'       => 'required|numeric',
            'rows.*.height'      => 'required|numeric',
            'rows.*.exp_time'    => 'date_format:H:i',
            'rows.*.quantity'    => 'required|integer|min:1',
        ]);

        Log::info('Validated Data:', $validatedData);
        // Save each row
        foreach ($validatedData['rows'] as $row) {
           $workorders= WorkOrder::create([
                'customer_id'      => $row['customer_id'],
                'part'             => $row['part'],
                'date'             => $row['date'],
                'dimeter'          => $row['dimeter'],
                'length'           => $row['length'],
                'width'            => $row['width'],
                'height'           => $row['height'],
                'exp_time'         => $row['exp_time'],
                'quantity'         => $row['quantity'],
                'part_description' => $row['part_description'],
            ]);
        }

        Log::info('Total Work Orders in DB: ' . $workorders->count());

        $workorders = WorkOrder::with('customer')->get();
        return view('WorkOrder.view', compact('workorders'));
    }



    /**
     * Display the specified resource.
     */
    public function edit(string $encryptedId, Request $request)
    {
        // dd( $request->all());
        try {
            $id = base64_decode($encryptedId);
            $workorder = WorkOrder::with('customer')->findOrFail($id);
            $codes = Customer::select('id', 'code', 'name')->get();


            $workorders = WorkOrder::with('customer')
                ->where('customer_id', $workorder->customer_id)
                ->where('date', $workorder->date)
                ->get();

            // dd( $workorders );
            return view('WorkOrder.add', compact('workorder', 'id', 'codes', 'workorders'));
        } catch (\Exception $wo) {
            abort(404);
        }
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);



        $request->validate([

            'part'               => 'required|string|max:100',
            'date'               => 'required|date',
            'part_description'   => 'required|string|max:1000',
            'dimeter'            => 'required|numeric',
            'length'             => 'required|numeric',
            'width'              => 'required|numeric',
            'height'             => 'required|numeric',
            'exp_time' => ['required', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'],
            'quantity'           => 'required|integer|min:1',
        ]);

        $workOrder = WorkOrder::findOrFail($id);


        // $workOrder->entry_code        = $request->entry_code;

        $workOrder->part              = $request->part;
        $workOrder->date              = $request->date;
        $workOrder->part_description  = $request->part_description;
        $workOrder->dimeter           = $request->dimeter;
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
