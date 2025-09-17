<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\SetupSheet;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Setting;
use Illuminate\Http\Request;

class SetupSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function AddSetupSheet()
    {

        $codes = Customer::where('status', 1)   // फक्त active
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        $settings   = Setting::all();
        return view('SetupSheet.add', compact('codes', 'settings'));
    }

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

            // Hole Details (arrays)
            'holes'        => 'required|array',
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

        if ($request->hasFile('setup_image')) {
            $image = $request->file('setup_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('setup_images'), $imageName);
            $setupSheet->setup_image = $imageName;
        }

        $setupSheet->save();

        return redirect()->route('ViewSetupSheet')->with('success', 'SetupSheet created successfully.');
    }

    public function ViewSetupSheet()
    {
        $sheets = SetupSheet::orderBy('id', 'desc')->get();
        return view('SetupSheet.view', compact('sheets'));
    }

    public function editSetupSheet(string $encryptedId)
    {

        $id = base64_decode($encryptedId);
        $record = SetupSheet::findOrFail($id);
        $setupSheet = SetupSheet::findOrFail($id);
        $settings   = Setting::all();
        $id = base64_decode($encryptedId);
        $record = SetupSheet::findOrFail($id);
        $setupSheet = SetupSheet::findOrFail($id);
        $settings   = Setting::all();

        // Active customers + inactive customer 
        $codes = Customer::where('status', 1)
            ->orWhere('id', $setupSheet->customer_id)
            ->select('id', 'code', 'name')
            ->orderBy('id', 'desc')
            ->get();

        return view('SetupSheet.add', compact('setupSheet', 'codes', 'settings', 'record'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setupSheet = SetupSheet::findOrFail($id);

        // Validation
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

            'holes'        => 'required|array',
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

        // Update normal fields
        $setupSheet->update($validated);

        // Update image if uploaded
        if ($request->hasFile('setup_image')) {
            $image = $request->file('setup_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('setup_images');

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move new image
            $image->move($destinationPath, $imageName);

            // Delete old image if exists
            if ($setupSheet->setup_image && file_exists(public_path('setup_images/' . $setupSheet->setup_image))) {
                unlink(public_path('setup_images/' . $setupSheet->setup_image));
            }

            // Save new image name
            $setupSheet->setup_image = $imageName;
            $setupSheet->save();
        }

        return redirect()->route('ViewSetupSheet')->with('success', 'Setup Sheet updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $setup = SetupSheet::findOrFail($id);
        $setup->delete();
        return redirect()->route('ViewSetupSheet')->with('success', 'Branch deleted successfully.');
    }

    public function getCustomerParts($customerId)
    {
        $parts = WorkOrder::where('customer_id', $customerId)
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
                // PartCode = CustomerCode_ProjectID_PartNo
                'part_code' => ($wo->customer->code ?? '') . '_' . $wo->project_id . '_' . $wo->part,
                'part_description' => $wo->part_description,
                'size_in_x' => $wo->length,
                'size_in_y' => $wo->width,
                'size_in_z' => $wo->height,
                'e_time' => $wo->exp_time,
                'qty' => $wo->quantity,
                // Work order no = project_id
                'work_order_no' => $wo->project_id,
            ];
        });

        return response()->json($formatted);
    }
}
