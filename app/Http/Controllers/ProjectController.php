<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use App\Models\MaterialOrder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function AddProject()
    {
        $adminId = Auth::id();

        // Customer Codes
        $codes = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        // Customers List
        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        // Projects (Latest First - by project_no )
        $projects = Project::with('customer')
            ->where('admin_id', $adminId)
            ->orderBy('project_no', 'desc') //  important change
            ->get();

        $latestMaterialOrderNo = MaterialOrder::where('admin_id', $adminId)
            ->whereNull('deleted_at')
            ->latest('id')
            ->value('work_order_no');

        $highlightProjectId = null;
        $highlightProjectPrefix = null;

        if (!empty($latestMaterialOrderNo) && str_contains($latestMaterialOrderNo, '_')) {
            $parts = explode('_', $latestMaterialOrderNo);
            $highlightProjectPrefix = $parts[0] ?? null; // SHM
            $highlightProjectId = isset($parts[1]) ? (int) $parts[1] : null; // 2
        }

        // Next Project Number (Safe)
        $maxProjectNo = Project::where('admin_id', $adminId)->max('project_no');
        $nextProjectNo = $maxProjectNo ? $maxProjectNo + 1 : 1;

        return view('Project.add', compact('customers', 'codes', 'projects', 'nextProjectNo', 'highlightProjectId','highlightProjectPrefix'));
    }
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

        $count = Project::where('admin_id', $adminId)->count();
        $projectNo = $count + 1;

        // dd($projectNo);
        Project::create([
            'customer_id'   => $request->customer_id,
            'customer_code' => $customer_code,
            'project_name'  => $request->project_name,
            'quantity'      => $request->quantity,
            'date'          => $request->date ?: now(),
            'admin_id'      => $adminId,
            // 'project_no'    => $projectNo,
            'project_no'    => $request->project_no,
        ]);

        return redirect()->route('AddProject')->with('success', 'Project added successfully.');
    }
    public function ViewProject()
    {
        $adminId = Auth::id();

        $projects = Project::with('customer')->where('admin_id', Auth::id())->latest()->get();

        return view('Project.view', compact('projects'));
    }
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

        $projects = Project::with('customer')
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        // hightlight entry order
        $latestProjectId = MaterialOrder::where('admin_id', $adminId)
            ->latest('id')
            ->value('work_order_no');

        $highlightProjectId = null;

        if ($latestProjectId) {
            $parts = explode('_', $latestProjectId);
            $highlightProjectId = $parts[1] ?? null; // project_id
        }

        $nextProjectNo = Project::where('admin_id', $adminId)->max('project_no') + 1;

        return view('Project.add', compact('project', 'customers', 'projects', 'nextProjectNo', 'highlightProjectId'));
    }
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
            'code'          => 'nullable|string|max:255',
        ]);

        $customer_code = $request->filled('code')
            ? $request->code
            : (Customer::where('id', $request->customer_id)
                ->where('admin_id', $adminId)
                ->value('code'));

        $project->update([
            'customer_id'   => $request->customer_id,
            'customer_code' => $customer_code,
            'project_name'  => $request->project_name,
            'quantity'      => $request->quantity,
            'date'          => $request->date ?: now(),
        ]);

        return redirect()->route('AddProject')->with('success', 'Project updated successfully.');
    }
    public function destroy(string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $project = Project::where('admin_id', $adminId)->findOrFail($id);
        $project->delete();

        return redirect()->route('AddProject')->with('success', 'Project deleted successfully.');
    }
}
