<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialOrder;
use App\Models\Customer;
use App\Models\MaterialReq;
use Illuminate\Support\Facades\Auth; // ✅ Import Auth

class MaterialorderController extends Controller
{
   public function AddMaterialorder()
{
    $adminId = Auth::id();  

    $codes = Customer::where('status', 1)
        ->where('admin_id', $adminId)
        ->with('materialreq')
        ->orderBy('id', 'desc')
        ->get();

    $customers = Customer::where('status', 1)
        ->where('admin_id', $adminId)
        ->orderBy('name')
        ->get();

    return view('Materialorder.add', compact('codes', 'customers'));
}

    public function ViewMaterialorder()
    {
        // Show orders for current admin
        $orders = MaterialOrder::where('admin_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('Materialorder.view', compact('orders'));
    }

 public function storeMaterialorder(Request $request)
    {
        // ✅ Server-side validation
        $validatedData = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'work_order_no'   => 'required|string|max:255',
            'date'            => 'required|date',
            'work_order_desc' => 'required|string|max:255',
            'f_diameter'      => 'nullable|numeric|min:0',
            'f_length'        => 'nullable|numeric|min:0',
            'f_width'         => 'nullable|numeric|min:0',
            'f_height'        => 'nullable|numeric|min:0',
            'r_diameter'      => 'nullable|numeric|min:0',
            'r_length'        => 'nullable|numeric|min:0',
            'r_width'         => 'nullable|numeric|min:0',
            'r_height'        => 'nullable|numeric|min:0',
            'material'        => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
        ]);

        // Add admin_id for tracking
        $validatedData['admin_id'] = Auth::id();

        // Save to database
        MaterialOrder::create($validatedData);

        return redirect()->route('ViewMaterialorder')
            ->with('success', 'Material Order created successfully.');
    }

    public function editMaterialorder($id)
    {
        $decodedId = base64_decode($id);

        $record = MaterialOrder::withTrashed()
            ->where('admin_id', Auth::id())
            ->findOrFail($decodedId);

        $codes = Customer::where('status', 1)->select('id', 'code', 'name')->get();
        $customers = Customer::where('status', 1)->get();

        return view('Materialorder.add', compact('record', 'codes', 'customers'));
    }



    public function update(Request $request, $id)
    {
        $decodedId = base64_decode($id);

        $record = MaterialOrder::withTrashed()
            ->where('admin_id', Auth::id())
            ->findOrFail($decodedId);

        if ($record->trashed()) {
            $record->restore();
        }

        $validated = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'date'            => 'required|date',
            'work_order_desc' => 'required|string|max:255',
            'work_order_no'   => 'required|string|max:255',
            'f_diameter'      => 'nullable|numeric|min:0',
            'f_length'        => 'nullable|numeric|min:0',
            'f_width'         => 'nullable|numeric|min:0',
            'f_height'        => 'nullable|numeric|min:0',
            'r_diameter'      => 'nullable|numeric|min:0',
            'r_length'        => 'nullable|numeric|min:0',
            'r_width'         => 'nullable|numeric|min:0',
            'r_height'        => 'nullable|numeric|min:0',
            'material'        => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
        ]);

        $record->update($validated);

        return redirect()->route('ViewMaterialorder')
            ->with('success', "Material Order '{$record->work_order_desc}' updated successfully.");
    }

    public function destroy($id)
    {
        $record = MaterialOrder::where('admin_id', Auth::id())
            ->findOrFail(base64_decode($id));

        $record->delete();

        return redirect()->route('ViewMaterialorder')
            ->with('success', 'Material Order deleted successfully.');
    }

    public function trash()
    {
        $trashedOrders = MaterialOrder::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $activeOrders = MaterialOrder::where('admin_id', Auth::id())->get();

        return view('Materialorder.trash', compact('trashedOrders', 'activeOrders'));
    }

    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $order = MaterialOrder::withTrashed()->where('admin_id', Auth::id())
            ->findOrFail($id);

        $exists = MaterialOrder::where('work_order_desc', $order->work_order_desc)
            ->whereNull('deleted_at')
            ->where('admin_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->route('editMaterialorder', base64_encode($order->id))
                ->with('success', "Material Order '{$order->work_order_desc}' already exists. Redirected to Edit Page.");
        }

        $order->restore();

        return redirect()->route('ViewMaterialorder')
            ->with('success', "Material Order '{$order->work_order_desc}' restored successfully.");
    }


    public function getCustomerData($id)
    {
        $materialReq = MaterialReq::where('customer_id', $id)
            ->latest()
            ->first();

        if ($materialReq) {
            return response()->json([
                'code'        => $materialReq->customer->code ?? '',
                'work_order_no' => $materialReq->work_order_no ?? '',
                'description' => $materialReq->description ?? '',
                'material'    => $materialReq->material ?? '',
                'qty'         => $materialReq->quantity ?? '',
                'f_diameter'  => $materialReq->f_diameter ?? '',
                'f_length'    => $materialReq->f_length ?? '',
                'f_width'     => $materialReq->f_width ?? '',
                'f_height'    => $materialReq->f_height ?? '',
                'r_diameter'  => $materialReq->r_diameter ?? '',
                'r_length'    => $materialReq->r_length ?? '',
                'r_width'     => $materialReq->r_width ?? '',
                'r_height'    => $materialReq->r_height ?? '',
            ]);
        }

        return response()->json([]);
    }
}
