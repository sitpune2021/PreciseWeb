<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialReqController extends Controller
{
    public function AddMaterialReq()
{
    $adminId = Auth::id(); // get current admin id

    $codes = Customer::where('status', 1)
        ->where('admin_id', $adminId)
        ->select('id', 'code', 'name')
        ->orderBy('id', 'desc')
        ->get();

    $customers = Customer::where('status', 1)
        ->where('admin_id', $adminId)
        ->orderBy('name')
        ->get();

    $materialtype = MaterialType::where('admin_id', $adminId)->get();

    return view('MaterialReq.add', compact('codes', 'materialtype', 'customers'));
}


 public function storeMaterialReq(Request $request)
{
    $validated = $request->validate([
        'customer_id'   => 'required|exists:customers,id',
        'code'          => 'required|string|max:50',
        'date'          => 'required|date',
        'description'   => 'required|string|max:255',
        'work_order_no' => 'required|string|max:50',
        'dia'           => 'nullable|numeric|min:0',
        'length'        => 'nullable|numeric|min:0',
        'width'         => 'nullable|numeric|min:0',
        'height'        => 'required|numeric|min:0',
        'material'      => 'required|exists:material_types,id', // ID comes from select
        'qty'           => 'required|numeric|min:1',
        'lathe'         => 'nullable|numeric|min:0',
        'mg4'           => 'nullable|numeric|min:0',
        'mg2'           => 'nullable|numeric|min:0',
        'rg2'           => 'nullable|numeric|min:0',
        'sg4'           => 'nullable|numeric|min:0',
        'sg2'           => 'nullable|numeric|min:0',
        'vmc_cost'      => 'nullable|numeric|min:0',
        'hrc'           => 'nullable|numeric|min:0',
        'edm_qty'       => 'nullable|numeric|min:0',
        'edm_rate'      => 'nullable|numeric|min:0',
        'cl'            => 'nullable|string|max:50',
    ]);

    // ✅ Fetch the material record by ID
    $material = MaterialType::findOrFail($request->material);

    // ✅ Volume calculation
    $volume = ($request->dia > 0)
        ? pi() * pow(($request->dia / 2), 2) * $request->height
        : $request->length * $request->width * $request->height;

    $weight_per_piece = ($volume * $material->material_gravity) / 1000000;
    $weight = round($weight_per_piece * $request->qty, 3);

    $material_cost = round(($weight_per_piece * $material->material_rate) * $request->qty, 2);
    $edm_cost = ($request->edm_qty * $request->edm_rate) * $request->qty;
    $machine_cost = (
        $request->lathe +
        $request->mg4 +
        $request->mg2 +
        $request->rg2 +
        $request->sg4 +
        $request->sg2 +
        $request->vmc_cost +
        $request->hrc
    ) * $request->qty;

    $total_cost = round($material_cost + $edm_cost + $machine_cost, 2);

    // ✅ Build data to save
    $data = $validated;
    $data['material'] = $material->material_type;  // <-- SAVE NAME, not ID
    $data['material_gravity'] = $material->material_gravity;
    $data['material_rate'] = $material->material_rate;
    $data['weight'] = $weight;
    $data['material_cost'] = $material_cost;
    $data['total_cost'] = $total_cost;
    $data['admin_id'] = Auth::id();

    // ✅ Save to database
    MaterialReq::create($data);

    return redirect()->route('ViewMaterialReq')
        ->with('success', 'Material Requirement Added Successfully!');
}


    public function ViewMaterialReq()
    {
        // Only show current user's records
        $materialReq = MaterialReq::where('admin_id', Auth::id())
            ->orderBy('updated_at', 'desc')->get();

        return view('MaterialReq.view', compact('materialReq'));
    }

    // public function ViewMaterialReq()
    // {
    //     $materialReq = MaterialReq::orderBy('updated_at', 'desc')->get();

    //     return view('MaterialReq.view', compact('materialReq'));
    // }

    public function editMaterialReq(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $materialReq = MaterialReq::where('admin_id', Auth::id())->findOrFail($id);

        $materialtype = MaterialType::all();
        $codes = Customer::select('id', 'name', 'code')->get();

        return view('MaterialReq.add', compact('codes', 'materialtype', 'materialReq'));
    }
public function updateMaterialReq(Request $request, $id)
{
    $id = base64_decode($id);
    $materialReq = MaterialReq::where('admin_id', Auth::id())->findOrFail($id);

    $request->validate([
        'customer_id' => 'required',
        'code' => 'required',
        'date' => 'required|date',
        'work_order_no' => 'required',
        'description' => 'required',
        'material' => 'required|exists:material_types,id', // validate ID
    ]);

    // ✅ Fetch the material record to get the name, rate, and gravity
    $material = MaterialType::findOrFail($request->material);

    // ✅ Calculate volume (optional, if you want to update weight & cost)
    $volume = ($request->dia > 0)
        ? pi() * pow($request->dia / 2, 2) * $request->height
        : $request->length * $request->width * $request->height;

    $weight_per_piece = ($volume * $material->material_gravity) / 1000000;
    $weight = round($weight_per_piece * $request->qty, 3);

    $material_cost = round(($weight_per_piece * $material->material_rate) * $request->qty, 2);

    $edm_cost = ($request->edm_qty * $request->edm_rate) * $request->qty;

    $machine_cost = (
        $request->lathe +
        $request->mg4 +
        $request->mg2 +
        $request->rg2 +
        $request->sg4 +
        $request->sg2 +
        $request->vmc_cost +
        $request->hrc
    ) * $request->qty;

    $total_cost = round($material_cost + $edm_cost + $machine_cost, 2);

    // ✅ Update the record with material name and calculated values
    $materialReq->update([
        'customer_id' => $request->customer_id,
        'code' => $request->code,
        'date' => $request->date,
        'work_order_no' => $request->work_order_no,
        'description' => $request->description,
        'dia' => $request->dia,
        'length' => $request->length,
        'width' => $request->width,
        'height' => $request->height,
        'material' => $material->material_type, // <-- save name
        'material_rate' => $material->material_rate,
        'material_gravity' => $material->material_gravity,
        'qty' => $request->qty,
        'weight' => $weight,
        'material_cost' => $material_cost,
        'lathe' => $request->lathe,
        'mg4' => $request->mg4,
        'mg2' => $request->mg2,
        'rg2' => $request->rg2,
        'sg4' => $request->sg4,
        'sg2' => $request->sg2,
        'vmc_hrs' => $request->vmc_hrs,
        'vmc_cost' => $request->vmc_cost,
        'hrc' => $request->hrc,
        'edm_qty' => $request->edm_qty,
        'edm_rate' => $request->edm_rate,
        'cl' => $request->cl,
        'total_cost' => $total_cost,
    ]);

    return redirect()->route('ViewMaterialReq')
        ->with('success', 'Material Requirement updated successfully!');
}


    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $materialReq = MaterialReq::where('admin_id', Auth::id())->findOrFail($id);
        $materialReq->delete();

        return redirect()->route('ViewMaterialReq')->with('success', 'Material Requirement deleted successfully.');
    }

    public function trash()
    {
        $trashedMaterialReq = MaterialReq::onlyTrashed()
            ->orderBy('id', 'desc')
            ->get();

        $materialReq = MaterialReq::all();
        return view('MaterialReq.trash', compact('trashedMaterialReq', 'materialReq'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $material = MaterialReq::withTrashed()->findOrFail($id);

        $exists = MaterialReq::where('code', $material->code)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            $material->restore();

            return redirect()->route('editMaterialReq', base64_encode($material->id))
                ->with('success', "Material Requirement '{$material->code}' already exists. Redirected to Edit Page.");
        }

        $material->restore();

        return redirect()->route('ViewMaterialReq')
            ->with('success', "Material Requirement '{$material->code}' restored successfully.");
    }

    public function getMaterial($id)
    {
        $material = MaterialType::findOrFail($id);

        return response()->json([
            'gravity' => $material->material_gravity,
            'rate'    => $material->material_rate,
        ]);
    }
}
