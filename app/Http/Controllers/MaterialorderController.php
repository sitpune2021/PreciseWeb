<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialOrder;
use App\Models\Customer;
use App\Models\MaterialReq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MaterialorderController extends Controller
{
    public function AddMaterialorder()
    {
        $adminId = Auth::id();

        // Customers should be admin-specific
        $codes = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->with('materialreq') // eager load relation (ensure relation does not itself filter by admin)
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get();

        // $materialReq is null for add form
        $materialReq = null;

        return view('Materialorder.add', compact('codes', 'customers', 'materialReq'));
    }
    public function ViewMaterialorder()
    {
        $orders = MaterialOrder::where('admin_id', Auth::id())
            ->latest()
            ->get();

        return view('Materialorder.view', compact('orders'));
    }
    public function storeMaterialorder(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required',
            'work_order_no'    => 'required',
            'date'             => 'required|date',
            'material_req_ids' => 'required|array|min:1',
            'work_order_desc'  => 'required|array',
            'material'         => 'required|array',
            'quantity'         => 'required|array',
        ]);

        foreach ($request->material_req_ids as $index => $reqId) {

            // 🔥 GET SR NO FROM MATERIAL REQ
            $materialReq = MaterialReq::find($reqId);

            MaterialOrder::create([
                'admin_id'        => Auth::id(),
                'customer_id'     => $request->customer_id,
                'material_req_id' => $reqId,

                // ✅ SAVE SR NO
                'sr_no'           => $materialReq->sr_no ?? null,

                'work_order_no'   => $request->work_order_no,
                'date'            => $request->date,

                'work_order_desc' => $request->work_order_desc[$index] ?? null,
                'r_diameter'      => $request->r_diameter[$index] ?? null,
                'r_length'        => $request->r_length[$index] ?? null,
                'r_width'         => $request->r_width[$index] ?? null,
                'r_height'        => $request->r_height[$index] ?? null,
                'material'        => $request->material[$index] ?? null,
                'quantity'        => $request->quantity[$index] ?? 0,
            ]);
        }

        return redirect()->route('ViewMaterialorder')
            ->with('success', 'Material Order saved successfully');
    }
    public function editMaterialorder($id)
    {
        $decodedId = base64_decode($id);

        $record = MaterialOrder::withTrashed()
            ->with('materialReq') // 🔥 IMPORTANT
            ->where('admin_id', Auth::id())
            ->findOrFail($decodedId);

        // Load customers for this admin (so dropdown shows only admin's customers)
        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id())
            ->select('id', 'code', 'name')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', Auth::id())
            ->get();

        // load the MaterialReq linked to this order so form fields can be pre-filled
        $materialReq = null;
        if (!empty($record->material_req_id)) {
            $materialReq = MaterialReq::with('materialType')->find($record->material_req_id);
        }

        return view('Materialorder.add', compact('record', 'codes', 'customers', 'materialReq'));
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

        // Validate array inputs
        $validated = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'date'            => 'required|date',
            'work_order_no'   => 'required|string|max:255',

            'work_order_desc' => 'required|array',
            'work_order_desc.*' => 'nullable|string|max:255',

            'f_diameter' => 'nullable|array',
            'f_diameter.*' => 'nullable|numeric|min:0',

            'f_length' => 'nullable|array',
            'f_length.*' => 'nullable|numeric|min:0',

            'f_width' => 'nullable|array',
            'f_width.*' => 'nullable|numeric|min:0',

            'f_height' => 'nullable|array',
            'f_height.*' => 'nullable|numeric|min:0',

            'r_diameter' => 'nullable|array',
            'r_diameter.*' => 'nullable|numeric|min:0',

            'r_length' => 'nullable|array',
            'r_length.*' => 'nullable|numeric|min:0',

            'r_width' => 'nullable|array',
            'r_width.*' => 'nullable|numeric|min:0',

            'r_height' => 'nullable|array',
            'r_height.*' => 'nullable|numeric|min:0',

            'material' => 'required|array',
            'material.*' => 'required|string|max:255',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);


        // Log before update
        Log::info('Material Order Update Start', [
            'id' => $decodedId,
            'old_record' => $record->toArray(),
            'request_data' => $request->all(),
        ]);

        // Since table has single columns, pick first element from arrays
        $record->update([
            'customer_id'     => $validated['customer_id'],
            'date'            => $validated['date'],
            'work_order_no'   => $validated['work_order_no'],
            'work_order_desc' => $validated['work_order_desc'][0] ?? null,
            'f_diameter'      => $validated['f_diameter'][0] ?? null,
            'f_length'        => $validated['f_diameter'][1] ?? null, // if needed
            'f_width'         => $validated['f_diameter'][2] ?? null, // etc.
            'f_height'        => $validated['f_diameter'][3] ?? null,
            'r_diameter'      => $validated['r_diameter'][0] ?? null,
            'r_length'        => $validated['r_length'][0] ?? null,
            'r_width'         => $validated['r_width'][0] ?? null,
            'r_height'        => $validated['r_height'][0] ?? null,
            'material'        => $validated['material'][0] ?? null,
            'quantity'        => $validated['quantity'][0] ?? null,
        ]);

        // Log after update
        Log::info('Material Order Updated', [
            'id' => $decodedId,
            'updated_record' => $record->fresh()->toArray(),
        ]);

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
    public function getMaterialRequestDetails($id)
    {
        $data = MaterialReq::with('materialType')->find($id);

        if (!$data) {
            return response()->json(['status' => 'not_found']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'description'   => $data->description,
                'material_name' => $data->materialType->material_type ?? '',
                'dia'           => $data->dia,
                'length'        => $data->length,
                'width'         => $data->width,
                'height'        => $data->height,
                'qty'           => $data->qty,
            ]
        ]);
    }
    public function getMaterialRequests($customer_id)
    {
        $requests = MaterialReq::with('materialType')
            ->where('customer_id', $customer_id)
            ->orderBy('sr_no')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests->map(function ($r) {
                return [
                    'id'            => $r->id,
                    'sr_no'         => $r->sr_no,
                    'description'   => $r->description,
                    'material_name' => $r->materialType->material_type ?? '',
                    'dia'           => $r->dia,
                    'length'        => $r->length,
                    'width'         => $r->width,
                    'height'        => $r->height,
                    'qty'           => $r->qty,
                ];
            })
        ]);
    }
    public function getCustomerWo($customerId)
    {
        $wo = MaterialReq::where('customer_id', $customerId)
            ->orderBy('id', 'desc')
            ->value('work_order_no');

        return response()->json([
            'work_order_no' => $wo
        ]);
    }
}
