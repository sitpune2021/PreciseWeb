<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Hsncode;
use App\Models\MachineRecord;
use App\Models\WorkOrder;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Auth::id();


        $workOrders = WorkOrder::where('admin_id', $adminId)
            ->where('status', 1)
            ->with('customer')
            ->get();

       $customers = Customer::where('status', 1)
    ->where('admin_id', $adminId)
    ->orderBy('id', 'desc') // 🔹 नवीन entry top वर आणते
    ->get(); 

        $customerId = $request->input('customer_id');

        $invoices = Invoice::with(['customer', 'items']);

        if ($customerId) {
            $invoices->where('customer_id', $customerId);
        }

        $invoices = $invoices->latest()->get();

        return view('invoice.index', compact('customers', 'invoices', 'customerId', 'workOrders'));
    }

    public function create()
    {
        $adminId = Auth::id();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->get();

        $hsncodes = Hsncode::where('is_active', 1)
            ->where('admin_id', $adminId)
            ->get();

        $workOrders = WorkOrder::where('status', 1)
            ->where('admin_id', $adminId)
            ->with('customer')
            ->get(['id', 'customer_id', 'part_description', 'exp_time', 'quantity', 'material', 'date']);

        return view('invoice.add', compact('customers', 'hsncodes', 'workOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
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
            'adjustment'  => 'nullable|numeric',
            'round_off'   => 'nullable|numeric',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $adminId = auth()->id();

        $invoice = Invoice::create([
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
            'bank_details'     => $request->bank_details,
            'amount_in_words' => $request->amount_in_words,
            'admin_id'        => $adminId,
            'invoice_no'      => 'INV-' . time(),
            'invoice_date'    => now(),
        ]);

        foreach ($request->desc as $i => $desc) {
            $invoice->items()->create([
                'part_name' => $desc ?? '',

                'hsn_code'  => $request->hsn_code ?? null,
                'qty'       => $request->qty[$i] ?? 0,
                'rate'      => $request->rate[$i] ?? 0,
                'amount'    => $request->amount[$i] ?? 0,
                'hrs'       => isset($request->hrs[$i])
                    ? floatval(preg_replace(
                        '/[^0-9.\-]/',
                        '',
                        $request->hrs[$i]
                    )) : 0,
                'vmc'       => $request->vmc_hr[$i] ?? 0,
                'adj'       => $request->adj[$i] ?? 0,
                'sgst'      => $request->sgst_amt ?? 0,
                'cgst'      => $request->cgst_amt ?? 0,
                'igst'      => $request->igst ?? 0,
                'invoice_id' => $invoice->id,
            ]);
            if (!empty($request->work_order_id[$i])) {
                // dd($request->work_order_id);
                $workOrder = WorkOrder::find($request->work_order_id[$i]);
                if ($workOrder) {

                    $workOrder->update(['status' => 2]);
                }
            }
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice created successfully!');
    }

    public function download($id)
    {
        $invoice = Invoice::with(['customer', 'items'])->findOrFail($id);

        $pdf = PDF::loadView('invoice.pdf', compact('invoice'));
        return $pdf->download('Invoice-' . $invoice->invoice_no . '.pdf');
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $adminId = Auth::id();
        $c = Client::where('login_id', $adminId)->first([
            'name',
            'phone_no',
            'email_id',
            'gst_no',
            'logo',
            'address'
        ]);

        return view('invoice.print', compact('invoice', 'c'));
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
 
 
    $usedWorkOrders = \DB::table('invoice_items')
        ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
        ->where('invoices.customer_id', $customer_id)
        ->where('invoices.admin_id', $adminId)
        ->pluck('invoice_items.part_name')
        ->toArray();
 
    $records = WorkOrder::where('admin_id', $adminId)
        ->where('customer_id', $customer_id)
        ->where('status', 1)
        ->whereNotIn('part_description', $usedWorkOrders)
        ->orderBy('id', 'asc')
        ->get(['id', 'project_id', 'part_description', 'exp_time', 'quantity', 'material']);
 
    if ($records->isEmpty()) {
        return response()->json([]);
    }
 
    $machineRecords = MachineRecord::where('admin_id', $adminId)
        ->get(['id', 'work_order', 'hrs']);
 
    $materials = MaterialType::where('admin_id', $adminId)
        ->get(['material_type', 'material_rate']);
 
    $data = $records->map(function ($r) use ($materials, $machineRecords) {
     
        $machine = $machineRecords->first(function ($m) use ($r) {
            return trim(strtolower($m->work_order)) === trim(strtolower($r->project_id));
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
        ];
    });
 
    return response()->json($data);
}
}
