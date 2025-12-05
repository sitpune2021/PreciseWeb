<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Project;
use App\Models\MaterialType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function addWorkOrder()
    {
        $adminId = auth()->id();

        $codes = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $materialtype = MaterialType::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $projects = Project::where('admin_id', $adminId)
            ->select('id', 'project_name', 'customer_id', 'quantity')
            ->orderBy('project_name')
            ->get();

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.add', compact('codes', 'projects', 'workorders', 'materialtype'));
    }

    public function ViewWorkOrder()
    {
        $adminId = auth()->id();

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')  // Only ID desc → New items come on top
            ->get();

        return view('WorkOrder.view', compact('workorders'));
    }

    public function storeWorkEntry(Request $request)
    {
        $validatedData = $request->validate([
            'rows'                    => 'required|array|min:1',
            'rows.*.customer_id'      => 'required|exists:customers,id',
            'rows.*.part'             => 'required|string|max:100',
            'rows.*.project_id'       => 'required|string|max:250',
            'rows.*.date'             => 'required|date',
            'rows.*.part_description' => 'required|string|max:1000',
            'rows.*.dimeter'          => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.length'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.width'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.height'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.exp_time'         => 'nullable|string|max:50',
            'rows.*.quantity'         => 'required|integer|min:1',
            'rows.*.material'         => 'required|string|max:200',
        ]);

        $adminId = auth()->id();

        foreach ($validatedData['rows'] as $row) {
            WorkOrder::create([
                'customer_id'      => $row['customer_id'],
                'project_id'       => $row['project_id'],
                'part'             => $row['part'],
                'date'             => $row['date'],
                'dimeter'          => $row['dimeter'],
                'length'           => $row['length'],
                'width'            => $row['width'],
                'height'           => $row['height'],
                'exp_time'         => $row['exp_time'],
                'quantity'         => $row['quantity'],
                'part_description' => $row['part_description'],
                'material'         => $row['material'],
                'admin_id'         => $adminId,
                'status'           => 1,
            ]);
        }

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Orders added successfully!');
    }

    public function edit(string $encryptedId)
    {
        $adminId = auth()->id();
        $id = base64_decode($encryptedId);

        $workorder = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->findOrFail($id);

        $codes = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name')
            ->get();

        $projects = Project::where('admin_id', $adminId)
            ->select('id', 'project_name')
            ->get();

        $materialtype = MaterialType::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.add', compact('workorder', 'codes', 'projects', 'materialtype'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $adminId = auth()->id();
        $id = base64_decode($encryptedId);

        $validated = $request->validate([
            'part'             => 'required|string|max:100',
            'project_id'       => 'required|string|max:250',
            'date'             => 'required|date',
            'part_description' => 'required|string|max:1000',
            'dimeter'          => 'nullable|numeric',
            'length'           => 'nullable|numeric',
            'width'            => 'nullable|numeric',
            'height'           => 'nullable|numeric',
            'exp_time'         => 'nullable|string|max:50',
            'quantity'         => 'required|integer|min:1',
            'material'         => 'required|string|max:200',
        ]);

        $workOrder = WorkOrder::where('admin_id', $adminId)->findOrFail($id);
        $workOrder->update($validated);

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Entry updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $adminId = auth()->id();
        $id = base64_decode($encryptedId);

        $workOrder = WorkOrder::where('admin_id', $adminId)->findOrFail($id);
        $workOrder->delete();

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Order deleted successfully.');
    }

    public function getProjects($customerId)
    {
        $adminId = auth()->id();

        $projects = Project::where('customer_id', $customerId)
            ->where('admin_id', $adminId)
            ->select('id', 'project_name', 'quantity')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($projects);
    }

    public function getParts($projectId)
    {
        $adminId = auth()->id();

        $parts = WorkOrder::where('project_id', $projectId)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'asc')
            ->pluck('part');

        return response()->json($parts);
    }

    public function trash()
    {
        $adminId = auth()->id();

        $trashWorkOrders = WorkOrder::onlyTrashed()
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $workOrders = WorkOrder::where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.trash', compact('trashWorkOrders', 'workOrders'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $workOrder = WorkOrder::withTrashed()->findOrFail($id);

        $exists = WorkOrder::where('project_id', $workOrder->project_id)
            ->where('customer_id', $workOrder->customer_id)
            ->where('part', $workOrder->part)
            ->whereNull('deleted_at')
            ->exists();

        $workOrder->restore();

        if ($exists) {
            return redirect()->route('editWorkOrder', base64_encode($workOrder->id))
                ->with('success', "Work Order '{$workOrder->work_order_no}' already exists. Redirected to Edit Page.");
        }

        return redirect()->route('ViewWorkOrder')
            ->with('success', "Work Order '{$workOrder->work_order_no}' restored successfully.");
    }
}
