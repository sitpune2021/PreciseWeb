<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Container\Attributes\Log;

class ProjectController extends Controller
{
    /**
     * Show the form to add a project.
     */
    public function AddProject()
    {

        $codes = Customer::select('id', 'code', 'name')->get();
        return view('Project.add', compact('codes'));
    }

    /**
     * Store a new project in the database.
     */
    public function storeProject(Request $request)
    {

                $validated = $request->validate([
                    'customer_id'    => 'required|exists:customers,id',
                    'name'           => 'required|string|max:255',
                    'description'    => 'required|string',
                    'qty'            => 'required|integer',
                    'StartDate'      => 'required|date',
                    'EndDate'        => 'required|date|after_or_equal:StartDate',
                ]);

        try {
                $codes = Customer::find($request->code);
                $project = Project::create($validated);

                // $project->update([
                //     'code' => $project->id . $codes->code
                // ]);

                return redirect()->route('ViewProject')->with('success', 'Project added successfully.');
        } catch (\Exception $e) {

            dd($e);
            
            return back()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }


    public function ViewProject()
    {
        $projects = Project::with('customer')->get();
        // dd($projects);
        return view('Project.view', compact('projects'));
    }

    /**
     * Show the edit form for a project.
     */

    public function edit(string $encryptedId)
    {
        try {
        $codes = Customer::select('id', 'code', 'name')->get();
            $id = base64_decode($encryptedId);
            $project = Project::findOrFail($id);
            return view('Project.add', compact('project','codes'));
        } catch (\Exception $e) {
            abort(404);
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

            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'qty'            => 'required|integer|',
            'StartDate'      => 'nullable|date',
            'EndDate'        => 'nullable|date|after_or_equal:StartDate',
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
