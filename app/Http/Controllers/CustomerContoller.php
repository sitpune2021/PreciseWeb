<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomerContoller extends Controller
{
    public function AddCustomer()
    {
        return view('Customer.add');
    }

    public function storeCustomer(Request $request)
    {
        $request->merge([
            'gst_no' => strtoupper($request->input('gst_no')),
        ]);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers')->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'contact_person' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z.\s]+$/'
            ],
            'phone_no' => [
                'nullable',
                'digits:10',
                'regex:/^[0-9]{10}$/',
                Rule::unique('customers')->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'email_id' => [
                'nullable',
                'email',
                'max:40',
            ],
            'gst_no' => [
                'nullable',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('customers')->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'address' => 'nullable|string',
        ]);

        // Generate customer code
        $customer_name_words = explode(' ', trim($request->input('name')));
        if (count($customer_name_words) == 1) {
            $code = strtoupper(Str::substr($customer_name_words[0], 0, 3));
        } elseif (count($customer_name_words) == 2) {
            $code = strtoupper(Str::substr($customer_name_words[0], 0, 2) . Str::substr($customer_name_words[1], 0, 1));
        } else {
            $code = strtoupper(Str::substr($customer_name_words[0], 0, 1) .
                Str::substr($customer_name_words[1], 0, 1) .
                Str::substr($customer_name_words[2], 0, 1));
        }
        $adminId = Auth::id();

        // last serial get करा
        $lastSerial = Customer::where('admin_id', $adminId)
            ->max('customer_srno');

        $nextSerial = $lastSerial ? $lastSerial + 1 : 1;
        Customer::create([
            'admin_id'        => $adminId,
            'login_id'        => 0,
            'name'            => $request->name,
            'code'            => $code,
            'email_id'        => $request->email_id,
            'contact_person'  => $request->contact_person,
            'phone_no'        => $request->phone_no,
            'gst_no'          => $request->gst_no,
            'address'         => $request->address,
            'status'          => 1,
            'customer_srno' => $nextSerial,   
        ]);

        return redirect()->route('ViewCustomer')->with('success', 'Customer created successfully.');
    }

    public function ViewCustomer(Request $request)
    {
        $query = Customer::where('admin_id', Auth::id())
            ->orderBy('id', 'desc');

        // Financial Year Filter
        if ($request->filled('financial_year')) {
            $years = explode('-', $request->financial_year);
            $startYear = $years[0];
            $endYear   = $startYear + 1;

            $startDateFY = $startYear . '-04-01 00:00:00';
            $endDateFY   = $endYear . '-03-31 23:59:59';

            $query->whereBetween('created_at', [$startDateFY, $endDateFY]);
        }

        // Custom Date Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . " 00:00:00",
                $request->end_date . " 23:59:59"
            ]);
        }

        $customer = $query->get();

        return view('Customer.view', compact('customer'));
    }

    public function edit(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $customer = Customer::where('admin_id', Auth::id())->findOrFail($id);
        return view('Customer.add', compact('customer'));
    }

    public function update(Request $request, string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers', 'name')
                    ->ignore($id)
                    ->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'code' => [
                'nullable',
                Rule::unique('customers', 'code')
                    ->ignore($id)
                    ->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'contact_person' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z.\s]+$/',
            ],
            'phone_no' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers', 'phone_no')
                    ->ignore($id)
                    ->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'email_id' => [
                'nullable',
                'email',
                'max:40',
            ],
            'gst_no' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers', 'gst_no')
                    ->ignore($id)
                    ->where(fn($query) => $query->where('admin_id', Auth::id())),
            ],
            'address' => 'nullable|string',
        ]);

        $customer = Customer::where('admin_id', Auth::id())->findOrFail($id);

        $validated['admin_id'] = Auth::id();

        $customer->update($validated);

        return redirect()->route('ViewCustomer')->with('success', 'Customer updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        $id = base64_decode($encryptedId);
        $customer = Customer::where('admin_id', Auth::id())->findOrFail($id);
        $customer->status = 0;
        $customer->save();
        $customer->delete();

        return redirect()->route('ViewCustomer')->with('success', 'Customer deleted successfully.');
    }

    public function updateCustomerStatus(Request $request)
    {
        $customer = Customer::where('admin_id', Auth::id())->findOrFail($request->id);
        $customer->status = $request->has('status') ? 1 : 0;
        $customer->save();

        return back()->with('success', 'Status updated!');
    }

    public function importCustomers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        $ext  = $file->getClientOriginalExtension();

        $rows = [];
        if ($ext == 'csv') {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
        }

        $duplicates = [];

        foreach ($rows as $key => $row) {
            if ($key === 0) continue;
            if (empty(array_filter($row))) continue;

            $name    = $row[0] ?? null;
            $email   = $row[1] ?? null;
            $code    = $row[2] ?? null;
            $person  = $row[3] ?? null;
            $phone   = $row[4] ?? null;
            $address = $row[5] ?? null;
            $gst_no  = $row[6] ?? null;

            if (!$name) continue;

            // Check duplicate per admin
            $existing = Customer::where('name', $name)
                ->where('admin_id', Auth::id())
                ->first();

            if ($existing) {
                $duplicates[] = $name;
                continue;
            }

            if (!$code) {
                $nameWords = explode(' ', $name);
                if (count($nameWords) == 1) {
                    $code = strtoupper(substr($nameWords[0], 0, 3));
                } elseif (count($nameWords) == 2) {
                    $code = strtoupper(substr($nameWords[0], 0, 2) . substr($nameWords[1], 0, 1));
                } else {
                    $code = strtoupper(substr($nameWords[0], 0, 1) .
                        substr($nameWords[1], 0, 1) .
                        substr($nameWords[2], 0, 1));
                }
            }

            $phone = substr(preg_replace('/[^0-9]/', '', $phone ?? ''), 0, 15);

            if ($gst_no && !preg_match('/^[0-9A-Z]{15}$/', $gst_no)) {
                $gst_no = null;
            }

            Customer::create([
                'admin_id'       => Auth::id(),
                'name'           => $name,
                'email_id'       => $email,
                'code'           => $code,
                'address'        => $address,
                'phone_no'       => $phone,
                'contact_person' => $person,
                'gst_no'         => $gst_no,
                'status'         => 1
            ]);
        }

        $message = !empty($duplicates)
            ? 'Customers imported successfully! But these names already exist: ' . implode(', ', $duplicates)
            : 'Customers imported successfully!';

        return redirect()->back()->with('success', $message);
    }

    public function exportSample()
    {
        $filePath = public_path('assets/excel/PRECISE_ENGINEERING.xlsx');

        if (file_exists($filePath)) {
            $headers = [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="sample_customer.xlsx"',
                'Content-Length'      => filesize($filePath),
            ];

            return response()->make(file_get_contents($filePath), 200, $headers);
        }

        abort(404, 'File not found.');
    }
}
