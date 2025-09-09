<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    

    /**
     * Show the form for creating a new invoice.
     */
     

    public function AddInvoice()
{
    $user = Auth::user();
    $client = Client::where('login_id', $user->id)->first();

    return view('invoices.add', compact('client'));
}



    /**
     * Store a newly created invoice in storage.
     */
    public function StoreInvoice(Request $request)
{
    $request->validate([
        'invoice_no'     => 'required|unique:invoices,invoice_no',
        'invoice_date'   => 'required|date',
        'buyer_name'     => 'required|string|max:255',
        'particulars'    => 'required|string|max:255',
        'qty'            => 'required|integer|min:1',
        'rate'           => 'required|numeric|min:0',
    ]);

    
    $user   = Auth::user();
    $client = Client::where('login_id', $user->id)->first();

    if (!$client) {
        return redirect()->back()->with('error', 'Client not found for this user.');
    }

    // amount calculate
    $amount = $request->qty * $request->rate;

    $requestData              = $request->all();
    $requestData['client_id'] = $client->id; // auto client_id assign
    $requestData['amount']    = $amount;
    $requestData['sub_total'] = $amount;

    Invoice::create($requestData);

    return redirect()->route('ViewInvoice')->with('success', 'Invoice created successfully.');
}

    /**
     * Display the specified invoice.
     */
    public function ViewInvoice()
{
    $invoices = Invoice::with('client')->latest()->get();
    return view('invoices.view', compact('invoices'));
}


    /**
     * Show the form for editing the specified invoice.
     */
   

   public function editInvoice(string $encryptedId)
{
    $id = base64_decode($encryptedId);

    $invoice = Invoice::findOrFail($id);

    $user   = Auth::user();
    $client = Client::where('login_id', $user->id)->first();

    return view('invoices.add', compact('invoice', 'client'));
}




    /**
     * Update the specified invoice in storage.
     */
    public function updateInvoice(Request $request, string $encryptedId)
{
    $id = base64_decode($encryptedId);
    $invoice = Invoice::findOrFail($id);

     
    $request->validate([
        'invoice_no'   => 'required|unique:invoices,invoice_no,' . $invoice->id,
        'invoice_date' => 'required|date',
        'buyer_name'   => 'required|string|max:255',
        'particulars'  => 'required|string|max:255',
        'qty'          => 'required|integer|min:1',
        'rate'         => 'required|numeric|min:0',
    ]);

    $user   = Auth::user();
    $client = Client::where('login_id', $user->id)->firstOrFail();

    $amount = $request->qty * $request->rate;

$data               = $request->all();
$data['client_id']  = $client->id;
$data['amount']     = $amount;
$data['sub_total']  = $amount;
$data['total_amount'] = $amount + ($request->cgst ?? 0) + ($request->sgst ?? 0) + ($request->igst ?? 0);

$invoice->update($data);

    return redirect()->route('ViewInvoice')->with('success', 'Invoice updated successfully.');
}

    /**
     * Remove the specified invoice from storage.
     */
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
