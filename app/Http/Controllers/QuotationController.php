<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\MaterialType;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function Addquotation()
    {
        $year  = now()->year;
        $month = now()->month;

        if ($month >= 4) {
            $startYear = substr($year, 2, 2);
            $endYear   = substr($year + 1, 2, 2);
        } else {
            $startYear = substr($year - 1, 2, 2);
            $endYear   = substr($year, 2, 2);
        }

        $financialYear = $startYear . $endYear;

        $lastQuotation = Quotation::where('admin_id', auth()->id())
            ->where('quotation_no', 'like', $financialYear . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;

        if ($lastQuotation) {
            $lastNumber = explode('-', $lastQuotation->quotation_no)[1];
            $nextNumber = $lastNumber + 1;
        }

        $quotation_no = $financialYear . '-' . $nextNumber;


        $adminId = Auth::id();

        $codes = Customer::where('status', 1)
            ->whereNotNull('admin_id')
            ->where('admin_id', $adminId)
            ->select('id', 'code', 'name', 'customer_srno')
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('admin_id', $adminId)
            ->orderBy('name')
            ->get();
        $materialtype = MaterialType::where('admin_id', $adminId)
            ->orderBy('id', 'desc')
            ->get();

        $rates = Rate::where('admin_id', Auth::id())
            ->where('is_active', 1)
            ->pluck('rate', 'name');

        return view('Quotation.add', compact('codes', 'materialtype', 'customers', 'quotation_no', 'rates'));
    }
    // public function storequotation(Request $request)
    // {
    //     Log::info('Quotation Request:', $request->all());
    //     $request->validate([
    //         'customer_id' => 'required|exists:customers,id',
    //         'project_name' => 'required|string',
    //         'date' => 'required|date',

    //         'items' => 'required|array|min:1',
    //         'items.*.Description' => 'required|string',
    //         'items.*.qty' => 'required|numeric|min:1',
    //         'items.*.material_type_id' => 'nullable|exists:material_types,id',

    //     ], [

    //         // Qty Messages
    //         'items.*.qty.required' => 'Please enter quantity.',
    //         'items.*.qty.numeric'  => 'Quantity must be a number.',
    //         'items.*.qty.min'      => 'Quantity must be at least 1.',

    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // 🔹 Generate Financial Year
    //         $year  = now()->year;
    //         $month = now()->month;

    //         if ($month >= 4) {
    //             $startYear = substr($year, 2, 2);
    //             $endYear   = substr($year + 1, 2, 2);
    //         } else {
    //             $startYear = substr($year - 1, 2, 2);
    //             $endYear   = substr($year, 2, 2);
    //         }

    //         $financialYear = $startYear . $endYear;

    //         // 🔹 Get Last Number
    //         $lastQuotation = Quotation::where('admin_id', auth()->id())
    //             ->where('quotation_no', 'like', $financialYear . '-%')
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         $nextNumber = 1;

    //         if ($lastQuotation) {
    //             $lastNumber = explode('-', $lastQuotation->quotation_no)[1];
    //             $nextNumber = $lastNumber + 1;
    //         }

    //         $quotation_no = $financialYear . '-' . $nextNumber;

    //         // $quotation_no = (Quotation::where('admin_id', auth()->id())->max('sr_no') ?? 0) + 1;

    //         $quotation = Quotation::create([
    //             'customer_id' => $request->customer_id,
    //             'quotation_no' => $quotation_no,
    //             'project_name' => $request->project_name,
    //             'date'        => $request->date,
    //             'admin_id'    => auth()->id(),
    //             'terms_conditions' => $request->terms_conditions, // nullable
    //             'overhead_percent' => (float)($request->overhead_percent ?? 0),
    //             'profit_percent'   => (float)($request->profit_percent ?? 0),

    //         ]);
    //         $grandTotal = 0;

    //         foreach ($request->items as $item) {
    //             $material = MaterialType::find($item['material_type_id']);
    //             if (!$material) {
    //                 DB::rollBack();
    //                 return back()->with('error', 'Invalid Material selected for one of the items.');
    //             }

    //             $vmcSoftHours = (float)($item['vmc_soft'] ?? 0);
    //             $vmcHardHours = (float)($item['vmc_hard'] ?? 0);

    //             $vmcSoftRate = $rates['Vmc Soft'] ?? 0;
    //             $vmcHardRate = $rates['Vmc Hard'] ?? 0;

    //             $vmcSoftCost = $vmcSoftHours * $vmcSoftRate;
    //             $vmcHardCost = $vmcHardHours * $vmcHardRate;

    //             $machiningCost = (float)($item['machining_cost'] ?? 0);
    //             $grandTotal += $machiningCost;

    //             $quotation->items()->create([
    //                 'description' => $item['Description'] ?? null,
    //                 'dia' => (float)($item['dia'] ?? 0),
    //                 'length' => (float)($item['length'] ?? 0),
    //                 'width' => (float)($item['width'] ?? 0),
    //                 'height' => (float)($item['height'] ?? 0),
    //                 'qty' => (float)($item['qty'] ?? 1),
    //                 'material_type_id' => $material->id,
    //                 'material' => $material->material_type,
    //                 'material_rate' => (float)($item['material_rate'] ?? 0),
    //                 'material_cost' => (float)($item['material_cost'] ?? 0),
    //                 'lathe' => (float)($item['lathe'] ?? 0),
    //                 'mg' => (float)($item['mg'] ?? 0),
    //                 'rg' => (float)($item['rg'] ?? 0),
    //                 'cg' => (float)($item['cg'] ?? 0),
    //                 'sg' => (float)($item['sg'] ?? 0),
    //                 'vmc_soft' => $vmcSoftCost,
    //                 'vmc_hard' => $vmcHardCost,
    //                 'edm_hole' => (float)($item['edm_hole'] ?? 0),
    //                 'wirecut' => (float)($item['wirecut'] ?? 0),
    //                 'ht' => (float)($item['ht'] ?? 0),
    //                 'material_gravity' => $item['gravity'] ?? 0,
    //                 'machining_cost' => $machiningCost,
    //             ]);
    //         }

    //         // Profit & Overhead
    //         $profitPercent = (float)($request->profit_percent ?? 0);
    //         $overheadPercent = (float)($request->overhead_percent ?? 0);

    //         $profitAmount = round(($grandTotal * $profitPercent) / 100, 2);
    //         $overheadAmount = round(($grandTotal * $overheadPercent) / 100, 2);

    //         $totalToolCost = round($grandTotal + $profitAmount + $overheadAmount, 2);

    //         $quotation->update([
    //             'total_manufacturing_cos' => round($grandTotal, 2),
    //             'profit_percent' => $profitPercent,
    //             'overhead_percent' => $overheadPercent,
    //             'profit' => $profitAmount,
    //             'overhead' => $overheadAmount,
    //             'total_tool_cost' => $totalToolCost,
    //         ]);

    //         $quotation->update([
    //             'total_manufacturing_cos' => round($grandTotal, 2),
    //         ]);

    //         DB::commit();

    //         return redirect()->route('Viewquotation')
    //             ->with('success', 'Quotation Created Successfully');
    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         return back()->with('error', $e->getMessage());
    //     }
    // }


    public function storequotation(Request $request)
    {
        Log::info('Quotation Request:', $request->all());

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_name' => 'required|string',
            'date' => 'required|date',

            'items' => 'required|array|min:1',
            'items.*.Description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.material_type_id' => 'nullable|exists:material_types,id',
        ]);

        DB::beginTransaction();

        try {

            // 🔹 Financial Year
            $year  = now()->year;
            $month = now()->month;

            if ($month >= 4) {
                $financialYear = substr($year, 2, 2) . substr($year + 1, 2, 2);
            } else {
                $financialYear = substr($year - 1, 2, 2) . substr($year, 2, 2);
            }

            $lastQuotation = Quotation::where('admin_id', auth()->id())
                ->where('quotation_no', 'like', $financialYear . '-%')
                ->latest()
                ->first();

            $nextNumber = $lastQuotation
                ? ((int)explode('-', $lastQuotation->quotation_no)[1] + 1)
                : 1;

            $quotation_no = $financialYear . '-' . $nextNumber;

            $quotation = Quotation::create([
                'customer_id' => $request->customer_id,
                'quotation_no' => $quotation_no,
                'project_name' => $request->project_name,
                'date' => $request->date,
                'admin_id' => auth()->id(),
                'terms_conditions' => $request->terms_conditions,
                'overhead_percent' => (float)($request->overhead_percent ?? 0),
                'profit_percent' => (float)($request->profit_percent ?? 0),
            ]);

            // 🔹 Rates
            $rates = \DB::table('rates')->pluck('rate', 'name')->toArray();

            $grandTotal = 0;

            foreach ($request->items as $item) {

                $material = MaterialType::find($item['material_type_id'] ?? null);

                if (!$material) {
                    DB::rollBack();
                    return back()->with('error', 'Invalid Material');
                }

                // 🔹 BASIC VALUES
                $qty = (float)($item['qty'] ?? 1);
                $height = (float)($item['height'] ?? 0);

                // 🔹 MATERIAL COST (same as JS input)
                $materialCost = (float)($item['material_cost'] ?? 0);

                // 🔹 HOURS
                $vmcSoftHours = (float)($item['vmc_soft'] ?? 0);
                $vmcHardHours = (float)($item['vmc_hard'] ?? 0);

                // 🔹 RATES
                $vmcSoftRate = $rates['Vmc Soft'] ?? 0;
                $vmcHardRate = $rates['Vmc Hard'] ?? 0;

                // 🔹 COST
                $vmcSoftCost = $vmcSoftHours * $vmcSoftRate;
                $vmcHardCost = $vmcHardHours * $vmcHardRate;

                // 🔹 EDM (SYNC WITH JS)
                $edmQty = (float)($item['edm_qty'] ?? 0);
                $edmCost = $height * $edmQty * 6;

                // 🔹 WIRECUT (SYNC WITH JS)
                $wirecutInput = (float)($item['wirecut'] ?? 0);
                $wirecutCost = $wirecutInput * $height * 0.25;

                // 🔹 FINAL MACHINING COST (EXACT JS COPY)
                $machiningCost = (
                    $materialCost +
                    (float)($item['lathe'] ?? 0) +
                    (float)($item['mg'] ?? 0) +
                    (float)($item['rg'] ?? 0) +
                    (float)($item['cg'] ?? 0) +
                    (float)($item['sg'] ?? 0) +
                    $vmcSoftCost +
                    $vmcHardCost +
                    $edmCost +
                    $wirecutCost +
                    (float)($item['ht'] ?? 0)
                ) * $qty;

                $grandTotal += $machiningCost;

                // 🔹 SAVE ITEM
                $quotation->items()->create([
                    'description' => $item['Description'] ?? null,

                    'dia' => (float)($item['dia'] ?? 0),
                    'length' => (float)($item['length'] ?? 0),
                    'width' => (float)($item['width'] ?? 0),
                    'height' => (float)($item['height'] ?? 0),

                    'qty' => $qty,
                    'qty_in_kg' => (float)($item['qty_in_kg'] ?? 0),

                    'material_gravity' => (float)($item['gravity'] ?? 0),

                    'material_type_id' => $material->id,
                    'material' => $material->material_type,

                    'material_rate' => (float)($item['material_rate'] ?? 0),
                    'material_cost' => $materialCost,

                    'lathe' => (float)($item['lathe'] ?? 0),
                    'mg' => (float)($item['mg'] ?? 0),
                    'rg' => (float)($item['rg'] ?? 0),
                    'cg' => (float)($item['cg'] ?? 0),
                    'sg' => (float)($item['sg'] ?? 0),

                    'vmc_soft' => $vmcSoftCost,
                    'vmc_hard' => $vmcHardCost,

                    'edm_hole' => $edmCost,
                    'wirecut' => $wirecutCost,
                    'ht' => (float)($item['ht'] ?? 0),

                    'machining_cost' => $machiningCost,
                ]);
            }

            // 🔹 PROFIT + OVERHEAD
            $profit = ($grandTotal * ($request->profit_percent ?? 0)) / 100;
            $overhead = ($grandTotal * ($request->overhead_percent ?? 0)) / 100;

            $total = $grandTotal + $profit + $overhead;

            $quotation->update([
                'total_manufacturing_cos' => round($grandTotal, 2),
                'profit' => round($profit, 2),
                'overhead' => round($overhead, 2),
                'total_tool_cost' => round($total, 2),
            ]);

            DB::commit();

            return redirect()->route('Viewquotation')
                ->with('success', 'Quotation Created Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    public function editquotation($id)
    {
        $id = base64_decode($id);

        $quotation = Quotation::with('items')->findOrFail($id);
        $codes = Customer::all();
        $materialtype = MaterialType::all();

        $rates = Rate::where('admin_id', Auth::id())
            ->where('is_active', 1)
            ->pluck('rate', 'name');

        return view('Quotation.add', compact('quotation', 'codes', 'materialtype', 'rates'));
    }
    public function update(Request $request, $id)
    {
        $id = base64_decode($id);

        $request->validate([
            'customer_id' => 'required',
            'quotation_no' => 'required',
            'project_name' => 'required',
            'date'        => 'required|date',
            'items'       => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $quotation = Quotation::with('items')->findOrFail($id);

            // GET RATES (IMPORTANT)
            $rates = Rate::where('admin_id', Auth::id())
                ->where('is_active', 1)
                ->pluck('rate', 'name');

            /*  UPDATE HEADER */
            $quotation->update([
                'customer_id'      => $request->customer_id,
                'quotation_no'     => $request->quotation_no,
                'project_name'     => $request->project_name,
                'date'             => $request->date,
                'profit_percent'   => $request->profit_percent ?? 0,
                'overhead_percent' => $request->overhead_percent ?? 0,
                'terms_conditions' => $request->terms_conditions,
            ]);

            /*  DELETE OLD ITEMS  */
            $quotation->items()->delete();

            $grandTotal = 0;

            /*  INSERT ITEMS  */
            foreach ($request->items as $item) {

                // HOURS INPUT
                $vmcSoftHours = floatval($item['vmc_soft'] ?? 0);
                $vmcHardHours = floatval($item['vmc_hard'] ?? 0);

                // RATE FROM DB
                $vmcSoftRate = $rates['Vmc Soft'] ?? 0;
                $vmcHardRate = $rates['Vmc Hard'] ?? 0;

                // FINAL COST
                $vmcSoftCost = $vmcSoftHours * $vmcSoftRate;
                $vmcHardCost = $vmcHardHours * $vmcHardRate;

                // MACHINING COST
                $machiningCost = floatval($item['machining_cost'] ?? 0);
                $grandTotal += $machiningCost;

                $material = MaterialType::find($item['material_type_id'] ?? null);

                if (!$material) {
                    DB::rollBack();
                    return back()->with('error', 'Invalid Material');
                }

                $quotation->items()->create([
                    'description'    => $item['Description'] ?? null,
                    
                    'dia'            => floatval($item['dia'] ?? 0),
                    'length'         => floatval($item['length'] ?? 0),
                    'width'          => floatval($item['width'] ?? 0),
                    'height'         => floatval($item['height'] ?? 0),

                    'qty'            => floatval($item['qty'] ?? 1),
                    'qty_in_kg'      => floatval($item['qty_in_kg'] ?? 0),

                    'material_gravity' => (float)($item['gravity'] ?? 0),

                    'material_type_id' => $material->id,
                    'material' => $material->material_type,

                    'material_rate'  => floatval($item['material_rate'] ?? 0),
                    'material_cost'  => floatval($item['material_cost'] ?? 0),

                    'lathe'          => floatval($item['lathe'] ?? 0),
                    'mg'             => floatval($item['mg'] ?? 0),
                    'rg'             => floatval($item['rg'] ?? 0),
                    'cg'             => floatval($item['cg'] ?? 0),
                    'sg'             => floatval($item['sg'] ?? 0),

                    // FIXED (MAIN LOGIC)
                    'vmc_soft'       => $vmcSoftCost,
                    'vmc_hard'       => $vmcHardCost,

                    'edm_qty'        => floatval($item['edm_qty'] ?? 0),
                    'edm_hole'       => floatval($item['edm_hole'] ?? 0),
                    'ht'             => (float)($item['ht'] ?? 0),
                    'wirecut'        => floatval($item['wirecut'] ?? 0),

                    'machining_cost' => $machiningCost,
                ]);
            }

            /*  UPDATE TOTAL  */
            /*  PROFIT + OVERHEAD CALCULATION */
            $profit = ($grandTotal * ($request->profit_percent ?? 0)) / 100;
            $overhead = ($grandTotal * ($request->overhead_percent ?? 0)) / 100;

            $total = $grandTotal + $profit + $overhead;

            /*  UPDATE TOTAL  */
            $quotation->update([
                'total_manufacturing_cos' => round($grandTotal, 2),
                'profit' => round($profit, 2),
                'overhead' => round($overhead, 2),
                'total_tool_cost' => round($total, 2),
            ]);

            DB::commit();

            return redirect()->route('Viewquotation')
                ->with('success', 'Quotation Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    public function Viewquotation()
    {
        $quotations = Quotation::with(['customer'])
            ->where('admin_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Quotation.view', compact('quotations'));
    }
    public function printquotation($id)
    {
        $id = base64_decode($id);
        $quotation = Quotation::with('items', 'customer')->findOrFail($id);
        $adminId = Auth::id();
        $adminSetting = AdminSetting::where('admin_id', Auth::id())->first();
        $client = Client::where('login_id', $adminId)->first([
            'name',
            'phone_no',
            'email_id',
            'gst_no',
            'logo',
            'address'
        ]);

        $manufacturing = $quotation->total_manufacturing_cos;

        $profitAmount = ($manufacturing * $quotation->profit_percent) / 100;
        $overheadAmount = ($manufacturing * $quotation->overhead_percent) / 100;

        $totalToolCost = $manufacturing + $profitAmount + $overheadAmount;

        return view('Quotation.print', compact(
            'quotation',
            'client',
            'adminSetting',
            'profitAmount',
            'overheadAmount',
            'manufacturing',
            'totalToolCost'
        ));
    }
    public function destroy(string $id)
    {
        $id = base64_decode($id);
        $Quotation = Quotation::where('admin_id', Auth::id())->findOrFail($id);
        $Quotation->status = 0;
        $Quotation->save();
        $Quotation->delete();

        return redirect()->route('Viewquotation')->with('success', 'Quotation deleted successfully.');
    }
}
