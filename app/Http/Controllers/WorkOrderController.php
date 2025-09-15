<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function addWorkOrder()
{
    // Active customers only
    $codes = Customer::where('status',1)->select('id', 'code', 'name')->orderBy('name')->get();
    $projects = Project::select('id', 'project_name')->orderBy('project_name')->get();
    $workorders = WorkOrder::with(['customer', 'project'])->orderBy('id', 'desc')->get();
 
    return view('WorkOrder.add', compact('codes', 'projects', 'workorders'));
}

    public function ViewWorkOrder()
    {
        $workorders = WorkOrder::with(['customer', 'project'])
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.view', compact('workorders'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function storeWorkEntry(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'rows'                     => 'required|array|min:1',
            'rows.*.customer_id'       => 'required|exists:customers,id',
            'rows.*.part'              => 'required|string|max:100',
            'rows.*.project_id' => 'required|string|max:250',
            'rows.*.date'              => 'required|date',
            'rows.*.part_description' => 'required|string|max:1000',
            'rows.*.dimeter'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.length'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.width'             => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.height'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.exp_time' => 'nullable|string|max:50',
            'rows.*.quantity'    => 'required|integer|min:1',
        ]);

        foreach ($validatedData['rows'] as $row) {
            WorkOrder::create([
                'customer_id'      => $row['customer_id'],
                'project_id'     => $row['project_id'],
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


        $workorders = WorkOrder::with('customer')->orderBy('id', 'desc')->get();
        return view('WorkOrder.view', compact('workorders'));
    }

    /**
     * Display the specified resource.
     */
     public function edit(string $encryptedId, Request $request)
{
    $id = base64_decode($encryptedId);
    $workorder = WorkOrder::with(['customer', 'project'])->findOrFail($id);
 
    $codes = Customer::where('status', 1)->select('id', 'code', 'name')->get(); // Active only
    $projects = Project::select('id', 'project_name')->get();
 
    $workorders = WorkOrder::with('customer')
        ->where('customer_id', $workorder->customer_id)
        ->where('date', $workorder->date)
        ->orderBy('id', 'desc')
        ->get();
 
    return view('WorkOrder.add', compact('workorder', 'id', 'codes', 'workorders', 'projects'));
}


    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $request->validate([
            'part'               => 'required|string|max:100',
            'project_id'       => 'required|string|max:250',
            'date'               => 'required|date',
            'part_description'   => 'required|string|max:1000',
            'dimeter'            => 'nullable|numeric',
            'length'             => 'nullable|numeric',
            'width'              => 'nullable|numeric',
            'height'             => 'nullable|numeric',
            'exp_time'           => 'nullable|string|max:50',
            'quantity'           => 'required|integer|min:1',
        ]);

        $workOrder = WorkOrder::findOrFail($id);

        $workOrder->part              = $request->part;
        $workOrder->project_id      = $request->project_id;
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



    public function getProjects($customerId)
    {
        $projects = Project::where('customer_id', $customerId)
            ->get(['id', 'project_name','quantity']);
        return response()->json($projects);
    }
    
    public function getParts($projectId)
    {
        $parts = WorkOrder::where('project_id', $projectId)
            ->orderBy('id', 'asc')
            ->pluck('part');

        return response()->json($parts);
    }




}
