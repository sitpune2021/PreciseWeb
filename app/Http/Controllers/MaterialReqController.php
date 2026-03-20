<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;
use App\Models\MaterialType;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialReqController extends Controller
{
    public function AddMaterialReq()
    {
        $adminId = Auth::id();
        $parts = WorkOrder::where('admin_id', $adminId)->get();
        $codes = Customer::where('status', 1)
            ->whereNotNull('admin_id')
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name', 'customer_srno')
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get();
        $materialtype = MaterialType::where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        return view('MaterialReq.add', compact('codes', 'materialtype', 'customers', 'parts'));
    }
    public function storeMaterialReq(Request $request)
    {

        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:255',
            'dia'           => 'nullable|numeric|min:0',
            'length'        => 'nullable|numeric|min:0',
            'width'         => 'nullable|numeric|min:0',
            'height'        => 'required|numeric|min:0',
            'material'      => 'required|exists:material_types,id',
            'qty'           => 'required|numeric|min:1',
        ]);

        // ✅ GET WORK ORDER FIRST
        $workOrder = WorkOrder::with(['project', 'customer'])
            ->findOrFail($request->work_order_id);

        $material = MaterialType::findOrFail($request->material);

        // ✅ Volume
        $volume = ($request->dia > 0)
            ? pi() * pow(($request->dia / 2), 2) * $request->height
            : $request->length * $request->width * $request->height;

        $weight_per_piece = ($volume * $material->material_gravity) / 1000000;
        $weight = round($weight_per_piece * $request->qty, 3);

        $material_cost = round($weight_per_piece * $material->material_rate * $request->qty, 2);

        $total_cost = $material_cost; // simplify (बाकी add करू शकतोस)

        // ✅ SR NO
        $lastSrNo = MaterialReq::where('admin_id', Auth::id())->max('sr_no');
        $sr_no = $lastSrNo ? $lastSrNo + 1 : 1;

        // ✅ FINAL DATA (IMPORTANT 🔥)
        $data = [
            'sr_no'            => $sr_no,
            'admin_id'         => Auth::id(),

            // 🔥 WorkOrder based
            'work_order_id'    => $workOrder->id,
            'customer_id'      => $workOrder->customer_id,
            'project_id'       => $workOrder->project_id,
            'part_no'          => $workOrder->part,
            'work_order_no'    => $workOrder->id, // or wo_no

            // form data
            'date'             => $request->date,
            'description'      => $request->description,
            'dia'              => $request->dia,
            'length'           => $request->length,
            'width'            => $request->width,
            'height'           => $request->height,
            'material'         => $request->material,
            'qty'              => $request->qty,

            // calculated
            'material_gravity' => $material->material_gravity,
            'material_rate'    => $material->material_rate,
            'weight'           => $weight,
            'material_cost'    => $material_cost,
            'total_cost'       => $total_cost,
        ];

        // ✅ SAVE AFTER ALL DATA READY
        MaterialReq::create($data);

        return redirect()->route('ViewMaterialReq')
            ->with('success', 'Material Requirement Added Successfully!');
    }
    public function ViewMaterialReq()
    {
        $materialReq = MaterialReq::with(['materialType', 'customer'])
            ->where('admin_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('MaterialReq.view', compact('materialReq'));
    }
    public function editMaterialReq(string $encryptedId)
    {
        $adminId = Auth::id();
        $id = base64_decode($encryptedId);

        $materialReq = MaterialReq::where('admin_id', $adminId)->findOrFail($id);

        $materialtype = MaterialType::where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $codes = Customer::select('id', 'name', 'code', 'customer_srno')
            ->where('admin_id', $adminId)
            ->get();

        // ✅ Add $parts
        $parts = WorkOrder::where('admin_id', $adminId)->get();

        return view('MaterialReq.add', compact('codes', 'materialtype', 'materialReq', 'parts'));
    }

    public function updateMaterialReq(Request $request, $id)
    {
        $id = base64_decode($id);

        $materialReq = MaterialReq::where('admin_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'work_order_id' => 'required',
            'date' => 'required|date',
            'description' => 'nullable',
        ]);

        $materialReq->update($request->all());

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
    public function getWorkOrdersByCustomer($Id)
    {
        $adminId = Auth::id();

        $workorders = WorkOrder::with(['customer', 'project'])
            ->where('admin_id', $adminId)
            ->where('customer_id', $Id)
            ->whereNull('deleted_at')
            ->get();
        // dd($workorders);
        return response()->json($workorders);
    }
}
