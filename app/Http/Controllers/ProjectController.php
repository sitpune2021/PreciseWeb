<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Container\Attributes\Log;

class ProjectController extends Controller
{
    /**
     * Show the form to add a project.
     */
    public function AddProject()
    {
        $codes = Customer::select('id', 'code', 'name')->orderBy('id', 'desc')->get();

        return view('Project.add', compact('codes'));
    }


    /**
     * Store a new project in the database.
     */
    public function storeProject(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'project_name'  => 'required|string|max:255',
            'project_code'  => 'nullable|string|max:100|unique:projects,project_code',
            'quantity'      => 'required|integer|min:1',
            'date'          => 'nullable|date',
        ]);

        // Auto-generate Project Code (acronym)
        $projectWords = explode(' ', trim($request->input('project_name')));
        $code = '';

        if (count($projectWords) == 1) {
            $code = strtoupper(substr($projectWords[0], 0, 3));
        } elseif (count($projectWords) == 2) {
            $code = strtoupper(substr($projectWords[0], 0, 2) . substr($projectWords[1], 0, 1));
        } else {
            $code = strtoupper(substr($projectWords[0], 0, 1) . substr($projectWords[1], 0, 1) . substr($projectWords[2], 0, 1));
        }

        // Get Customer Code from selected customer
        $customer = Customer::find($request->customer_id);
        $customerCode = $customer ? $customer->code : null;

        // Prepare data for insertion
        $projectData = $validated;
        $projectData['project_code'] = $code;         // set auto project code
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

    /**
     * Show the edit form for a project.
     */

    public function edit(string $encryptedId)
    { {
            $codes = Customer::select('id', 'code', 'name')->get();
            $id = base64_decode($encryptedId);
            $project = Project::findOrFail($id);
            return view('Project.add', compact('project', 'codes'));
        }
    }

    /**
     * Update an existing project.
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'project_name'   => 'required|string|max:255',
            'project_code'   => 'nullable|string|max:100',
            // 'name'            => 'required|string|max:255',
            'customer_code'  => 'nullable|string|max:100',
            'quantity'       => 'required|integer|min:1',
            'date'           => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('ViewProject')->with('success', 'Project updated successfully.');
    }

    /**
     * Delete a project (you can complete this method as needed).
     */
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('ViewProject')->with('success', 'Branch deleted successfully.');
    }
}
