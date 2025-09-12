@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">View Invoices</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Client</th>
                                            <th>Buyer Name</th>
                                            <th>Total Amount</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $i)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $i->invoice_no }}</td>
                                            <td>{{ $i->invoice_date }}</td>
                                            <td>{{ $i->client->name ?? '-' }}</td>
                                            <td>{{ $i->buyer_name }}</td>
                                            <td>{{ $i->grand_total }}</td>
                                            <td>
                                                <!-- Edit -->
                                                <a href="{{ route('editInvoice', base64_encode($i->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>

                                                <!-- Delete -->
                                                <a href="{{ route('deleteInvoice', base64_encode($i->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')" 
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </a>

                                                <!-- View -->
                                                <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn"
                                                    data-invoiceno="{{ $i->invoice_no }}"
                                                    data-invoicedate="{{ $i->invoice_date }}"
                                                    data-client="{{ $i->client->name ?? '-' }}"
                                                    data-msme="{{ $i->msme_no }}"
                                                    data-buyer="{{ $i->buyer_name }}"
                                                    data-buyeraddress="{{ $i->buyer_address }}"
                                                    data-consignee="{{ $i->consignee_name }}"
                                                    data-consigneeaddress="{{ $i->consignee_address }}"
                                                    data-attn="{{ $i->kind_attn_name }}"
                                                    data-contact="{{ $i->contact_no }}"
                                                    data-desc="{{ $i->description }}"
                                                    data-hsn="{{ $i->hsn_code }}"
                                                    data-qty="{{ $i->qty }}"
                                                    data-rate="{{ $i->rate }}"
                                                    data-amount="{{ $i->amount }}"
                                                    data-hrs="{{ $i->hrs_per_job }}"
                                                    data-cost="{{ $i->cost }}"
                                                    data-subtotal="{{ $i->sub_total }}"
                                                    data-sgst="{{ $i->sgst }}"
                                                    data-cgst="{{ $i->cgst }}"
                                                    data-igst="{{ $i->igst }}"
                                                    data-tax="{{ $i->total_tax_payable }}"
                                                    data-grand="{{ $i->grand_total }}"
                                                    data-declaration="{{ $i->declaration }}"
                                                    data-note="{{ $i->note }}"
                                                    data-bank="{{ $i->bank_details }}"
                                                    data-words="{{ $i->amount_in_words }}">
                                                    <i class="ri-eye-fill"></i>
                                                </button>

                                                <!-- Print -->
                                                <a href="{{ route('printInvoice', base64_encode($i->id)) }}" target="_blank" class="btn btn-dark btn-sm">
                                                    <i class="ri-printer-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Modal -->
            <div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Invoice Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr><th>Invoice No</th><td id="view_invoiceno"></td></tr>
                                <tr><th>Invoice Date</th><td id="view_invoicedate"></td></tr>
                                <tr><th>Client</th><td id="view_client"></td></tr>
                                <tr><th>MSME No</th><td id="view_msme"></td></tr>
                                <tr><th>Buyer Name</th><td id="view_buyer"></td></tr>
                                <tr><th>Buyer Address</th><td id="view_buyeraddress"></td></tr>
                                <tr><th>Consignee Name</th><td id="view_consignee"></td></tr>
                                <tr><th>Consignee Address</th><td id="view_consigneeaddress"></td></tr>
                                <tr><th>Kind Attn</th><td id="view_attn"></td></tr>
                                <tr><th>Contact No</th><td id="view_contact"></td></tr>
                                <tr><th>Particulars</th><td id="view_desc"></td></tr>
                                <tr><th>HSN Code</th><td id="view_hsn"></td></tr>
                                <tr><th>Qty</th><td id="view_qty"></td></tr>
                                <tr><th>Rate</th><td id="view_rate"></td></tr>
                                <tr><th>Amount</th><td id="view_amount"></td></tr>
                                <tr><th>Hrs/Job</th><td id="view_hrs"></td></tr>
                                <tr><th>Cost</th><td id="view_cost"></td></tr>
                                <tr><th>Sub Total</th><td id="view_subtotal"></td></tr>
                                <tr><th>SGST</th><td id="view_sgst"></td></tr>
                                <tr><th>CGST</th><td id="view_cgst"></td></tr>
                                <tr><th>IGST</th><td id="view_igst"></td></tr>
                                <tr><th>Total Tax</th><td id="view_tax"></td></tr>
                                <tr><th>Grand Total</th><td id="view_grand"></td></tr>
                                <tr><th>Declaration</th><td id="view_declaration"></td></tr>
                                <tr><th>Note</th><td id="view_note"></td></tr>
                                <tr><th>Bank Details</th><td id="view_bank"></td></tr>
                                <tr><th>Amount in Words</th><td id="view_words"></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".viewBtn").forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("view_invoiceno").textContent = this.dataset.invoiceno;
                document.getElementById("view_invoicedate").textContent = this.dataset.invoicedate;
                document.getElementById("view_client").textContent = this.dataset.client;
                document.getElementById("view_msme").textContent = this.dataset.msme;
                document.getElementById("view_buyer").textContent = this.dataset.buyer;
                document.getElementById("view_buyeraddress").textContent = this.dataset.buyeraddress;
                document.getElementById("view_consignee").textContent = this.dataset.consignee;
                document.getElementById("view_consigneeaddress").textContent = this.dataset.consigneeaddress;
                document.getElementById("view_attn").textContent = this.dataset.attn;
                document.getElementById("view_contact").textContent = this.dataset.contact;
                document.getElementById("view_desc").textContent = this.dataset.desc;
                document.getElementById("view_hsn").textContent = this.dataset.hsn;
                document.getElementById("view_qty").textContent = this.dataset.qty;
                document.getElementById("view_rate").textContent = this.dataset.rate;
                document.getElementById("view_amount").textContent = this.dataset.amount;
                document.getElementById("view_hrs").textContent = this.dataset.hrs;
                document.getElementById("view_cost").textContent = this.dataset.cost;
                document.getElementById("view_subtotal").textContent = this.dataset.subtotal;
                document.getElementById("view_sgst").textContent = this.dataset.sgst;
                document.getElementById("view_cgst").textContent = this.dataset.cgst;
                document.getElementById("view_igst").textContent = this.dataset.igst;
                document.getElementById("view_tax").textContent = this.dataset.tax;
                document.getElementById("view_grand").textContent = this.dataset.grand;
                document.getElementById("view_declaration").textContent = this.dataset.declaration;
                document.getElementById("view_note").textContent = this.dataset.note;
                document.getElementById("view_bank").textContent = this.dataset.bank;
                document.getElementById("view_words").textContent = this.dataset.words;

                let modal = new bootstrap.Modal(document.getElementById("viewInvoiceModal"));
                modal.show();
            });
        });
    });
</script>

@endsection
