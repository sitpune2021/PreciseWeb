<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Hsncode;
use App\Models\InvoiceItem;
use App\Models\MachineRecord;
use App\Models\WorkOrder;
use App\Models\MaterialType;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProformaItem;
use App\Models\Project;

class ProformaInvoiceController extends Controller
{
    public function index(Request $request)
    {

        $adminId = Auth::id();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $workOrders = WorkOrder::where('admin_id', $adminId)
            ->where('status', 1)
            ->with('customer')
            ->get();

        $customerId = $request->input('customer_id');

        $invoices = ProformaInvoice::with(['customer', 'items'])
            ->where('admin_id', $adminId);

        if (!empty($customerId)) {
            $invoices->where('customer_id', $customerId);
        }

        $invoices = $invoices->latest()->get();

        return view('proforma.index', compact('customers', 'invoices', 'customerId', 'workOrders'));
    }

    public function create()
    {
        $adminId = Auth::id();
        $adminSetting = AdminSetting::first();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', $adminId)
            ->get();

        $workOrders = WorkOrder::where('status', 1)
            ->where('admin_id', $adminId)
            ->with('customer')
            ->get(['id', 'customer_id', 'part_description', 'exp_time', 'quantity', 'material', 'date']);

        return view('proforma.add', compact('customers', 'hsncodes', 'workOrders', 'adminSetting'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_id' => 'required',
            'invoice_date'        => 'required',
            'desc.*'      => 'required|string',
            'hsn_code.*'  => 'required|string',
            'qty.*'       => 'required|numeric|min:1',
            'rate.*'      => 'required|numeric|min:0',
            'amount.*'    => 'required|numeric|min:0',
            'hrs.*'       => 'nullable|string',
            'vmc_hr.*'    => 'nullable|numeric|min:0',
            'adj.*'       => 'nullable|numeric|min:0',
            'sub_total'   => 'required|numeric|min:0',
            'total_tax'   => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $adminId = auth()->id();
        $customer = Customer::find($request->customer_id);
        $words = explode(' ', $customer->customer_name);
        $prefix = '';
        foreach ($words as $w) {
            $prefix .= strtoupper(substr($w, 0, 1));
        }

        $year = date('y');
        $financialYear = $year . ($year + 1);

        $lastInvoice = ProformaInvoice::where('admin_id', $adminId)
            ->where('invoice_no', 'LIKE', $prefix . $financialYear . '%')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_no, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = "001";
        }

        $invoiceNo = $prefix . $financialYear . "-G" . $newNumber;

        $invoice = ProformaInvoice::create([
            'customer_id'     => $request->customer_id,
            'sub_total'       => $request->sub_total,
            'total_tax'       => $request->total_tax,
            'adjustment'      => $request->adj_total ?? 0,
            'round_off'       => $request->round_off ?? 0,
            'grand_total'     => $request->grand_total,
            'total_hrs'       => array_sum($request->hrs ?? []),
            'total_vmc'       => array_sum($request->vmc ?? []),
            'declaration'     => $request->declaration,
            'note'            => $request->note,
            'bank_details'    => $request->bank_details,
            'amount_in_words' => $request->amount_in_words,
            'admin_id'        => $adminId,
            'invoice_no'      => $invoiceNo,
            'invoice_date'    => $request->invoice_date,
        ]);

        foreach ($request->desc as $i => $desc) {
            $invoice->items()->create([
                'part_name' => $desc ?? '',
                'project_id'  => $request->project_id[$i] ?? null,
                'work_order_id' => $request->work_order_id[$i] ?? null,
                'hsn_code' => $request->hsn_code[0] ?? null,
                'material_rate'   => $request->material_rate[$i] ?? 0,
                'qty'       => $request->qty[$i] ?? 0,
                'rate'      => $request->rate[$i] ?? 0,
                'amount'    => $request->amount[$i] ?? 0,
                'hrs'       => isset($request->hrs[$i]) ? floatval(preg_replace('/[^0-9.\-]/', '', $request->hrs[$i])) : 0,
                'vmc'       => $request->vmc_hr[$i] ?? 0,
                'adj'       => $request->adj[$i] ?? 0,
                'sgst'      => $request->sgst_percent ?? 0,
                'cgst'      => $request->cgst_percent ?? 0,
                'igst'      => $request->igst ?? 0,
                'invoice_id' => $invoice->id,
            ]);

            if (!empty($request->work_order_id[$i])) {
                $workOrder = WorkOrder::find($request->work_order_id[$i]);
                if ($workOrder) {
                    $workOrder->update(['status' => 2]);
                }
            }
        }

        return redirect()->route('proforma.index')->with('success', 'Proforma created successfully! ' . $invoiceNo);
    }

    public function printInvoice($id)
    {
        $invoice = ProformaInvoice::with(['items.workOrder.project'])->findOrFail($id);
        $adminSetting = AdminSetting::first();

        $adminId = Auth::id();
        $c = Client::where('login_id', $adminId)->first([
            'name',
            'phone_no',
            'email_id',
            'gst_no',
            'logo',
            'address'
        ]);

        $workOrders = $invoice->items
            ->pluck('workOrder')
            ->filter()          // remove null
            ->unique('id');

        return view('proforma.print', compact('invoice', 'c', 'adminSetting', 'workOrders'));
    }


    public function getHsnDetails($id)
    {
        $hsn_code = Hsncode::where('id', $id)
            ->where('admin_id', Auth::id())
            ->first();

        if ($hsn_code) {
            return response()->json([
                'hsn_code' => $hsn_code->hsn_code,
                'sgst'     => $hsn_code->sgst,
                'cgst'     => $hsn_code->cgst,
                'igst'     => $hsn_code->igst,
            ]);
        }

        return response()->json(['error' => 'Not Found'], 404);
    }

    public function getMachineRecords($customer_id)
    {
        $adminId = Auth::id();

        $usedWorkOrders = \DB::table('proforma_items')
            ->join('proforma_invoices', 'proforma_items.invoice_id', '=', 'proforma_invoices.id')
            ->where('proforma_invoices.customer_id', $customer_id)
            ->where('proforma_invoices.admin_id', $adminId)
            ->pluck('proforma_items.work_order_id')
            ->toArray();


        $records = WorkOrder::where('admin_id', $adminId)
            ->where('customer_id', $customer_id)
            ->where('status', 1)
            ->whereNotIn('id', $usedWorkOrders)
            ->orderBy('id', 'asc')
            ->get(['id', 'project_id', 'part_description', 'exp_time', 'quantity', 'material']);

        if ($records->isEmpty()) {
            return response()->json([]);
        }

        $machineRecords = MachineRecord::where('admin_id', $adminId)
            ->get(['id', 'work_order_id', 'hrs']);


        $materials = MaterialType::where('admin_id', $adminId)
            ->get(['material_type', 'material_rate']);


        $data = $records->map(function ($r) use ($materials, $machineRecords) {

            $machine = $machineRecords->first(function ($m) use ($r) {

                if (!empty($m->work_order_id) && $m->work_order_id == $r->id) {
                    return true;
                }

                return trim(strtolower($m->work_order_id)) === trim(strtolower($r->project_id));
            });

            $mat = $materials->firstWhere('material_type', $r->material);

            return [
                'id'               => $r->id,
                'project_id'       => $r->project_id,
                'part_description' => $r->part_description,
                'quantity'         => $r->quantity,
                'exp_time'         => $r->exp_time,
                'vmc_hr'           => $machine->hrs ?? 0,
                'material_type'    => $r->material,
                'material_rate'    => $mat->material_rate ?? 0,
                'workorder_id'     => $r->id,
                'machine_id'       => $machine->id ?? null,
            ];
        });

        return response()->json($data);
    }

    public function convertToTax($id)
    {
        $pro = ProformaInvoice::with('items')->findOrFail($id);
        $adminId = Auth::id();

        $year = date('y');
        $financialYear = $year . ($year + 1);


        $lastInvoice = Invoice::where('admin_id', $adminId)
            ->where('invoice_no', 'LIKE',   $financialYear . '%')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_no, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = "001";
        }

        $invoiceNo =  $financialYear . "-I" . $newNumber;

        $invoice = Invoice::create([
            'customer_id'     => $pro->customer_id,
            'sub_total'       => $pro->sub_total,
            'total_tax'       => $pro->total_tax,
            'adjustment'      => $pro->adjustment,
            'round_off'       => $pro->round_off,
            'grand_total'     => $pro->grand_total,
            'total_hrs'       => $pro->total_hrs,
            'total_vmc'       => $pro->total_vmc,
            'declaration'     => $pro->declaration,
            'note'            => $pro->note,
            'bank_details'    => $pro->bank_details,
            'amount_in_words' => $pro->amount_in_words,
            'admin_id'        => $adminId,
            'invoice_no'      => $invoiceNo,
            'invoice_date'    => now(),
        ]);

        foreach ($pro->items as $item) {
            $invoice->items()->create([
                'part_name'   => $item->part_name,
                'project_id'  => $item->project_id,
                'work_order_id' => $item->work_order_id,
                'hsn_code'    => $item->hsn_code,
                'qty'         => $item->qty,
                'rate'        => $item->rate,
                'amount'      => $item->amount,
                'hrs'         => $item->hrs,
                'vmc'         => $item->vmc,
                'adj'         => $item->adj,
                'sgst'        => $item->sgst,
                'cgst'        => $item->cgst,
                'igst'        => $item->igst,
            ]);
        }

        // return redirect()->route('invoice.print', $invoice->id)
        //     ->with('success', 'TAX Invoice created successfully!');

        return back()->with('success', 'Converted Successfully!');
    }

