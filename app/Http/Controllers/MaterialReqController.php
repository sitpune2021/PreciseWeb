<?php

namespace App\Http\Controllers;

use App\Models\MaterialReq;
use App\Models\Customer;



use Illuminate\Http\Request;

class MaterialReqController extends Controller
{

    public function AddMaterialReq()
    {
        $codes = Customer::select('id', 'code', 'name')->orderBy('id', 'desc')->get();

        return view('MaterialReq.add', compact('codes'));
    }

    public function storeMaterialReq(Request $request)
    {
        // Validation
        $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'code'          => 'required|string|max:50|unique:material_reqs,code',
            'date'          => 'required|date',
            'description'   => 'required|string|max:255',
            'work_order_no' => 'required|string|max:50',
            'dia'           => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'length'        => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'width'         => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'height'        => ['required', 'regex:/^\d+(\.\d+)?$/'],
            'material'      => 'required|in:Steel,Aluminium,Copper',
            'qty'           => 'required|numeric|min:1',
            'weight'        => 'required|numeric|min:0',
            'lathe'         => 'required|numeric|min:0',
            'mg4'           => 'required|numeric|min:0',
            'mg2'           => 'required|numeric|min:0',
            'rg2'           => 'required|numeric|min:0',
            'sg4'           => 'required|numeric|min:0',
            'sg2'           => 'required|numeric|min:0',
            'vmc_hrs'       => 'required|numeric|min:0',
            'vmc_cost'      => 'required|numeric|min:0',
            'hrc'           => 'required|numeric|min:0',
            'edm_qty'       => 'required|numeric|min:0',
            'edm_rate'      => 'required|numeric|min:0',
            'cl'            => 'required|string|max:50',
            'total_cost'    => 'required|numeric|min:0',
        ]);

        // Store data
        MaterialReq::create($request->all());

        return redirect()->route('ViewMaterialReq')->with('success', 'Material Requirement Added Successfully!');
    }

    public function ViewMaterialReq()
    {
        $materialReq = MaterialReq::orderBy('id', 'desc')->get();

        return view('MaterialReq.view', compact('materialReq'));
    }

    public function editMaterialReq(string $encryptedId)
    {
        try {
            $id = base64_decode($encryptedId);
            $materialReq = MaterialReq::findOrFail($id);
            $codes = Customer::select('id', 'name', 'code')->get(); // Customer list for dropdown
            return view('MaterialReq.add', compact('materialReq', 'codes'));
        } catch (\Exception $e) {
            abort(404);
        }
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
        $materialReq->qty         = $request->qty;
        $materialReq->weight      = $request->weight;
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
}
