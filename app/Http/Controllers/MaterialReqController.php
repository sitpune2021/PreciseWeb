<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;
use App\Models\MaterialType;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // 1️⃣ Validate input
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

        $adminId = Auth::id();
        $userId  = Auth::id(); // Logged-in user

        // 2️⃣ Fetch work order & material
        $workOrder = WorkOrder::with(['project', 'customer'])->findOrFail($request->work_order_id);
        $material  = MaterialType::findOrFail($request->material);

        // 3️⃣ Calculate volume & weight
        $volume = ($request->dia > 0)
            ? pi() * pow($request->dia / 2, 2) * $request->height   // Cylinder
            : $request->length * $request->width * $request->height; // Block

        $weightPerPiece = ($volume * $material->material_gravity) / 1000000;
        $totalWeight    = round($weightPerPiece * $request->qty, 3);
        $materialCost   = round($weightPerPiece * $material->material_rate * $request->qty, 2);
        $totalCost      = $materialCost; // For now, can add machining later

        // 4️⃣ SR NO
        $lastSrNo = MaterialReq::where('admin_id', $adminId)->max('sr_no');
        $sr_no = $lastSrNo ? $lastSrNo + 1 : 1;

        // 5️⃣ Prepare data
        $data = [
            'sr_no'         => $sr_no,
            'admin_id'      => $adminId,
            'user_id'       => $userId,
            'work_order_id' => $workOrder->id,
            'work_order_no' => $workOrder->work_order_no ?? 'RMW_' . $workOrder->project_id . '_' . $workOrder->id,
            'customer_id'   => $workOrder->customer_id,
            'project_id'    => $workOrder->project_id,
            'part_no'       => $workOrder->part,
            'date'          => $request->date,
            'description'   => $request->description,
            'dia'           => $request->dia,
            'length'        => $request->length,
            'width'         => $request->width,
            'height'        => $request->height,
            'material'      => $request->material,
            'qty'           => $request->qty,
            'material_gravity' => $material->material_gravity,
            'material_rate'    => $material->material_rate,
            'weight'        => $totalWeight,
            'material_cost' => $materialCost,
            'total_cost'    => $totalCost,
        ];

        // 6️⃣ Create record & log
        try {
            $materialReq = MaterialReq::create($data);

            Log::info('Material Requirement created', [
                'material_req_id' => $materialReq->id,
                'user_id'         => $userId,
                'admin_id'        => $adminId,
                'work_order_id'   => $workOrder->id,
                'material_id'     => $material->id,
                'qty'             => $request->qty,
                'material_cost'   => $materialCost,
                'total_cost'      => $totalCost,
            ]);

            return redirect()->route('ViewMaterialReq')
                ->with('success', 'Material Requirement Added Successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create Material Requirement', [
                'error' => $e->getMessage(),
                'data'  => $data
            ]);

            return redirect()->back()->with('error', 'Failed to add Material Requirement. Please try again.');
        }
    }
    public function ViewMaterialReq()
{
    $materialReq = MaterialReq::with(['workOrder.customer', 'workOrder.project', 'materialType'])
        ->where('admin_id', Auth::id())
        ->orderBy('created_at')
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

        $materialReq = MaterialReq::where('admin_id', Auth::id())->findOrFail($id);

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

        // ✅ GET WORK ORDER & MATERIAL
        $workOrder = WorkOrder::with(['project', 'customer'])->findOrFail($request->work_order_id);
        $material = MaterialType::findOrFail($request->material);

        // ✅ CALCULATE VOLUME & WEIGHT
        $volume = ($request->dia > 0)
            ? pi() * pow(($request->dia / 2), 2) * $request->height
            : $request->length * $request->width * $request->height;

        $weight_per_piece = ($volume * $material->material_gravity) / 1000000;
        $weight = round($weight_per_piece * $request->qty, 3);
        $material_cost = round($weight_per_piece * $material->material_rate * $request->qty, 2);
        $total_cost = $material_cost;

        // ✅ UPDATE DATA
        $materialReq->update([
            'work_order_id'    => $workOrder->id,
            'work_order_no'    => $workOrder->work_order_no ?? $workOrder->id,
            'customer_id'      => $workOrder->customer_id,
            'project_id'       => $workOrder->project_id,
            'part_no'          => $workOrder->part,
            'date'             => $request->date,
            'description'      => $request->description,
            'dia'              => $request->dia,
            'length'           => $request->length,
            'width'            => $request->width,
            'height'           => $request->height,
            'material'         => $request->material,
            'qty'              => $request->qty,
            'material_gravity' => $material->material_gravity,
            'material_rate'    => $material->material_rate,
            'weight'           => $weight,
            'material_cost'    => $material_cost,
            'total_cost'       => $total_cost,
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