    public function proformaEdit($id)
    {

        $adminId = Auth::id();

        $id = base64_decode($id);

        $data = ProformaInvoice::with('items')->findOrFail($id);

        $customers = Customer::all();
        $clients   = Client::all();
        $hsncodes  = Hsncode::all();

        $workOrders = WorkOrder::where('customer_id', $data->customer_id)
            ->where('admin_id', $adminId)
            ->get();




        $adminSetting = AdminSetting::first();
        return view('proforma.add', compact('data', 'customers', 'clients', 'hsncodes', 'workOrders', 'adminSetting'));
    }

    public function proformaUpdate(Request $request, $id)
    {

        $adminId = Auth::id();;
        $id = base64_decode($id);
        $invoice = ProformaInvoice::findOrFail($id);

        if ($invoice->is_proforma_printed == 1) {
            return back()->with('error', 'Proforma already printed. Cannot update.');
        }

        $request->validate([
            'customer_id' => 'nullable',
            'desc.*'      => 'required|string',
            'hsn_code.*'  => 'required|string',
            'qty.*'       => 'required|numeric|min:1',
            'rate.*'      => 'required|numeric|min:0',
            'amount.*'    => 'required|numeric|min:0',
            'hrs.*'       => 'nullable|string',
            'vmc_hr.*'    => 'nullable|numeric|min:0',
            'adj.*'       => 'nullable|numeric|min:0',
            'sub_total'   => 'required|numeric|min:0',
            'total_tax'   => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'customer_id'     => $request->customer_id,
            'sub_total'       => $request->sub_total,
            'total_tax'       => $request->total_tax,
            'adjustment'      => $request->adj_total ?? 0,
            'round_off'       => $request->round_off ?? 0,
            'grand_total'     => $request->grand_total,
            'total_hrs'       => array_sum($request->hrs ?? []),
            'total_vmc'       => array_sum($request->vmc ?? []),
            'declaration'     => $request->declaration,
            'note'            => $request->note,
            'bank_details'    => $request->bank_details,
            'amount_in_words' => $request->amount_in_words,
            'admin_id'        => $adminId,
            'invoice_no'      => $request->invoice_no,
            'invoice_date'    => $request->invoice_date,
        ]);


        if (!empty($request->desc)) {

            foreach ($request->desc as $i => $desc) {

                $hsn = $request->hsn_code[0] ?? null;
                $hsnMaster = $hsn ? Hsncode::where('hsn_code', $hsn)->first() : null;

                $cgst_rate = $hsnMaster->cgst ?? 0;
                $sgst_rate = $hsnMaster->sgst ?? 0;
                $igst_rate = $hsnMaster->igst ?? 0;

                // Correct GST logic
                if (($cgst_rate > 0 || $sgst_rate > 0) && $igst_rate > 0) {
                    $igst_rate = 0;
                }
                if ($igst_rate > 0) {
                    $cgst_rate = 0;
                    $sgst_rate = 0;
                }

                // Convert HRS string (3.30 hr → 3.30)
                $hrs = isset($request->hrs[$i])
                    ? floatval(preg_replace('/[^0-9.\-]/', '', $request->hrs[$i]))
                    : 0;

                // If ID exists → UPDATE, else CREATE
                if (!empty($request->id[$i])) {



                    $item = ProformaItem::find($request->id[$i]);

                    $item->update([
                        'part_name'     => $desc,
                        'project_id'    => $request->project_id[$i],
                        'work_order_id' => $request->work_order_id[$i],
                        'hsn_code'      => $request->hsn_code[0],
                        'qty'           => $request->qty[$i],
                        'rate'          => $request->rate[$i],
                        'amount'        => $request->amount[$i],
                        'material_rate' => $request->material_rate[$i],
                        'hrs'           => $hrs,
                        'vmc'           => $request->vmc_hr[$i],
                        'adj'           => $request->adj[$i],
                        'sgst'          => $sgst_rate,
                        'cgst'          => $cgst_rate,
                        'igst'          => $igst_rate,
                    ]);
                } else {

                    // Create new
                    $invoice->items()->create([
                        'part_name'     => $desc,
                        'project_id'    => $request->project_id[$i],
                        'work_order_id' => $request->work_order_id[$i],
                        'hsn_code'      => $request->hsn_code[0],
                        'qty'           => $request->qty[$i],
                        'rate'          => $request->rate[$i],
                        'amount'        => $request->amount[$i],
                        'material_rate' => $request->material_rate[$i],
                        'hrs'           => $hrs,
                        'vmc'           => $request->vmc_hr[$i],
                        'adj'           => $request->adj[$i],
                        'sgst'          => $sgst_rate,
                        'cgst'          => $cgst_rate,
                        'igst'          => $igst_rate,
                    ]);
                }

                // Update Work Order status
                if (!empty($request->work_order_id[$i])) {
                    WorkOrder::where('id', $request->work_order_id[$i])
                        ->update(['status' => 2]);
                }
            }
        }

        return redirect()
            ->route('proforma.index')
            ->with('success', 'Proforma Invoice Updated Successfully.');
    }
}
