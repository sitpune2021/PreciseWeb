<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\SetupSheet;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetupSheetController extends Controller
{
    /**
     * Add Setup Sheet Form
     */
    public function AddSetupSheet()
    {
        $codes = Customer::where('status', 1)
            ->where('admin_id', Auth::id()) // Only current admin
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $settings = Setting::where('admin_id', Auth::id())->get(); // Only current admin
        return view('SetupSheet.add', compact('codes', 'settings'));
    }

    /**
     * Store Setup Sheet
     */
    public function storeSetupSheet(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'part_code'     => 'required|string|max:255',
            'work_order_no' => 'required|string|max:255',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',

            'size_in_x' => 'nullable|numeric|min:0',
            'size_in_y' => 'nullable|numeric|min:0',
            'size_in_z' => 'nullable|numeric|min:0',

            'setting'   => 'required|string|max:255',
            'e_time'    => 'required|string|max:255',

            'x_refer'  => 'required|string|max:255',
            'y_refer'  => 'required|string|max:255',
            'z_refer'  => 'required|string|max:255',
            'clamping' => 'required|string|max:255',

            'qty' => ['required', 'integer', 'min:1'],

            // Hole Details
            'holes'        => 'nullable|array',
            'holes.*'      => 'required|numeric|min:0',
            'hole_x'       => 'required|array',
            'hole_x.*'     => 'required|numeric|min:0',
            'hole_y'       => 'required|array',
            'hole_y.*'     => 'required|numeric|min:0',
            'hole_dia'     => 'required|array',
            'hole_dia.*'   => 'required|numeric|min:0',
            'hole_depth'   => 'required|array',
            'hole_depth.*' => 'required|numeric|min:0',

            // Image
            'setup_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $setupSheet = new SetupSheet($validated);
        $setupSheet->admin_id = Auth::id(); // Assign current admin

        if ($request->hasFile('setup_image')) {
            $image = $request->file('setup_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('setup_images'), $imageName);
            $setupSheet->setup_image = $imageName;
        }

        $setupSheet->save();

        return redirect()->route('ViewSetupSheet')->with('success', 'SetupSheet created successfully.');
    }

    /**
     * View All Setup Sheets
     */
    public function ViewSetupSheet()
    {
        $sheets = SetupSheet::where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
        return view('SetupSheet.view', compact('sheets'));
    }

    /**
     * Edit Setup Sheet
     */
    public function editSetupSheet(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $record = SetupSheet::where('admin_id', Auth::id())->findOrFail($id);
        $setupSheet = $record;
        $settings = Setting::where('admin_id', Auth::id())->get();

        // Active customers + current record customer
        $codes = Customer::where(function ($q) use ($setupSheet) {
            $q->where('status', 1)
                ->where('admin_id', Auth::id())
                ->orWhere('id', $setupSheet->customer_id);
        })
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        return view('SetupSheet.add', compact('setupSheet', 'codes', 'settings', 'record'));
    }

    /**
     * Update Setup Sheet
     */
    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setupSheet = SetupSheet::where('admin_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'customer_id'   => 'nullable|exists:customers,id',
            'part_code'     => 'required|string|max:255',
            'work_order_no' => 'required|string|max:255',
            'date'          => 'required|date',
            'description'   => 'nullable|string|max:500',

            'size_in_x' => 'nullable|numeric|min:0',
            'size_in_y' => 'nullable|numeric|min:0',
            'size_in_z' => 'nullable|numeric|min:0',

            'setting'   => 'required|string|max:255',
            'e_time'    => 'required|string|max:255',

            'x_refer'  => 'required|string|max:255',
            'y_refer'  => 'required|string|max:255',
            'z_refer'  => 'required|string|max:255',
            'clamping' => 'required|string|max:255',

            'qty'         => ['required', 'integer', 'min:1'],

            'holes'        => 'nullable|array',
            'holes.*'      => 'required|numeric|min:0',
            'hole_x'       => 'required|array',
            'hole_x.*'     => 'required|numeric|min:0',
            'hole_y'       => 'required|array',
            'hole_y.*'     => 'required|numeric|min:0',
            'hole_dia'     => 'required|array',
            'hole_dia.*'   => 'required|numeric|min:0',
            'hole_depth'   => 'required|array',
            'hole_depth.*' => 'required|numeric|min:0',

            // Image validation
            'setup_image'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $setupSheet->update($validated);

        if ($request->hasFile('setup_image')) {
            $image = $request->file('setup_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('setup_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $imageName);

            if ($setupSheet->setup_image && file_exists(public_path('setup_images/' . $setupSheet->setup_image))) {
                unlink(public_path('setup_images/' . $setupSheet->setup_image));
            }

            $setupSheet->setup_image = $imageName;
            $setupSheet->save();
        }

        return redirect()->route('ViewSetupSheet')->with('success', 'Setup Sheet updated successfully.');
    }

    /**
     * Delete Setup Sheet
     */
    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setup = SetupSheet::where('admin_id', Auth::id())->findOrFail($id);
        $setup->delete();
        return redirect()->route('ViewSetupSheet')->with('success', 'Setup Sheet deleted successfully.');
    }

    /**
     * Get Customer Parts
     */
    public function getCustomerParts($customerId)
    {
        $parts = WorkOrder::where('customer_id', $customerId)
            ->where('admin_id', Auth::id())
            ->select(
                'id',
                'project_id',
                'part',
                'part_description',
                'customer_id',
                'length',
                'width',
                'height',
                'exp_time',
                'quantity'
            )
            ->with('customer:id,code')
            ->get();

        $formatted = $parts->map(function ($wo) {
            return [
                'id' => $wo->id,
                'part' => $wo->part,
                'part_code' => ($wo->customer->code ?? '') . '_' . $wo->project_id . '_' . $wo->part,
                'part_description' => $wo->part_description,
                'size_in_x' => $wo->length,
                'size_in_y' => $wo->width,
                'size_in_z' => $wo->height,
                'e_time' => $wo->exp_time,
                'qty' => $wo->quantity,
                'work_order_no' => $wo->project_id,
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Trash Setup Sheets
     */
    public function trash()
    {
        $trashedSheets = SetupSheet::onlyTrashed()
            ->where('admin_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        $sheets = SetupSheet::where('admin_id', Auth::id())->get();

        return view('SetupSheet.trash', compact('trashedSheets', 'sheets'));
    }

    /**
     * Restore Setup Sheet
     */
    public function restore($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $sheet = SetupSheet::withTrashed()
            ->where('admin_id', Auth::id())
            ->findOrFail($id);

        $exists = SetupSheet::where('part_code', $sheet->part_code)
            ->where('admin_id', Auth::id())
            ->whereNull('deleted_at')
            ->exists();

        $sheet->restore();

        if ($exists) {
            return redirect()->route('editSetupSheet', base64_encode($sheet->id))
                ->with('success', "Setup Sheet '{$sheet->part_code}' already exists. Redirected to Edit Page.");
        }

        return redirect()->route('ViewSetupSheet')
            ->with('success', "Setup Sheet '{$sheet->part_code}' restored successfully.");
    }
}
