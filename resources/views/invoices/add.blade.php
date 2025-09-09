@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($invoice) ? 'Edit Invoice' : 'Add Invoice' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($invoice) ? route('updateInvoice', base64_encode($invoice->id)) : route('StoreInvoice') }}" method="POST">
                        @csrf
                        @if(isset($invoice))
                        @method('PUT')
                        @endif
                        <div class="row g-3">
                          <!-- Invoice Info -->
                            <div class="col-md-3">
                                <label class="form-label">Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control"
                                    value="{{ old('invoice_no', $invoice->invoice_no ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control"
                                    value="{{ old('invoice_date', $invoice->invoice_date ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Our CH No</label>
                                <input type="text" name="our_ch_no" class="form-control"
                                    value="{{ old('our_ch_no', $invoice->our_ch_no ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Our CH No Date</label>
                                <input type="date" name="our_ch_no_date" class="form-control"
                                    value="{{ old('our_ch_no_date', $invoice->our_ch_no_date ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Your CH No</label>
                                <input type="text" name="y_ch_no" class="form-control"
                                    value="{{ old('y_ch_no', $invoice->y_ch_no ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Your CH No Date</label>
                                <input type="date" name="y_ch_no_date" class="form-control"
                                    value="{{ old('y_ch_no_date', $invoice->y_ch_no_date ?? '') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">PO No</label>
                                <input type="text" name="p_o_no" class="form-control"
                                    value="{{ old('p_o_no', $invoice->p_o_no ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PO Date</label>
                                <input type="date" name="p_o_no_date" class="form-control"
                                    value="{{ old('p_o_no_date', $invoice->p_o_no_date ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Description Fast</label>
                                <input type="text" name="description_fast" class="form-control"
                                    value="{{ old('description_fast', $invoice->description_fast ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">GST No</label>
                                <input type="text" name="gst_no" class="form-control"
                                    value="{{ old('gst_no', $invoice->gst_no ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">MSME No</label>
                                <input type="text" name="msme_no" class="form-control"
                                    value="{{ old('msme_no', $invoice->msme_no ?? '') }}">
                            </div>
                            

                            <!-- Buyer -->
                            <div class="col-md-4">
                                <label class="form-label">Buyer Name</label>
                                <input type="text" name="buyer_name" class="form-control"
                                    value="{{ old('buyer_name', $invoice->buyer_name ?? '') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Buyer Address</label>
                                <textarea name="buyer_address" class="form-control">{{ old('buyer_address', $invoice->buyer_address ?? '') }}</textarea>
                            </div>

                            <!-- Consignee -->
                            <div class="col-md-4">
                                <label class="form-label">Consignee Name</label>
                                <input type="text" name="consignee_name" class="form-control"
                                    value="{{ old('consignee_name', $invoice->consignee_name ?? '') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Consignee Address</label>
                                <textarea name="consignee_address" class="form-control">{{ old('consignee_address', $invoice->consignee_address ?? '') }}</textarea>
                            </div>

                            <!-- Extra Contact -->
                            <div class="col-md-4">
                                <label class="form-label">KI Attn Name</label>
                                <input type="text" name="ki_attn_name" class="form-control"
                                    value="{{ old('ki_attn_name', $invoice->ki_attn_name ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">KI Contact No</label>
                                <input type="text" name="_ki_contact_no" class="form-control"
                                    value="{{ old('_ki_contact_no', $invoice->_ki_contact_no ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">KI GST</label>
                                <input type="text" name="ki_gst" class="form-control"
                                    value="{{ old('ki_gst', $invoice->ki_gst ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kind Attn Name</label>
                                <input type="text" name="kind_attn_name" class="form-control"
                                    value="{{ old('kind_attn_name', $invoice->kind_attn_name ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact No</label>
                                <input type="text" name="contact_no" class="form-control"
                                    value="{{ old('contact_no', $invoice->contact_no ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Kind GST</label>
                                <input type="text" name="kind_gst" class="form-control"
                                    value="{{ old('kind_gst', $invoice->kind_gst ?? '') }}">
                            </div>

                            <!-- Item -->
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" class="form-control"
                                    value="{{ old('description', $invoice->description ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">HSN Code</label>
                                <input type="text" name="hsn_code" class="form-control"
                                    value="{{ old('hsn_code', $invoice->hsn_code ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Qty</label>
                                <input type="number" name="qty" class="form-control"
                                    value="{{ old('qty', $invoice->qty ?? 1) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Rate</label>
                                <input type="text" name="rate" class="form-control"
                                    value="{{ old('rate', $invoice->rate ?? 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Amount</label>
                                <input type="text" name="amount" class="form-control"
                                    value="{{ old('amount', $invoice->amount ?? 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hrs per Job</label>
                                <input type="text" name="hrs_per_job" class="form-control"
                                    value="{{ old('hrs_per_job', $invoice->hrs_per_job ?? 0) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cost</label>
                                <input type="text" name="cost" class="form-control"
                                    value="{{ old('cost', $invoice->cost ?? 0) }}">
                            </div>

                            <!-- Totals -->
                            <div class="col-md-4">
                                <label class="form-label">Sub Total</label>
                                <input type="text" name="sub_total" class="form-control"
                                    value="{{ old('sub_total', $invoice->sub_total ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SGST</label>
                                <input type="text" name="sgst" class="form-control"
                                    value="{{ old('sgst', $invoice->sgst ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CGST</label>
                                <input type="text" name="cgst" class="form-control"
                                    value="{{ old('cgst', $invoice->cgst ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">IGST</label>
                                <input type="text" name="igst" class="form-control"
                                    value="{{ old('igst', $invoice->igst ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Tax Payable</label>
                                <input type="text" name="total_tax_payable" class="form-control"
                                    value="{{ old('total_tax_payable', $invoice->total_tax_payable ?? 0) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Grand Total</label>
                                <input type="text" name="grand_total" class="form-control"
                                    value="{{ old('grand_total', $invoice->grand_total ?? 0) }}">
                            </div>

                            <!-- Others -->
                            <div class="col-md-6">
                                <label class="form-label">Declaration</label>
                                <input type="text" name="declaration" class="form-control"
                                    value="{{ old('declaration', $invoice->declaration ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control">{{ old('note', $invoice->note ?? '') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bank Details</label>
                                <textarea name="bank_details" class="form-control">{{ old('bank_details', $invoice->bank_details ?? '') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount in Words</label>
                                <input type="text" name="amount_in_words" class="form-control"
                                    value="{{ old('amount_in_words', $invoice->amount_in_words ?? '') }}">
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">{{ isset($invoice) ? 'Update' : 'Submit' }}</button>
                                @if(isset($invoice))
                                <a href="{{ route('ViewInvoice') }}" class="btn btn-info">Cancel</a>
                                @else
                                <button type="reset" class="btn btn-info">Reset</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Form End -->

        </div>
    </div>
</div>

@endsection
