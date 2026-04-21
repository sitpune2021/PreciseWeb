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

        $orders = MaterialOrder::where('admin_id', Auth::id())
            ->latest()
            ->get();

        // $materialReq is null for add form
        $materialReq = null;

        return view('Materialorder.add', compact('codes', 'customers', 'materialReq', 'orders'));
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
        Log::info('Material Order Request Start', $request->all());

        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'work_order_no'    => 'required|string|max:255',
            'date'             => 'required|date',
            'material_req_ids' => 'required|array|min:1',
            'work_order_desc'  => 'required|array',
            'material'         => 'required|array',
            'quantity'         => 'required|array',
        ]);

        $processed = [];

        foreach ($request->material_req_ids as $index => $reqId) {

            Log::info('Processing Req ID:', ['reqId' => $reqId, 'index' => $index]);

            if (in_array($reqId, $processed)) {
                Log::warning('Duplicate Req ID Skipped', ['reqId' => $reqId]);
                continue;
            }

            $processed[] = $reqId;

            $materialReq = MaterialReq::find($reqId);

            if (!$materialReq) {
                Log::error('MaterialReq NOT FOUND', ['reqId' => $reqId]);
                continue;
            }

            Log::info('MaterialReq Data', [
                'id' => $materialReq->id,
                'project_id' => $materialReq->project_id,
                'sr_no' => $materialReq->sr_no
            ]);

            $data = [
                'admin_id'        => Auth::id(),
                'project_id'      => $materialReq->project_id,
                'sr_no'           => $materialReq->sr_no, //  ADD THIS
                'material_req_id' => $materialReq->id,
                'customer_id'     => $request->customer_id,
                'work_order_no'   => $materialReq->work_order_no,
                'date'            => $request->date,

                'work_order_desc' => $request->work_order_desc[$index] ?? $materialReq->description,

                'f_diameter' => $request->f_diameter[$index] ?? $materialReq->dia,
                'f_length'   => $request->f_length[$index] ?? $materialReq->length,
                'f_width'    => $request->f_width[$index] ?? $materialReq->width,
                'f_height'   => $request->f_height[$index] ?? $materialReq->height,

                'r_diameter' => $request->r_diameter[$index] ?? null,
                'r_length'   => $request->r_length[$index] ?? null,
                'r_width'    => $request->r_width[$index] ?? null,
                'r_height'   => $request->r_height[$index] ?? null,

                'material'   => $request->material[$index] ?? 'N/A',
                'quantity'   => $request->quantity[$index] ?? 0,
            ];

            Log::info('Insert Data', $data);

            MaterialOrder::create($data);
        }

        Log::info('Material Order Insert Completed');

        return redirect()->route('AddMaterialorder')
            ->with('success', 'Material Order saved successfully');
    }
    public function editMaterialorder($id)
    {
        $decodedId = base64_decode($id);

        $record = MaterialOrder::where('admin_id', Auth::id())
            ->findOrFail($decodedId);


        // $records = MaterialOrder::where('work_order_no', $record->work_order_no)->get();

        // ONLY SINGLE RECORD
        $records = collect([$record]); // table sathi ekach row

        $selectedIds = [$record->material_req_id]; // single select

        $customers = Customer::where('status', 1)
            ->where('admin_id', Auth::id())
            ->get();

        $materialRequests = MaterialReq::where('customer_id', $record->customer_id)
            ->orderBy('project_id')
            ->get();

        $orders = MaterialOrder::where('admin_id', Auth::id())
            ->latest()
            ->get();

        return view('Materialorder.add', compact(
            'record',
            'records',
            'selectedIds',
            'customers',
            'materialRequests',
            'orders'
        ));
    }

    // public function update(Request $request, $id)
    // {
    //     $decodedId = base64_decode($id);

    //     $record = MaterialOrder::findOrFail($decodedId);

    //     $record->update([
    //         'customer_id'     => $request->customer_id,
    //         'date'            => $request->date,
    //         'work_order_desc' => $request->work_order_desc[0] ?? $record->work_order_desc,

    //         'f_diameter' => $request->f_diameter[0] ?? null,
    //         'f_length'   => $request->f_length[0] ?? null,
    //         'f_width'    => $request->f_width[0] ?? null,
    //         'f_height'   => $request->f_height[0] ?? null,

    //         'r_diameter' => $request->r_diameter[0] ?? null,
    //         'r_length'   => $request->r_length[0] ?? null,
    //         'r_width'    => $request->r_width[0] ?? null,
    //         'r_height'   => $request->r_height[0] ?? null,

    //         'material'   => $request->material[0] ?? null,
    //         'quantity'   => $request->quantity[0] ?? null,
    //     ]);

    //     return redirect()->route('AddMaterialorder')
    //         ->with('success', 'Material Order updated successfully');
    // }


    public function update(Request $request, $id)
    {
        $decodedId = base64_decode($id);

        $record = MaterialOrder::findOrFail($decodedId);

        // ✅ Validate
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'date'             => 'required|date',
            'material_req_ids' => 'required|array|min:1',
            'work_order_desc'  => 'required|array',
            'material'         => 'required|array',
            'quantity'         => 'required|array',
        ]);

        // ✅ Delete old records of same WO
        MaterialOrder::where('work_order_no', $record->work_order_no)
            ->where('admin_id', Auth::id())
            ->delete();

        $processed = [];

        foreach ($request->material_req_ids as $index => $reqId) {

            if (in_array($reqId, $processed)) continue;
            $processed[] = $reqId;

            $materialReq = MaterialReq::find($reqId);
            if (!$materialReq) continue;

            MaterialOrder::create([
                'admin_id'        => Auth::id(),
                'project_id'      => $materialReq->project_id,

                'sr_no'           => $materialReq->sr_no,
                'material_req_id' => $materialReq->id,
                'customer_id'     => $request->customer_id,
                'work_order_no'   => $record->work_order_no, // keep same WO
                'date'            => $request->date,

                'work_order_desc' => $request->work_order_desc[$index] ?? $materialReq->description,

                'f_diameter' => $request->f_diameter[$index] ?? $materialReq->dia,
                'f_length'   => $request->f_length[$index] ?? $materialReq->length,
                'f_width'    => $request->f_width[$index] ?? $materialReq->width,
                'f_height'   => $request->f_height[$index] ?? $materialReq->height,

                'r_diameter' => $request->r_diameter[$index] ?? null,
                'r_length'   => $request->r_length[$index] ?? null,
                'r_width'    => $request->r_width[$index] ?? null,
                'r_height'   => $request->r_height[$index] ?? null,

                'material'   => $request->material[$index] ?? 'N/A',
                'quantity'   => $request->quantity[$index] ?? 0,
            ]);
        }

        return redirect()->route('AddMaterialorder')
            ->with('success', 'Material Order updated successfully');
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
            ->orderBy('project_id') // optional (instead of sr_no)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests->map(function ($r) {
                return [
                    'id'            => $r->id,
                    'project_id'    => $r->project_id, // ✅ ADD THIS
                    'work_order_no' => $r->work_order_no,
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
