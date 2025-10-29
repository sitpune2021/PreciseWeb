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
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
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
            'project_no'    => $projectNo,
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
            'code'          => 'nullable|string|max:255', // 👈 add this for textbox value
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
}
