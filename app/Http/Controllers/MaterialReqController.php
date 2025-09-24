<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;
use App\Models\MaterialType;



use Illuminate\Http\Request;

class MaterialReqController extends Controller
{

    public function AddMaterialReq()
    {
        $codes = Customer::where('status', 1)  
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();
        $customers = Customer::where('status', 1)->orderBy('name')->get();
        $materialtype   = MaterialType::all();
        return view('MaterialReq.add', compact('codes', 'materialtype', 'customers'));
    }

    public function storeMaterialReq(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'code'          => 'required|string|max:50|unique:material_reqs,code',
            'date'          => 'required|date',
            'description'   => 'required|string|max:255',
            'work_order_no' => 'required|string|max:50',
            'dia'           => 'nullable|numeric|min:0',
            'length'        => 'nullable|numeric|min:0',
            'width'         => 'nullable|numeric|min:0',
            'height'        => 'required|numeric|min:0',
            'material'      => 'required|exists:material_types,id',
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

        // Get material details
        $material = MaterialType::findOrFail($request->material);

        // 1) Volume (mm³)
        $volume = $request->length * $request->width * $request->height;

        // 2) Weight per piece (Kg)
        $weight_per_piece = ($volume * $material->material_gravity) / 1000000;

        // 3) Total weight (Kg)
        $weight = $weight_per_piece * $request->qty;

        // 4) Material Cost
        $material_cost = $weight * $material->material_rate;

        // 5) EDM Cost
        $edm_cost = $request->edm_qty * $request->edm_rate;

        // 6) Machine Cost
        $machine_cost = $request->lathe
            + $request->mg4
            + $request->mg2
            + $request->rg2
            + $request->sg4
            + $request->sg2
            + $request->vmc_cost
            + $request->hrc;

        // 7) Final Total
        $total_cost = $material_cost + $machine_cost + $edm_cost;

        // 8) Round Excel-style
        $weight = round($weight, 3);              // 5.578
        $material_cost = round($material_cost, 2); // 2231.25
        $total_cost = round($total_cost, 2);       // 9513.44


        // ✅ Round like Excel
        $weight = round($weight, 3);        // 5.578
        $material_cost = round($material_cost, 2); // 2231.25
        $total_cost = round($total_cost, 2);       // 9513.44


        // Total Cost
        $total_cost = $material_cost
            + $request->lathe
            + $request->mg4
            + $request->mg2
            + $request->rg2
            + $request->sg4
            + $request->sg2
            + $request->vmc_cost
            + $request->hrc
            + $edm_cost;

        // Save data
        $data = $validated;
        $data['material_gravity'] = $material->material_gravity;
        $data['material_rate']    = $material->material_rate;
        $data['weight']           = $weight;
        $data['material_cost']    = $material_cost;
        $data['total_cost']       = $total_cost;

        MaterialReq::create($data);

        return redirect()->route('ViewMaterialReq')->with('success', 'Material Requirement Added Successfully!');
    }


    public function ViewMaterialReq()
    {
        $materialReq = MaterialReq::orderBy('id', 'desc')->get();
        $codes = Customer::select('id', 'code', 'name')->orderBy('id', 'desc')->get();
        return view('MaterialReq.view', compact('materialReq'));
    }

    public function editMaterialReq(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $materialReq = MaterialReq::findOrFail($id); // fetch the specific record
        $materialtype = MaterialType::all();
        $codes = Customer::select('id', 'name', 'code')->get(); // Customer list for dropdown
        return view('MaterialReq.add', compact('codes', 'materialtype', 'materialReq'));
    }


    // Update logic
    public function updateMaterialReq(Request $request, $id)
    {
        $id = base64_decode($id);

        $request->validate([
            'customer_id' => 'required',
            'code' => 'required',
            'date' => 'required|date',
            'work_order_no' => 'required',
            'description' => 'required',

        ]);

        $materialReq = MaterialReq::findOrFail($id);

        $materialReq->customer_id = $request->customer_id;
        $materialReq->code        = $request->code;
        $materialReq->date        = $request->date;
        $materialReq->work_order_no = $request->work_order_no;
        $materialReq->description = $request->description;
        $materialReq->dia         = $request->dia;
        $materialReq->length      = $request->length;
        $materialReq->width       = $request->width;
        $materialReq->height      = $request->height;
        $materialReq->material    = $request->material;
        $materialReq->material_rate = $request->material_rate;
        $materialReq->material_gravity = $request->material_gravity;
        $materialReq->qty         = $request->qty;
        $materialReq->weight      = $request->weight;
        $materialReq->material_cost = $request->material_cost;
        $materialReq->lathe       = $request->lathe;
        $materialReq->mg4         = $request->mg4;
        $materialReq->mg2         = $request->mg2;
        $materialReq->rg2         = $request->rg2;
        $materialReq->sg4         = $request->sg4;
        $materialReq->sg2         = $request->sg2;
        $materialReq->vmc_hrs     = $request->vmc_hrs;
        $materialReq->vmc_cost    = $request->vmc_cost;
        $materialReq->hrc         = $request->hrc;
        $materialReq->edm_qty     = $request->edm_qty;
        $materialReq->edm_rate    = $request->edm_rate;
        $materialReq->cl          = $request->cl;
        $materialReq->total_cost  = $request->total_cost;

        $materialReq->save();

        return redirect()->route('ViewMaterialReq')->with('success', 'Material Requirement updated successfully!');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $materialReq = MaterialReq::findOrFail($id);
        $materialReq->delete();
        return redirect()->route('ViewMaterialReq')->with('success', 'Branch deleted successfully.');
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
