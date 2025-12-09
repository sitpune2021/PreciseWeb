<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialOrder;
use App\Models\Customer;
use App\Models\MaterialReq;
use Illuminate\Support\Facades\Auth;

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
        $orders = MaterialOrder::where('admin_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('Materialorder.view', compact('orders'));
    }

    public function storeMaterialorder(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'work_order_no'   => 'required|string|max:255',
            'date'            => 'required|date',
            'work_order_desc' => 'nullable|string|max:255',
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
        $validatedData['admin_id'] = Auth::id();

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
            'work_order_desc' => 'nullable|string|max:255',
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
        $adminId = Auth::id();

        $material = MaterialReq::with(['materialType', 'customer'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$material) {
            return response()->json([]);
        }

        return response()->json([
            'code'           => $material->customer->code ?? '',
            'work_order_no'  => $material->work_order_no ?? '',
            'description'    => $material->description ?? '',
            'material_id'    => $material->material,
            'material_name'  => $material->materialType->material_type ?? '',
            'qty'            => $material->qty ?? '',
            'f_diameter'     => $material->dia ?? '',
            'f_length'       => $material->length ?? '',
            'f_width'        => $material->width ?? '',
            'f_height'       => $material->height ?? '',
        ]);
    }

public function getMaterialRequests($customer_id)
{
    try {
        $adminId = Auth::id();

        $requests = MaterialReq::where('customer_id', $customer_id)
            ->where('admin_id', $adminId)
            ->select('id', 'sr_no', 'description', 'work_order_no') // make sure sr_no is selected
            ->orderBy('id', 'desc')
            ->get();

        if ($requests->isEmpty()) {
            return response()->json(['status' => 'empty']);
        }

        return response()->json(['status' => 'success', 'data' => $requests]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}



    public function getMaterialRequestDetails($id)
    {
        $data = MaterialReq::with('materialType')->find($id);

        if (!$data) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'work_order_no' => $data->work_order_no,
                'date' => $data->date,
                'description' => $data->description,

                'material_name' => $data->materialType ? $data->materialType->material_type : '',

                'dia' => $data->dia,
                'length' => $data->length,
                'width' => $data->width,
                'height' => $data->height,
                'qty' => $data->qty,
            ]
        ]);
    }
}
