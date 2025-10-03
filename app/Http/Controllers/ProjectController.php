<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Show Add Project Form
     */
    public function AddProject()
    {
        $adminId = Auth::id();

        $codes = Customer::where('status', 1)
            ->where('admin_id', $adminId) // only admin-specific customers
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get();

        return view('Project.add', compact('customers', 'codes'));
    }

    /**
     * Store Project
     */
    public function storeProject(Request $request)
    {
        $adminId = Auth::id();

        $validated = $request->validate([
            'customer_id'   => [
                'required',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('admin_id', $adminId)),
            ],
            'project_name'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')->where(fn($q) => $q->where('admin_id', $adminId)->where('customer_id', $request->customer_id)),
            ],
            'quantity'      => 'required|integer|min:1',
            'date'          => 'nullable|date',
        ]);

        $customer = Customer::where('id', $request->customer_id)
            ->where('admin_id', $adminId)
            ->first();

        $customer_code = $customer ? $customer->code : null;

        Project::create([
            'customer_id'   => $request->customer_id,
            'customer_code' => $customer_code,
            'project_name'  => $request->project_name,
            'quantity'      => $request->quantity,
            'date'          => $request->date ?: now(),
            'admin_id'      => $adminId,
        ]);

        return redirect()->route('ViewProject')->with('success', 'Project added successfully.');
    }

    /**
     * View Projects
     */
    public function ViewProject()
    {
        $adminId = Auth::id();

        $projects = Project::with('customer')
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('Project.view', compact('projects'));
    }

    /**
     * Edit Project
     */
    public function edit(string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $project = Project::where('admin_id', $adminId)->findOrFail($id);

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orWhere('id', $project->customer_id)
            ->orderBy('name')
            ->get();

        return view('Project.add', compact('project', 'customers'));
    }

    /**
     * Update Project
     */
    public function update(Request $request, string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $project = Project::where('admin_id', $adminId)->findOrFail($id);

        $validated = $request->validate([
            'customer_id'   => [
                'required',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('admin_id', $adminId)),
            ],
            'project_name'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('admin_id', $adminId)->where('customer_id', $request->customer_id)),
            ],
            'quantity'      => 'required|integer|min:1',
            'date'          => 'nullable|date',
        ]);

        $customer = Customer::where('id', $request->customer_id)
            ->where('admin_id', $adminId)
            ->first();

        $customer_code = $customer ? $customer->code : null;

        $project->update([
            'customer_id'   => $request->customer_id,
            'customer_code' => $customer_code,
            'project_name'  => $request->project_name,
            'quantity'      => $request->quantity,
            'date'          => $request->date ?: now(),
        ]);

        return redirect()->route('ViewProject')->with('success', 'Project updated successfully.');
    }

    /**
     * Delete Project
     */
    public function destroy(string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $project = Project::where('admin_id', $adminId)->findOrFail($id);
        $project->delete();

        return redirect()->route('ViewProject')->with('success', 'Project deleted successfully.');
    }

    /**
     * Export Sample Excel
     */
    public function exportSample()
    {
        $filePath = public_path('assets/excel/PRECISE_ENGINEERING.xlsx');

        if (file_exists($filePath)) {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="Sample_Project.xlsx"',
                'Content-Length' => filesize($filePath),
            ];

            return response()->make(file_get_contents($filePath), 200, $headers);
        }

        abort(404, 'File not found.');
    }

    /**
     * Import Projects via Excel/CSV
     */
    public function importProjects(Request $request)
    {
        $adminId = Auth::id();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        $rows = [];
        if ($ext == 'csv') {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
        }

        $duplicates = [];

        foreach ($rows as $key => $row) {
            if ($key === 0) continue;
            if (empty(array_filter($row))) continue;

            $createdDate  = trim($row[0] ?? '');
            $customerName = trim($row[1] ?? '');
            $customerCode = trim($row[2] ?? '');
            $projectName  = trim($row[3] ?? '');
            $quantity     = trim($row[4] ?? '');

            $customer = Customer::where('admin_id', $adminId)
                ->where(function ($q) use ($customerName, $customerCode) {
                    $q->where('code', $customerCode)
                        ->orWhere('name', $customerName);
                })
                ->first();

            if (!$customer) {
                if (!$customerCode) {
                    $nameWords = explode(' ', $customerName);
                    if (count($nameWords) == 1) {
                        $customerCode = strtoupper(substr($nameWords[0], 0, 3));
                    } elseif (count($nameWords) == 2) {
                        $customerCode = strtoupper(substr($nameWords[0], 0, 2) . substr($nameWords[1], 0, 1));
                    } else {
                        $customerCode = strtoupper(substr($nameWords[0], 0, 1) . substr($nameWords[1], 0, 1) . substr($nameWords[2], 0, 1));
                    }
                }

                $customer = Customer::create([
                    'name'     => $customerName,
                    'code'     => $customerCode,
                    'status'   => 1,
                    'admin_id' => $adminId,
                ]);
            } else {
                if (!$customer->code) {
                    $customer->code = $customerCode ?: strtoupper(substr($customerName, 0, 3));
                    $customer->save();
                }
                $customerCode = $customer->code;
            }

            $existing = Project::where('admin_id', $adminId)
                ->where('customer_id', $customer->id)
                ->where('project_name', $projectName)
                ->first();

            if ($existing) {
                $duplicates[] = "Duplicate: {$customerName} - {$projectName}";
                continue;
            }

            try {
                if (is_numeric($createdDate)) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($createdDate)->format('Y-m-d');
                } else {
                    $date = $createdDate
                        ? \Carbon\Carbon::parse($createdDate)->format('Y-m-d')
                        : now();
                }
            } catch (\Exception $e) {
                $date = now();
            }

            Project::create([
                'customer_id'   => $customer->id,
                'customer_code' => $customerCode,
                'project_name'  => $projectName,
                'quantity'      => (int)$quantity,
                'date'          => $date,
                'admin_id'      => $adminId,
            ]);
        }

        $message = !empty($duplicates)
            ? 'Projects imported with some duplicates: ' . implode(', ', $duplicates)
            : 'Projects imported successfully!';

        return redirect()->back()->with('success', $message);
    }
}
