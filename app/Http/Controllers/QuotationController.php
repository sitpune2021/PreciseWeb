<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function Addquotation()
    {
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

        return view('Quotation.add', compact('codes', 'materialtype', 'customers'));
    }
    public function storequotation(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required',
            'quotation_no' => 'required',
            'project_name' => 'required',
            'date'         => 'required|date',

            'items'        => 'required|array|min:1'
        ]);


        DB::beginTransaction();

        try {

            $srNo = (Quotation::where('admin_id', auth()->id())->max('sr_no') ?? 0) + 1;

            $quotation = Quotation::create([
                'customer_id' => $request->customer_id,
                'quotation_no' => $request->quotation_no,
                'project_name' => $request->project_name,
                'date'        => $request->date,
                'sr_no'       => $srNo,
                'admin_id'    => auth()->id(),
                'terms_conditions' => $request->terms_conditions
            ]);

            $grandTotal = 0;

            foreach ($request->items as $item) {

                $material = MaterialType::findOrFail($item['material_type_id']);
                /* INPUT */
                $dia    = (float)($item['dia'] ?? 0);
                $len    = (float)($item['length'] ?? 0);
                $wid    = (float)($item['WIDTH'] ?? 0);
                $hei    = (float)($item['HEIGHT'] ?? 0);
                $qty    = (float)($item['qty'] ?? 1);

                $rate    = (float)($item['material_rate'] ?? 0);
                $gravity = (float)($item['gravity'] ?? 0);

                $lathe   = (float)($item['lathe'] ?? 0);
                $vmcSoft = (float)($item['vmc_soft'] ?? 0);
                $vmcHard = (float)($item['vmc_hard'] ?? 0);

                $edmHole = (float)($item['edm_hole'] ?? 0);
                $wirecut = (float)($item['wirecut'] ?? 0);

                /* QTY IN KG */
                $cylWt = (pi() * pow($dia / 2, 2) * $hei / 1000000) * $gravity;
                $boxWt = ($len * $wid * $hei / 1000000) * $gravity;
                $qtyKg = $cylWt + $boxWt;

                /* MATERIAL COST */
                $materialCost = ($qtyKg * $rate) * 1.30;

                /* MG RG SG (Excel Match) */
                $mg = ((($len * $hei + $wid * $hei) * 2 * 0.5) / 100)
                    + (($len * $wid) * 2 * 0.5 / 100);

                $rg = ($len * $wid) * 2 * 0.3 / 100;

                $sg = ((($len * $hei + $wid * $hei) * 2) / 100)
                    + (($len * $wid) * 2 / 100);

                /* EXTRA */
                $edmCost     = $hei * $edmHole * 6;
                $htCost      = $qtyKg * 80;
                $wirecutCost = $wirecut * $hei * 0.25;


                /* MACHINING COST */
                $machiningCost =
                    (
                        $lathe +
                        $mg +
                        $rg +
                        $sg +
                        $edmCost +
                        $wirecutCost +
                        $htCost +
                        $vmcSoft +
                        $vmcHard
                    ) * $qty;

                $grandTotal += $machiningCost;

                /* SAVE ITEM */
                $quotation->items()->create([
                    'description'   => $item['Description'] ?? null,
                    'dia'           => $dia,
                    'length'        => $len,
                    'width'         => $wid,
                    'height'        => $hei,
                    'qty'           => $qty,
                    'qty_in_kg'     => round($qtyKg, 3),
                    'material'      => $material->material_type, // ✅ text
                    'material_type_id' => $material->id,
                    'material_rate' => $rate,
                    'material_cost' => round($materialCost, 2),
                    'lathe'         => $lathe,
                    'mg'            => round($mg, 2),
                    'rg'            => round($rg, 2),
                    'sg'            => round($sg, 2),
                    'vmc_soft'      => $vmcSoft,
                    'vmc_hard'      => $vmcHard,
                    'edm_hole'      => $edmHole,
                    'wirecut'       => $wirecut,
                    'ht' => round($htCost, 2),
                    'machining_cost' => round($machiningCost, 2),

                ]);
            }

            $quotation->update([
                'total_manufacturing_cos' => round($grandTotal, 2),
                'profit'   => round($grandTotal * 0.10, 2),
                'overhead' => round($grandTotal * 0.05, 2),
            ]);

            DB::commit();
            return redirect()->route('Viewquotation')->with('success', 'Quotation Created');
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

        return view('Quotation.add', compact('quotation', 'codes', 'materialtype'));
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

            $quotation = Quotation::findOrFail($id);

            /* ===== UPDATE HEADER ===== */
            $quotation->update([
                'customer_id'      => $request->customer_id,
                'quotation_no'     => $request->quotation_no,
                'project_name'     => $request->project_name,
                'date'             => $request->date,
                'profit'           => $request->profit ?? 0,
                'overhead'         => $request->overhead ?? 0,
                'terms_conditions' => $request->terms_conditions,
            ]);

            /* ===== DELETE OLD ITEMS ===== */
            $quotation->items()->delete();

            $grandTotal = 0;

            /* ===== INSERT ITEMS ===== */
            foreach ($request->items as $item) {
                // Use floatval() to make sure null or empty strings are treated as 0
                $machiningCost = floatval($item['machining_cost'] ?? 0);
                $grandTotal += $machiningCost;

                $quotation->items()->create([
                    'description'    => $item['Description'] ?? null,
                    'dia'            => $item['dia'] ?? null,
                    'length'         => $item['length'] ?? null,
                    'width'          => $item['WIDTH'] ?? null,
                    'height'         => $item['HEIGHT'] ?? null,
                    'qty'            => $item['qty'] ?? 1,
                    'qty_in_kg'      => $item['qty_in_kg'] ?? null,
                    'material_rate'  => $item['material_rate'] ?? null,
                    'material_cost'  => $item['material_cost'] ?? null,
                    'lathe'          => $item['lathe'] ?? null,
                    'mg'             => $item['mg'] ?? null,
                    'rg'             => $item['rg'] ?? null,
                    'cg'             => $item['cg'] ?? null,
                    'sg'             => $item['sg'] ?? null,
                    'vmc_soft'       => $item['vmc_soft'] ?? null,
                    'vmc_hard'       => $item['vmc_hard'] ?? null,
                    'edm_qty'        => $item['edm_qty'] ?? null,
                    'edm_hole'       => $item['edm_hole'] ?? null,
                    'ht'             => $item['h_t'] ?? null,
                    'wirecut'        => $item['wirecut'] ?? null,
                    'machining_cost' => $machiningCost,
                ]);
            }

            /* ===== UPDATE TOTAL ===== */
            $quotation->update([
                'total_manufacturing_cos' => $grandTotal
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
        $client = Client::where('login_id', $adminId)->first([
            'name',
            'phone_no',
            'email_id',
            'gst_no',
            'logo',
            'address'
        ]);

        return view('Quotation.print', compact('quotation', 'client'));
    }
}
