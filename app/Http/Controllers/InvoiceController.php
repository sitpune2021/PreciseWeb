<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{

    public function AddInvoice()
    {
        $user = Auth::user();
        $client = Client::where('login_id', $user->id)->first();

        return view('invoices.add', compact('client'));
    }

    public function StoreInvoice(Request $request)
    {
        // dd($request);

        $request->validate([
    'invoice_no'        => 'required|unique:invoices,invoice_no',
    'invoice_date'      => 'required|date',
    'buyer_name'        => 'required|string|max:255',
    'description'       => 'required|string|max:255',
    'qty'               => 'required|integer|min:1',
    'rate'              => 'required|numeric|min:0',
        'our_ch_no'         => 'nullable|string|max:255',
    'our_ch_no_date'    => 'nullable|date',
    'y_ch_no'           => 'nullable|string|max:255',
    'y_ch_no_date'      => 'nullable|date',
    'p_o_no'            => 'nullable|string|max:255',
    'p_o_no_date'       => 'nullable|date',
    'description_fast'  => 'nullable|string|max:255',
    'gst_no'            => 'nullable|string|max:50',
    'msme_no'           => 'nullable|string|max:50',

    // Buyer & Consignee
    
    'buyer_address'     => 'nullable|string',
    'consignee_name'    => 'nullable|string|max:255',
    'consignee_address' => 'nullable|string',

    // Contacts
    'ki_attn_name'      => 'nullable|string|max:255',
    '_ki_contact_no'    => 'nullable|string|max:20',
    'ki_gst'            => 'nullable|string|max:50',
    'kind_attn_name'    => 'nullable|string|max:255',
    'contact_no'        => 'nullable|string|max:20',
    'kind_gst'          => 'nullable|string|max:50',

    // Items
   
    'hsn_code'          => 'nullable|string|max:50',
 
    'amount'            => 'nullable|numeric|min:0',
    'hrs_per_job'       => 'nullable|numeric|min:0',
    'cost'              => 'nullable|numeric|min:0',

    // Totals
    'sub_total'         => 'nullable|numeric|min:0',
    'sgst'              => 'nullable|numeric|min:0',
    'cgst'              => 'nullable|numeric|min:0',
    'igst'              => 'nullable|numeric|min:0',
    'total_tax_payable' => 'nullable|numeric|min:0',
    'grand_total'       => 'nullable|numeric|min:0',

    // Others
    'declaration'       => 'nullable|string|max:255',
    'note'              => 'nullable|string',
    'bank_details'      => 'nullable|string',
    'amount_in_words'   => 'nullable|string|max:255',
]);

$user   = Auth::user();
$client = Client::where('login_id', $user->id)->firstOrFail();

$amount    = $request->qty * $request->rate;
$subTotal  = $amount;
$total     = $subTotal + ($request->cgst ?? 0) + ($request->sgst ?? 0) + ($request->igst ?? 0);

$data               = $request->all();
$data['client_id']  = $client->id;
$data['amount']     = $amount;
$data['sub_total']  = $subTotal;
$data['grand_total'] = $total;

Invoice::create($data);




     
     

 



        $user   = Auth::user();
        $client = Client::where('login_id', $user->id)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found for this user.');
        }

            $amount = $request->qty * $request->rate;
                $subTotal = $amount;
                $total = $subTotal + ($request->cgst ?? 0) + ($request->sgst ?? 0) + ($request->igst ?? 0);

                $data = $request->except(['amount', 'sub_total', 'grand_total']);
                $data['client_id'] = $client->id;
                $data['amount'] = $amount;
                $data['sub_total'] = $subTotal;
                $data['grand_total'] = $total;


        Invoice::create($data);

        return redirect()->route('ViewInvoice')->with('success', 'Invoice created successfully.');
    }

    public function ViewInvoice()
    {
        $invoices = Invoice::with('client')->latest()->get();
        return view('invoices.view', compact('invoices'));
    }

    public function editInvoice(string $encryptedId)
    {
        $id = base64_decode($encryptedId);

        $invoice = Invoice::findOrFail($id);

        $user   = Auth::user();
        $client = Client::where('login_id', $user->id)->first();

        return view('invoices.add', compact('invoice', 'client'));
    }

   public function updateInvoice(Request $request, string $encryptedId)
{
    $id = base64_decode($encryptedId);
    $invoice = Invoice::findOrFail($id);

    $request->validate([
        'invoice_no'   => 'required|unique:invoices,invoice_no,' . $invoice->id,
        'invoice_date' => 'required|date',
        'buyer_name'   => 'required|string|max:255',
        'description'  => 'required|string|max:255', // ✔ description वापरा
        'qty'          => 'required|integer|min:1',
        'rate'         => 'required|numeric|min:0',
    ]);

    $user   = Auth::user();
    $client = Client::where('login_id', $user->id)->firstOrFail();

    $amount    = $request->qty * $request->rate;
    $subTotal  = $amount;
    $total     = $subTotal + ($request->cgst ?? 0) + ($request->sgst ?? 0) + ($request->igst ?? 0);

    $data               = $request->all();
    $data['client_id']  = $client->id;
    $data['amount']     = $amount;
    $data['sub_total']  = $subTotal;
    $data['grand_total'] = $total;  

    $invoice->update($data);

    return redirect()->route('ViewInvoice')->with('success', 'Invoice updated successfully.');
}

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('ViewInvoice')->with('success', 'Invoice deleted successfully.');
    }

    public function printInvoice($encryptedId)
    {
        $id = base64_decode($encryptedId);
        $invoice = Invoice::with('client')->findOrFail($id);

        return view('invoices.print', compact('invoice'));
    }
}
