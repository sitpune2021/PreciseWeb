<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialTypeController extends Controller
{
    public function AddMaterialType()
    {
        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Materialtype.add', compact('materialtypes'));
    }

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
            'admin_id'         => Auth::id(),
        ]);

        return redirect()->route('AddMaterialType')->with('success', 'Material Type added successfully');
    }

    public function editMaterialType(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $materialtype = MaterialType::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
        return view('Materialtype.add', compact('materialtypes', 'materialtype'));
    }

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
                    ->ignore($materialtype->id),
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
            'is_active'        => 1,
        ]);

        return redirect()->route('AddMaterialType')
            ->with('success', 'Material Type updated successfully.');
    }

    public function updateMaterialStatus(Request $request)
    {

        $MaterialType = MaterialType::where('id', $request->id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $MaterialType->status = $request->input('status', 0);
        $MaterialType->save();


        return back()->with('success', 'Status updated!');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $MaterialType = MaterialType::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $MaterialType->is_active = 0;
        $MaterialType->save();
        $MaterialType->delete();

        return redirect()->route('AddMaterialType')
            ->with('success', 'Material Type deleted successfully.');
    }

    public function trash()
    {

        $trashedmaterialtypes = MaterialType::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $materialtypes = MaterialType::where('is_active', 1)
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('Materialtype.trash', compact('trashedmaterialtypes', 'materialtypes'));
    }

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

            $MaterialType->restore();
            $MaterialType->is_active = 0;
            $MaterialType->touch();
            $MaterialType->save();

            return redirect()->route('editMaterialType', base64_encode($MaterialType->id))
                ->with('success', "Material Type '{$MaterialType->material_type}' already exists. Redirected to Edit Page.");
        }

        $MaterialType->restore();
        $MaterialType->is_active = 1;
        $MaterialType->touch();
        $MaterialType->save();

        return redirect()->route('AddMaterialType')
            ->with('success', "Material Type '{$MaterialType->material_type}' restored successfully.");
    }
}
