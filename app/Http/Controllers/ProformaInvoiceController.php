<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Hsncode;
use App\Models\InvoiceItem;
use App\Models\MachineRecord;
use App\Models\MaterialReq;
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

        $customerId = $request->input('customer_id');

        $invoices = ProformaInvoice::with(['customer', 'items'])
            ->where('admin_id', $adminId);

        //  Filter
        if (!empty($customerId)) {
            $invoices->where('customer_id', $customerId);
        }

        //  Sorting + Pagination (recommended)
        $invoices = $invoices->latest('id')->paginate(10);

        return view('proforma.index', compact(
            'customers',
            'invoices',
            'customerId'
        ));
    }

    public function create()
    {
        $adminId = Auth::id();

        $adminSetting = AdminSetting::where('admin_id', $adminId)->first();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $hsncodes = Hsncode::where('admin_id', $adminId)
            ->where('is_active', 1)
            ->get();

        $workOrders = WorkOrder::where('status', 1)
            ->where('admin_id', $adminId)
            ->with('customer')
            ->get([
                'id',
                'customer_id',
                'part_description',
                'exp_time',
                'quantity',
                'material',
                'date'
            ]);

        //  Prefix (same logic, just defined)
        $prefix = Auth::user()->name
            ? strtoupper(substr(Auth::user()->name, 0, 2))
            : 'AD';

        //  Financial Year
        $year = date('y');
        $nextYear = date('y', strtotime('+1 year'));
        $financialYear = $year . $nextYear;

        //  Last Invoice (filtered by prefix + FY)
        $lastInvoice = ProformaInvoice::where('admin_id', $adminId)
            ->where('invoice_no', 'LIKE', $prefix . $financialYear . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            preg_match('/(\d+)$/', $lastInvoice->invoice_no, $matches);
            $number = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
        } else {
            $number = 1;
        }

        $newNumber = str_pad($number, 3, '0', STR_PAD_LEFT);

        $invoiceNo = $prefix . $financialYear . "-G" . $newNumber;

        return view('proforma.add', compact(
            'customers',
            'hsncodes',
            'workOrders',
            'adminSetting',
            'invoiceNo'
        ));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_id' => 'required',
            'invoice_date' => 'required',
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
            'machine_id.*' => 'nullable|exists:machine_records,id',
        ]);

        $adminId = auth()->id();

        //  Admin Code
        $companyName = Auth::user()->name ?? 'AD';
        $words = explode(' ', trim($companyName));

        $adminCode = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $adminCode .= strtoupper(substr($w, 0, 1));
            }
        }
        $adminCode = substr($adminCode, 0, 2);

        $year = date('y');
        $financialYear = $year . ($year + 1);

        $prefix = $adminCode; //  FIXED

        //  Last Invoice
        $lastInvoice = ProformaInvoice::where('admin_id', $adminId)
            ->where('invoice_no', 'LIKE', $prefix . $financialYear . '%')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            preg_match('/(\d+)$/', $lastInvoice->invoice_no, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            if ($request->invoice_no) {
                $invoiceNo = $request->invoice_no;
            } else {
                $newNumber = "001";
                $invoiceNo = $prefix . $financialYear . "-G" . $newNumber;
            }
        }

        if (!isset($invoiceNo)) {
            $invoiceNo = $prefix . $financialYear . "-G" . $newNumber;
        }

        //  Create Invoice
        $invoice = ProformaInvoice::create([
            'customer_id'     => $request->customer_id,
            'sub_total'       => $request->sub_total,
            'total_tax'       => $request->total_tax,
            'adjustment'      => $request->adj_total ?? 0,
            'round_off'       => $request->round_off ?? 0,
            'grand_total'     => $request->grand_total,
            'total_hrs'       => array_sum($request->hrs ?? []),
            'total_vmc'       => array_sum($request->vmc_hr ?? []), //  FIXED
            'declaration'     => $request->declaration,
            'note'            => $request->note,
            'bank_details'    => $request->bank_details,
            'amount_in_words' => $request->amount_in_words,
            'admin_id'        => $adminId,
            'invoice_no'      => $invoiceNo,
            'invoice_date'    => $request->invoice_date,
        ]);

        foreach ($request->desc as $i => $desc) {

            //  JSON decode
            $machineIds = isset($request->machine_ids[$i])
                ? json_decode($request->machine_ids[$i], true)
                : [];

            // fallback
            if (empty($machineIds) && !empty($request->machine_id[$i])) {
                $machineIds = [$request->machine_id[$i]];
            }

            if (empty($machineIds)) {
                continue;
            }

            //  GET ALL MACHINES IN ONE GO
            $machines = MachineRecord::whereIn('id', $machineIds)->get();

            if ($machines->isEmpty()) {
                continue;
            }

            //  TOTAL HRS (SUM OF ALL)
            $totalHrs = $machines->sum('hrs');

            //  FIRST MACHINE (for common data)
            $firstMachine = $machines->first();

            //  INSERT ONLY ONE ROW (IMPORTANT)
            $invoice->items()->create([
                'part_name'     => $desc ?? '',

                // correct mapping
                'project_id'    => $firstMachine->project_id ?? null,
                'work_order_id' => $firstMachine->work_order_id ?? null,

                //  STORE ALL IDS (JSON)
                'machine_id'    => json_encode($machineIds),

                'hsn_code'      => $request->hsn_code[$i] ?? null,

                // SUM hrs
                // 'hrs'           => $totalHrs,
                // 'vmc'           => $request->vmc_hr[$i] ?? 0,

                'hrs'           => $request->hrs[$i] ?? 0,   // EST time
                'vmc'           => $totalHrs,                // machine hrs
                'qty'           => $request->qty[$i] ?? 0,
                'rate'          => $request->rate[$i] ?? 0,
                'amount'        => $request->amount[$i] ?? 0,

                'material_rate' => $request->material_rate[$i] ?? 0,
                'material_cost' => $request->material_cost[$i] ?? 0,
                'total_cost'    => $request->total_cost[$i] ?? 0,

                'adj'           => $request->adj[$i] ?? 0,

                'sgst'          => $request->sgst_percent ?? 0,
                'cgst'          => $request->cgst_percent ?? 0,
                'igst' => $request->total_tax_percent ?? 0,

                'invoice_id'    => $invoice->id,
            ]);

            //  UPDATE ALL MACHINES AT ONCE
            MachineRecord::whereIn('id', $machineIds)->update([
                'status'     => 'complete',
                'invoice_no' => $invoice->id
            ]);

            \Log::info('Machines Updated', [
                'ids' => $machineIds,
                'count' => count($machineIds)
            ]);
        }
        return redirect()
            ->route('proforma.index')
            ->with('success', 'Proforma created successfully! ' . $invoiceNo);
    }

    public function printInvoice($id)
    {
        $invoice = ProformaInvoice::with([
            'items.workOrder.project'
        ])->findOrFail($id);

        $adminSetting = AdminSetting::where('admin_id', Auth::id())->first();
        $c = Client::where('login_id', Auth::id())->first();

        // Machine records cache (performance)
        $machineRecords = MachineRecord::pluck('part_no', 'id');

        // GROUPING LOGIC
        $items = $invoice->items->groupBy(function ($item) use ($machineRecords) {

            $ids = json_decode($item->machine_id, true);

            return collect($ids)
                ->map(fn($id) => $machineRecords[$id] ?? '')
                ->unique()   // IMPORTANT FIX
                ->implode(',');
        });

        // PASS grouped items
        return view('proforma.print', compact('invoice', 'items', 'c', 'adminSetting'));
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

    // public function convertToTax($id)
    // {
    //     $pro = ProformaInvoice::with('items')->findOrFail($id);
    //     $adminId = Auth::id();

    //     $year = date('y');
    //     $financialYear = $year . ($year + 1);


    //     $lastInvoice = Invoice::where('admin_id', $adminId)
    //         ->where('invoice_no', 'LIKE',   $financialYear . '%')
    //         ->orderBy('id', 'DESC')
    //         ->first();

    //     if ($lastInvoice) {
    //         $lastNumber = (int) substr($lastInvoice->invoice_no, -3);
    //         $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    //     } else {
    //         $newNumber = "001";
    //     }

    //     $invoiceNo =  $financialYear . "-G" . $newNumber;

    //     $invoice = Invoice::create([
    //         'customer_id'     => $pro->customer_id,
    //         'sub_total'       => $pro->sub_total,
    //         'total_tax'       => $pro->total_tax,
    //         'adjustment'      => $pro->adjustment,
    //         'round_off'       => $pro->round_off,
    //         'grand_total'     => $pro->grand_total,
    //         'total_hrs'       => $pro->total_hrs,
    //         'total_vmc'       => $pro->total_vmc,
    //         'declaration'     => $pro->declaration,
    //         'note'            => $pro->note,
    //         'bank_details'    => $pro->bank_details,
    //         'amount_in_words' => $pro->amount_in_words,
    //         'admin_id'        => $adminId,
    //         'invoice_no'      => $invoiceNo,
    //         'invoice_date'    => now(),
    //     ]);

    //     foreach ($pro->items as $item) {
    //         $invoice->items()->create([
    //             'part_name'   => $item->part_name,
    //             'project_id'  => $item->project_id,
    //             'work_order_id' => $item->work_order_id,
    //             'hsn_code'    => $item->hsn_code,
    //             'qty'         => $item->qty,
    //             'rate'        => $item->rate,
    //             'amount'      => $item->amount,
    //             'hrs'         => $item->hrs,
    //             'vmc'         => $item->vmc,
    //             'adj'         => $item->adj,
    //             'sgst'        => $item->sgst,
    //             'cgst'        => $item->cgst,
    //             'igst'        => $item->igst,
    //         ]);
    //     }

    //     // return redirect()->route('invoice.print', $invoice->id)
    //     //     ->with('success', 'TAX Invoice created successfully!');

    //     return back()->with('success', 'Converted Successfully!');
    // }


    public function getMachineRecords($customer_id)
    {
        $adminId = Auth::id();

        // Work Orders
        $workOrders = WorkOrder::where('admin_id', $adminId)
            ->where('customer_id', $customer_id)
            ->get()
            ->keyBy('id');

        // Machine Records
        $machineRecords = MachineRecord::where('admin_id', $adminId)
            ->where('status', 'pending')
            ->where('customer_id', $customer_id) //  MAIN FIX
            ->get()
            ->groupBy(function ($item) {
                return $item->work_order_id . '_' . $item->first_set;
            });

        if ($machineRecords->isEmpty()) {
            return response()->json([]);
        }

        // Materials
        $materials = MaterialType::where('admin_id', $adminId)
            ->get()
            ->keyBy('id');

        // Material Cost
        $MaterialReq = MaterialReq::selectRaw('work_order_id, SUM(material_cost) as total_cost')
            ->where('admin_id', $adminId)
            ->groupBy('work_order_id')
            ->get()
            ->keyBy('work_order_id');

        // FINAL DATA
        $data = $machineRecords->map(function ($records) use ($materials, $workOrders, $MaterialReq) {

            $first = $records->first();

            if (!$first) return null;

            $workOrderId = $first->work_order_id;
            $workOrder   = $workOrders[$workOrderId] ?? null;

            //  TOTAL HRS
            $totalHrs = (float) $records->sum('hrs');

            //  ALL MACHINE RECORD IDs
            $machineIds = $records->pluck('id')->values()->toArray();

            //  MATERIAL COST (multiple workorders handle)
            $materialCost = 0;

            if (isset($MaterialReq[$workOrderId])) {
                $materialCost = (float) $MaterialReq[$workOrderId]->total_cost;
            }

            //  MATERIAL RATE
            $materialRate = 0;
            if ($workOrder && isset($materials[$workOrder->material_id])) {
                $materialRate = (float) $materials[$workOrder->material_id]->material_rate;
            }

            return [
                'id'               => $first->id,

                // MACHINE RECORD BASED (FIXED)
                'project_id'       => $first->project_id,
                'part_description' => $first->first_set,
                'part_no'          => $first->part_no,
                'quantity'         => $workOrder->quantity ?? 0,
                'exp_time'         => $workOrder->exp_time ?? 0,
                // SUM OF ALL
                'vmc_hr'           => round($totalHrs, 2),
                'material_rate'    => $materialRate,
                'material_cost'    => round($materialCost, 2),
                'workorder_id'     => $workOrderId,
                // MULTIPLE IDS
                'machine_ids'      => $machineIds,
            ];
        })->filter()->values();

        return response()->json($data);
    }

    public function convertToTax($id)
    {
        $pro = ProformaInvoice::with('items')->findOrFail($id);
        $adminId = Auth::id();

        $adminId = auth()->id();

        //  Admin Code
        $companyName = Auth::user()->name ?? 'AD';
        $words = explode(' ', trim($companyName));

        $adminCode = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $adminCode .= strtoupper(substr($w, 0, 1));
            }
        }
        $adminCode = substr($adminCode, 0, 2);

        $year = date('y');
        $financialYear = $year . ($year + 1);

        $prefix = $adminCode;

        //  Last TAX Invoice (NOTE: Invoice model)
        $lastInvoice = Invoice::where('admin_id', $adminId)
            ->where('invoice_no', 'LIKE', $prefix . $financialYear . '%')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            preg_match('/(\d+)$/', $lastInvoice->invoice_no, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = "001";
        }

        //  FINAL TAX INVOICE NO
        $invoiceNo = $prefix . $financialYear . "-T" . $newNumber;

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

            if (empty($item->machine_id) && empty($item->work_order_id)) {
                continue;
            }
            $invoice->items()->create([
                'part_name'     => $item->part_name,
                'project_id'    => $item->project_id,
                'work_order_id' => $item->work_order_id, // can be null now
                'machine_id'    => $item->machine_id,     // IMPORTANT
                'hsn_code'      => $item->hsn_code,
                'qty'           => $item->qty,
                'rate'          => $item->rate,
                'amount'        => $item->amount,
                'material_cost' => $item->material_cost,
                'total_cost'    => $item->total_cost,
                'hrs'           => $item->hrs,
                'vmc'           => $item->vmc,
                'adj'           => $item->adj,
                'sgst'          => $item->sgst,
                'cgst'          => $item->cgst,
                'igst'          => $item->igst,
            ]);
        }


        return back()->with('success', 'Converted Successfully!');
    }

    public function proformaEdit($id)
    {
        $adminId = Auth::id();

        $id = base64_decode($id);

        // Secure fetch (VERY IMPORTANT)
        $data = ProformaInvoice::with('items')
            ->where('admin_id', $adminId)
            ->findOrFail($id);

        // Only current admin data
        $customers = Customer::where('admin_id', $adminId)->get();
        // $clients   = Client::where('admin_id', $adminId)->get();
        $clients = Client::where('login_id', Auth::id())->get();

        $hsncodes = Hsncode::where('admin_id', $adminId)
            ->where('is_active', 1)
            ->get();

        // WorkOrders (you can later filter used ones if needed)
        $workOrders = WorkOrder::where('customer_id', $data->customer_id)
            ->where('admin_id', $adminId)
            ->get();

        $adminSetting = AdminSetting::where('admin_id', $adminId)->first();

        return view('proforma.add', compact(
            'data',
            'customers',
            'clients',
            'hsncodes',
            'workOrders',
            'adminSetting'
        ));
    }

    // public function proformaUpdate(Request $request, $id)
    // {

    //     $adminId = Auth::id();;
    //     $id = base64_decode($id);
    //     $invoice = ProformaInvoice::findOrFail($id);

    //     if ($invoice->is_proforma_printed == 1) {
    //         return back()->with('error', 'Proforma already printed. Cannot update.');
    //     }

    //     $request->validate([
    //         'customer_id' => 'nullable',
    //         'desc.*'      => 'required|string',
    //         'hsn_code' => 'required|string',
    //         'qty.*'       => 'required|numeric|min:1',
    //         'rate.*'      => 'required|numeric|min:0',
    //         'amount.*'    => 'required|numeric|min:0',
    //         'hrs.*'       => 'nullable|string',
    //         'vmc_hr.*'    => 'nullable|numeric|min:0',
    //         'adj.*'       => 'nullable|numeric|min:0',
    //         'sub_total'   => 'required|numeric|min:0',
    //         'total_tax'   => 'required|numeric|min:0',
    //         'grand_total' => 'required|numeric|min:0',
    //     ]);

    //     $invoice->update([
    //         'customer_id'     => $request->customer_id,
    //         'sub_total'       => $request->sub_total,
    //         'total_tax'       => $request->total_tax,
    //         'adjustment'      => $request->adj_total ?? 0,
    //         'round_off'       => $request->round_off ?? 0,
    //         'grand_total'     => $request->grand_total,
    //         'total_hrs'       => array_sum($request->hrs ?? []),
    //         'total_vmc' => array_sum($request->vmc_hr ?? []),
    //         'declaration'     => $request->declaration,
    //         'note'            => $request->note,
    //         'bank_details'    => $request->bank_details,
    //         'amount_in_words' => $request->amount_in_words,
    //         'admin_id'        => $adminId,
    //         'invoice_no'      => $request->invoice_no,
    //         'invoice_date'    => $request->invoice_date,
    //     ]);


    //     if (!empty($request->desc)) {

    //         foreach ($request->desc as $i => $desc) {

    //             $hsn = $request->hsn_code[0] ?? null;
    //             $hsnMaster = $hsn
    //                 ? Hsncode::where('hsn_code', $hsn)
    //                 ->where('admin_id', $adminId)
    //                 ->first()
    //                 : null;

    //             $cgst_rate = $hsnMaster->cgst ?? 0;
    //             $sgst_rate = $hsnMaster->sgst ?? 0;
    //             $igst_rate = $hsnMaster->igst ?? 0;

    //             // Correct GST logic
    //             if (($cgst_rate > 0 || $sgst_rate > 0) && $igst_rate > 0) {
    //                 $igst_rate = 0;
    //             }
    //             if ($igst_rate > 0) {
    //                 $cgst_rate = 0;
    //                 $sgst_rate = 0;
    //             }

    //             // Convert HRS string (3.30 hr → 3.30)
    //             $hrs = isset($request->hrs[$i])
    //                 ? floatval(preg_replace('/[^0-9.\-]/', '', $request->hrs[$i]))
    //                 : 0;

    //             // If ID exists → UPDATE, else CREATE
    //             if (!empty($request->id[$i])) {



    //                 $item = ProformaItem::find($request->id[$i]);

    //                 $item->update([
    //                     'part_name'     => $desc,
    //                     'project_id'    => $request->project_id[$i],
    //                     'work_order_id' => $request->work_order_id[$i],
    //                     'hsn_code'      => $request->hsn_code[$i],
    //                     'qty'           => $request->qty[$i],
    //                     'rate'          => $request->rate[$i],
    //                     'amount'        => $request->amount[$i],
    //                     'material_rate' => $request->material_rate[$i],
    //                     'hrs'           => $hrs,
    //                     'vmc'           => $request->vmc_hr[$i],
    //                     'adj'           => $request->adj[$i],
    //                     'sgst'          => $sgst_rate,
    //                     'cgst'          => $cgst_rate,
    //                     'igst'          => $igst_rate,
    //                 ]);
    //             } else {

    //                 // Create new
    //                 $invoice->items()->create([
    //                     'part_name'     => $desc,
    //                     'project_id'    => $request->project_id[$i],
    //                     'work_order_id' => $request->work_order_id[$i],
    //                     'hsn_code'      => $request->hsn_code[0],
    //                     'qty'           => $request->qty[$i],
    //                     'rate'          => $request->rate[$i],
    //                     'amount'        => $request->amount[$i],
    //                     'material_rate' => $request->material_rate[$i],
    //                     'hrs'           => $hrs,
    //                     'vmc'           => $request->vmc_hr[$i],
    //                     'adj'           => $request->adj[$i],
    //                     'sgst'          => $sgst_rate,
    //                     'cgst'          => $cgst_rate,
    //                     'igst'          => $igst_rate,
    //                 ]);
    //             }

    //             // Update Work Order status
    //             if (!empty($request->work_order_id[$i])) {
    //                 WorkOrder::where('id', $request->work_order_id[$i])
    //                     ->update(['status' => 2]);
    //             }
    //         }
    //     }

    //     return redirect()
    //         ->route('proforma.index')
    //         ->with('success', 'Proforma Invoice Updated Successfully.');
    // }



    public function proformaUpdate(Request $request, $id)
    {
        $adminId = Auth::id();
        $id = base64_decode($id);

        //  Secure fetch
        $invoice = ProformaInvoice::where('admin_id', $adminId)
            ->findOrFail($id);


        if ($invoice->is_proforma_printed == 1) {
            return back()->with('error', 'Proforma already printed. Cannot update.');
        }

        //  VALIDATION
        $request->validate([
            'customer_id' => 'nullable',
            'desc.*'      => 'required|string',
            'hsn_code.*'  => 'required|string',
            'qty.*'       => 'required|numeric|min:1',
            'rate.*'      => 'required|numeric|min:0',
            'amount.*'    => 'required|numeric|min:0',
            'sub_total'   => 'required|numeric|min:0',
            'total_tax'   => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'hrs.*' => 'nullable|string|max:50',
        ]);

        //  UPDATE INVOICE
        $invoice->update([
            'customer_id' => $request->customer_id,
            'sub_total'   => $request->sub_total,
            'total_tax'   => $request->total_tax,
            'adjustment'  => $request->adj_total ?? 0,
            'round_off'   => $request->round_off ?? 0,
            'grand_total' => $request->grand_total,
            'invoice_no'  => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'hsn_code'    => $request->hsn_code[0] ?? null,
            'admin_id'    => $adminId,
        ]);

        //  Delete removed items (IMPORTANT)
        $existingIds = array_filter($request->id ?? []);
        ProformaItem::where('invoice_id', $invoice->id)
            ->whereNotIn('id', $existingIds)
            ->delete();

        //  LOOP ITEMS
        foreach ($request->desc as $i => $desc) {

            $hsn = $request->hsn_code[$i] ?? null;

            $hsnMaster = Hsncode::where('hsn_code', $hsn)
                ->where('admin_id', $adminId)
                ->first();

            $cgst = $hsnMaster->cgst ?? 0;
            $sgst = $hsnMaster->sgst ?? 0;
            $igst = $hsnMaster->igst ?? 0;

            if ($igst > 0) {
                $cgst = 0;
                $sgst = 0;
            }

            $machineIds = isset($request->machine_ids[$i])
                ? json_decode($request->machine_ids[$i], true)
                : [];

            if (empty($machineIds) && !empty($request->machine_id[$i])) {
                $machineIds = [$request->machine_id[$i]];
            }

            foreach ($machineIds as $mid) {

                $machine = MachineRecord::find($mid);
                if (!$machine) continue;

                $itemData = [
                    'part_name'     => $desc,
                    'project_id'    => $machine->project_id ?? null,
                    'work_order_id' => $machine->work_order_id ?? null,
                    'hsn_code'      => $hsn,
                    'qty'           => $request->qty[$i] ?? 0,
                    'rate'          => $request->rate[$i] ?? 0,
                    'amount'        => $request->amount[$i] ?? 0,
                    'material_cost' => $request->material_cost[$i] ?? 0,
                    'total_cost'    => $request->total_cost[$i] ?? 0,

                    //  IMPORTANT (hrs string support)
                    'hrs'           => $request->hrs[$i] ?? '',

                    'vmc'           => $request->vmc_hr[$i] ?? 0,
                    'adj'           => $request->adj[$i] ?? 0,
                    'sgst'          => $sgst,
                    'cgst'          => $cgst,
                    'igst'          => $igst,
                    'machine_id' => json_encode($machineIds),
                ];

                if (!empty($request->id[$i])) {
                    $item = ProformaItem::find($request->id[$i]);
                    if ($item) {
                        $item->update($itemData);
                    } else {
                        $invoice->items()->create($itemData);
                    }
                } else {
                    $invoice->items()->create($itemData);
                }

                $machine->update(['status' => 'complete']);
            }
        }

        //  RETURN OUTSIDE LOOP
        return redirect()
            ->route('proforma.index')
            ->with('success', 'Proforma Invoice Updated Successfully.');
    }
}
