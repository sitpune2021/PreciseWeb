<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
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

        return view('Quotation.add', compact('codes', 'materialtype', 'customers', 'quotation_no'));
    }
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

        ], [

            // Qty Messages
            'items.*.qty.required' => 'Please enter quantity.',
            'items.*.qty.numeric'  => 'Quantity must be a number.',
            'items.*.qty.min'      => 'Quantity must be at least 1.',

        ]);

        DB::beginTransaction();

        try {
            // 🔹 Generate Financial Year
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

            // 🔹 Get Last Number
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

            // $quotation_no = (Quotation::where('admin_id', auth()->id())->max('sr_no') ?? 0) + 1;

            $quotation = Quotation::create([
                'customer_id' => $request->customer_id,
                'quotation_no' => $quotation_no,
                'project_name' => $request->project_name,
                'date'        => $request->date,
                'admin_id'    => auth()->id(),
                'terms_conditions' => $request->terms_conditions, // nullable
                'overhead_percent' => (float)($request->overhead_percent ?? 0),
                'profit_percent'   => (float)($request->profit_percent ?? 0),

            ]);
            $grandTotal = 0;

            foreach ($request->items as $item) {

                // $material = MaterialType::findOrFail($item['material_type_id']);
                $material = MaterialType::find($item['material_type_id']);

                if (!$material) {
                    DB::rollBack();
                    return back()->with('error', 'Invalid Material selected for one of the items.');
                }

                $machiningCost = (float)($item['machining_cost'] ?? 0);
                $grandTotal += $machiningCost;

                $quotation->items()->create([
                    'description'   => $item['Description'] ?? null,
                    'dia'           => (float)($item['dia'] ?? 0),
                    'length'        => (float)($item['length'] ?? 0),
                    'width'         => (float)($item['WIDTH'] ?? 0),
                    'height'        => (float)($item['HEIGHT'] ?? 0),
                    'qty'           => (float)($item['qty'] ?? 1),
                    'qty_in_kg'     => (float)($item['qty_in_kg'] ?? 0),

                    'material'      => $material->material_type,
                    'material_type_id' => $material->id,
                    'material_rate' => (float)($item['material_rate'] ?? 0),
                    'material_cost' => (float)($item['material_cost'] ?? 0),

                    'lathe'         => (float)($item['lathe'] ?? 0),
                    'mg'            => (float)($item['mg'] ?? 0),
                    'rg'            => (float)($item['rg'] ?? 0),
                    'cg'            => (float)($item['cg'] ?? 0),
                    'sg'            => (float)($item['sg'] ?? 0),
                    'vmc_soft'      => (float)($item['vmc_soft'] ?? 0),
                    'vmc_hard'      => (float)($item['vmc_hard'] ?? 0),
                    'edm_hole'      => (float)($item['edm_hole'] ?? 0),
                    'wirecut'       => (float)($item['wirecut'] ?? 0),
                    'ht'            => (float)($item['h_t'] ?? 0),
                    'material_gravity'  => $item['material_gravity'] ?? null,

                    'machining_cost' => $machiningCost,
                ]);
            }

            $profitPercent = (float)($request->profit_percent ?? 0);
            $overheadPercent = (float)($request->overhead_percent ?? 0);

            $profitAmount = round(($grandTotal * $profitPercent) / 100, 2);
            $overheadAmount = round(($grandTotal * $overheadPercent) / 100, 2);

            $totalToolCost = round($grandTotal + $profitAmount + $overheadAmount, 2);

            $quotation->update([
                'total_manufacturing_cos' => round($grandTotal, 2),
                'profit_percent' => $profitPercent,
                'overhead_percent' => $overheadPercent,
                'profit' => $profitAmount,
                'overhead' => $overheadAmount,
                'total_tool_cost' => $totalToolCost,
            ]);

            $quotation->update([
                'total_manufacturing_cos' => round($grandTotal, 2),
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

            // $quotation = Quotation::findOrFail($id);
            $quotation = Quotation::with('items')->findOrFail($id);


            /*  UPDATE HEADER */
            $quotation->update([
                'customer_id'      => $request->customer_id,
                'quotation_no'     => $request->quotation_no,
                'project_name'     => $request->project_name,
                'date'             => $request->date,
                'profit'           => $request->profit ?? 0,
                'overhead'         => $request->overhead ?? 0,
                'terms_conditions' => $request->terms_conditions,
            ]);

            /*  DELETE OLD ITEMS  */
            $quotation->items()->delete();

            $grandTotal = 0;

            /*  INSERT ITEMS  */
            foreach ($request->items as $item) {
                // Use floatval() to make sure null or empty strings are treated as 0
                $machiningCost = floatval($item['machining_cost'] ?? 0);
                $grandTotal += $machiningCost;

                $quotation->items()->create([
                    'description'    => $item['Description'] ?? null,
                    'material_type_id' => $item['material_type_id'] ?? null,
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

            /*  UPDATE TOTAL  */
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
