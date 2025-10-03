<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialTypeController extends Controller
{
    // Show form + list
    public function AddMaterialType()
    {

        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())           //admin_id
            ->latest()
            ->get();

        return view('Materialtype.add', compact('materialtypes'));
    }

    // Store new material type
    public function storeMaterialType(Request $request)
    {
        $request->validate([
            'material_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('material_types', 'material_type')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    }),
            ],
            'material_rate' => [
                'required',
                'numeric',
                'min:0',
            ],
            'material_gravity' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        MaterialType::create([
            'material_type'    => $request->material_type,
            'material_rate'    => $request->material_rate,
            'material_gravity' => $request->material_gravity,
            'is_active'        => 1,
            'admin_id'         => Auth::id(),   // ✅ logged-in admin
        ]);

        return redirect()->route('AddMaterialType')->with('success', 'Material Type added successfully');
    }



    // Edit form
    public function editMaterialType(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $materialtype = MaterialType::where('id', $id)
            ->where('admin_id', Auth::id())                 //admin_id
            ->firstOrFail();

        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())                 //admin_id
            ->orderBy('id', 'desc')
            ->get();
        return view('Materialtype.add', compact('materialtypes', 'materialtype'));
    }

    // Update record
    public function updateMaterialType(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $materialtype = MaterialType::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'material_type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('material_types', 'material_type')
                    ->where(function ($query) {
                        $query->where('admin_id', Auth::id())
                            ->whereNull('deleted_at')
                            ->where('is_active', 1);
                    })
                    ->ignore($materialtype->id),   // ✅ सध्याचा record ignore करा
            ],
            'material_rate' => [
                'required',
                'numeric',
                'min:0',
            ],
            'material_gravity' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $materialtype->update([
            'material_type'    => $request->material_type,
            'material_rate'    => $request->material_rate,
            'material_gravity' => $request->material_gravity,
            'admin_id'         => Auth::id(),
        ]);

        return redirect()->route('AddMaterialType')
            ->with('success', 'Material Type updated successfully.');
    }



    public function updateMaterialStatus(Request $request)
    {

        $MaterialType = MaterialType::where('id', $request->id)
            ->where('admin_id', Auth::id())         //admin_id
            ->firstOrFail();

        $MaterialType->status = $request->input('status', 0);
        $MaterialType->save();

        return back()->with('success', 'Status updated!');
    }


    // Delete record
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $MaterialType = MaterialType::where('id', $id)
            ->where('admin_id', Auth::id())         //admin_id
            ->firstOrFail();

        $MaterialType->is_active = 0;
        $MaterialType->save();
        $MaterialType->delete();

        return redirect()->route('AddMaterialType')
            ->with('success', 'Material Type deleted successfully.');
    }


    public function trash()
    {
        // Get soft deleted MaterialTypes
        $trashedmaterialtypes = MaterialType::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        // Get active MaterialTypes
        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc') // newest top
            ->get();

        return view('Materialtype.trash', compact('trashedmaterialtypes', 'materialtypes'));
    }

    // Restore MaterialType
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $MaterialType = MaterialType::withTrashed()
            ->where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $exists = MaterialType::where('material_type', $MaterialType->material_type)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->where('is_active', 1)
            ->exists();

        if ($exists) {
            $MaterialType->is_active = 1;
            $MaterialType->restore();
            $MaterialType->save();

            return redirect()->route('editMaterialType', base64_encode($MaterialType->id))
                ->with('success', "Material Type '{$MaterialType->material_type}' already exists. Redirected to Edit Page.");
        }

        $MaterialType->is_active = 1;
        $MaterialType->restore();
        $MaterialType->save();

        return redirect()->route('AddMaterialType')
            ->with('success', "Material Type '{$MaterialType->material_type}' restored successfully.");
    }
}
