<?php
 
namespace App\Http\Controllers;
 
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
 
class MaterialTypeController extends Controller
{
    // Show form + list
    public function AddMaterialType()
    {
        $materialtypes = MaterialType::orderBy('id', 'desc')->get();
        return view('Materialtype.add', compact('materialtypes'));
    }
 
    // Store new material type
  public function storeMaterialType(Request $request)
{
    $request->validate([
        'material_type' => 'required|string|max:255',
        'material_rate' => 'required|numeric|min:0',
    ]);
 
    $exists = MaterialType::where('material_type', $request->material_type)
                ->where('status', 1)
                ->exists();

    if ($exists) {
        return back()->withErrors(['material_type' => 'The material type has already been taken.'])->withInput();
    }
 
    MaterialType::create([
        'material_type' => $request->material_type,
        'material_rate' => $request->material_rate,
        'status' => 1,
    ]);

    return redirect()->route('AddMaterialType')->with('success', 'Material Type added successfully');
}

 
    // Edit form
    public function editMaterialType(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $materialtype = MaterialType::findOrFail($id);
        $materialtypes = MaterialType::orderBy('id', 'desc')->get();
        return view('Materialtype.add', compact('materialtypes', 'materialtype'));
    }
 
    // Update record
    public function updateMaterialType(Request $request, string $encryptedId)
{
    $id = base64_decode($encryptedId);

    $request->validate([
        'material_type'  => 'required|string|max:255|unique:material_types,material_type,' . $id,
        'material_rate' => 'required|numeric|min:0',
    ]);

    try {
        $materialtype = MaterialType::findOrFail($id);
        $materialtype->material_type = $request->material_type;
        $materialtype->material_rate = $request->material_rate;
        $materialtype->save();

        return redirect()->route('AddMaterialType')
            ->with('success', 'Material Type updated successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong.');
    }
}

 
    // Delete record
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        try {
            $materialtype = MaterialType::findOrFail($id);
            $materialtype->delete();
 
            return redirect()->route('AddMaterialType')
                ->with('success', 'Material Type deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }
 
    public function trashMaterialType()
    {
        $materialtypes = MaterialType::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('Materialtype.trash', compact('materialtypes'));
    }
 
    public function restoreMaterialType($id)
    {
        $materialtype = MaterialType::onlyTrashed()->findOrFail(base64_decode($id));
        $materialtype->restore();
        return redirect()->route('AddMaterialType')->with('success', 'Material Type restored successfully!');
    }
}