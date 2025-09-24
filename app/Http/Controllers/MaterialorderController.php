<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialOrder;
use App\Models\Customer;
class MaterialorderController extends Controller
{
  
    public function AddMaterialorder()
    {
         $codes = Customer::where('status', 1)  
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();
        $customers = Customer::where('status', 1)->orderBy('name')->get();
        return view('Materialorder.add',compact('codes','customers'));
    }

  
   public function ViewMaterialorder()
    {
        $orders = MaterialOrder::latest()->paginate(10);
        return view('Materialorder.view', compact('orders'));
    }

    public function storeMaterialorder(Request $request)
    {
        
        $request->validate([
    // Basic info
    'sr_no'            => 'required|integer',
    'date'             => 'required|date',
    'work_order_desc'  => 'required|string|max:255',

    // Finish size (optional, numeric)
    'f_diameter'       => 'nullable|numeric|min:0',
    'f_length'         => 'nullable|numeric|min:0',
    'f_width'          => 'nullable|numeric|min:0',
    'f_height'         => 'nullable|numeric|min:0',

    // Raw size (optional, numeric)
    'r_diameter'       => 'nullable|numeric|min:0',
    'r_length'         => 'nullable|numeric|min:0',
    'r_width'          => 'nullable|numeric|min:0',
    'r_height'         => 'nullable|numeric|min:0',

    // Material
    'material'         => 'required|string|max:255',

    // Quantity
    'quantity'         => 'required|integer|min:1',
]);


        MaterialOrder::create($request->all());

        return redirect()->route('ViewMaterialorder')
                         ->with('success', 'Material Order created successfully.');
    }

    /**
     * Edit material order.
     */
    public function edit($id)
    {
        $record = MaterialOrder::findOrFail(base64_decode($id));
        return view('Materialorder.add', compact('record'));
    }

    /**
     * Update material order.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'sr_no' => 'required|integer',
            'date' => 'required|date',
            'work_order_desc' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        $record = MaterialOrder::findOrFail(base64_decode($id));
        $record->update($request->all());

        return redirect()->route('ViewMaterialorder')
                         ->with('success', 'Material Order updated successfully.');
    }

    /**
     * Delete material order.
     */
    public function destroy($id)
    {
        $record = MaterialOrder::findOrFail(base64_decode($id));
        $record->delete();

        return redirect()->route('ViewMaterialorder')
                         ->with('success', 'Material Order deleted successfully.');
    }

    
}
