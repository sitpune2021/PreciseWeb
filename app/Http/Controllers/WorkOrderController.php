<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    // Show form to add work orders
    public function addWorkOrder()
    {
        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->select('id', 'code', 'name')
            ->orderBy('name')
            ->get();

        $projects = Project::where('admin_id', Auth::id()) // ✅ admin filter
            ->select('id', 'project_name')
            ->orderBy('project_name')
            ->get();

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.add', compact('codes', 'projects', 'workorders'));
    }

    // View all work orders
    public function ViewWorkOrder()
    {
        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->orderBy('id', 'desc')
            ->get();

        return view('WorkOrder.view', compact('workorders'));
    }

    // Store work order(s)
    public function storeWorkEntry(Request $request)
    {
        $validatedData = $request->validate([
            'rows'                     => 'required|array|min:1',
            'rows.*.customer_id'       => 'required|exists:customers,id',
            'rows.*.part'              => 'required|string|max:100',
            'rows.*.project_id'        => 'required|string|max:250',
            'rows.*.date'              => 'required|date',
            'rows.*.part_description'  => 'required|string|max:1000',
            'rows.*.dimeter'           => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.length'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.width'             => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.height'            => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'rows.*.exp_time'          => 'nullable|string|max:50',
            'rows.*.quantity'          => 'required|integer|min:1',
        ]);

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
                'admin_id'         => Auth::id(), // ✅ assign admin_id
            ]);
        }

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Orders added successfully!');
    }

    // Edit work order
    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $workorder = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->findOrFail($id);

        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->select('id', 'code', 'name')
            ->get();

        $projects = Project::where('admin_id', Auth::id()) // ✅ admin filter
            ->select('id', 'project_name')
            ->get();

        return view('WorkOrder.add', compact('workorder', 'codes', 'projects'));
    }

    // Update work order
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $validated = $request->validate([
            'part'               => 'required|string|max:100',
            'project_id'         => 'required|string|max:250',
            'date'               => 'required|date',
            'part_description'   => 'required|string|max:1000',
            'dimeter'            => 'nullable|numeric',
            'length'             => 'nullable|numeric',
            'width'              => 'nullable|numeric',
            'height'             => 'nullable|numeric',
            'exp_time'           => 'nullable|string|max:50',
            'quantity'           => 'required|integer|min:1',
        ]);

        $workOrder = WorkOrder::where('admin_id', Auth::id()) // ✅ admin filter
            ->findOrFail($id);

        $workOrder->update($validated);

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Entry updated successfully.');
    }

    // Delete work order
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $workOrder = WorkOrder::where('admin_id', Auth::id()) // ✅ admin filter
            ->findOrFail($id);

        $workOrder->delete();

        return redirect()->route('ViewWorkOrder')->with('success', 'Work Order deleted successfully.');
    }

    // Get projects by customer (AJAX)
    public function getProjects($customerId)
    {
        $projects = Project::where('customer_id', $customerId)
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->get(['id', 'project_name']);
        return response()->json($projects);
    }

    // Get previous parts by project (AJAX)
    public function getParts($projectId)
    {
        $parts = WorkOrder::where('project_id', $projectId)
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->orderBy('id', 'asc')
            ->pluck('part');

        return response()->json($parts);
    }

    // Export sample Excel
    public function exportSample()
    {
        $filePath = public_path('assets/excel/WORKORDER_ENGINEERING.xlsx');
        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="Sample_WorkOrder.xlsx"',
                'Content-Length' => filesize($filePath),
            ];
            return response()->make(file_get_contents($filePath), 200, $headers);
        }
        abort(404, 'File not found.');
    }

    // Import work orders
    public function importWorkOrder(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $file = $request->file('file');
        $ext  = $file->getClientOriginalExtension();

        $rows = [];
        if ($ext === 'csv') {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
        }

        foreach ($rows as $key => $row) {
            if ($key === 0) continue;
            if (empty(array_filter($row))) continue;

            $customerCode = trim($row[1] ?? '');
            $customer = Customer::where('code', $customerCode)
                ->where('admin_id', Auth::id())
                ->first();

            if (!$customer) {
                $customerCode = $customerCode ?: 'CUST' . strtoupper(substr(uniqid(), -4));
                $customer = Customer::create([
                    'name'     => $customerCode,
                    'code'     => $customerCode,
                    'status'   => 1,
                    'admin_id' => Auth::id(), // ✅ assign admin
                ]);
            }

            $date = is_numeric($row[3] ?? null)
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d')
                : ($row[3] ? Carbon::parse($row[3])->format('Y-m-d') : now()->format('Y-m-d'));

            WorkOrder::create([
                'project_id'       => $row[0] ?? null,
                'customer_id'      => $customer->id,
                'part'             => $row[2] ?? null,
                'date'             => $date,
                'part_description' => $row[4] ?? '-',
                'dimeter'          => (float)($row[5] ?? 0),
                'length'           => (float)($row[6] ?? 0),
                'width'            => (float)($row[7] ?? 0),
                'height'           => (float)($row[8] ?? 0),
                'exp_time'         => $row[9] ?? '-',
                'quantity'         => (int)($row[10] ?? 0),
                'admin_id'         => Auth::id(), // ✅ assign admin
            ]);
        }

        return redirect()->back()->with('success', 'Work Orders imported successfully!');
    }

    // Show trash
    public function trash()
    {
        $trashedWorkOrders = WorkOrder::onlyTrashed()
            ->where('admin_id', Auth::id()) // ✅ admin filter
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('WorkOrder.trash', compact('trashedWorkOrders'));
    }
}
