<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Container\Attributes\Log;

class ProjectController extends Controller
{

    
    public function AddProject()
    {
        $codes = Customer::where('status', 1)   // फक्त active
                ->select('id', 'code', 'name')
                ->orderBy('id', 'desc')
                ->get();
        $customers = Customer::where('status', 1)->orderBy('name')->get();
        return view('Project.add', compact('customers','codes'));
    }
    public function storeProject(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'project_name'  => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'date'          => 'nullable|date',
        ]);

        // Auto-generate Project Code (acronym)
        $projectWords = explode(' ', trim($request->input('project_name')));
        $code = '';


        // Get Customer Code from selected customer
        $customer = Customer::find($request->customer_id);
        $customerCode = $customer ? $customer->code : null;

        // Prepare data for insertion
        $projectData = $validated;
        $projectData['customer_code'] = $customerCode; // set customer code

        
        // Create project
        Project::create($projectData);

        return redirect()->route('ViewProject')->with('success', 'Project added successfully.');
    }

    public function ViewProject()
    {
        $projects = Project::with('customer')
            ->orderBy('id', 'desc')
            ->get();

        return view('Project.view', compact('projects'));
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $project = Project::findOrFail($id);
 
        $customers = Customer::where('status',1)
                             ->orWhere('id', $project->customer_id)
                             ->orderBy('name')
                             ->get();
 
        return view('Project.add', compact('project','customers'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name'   => 'required|string|max:255',            
            // 'name'            => 'required|string|max:255',
            'customer_code'  => 'nullable|string|max:100',
            'quantity'       => 'required|integer|min:1',
            'date'           => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('ViewProject')->with('success', 'Project updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('ViewProject')->with('success', 'Branch deleted successfully.');
    }
}
