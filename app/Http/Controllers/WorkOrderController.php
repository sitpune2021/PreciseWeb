<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\MaterialOrder;
use App\Models\Project;
use App\Models\MaterialType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function addWorkOrder($id = null)
    {
        $adminId = Auth::id();

        $project = null; //  NEW
        $lastCustomer = null;

        if ($id) {
            $projectId = base64_decode($id);

            $project = Project::where('admin_id', $adminId)
                ->where('id', $projectId)
                ->first();

            if ($project) {
                $lastCustomer = $project->customer_id;
            }
        }

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
            ->select('id', 'project_no', 'project_name', 'customer_id', 'quantity')
            ->latest()
            ->get();

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $latestMaterialOrderNo = MaterialOrder::where('admin_id', $adminId)
            ->whereNull('deleted_at')
            ->latest('id')
            ->value('work_order_no');

        $highlightProjectId = MaterialOrder::where('admin_id', $adminId)
            ->whereNull('deleted_at')
            ->latest('id')
            ->value('project_id');

        //  project  last customer (old logic)
        if (!$lastCustomer) {
            $lastCustomer = WorkOrder::where('admin_id', $adminId)
                ->latest('id')
                ->value('customer_id');
        }

        return view('WorkOrder.add', compact(
            'codes',
            'projects',
            'workorders',
            'materialtype',
            'lastCustomer',
            'project',
            'highlightProjectId',
            'latestMaterialOrderNo'
        ));
    }

    public function ViewWorkOrder()
    {
        $adminId = Auth::id();

        $workorders = WorkOrder::with(['customer', 'project'])->where('admin_id', Auth::id())->latest()->get();

        return view('WorkOrder.view', compact('workorders'));
    }

    public function storeWorkEntry(Request $request)
    {
        $validatedData = $request->validate([
            'rows'                    => 'required|array|min:1',
            'rows.*.customer_id'      => 'required|exists:customers,id',
            'rows.*.part'             => 'required|string|max:100',
            'rows.*.project_id'       => 'required|exists:projects,id',
            'rows.*.date'             => 'required|date',
            'rows.*.part_description' => 'required|string|max:1000',
            'rows.*.dimeter'          => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.length'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.width'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.height'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.exp_time'         => 'nullable|string|max:50',
            'rows.*.quantity'         => 'required|integer|min:1',
            'rows.*.material_id' => 'required|exists:material_types,id',
        ]);

        $adminId = Auth::id();

        foreach ($validatedData['rows'] as $row) {


            WorkOrder::create([
                'customer_id'      => $row['customer_id'],
                'project_id'      => $row['project_id'],
                'part'             => $row['part'],
                'date'             => $row['date'],
                'dimeter'          => $row['dimeter'],
                'length'           => $row['length'],
                'width'            => $row['width'],
                'height'           => $row['height'],
                'exp_time'         => $row['exp_time'],
                'quantity'         => $row['quantity'],
                'part_description' => $row['part_description'],
                // 'material'         => $row['material'],
                'material_id'        => $row['material_id'],
                'admin_id'         => $adminId,
                'status'           => 1,
            ]);
        }

        return redirect()->route('AddWorkOrder')->with('success', 'Work Orders added successfully!');
    }

    public function edit(string $encryptedId)
    {
        $adminId = Auth::id();
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

        $lastCustomer = WorkOrder::where('admin_id', $adminId)
            ->latest()
            ->value('customer_id');

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $latestProjectId = MaterialOrder::where('admin_id', $adminId)
            ->whereNull('deleted_at')  // soft deleted ignore
            ->latest('id')
            ->value('work_order_no');

        $highlightProjectId = null;

        if (!empty($latestProjectId) && str_contains($latestProjectId, '_')) {
            $parts = explode('_', $latestProjectId);
            $highlightProjectId = isset($parts[1]) ? (int) $parts[1] : null; // cast to int
        }

        return view('WorkOrder.add', compact('workorder', 'codes', 'projects', 'materialtype', 'lastCustomer', 'workorders', 'highlightProjectId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $adminId = Auth::id();
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
            'material_id' => 'required|exists:material_types,id',
        ]);

        $workOrder = WorkOrder::where('admin_id', $adminId)->findOrFail($id);
        $workOrder->update($validated);

        return redirect()->route('AddWorkOrder')->with('success', 'Work Entry updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $workOrder = WorkOrder::where('admin_id', $adminId)->findOrFail($id);
        $workOrder->delete();

        return redirect()->route('AddWorkOrder')->with('success', 'Work Order deleted successfully.');
    }

    public function getProjects($customerId)
    {
        $adminId = Auth::id();

        $projects = Project::where('customer_id', $customerId)
            ->select('id', 'project_no', 'project_name', 'quantity')
            ->orderBy('id', 'desc') //  LATEST FIRST
            ->get();

        return response()->json($projects);
    }

    public function getParts($projectId)
    {
        $adminId = Auth::id();

        $parts = WorkOrder::where('project_id', $projectId)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'asc')
            ->pluck('part');

        return response()->json($parts);
    }

    // public function getNextPart($customerId, $projectId)
    // {
    //     $adminId = Auth::id();

    //     $lastPart = WorkOrder::where('customer_id', $customerId)
    //         ->where('project_id', $projectId)
    //         ->where('admin_id', $adminId)
    //         ->max('part');

    //     $nextPart = $lastPart ? $lastPart + 1 : 1;

    //     return response()->json([
    //         'next_part' => $nextPart
    //     ]);
    // }

    public function getNextPart($customerId, $projectId)
    {
        $adminId = Auth::id();

        $lastPart = WorkOrder::where('customer_id', $customerId)
            ->where('project_id', $projectId)
            ->where('admin_id', $adminId)
            ->orderByRaw('CAST(part as UNSIGNED) DESC')
            ->value('part');

        if (!$lastPart) {
            $nextPart = 1;
        } else {
            $nextPart = ((int)$lastPart % 10) + 1;
        }

        return response()->json([
            'next_part' => $nextPart
        ]);
    }

    public function getLastCustomer()
    {
        $adminId = Auth::id();

        $lastCustomer = WorkOrder::where('admin_id', $adminId)
            ->latest()
            ->value('customer_id');

        return response()->json(['customer_id' => $lastCustomer]);
    }

    public function getLastCustomerCode()
    {
        $lastCustomer = Customer::orderBy('id', 'desc')->first();

        if ($lastCustomer) {
            return response()->json([
                'code' => $lastCustomer->customer_code
            ]);
        }

        return response()->json([
            'code' => 'CUST001'
        ]);
    }

    public function trash()
    {
        $adminId = Auth::id();

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

        return redirect()->route('AddWorkOrder')
            ->with('success', "Work Order '{$workOrder->work_order_no}' restored successfully.");
    }
}
