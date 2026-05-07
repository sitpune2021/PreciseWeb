<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;
use App\Models\MaterialOrder;
use App\Models\MaterialType;
use App\Models\Rate;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaterialReqController extends Controller
{
    public function AddMaterialReq()
    {
        $adminId = Auth::id();
        $parts = WorkOrder::with('project', 'customer')
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();
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

        $rates = Rate::where('admin_id', Auth::id())
            ->where('is_active', 1)
            ->pluck('rate', 'name');

        return view('MaterialReq.add', compact('codes', 'materialtype', 'customers', 'parts', 'rates'));
    }

    public function storeMaterialReq(Request $request)
    {
        Log::info('MaterialReq Store Request:', $request->all());

        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'date'          => 'required|date',

            'dia'    => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width'  => 'nullable|numeric|min:0',
            'height' => 'required|numeric|min:0',

            'material' => 'required|exists:material_types,id',
            'qty'      => 'required|numeric|min:1',

            'lathe' => 'nullable|numeric|min:0',
            'mg4'   => 'nullable|numeric|min:0',
            'mg2'   => 'nullable|numeric|min:0',
            'rg2'   => 'nullable|numeric|min:0',
            'sg4'   => 'nullable|numeric|min:0',
            'sg2'   => 'nullable|numeric|min:0',

            'vmc_cost' => 'nullable|numeric|min:0',
            'vmc_hrs'  => 'nullable|numeric|min:0',

            'edm_qty'  => 'nullable|numeric|min:0',
            'edm_rate' => 'nullable|numeric|min:0',
            'cl'       => 'nullable|numeric|min:0',

            'column1' => 'nullable|numeric|min:0',
            'column2' => 'nullable|numeric|min:0',

            'hrc' => 'nullable|numeric|min:0',
        ]);

        $workOrder = WorkOrder::with(['project', 'customer'])->findOrFail($request->work_order_id);
        $material  = MaterialType::findOrFail($request->material);

        // Volume
        $volume = ($request->dia > 0)
            ? pi() * pow($request->dia / 2, 2) * $request->height
            : $request->length * $request->width * $request->height;

        // Weight
        $weightPerPiece = round(($volume * $material->material_gravity) / 1000000, 3);

        // Material Cost
        $materialCost = round($weightPerPiece * $material->material_rate, 2);

        // Machining
        $len = $request->length ?? 0;
        $wid = $request->width ?? 0;
        $hei = $request->height ?? 0;

        $mg4 = ((($len * $hei) + ($wid * $hei)) * 2 * 0.5 / 100);
        $mg2 = (($len * $wid) * 2 * 0.5 / 100);
        $rg2 = (($len * $wid) * 2 * 0.3 / 100);
        $sg4 = ((($len * $hei) + ($wid * $hei)) * 2 * 0.6 / 100);
        $sg2 = (($len * $wid) * 2 * 0.6 / 100);

        // Other Calculations
        $vmc = $request->vmc_cost ?? 0;
        $edm = ($request->edm_qty ?? 0) * $request->height * 6;
        $wirecut = ($request->cl ?? 0) * 0.2 * $request->height;

        $hrc = $request->hrc ?? 0;

        $column1 = $request->column1 ?? 0;
        $column2 = $request->column2 ?? 0;

        // Total
        $totalPerPiece =
            ($request->lathe ?? 0) +
            $mg4 + $mg2 + $rg2 + $sg4 + $sg2 +
            $vmc + $edm + $wirecut + $hrc +
            $materialCost + $column1 + $column2;

        $totalCost = round($totalPerPiece * $request->qty, 2);

        // STORE
        MaterialReq::create([
            'admin_id' => Auth::id(),

            // AUTO DATA
            'customer_id'   => $workOrder->customer_id,
            'code'          => $workOrder->customer->code ?? null,
            'work_order_no' => $workOrder->work_order_no ?? $workOrder->id,
            'part_no'       => $workOrder->part ?? null,
            'project_id'    => $workOrder->project_id ?? null,
            'description'   => $request->description,

            // BASIC
            'work_order_id' => $request->work_order_id,
            'date'          => $request->date,

            // SIZE
            'dia'    => $request->dia,
            'length' => $request->length,
            'width'  => $request->width,
            'height' => $request->height,

            // MATERIAL
            'material'         => $request->material,
            'material_rate'    => $material->material_rate,
            'material_gravity' => $material->material_gravity,

            'qty'    => $request->qty,
            'weight' => $weightPerPiece * $request->qty,
            'material_cost' => $materialCost,

            // MACHINING
            'mg4' => $mg4,
            'mg2' => $mg2,
            'rg2' => $rg2,
            'sg4' => $sg4,
            'sg2' => $sg2,

            'lathe' => $request->lathe ?? 0,

            // VMC
            'vmc_cost' => $vmc,
            'vmc_hrs'  => $request->vmc_hrs ?? 0,

            // EDM + Wirecut
            'edm_qty'  => $request->edm_qty ?? 0,
            'edm_rate' => $request->edm_rate ?? 0,
            'edm_cost' => $edm,

            'cl' => $request->cl ?? 0,
            'wirecut_rate' => $wirecut,

            // HRC
            'hrc' => $hrc,

            // Extra
            'column1' => $column1,
            'column2' => $column2,

            'total_cost' => $totalCost,
        ]);

        return redirect()->route('ViewMaterialReq')
            ->with('success', 'Material Requirement Added Successfully!');
    }

    public function ViewMaterialReq()
    {
        $adminId = Auth::id();

        $materialReq = MaterialReq::with(['workOrder.customer', 'workOrder.project', 'materialType'])
            ->where('admin_id', Auth::id())
            ->orderBy('created_at')
            ->get();

        $highlightProjectId = MaterialOrder::where('admin_id', $adminId)
            ->latest('id')
            ->value('project_id');

        return view('MaterialReq.view', compact('materialReq', 'highlightProjectId'));
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

        // Add $parts
        $parts = WorkOrder::with('project', 'customer')
            ->where('admin_id', $adminId)
            ->get();
        $rates = Rate::where('admin_id', Auth::id())
            ->where('is_active', 1)
            ->pluck('rate', 'name');

        return view('MaterialReq.add', compact('codes', 'materialtype', 'materialReq', 'parts', 'rates'));
    }

    public function updateMaterialReq(Request $request, $id)
    {
        $id = base64_decode($id);

        $materialReq = MaterialReq::where('admin_id', Auth::id())
            ->findOrFail($id);

        // VALIDATION
        $validated = $request->validate([

            'work_order_id' => 'required|exists:work_orders,id',
            'date'          => 'required|date',

            'height'        => 'required|numeric|min:0',
            'qty'           => 'required|numeric|min:1',

            'material'      => 'required|exists:material_types,id',

            'weight'        => 'nullable|numeric|min:0',
            'material_cost' => 'nullable|numeric|min:0',
            'total_cost'    => 'nullable|numeric|min:0',

            'material_rate' => 'nullable|numeric|min:0',

            'mg4' => 'nullable|numeric|min:0',
            'mg2' => 'nullable|numeric|min:0',
            'rg2' => 'nullable|numeric|min:0',
            'sg4' => 'nullable|numeric|min:0',
            'sg2' => 'nullable|numeric|min:0',

            'hrc' => 'nullable|numeric|min:0',
        ]);

        // NULL HANDLE
        $request->merge([

            'dia' => $request->dia ?: 0,
            'length' => $request->length ?: 0,
            'width' => $request->width ?: 0,

            'lathe' => $request->lathe ?: 0,

            'vmc_hrs' => $request->vmc_hrs ?: 0,
            'vmc_cost' => $request->vmc_cost ?: 0,

            'edm_qty' => $request->edm_qty ?: 0,

            'cl' => $request->cl ?: 0,

            'column1' => $request->column1 ?: 0,
            'column2' => $request->column2 ?: 0,

            'hrc' => $request->hrc ?: 0,
        ]);

        $workOrder = WorkOrder::with(['project', 'customer'])
            ->findOrFail($request->work_order_id);

        $material = MaterialType::findOrFail($request->material);

        // DIMENSIONS
        $dia = (float) $request->dia;
        $len = (float) $request->length;
        $wid = (float) $request->width;
        $hei = (float) $request->height;

        // VOLUME
        if ($dia > 0) {

            // ROUND MATERIAL
            $volume = pi() * pow($dia / 2, 2) * $hei;
        } else {

            // BLOCK MATERIAL
            $volume = $len * $wid * $hei;
        }

        // WEIGHT
        $weightPerPiece =
            ($volume * $material->material_gravity) / 1000000;

        // MATERIAL COST
        $materialCost =
            $weightPerPiece * $request->material_rate;

        // AUTO MACHINING
        $mg4 = $request->mg4 != ''
            ? $request->mg4
            : (((($len * $hei) + ($wid * $hei)) * 2 * 0.5) / 100);

        $mg2 = $request->mg2 != ''
            ? $request->mg2
            : ((($len * $wid) * 2 * 0.5) / 100);

        $rg2 = $request->rg2 != ''
            ? $request->rg2
            : ((($len * $wid) * 2 * 0.3) / 100);

        $sg4 = $request->sg4 != ''
            ? $request->sg4
            : (((($len * $hei) + ($wid * $hei)) * 2 * 0.6) / 100);

        $sg2 = $request->sg2 != ''
            ? $request->sg2
            : ((($len * $wid) * 2 * 0.6) / 100);

        // VMC
        $vmc = (float) $request->vmc_cost;

        // EDM
        $edm =
            ((float)$request->edm_qty * $hei * 6);

        // WIRECUT
        $wirecut =
            ((float)$request->cl * 0.2 * $hei);

        // HRC
        $hrc = $request->hrc != ''
            ? $request->hrc
            : round(($weightPerPiece * 70), 2);

        // EXTRA
        $column1 = (float) $request->column1;
        $column2 = (float) $request->column2;

        // TOTAL
        $totalPerPiece =

            (float)$request->lathe +

            (float)$mg4 +
            (float)$mg2 +
            (float)$rg2 +
            (float)$sg4 +
            (float)$sg2 +

            (float)$vmc +
            (float)$edm +
            (float)$wirecut +
            (float)$hrc +

            (float)$materialCost +
            (float)$column1 +
            (float)$column2;

        $totalCost =
            $totalPerPiece * (float)$request->qty;

        // UPDATE
        $materialReq->update([

            'work_order_id' => $request->work_order_id,

            'date' => $request->date,

            'description' => $request->description,

            'dia' => $dia,
            'length' => $len,
            'width' => $wid,
            'height' => $hei,

            'material' => $request->material,

            'material_rate' => $request->material_rate,

            'material_gravity' => $material->material_gravity,

            'qty' => $request->qty,

            // CALCULATED
            'weight' => round($weightPerPiece * $request->qty, 3),

            'material_cost' => round($materialCost, 2),

            // MACHINING
            'lathe' => $request->lathe,

            'mg4' => round($mg4, 2),
            'mg2' => round($mg2, 2),
            'rg2' => round($rg2, 2),
            'sg4' => round($sg4, 2),
            'sg2' => round($sg2, 2),

            // VMC
            'vmc_hrs' => $request->vmc_hrs,
            'vmc_cost' => round($vmc, 2),

            // EDM
            'edm_qty' => $request->edm_qty,
            'edm_rate' => round($edm, 2),

            // WIRECUT
            'cl' => $request->cl,
            'wirecut_rate' => round($wirecut, 2),

            // HRC
            'hrc' => round($hrc, 2),

            // EXTRA
            'column1' => round($column1, 2),
            'column2' => round($column2, 2),

            // TOTAL
            'total_cost' => round($totalCost, 2),
        ]);

        return redirect()->route('ViewMaterialReq')
            ->with('success', 'Updated successfully');
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
