@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Invoices</h5>
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
                                            <th>MSME No</th>
                                            <th>Buyer Name</th>
                                            <th>Buyer Address</th>
                                            <th>Buyer GST</th>
                                            <th>Consignee Name</th>
                                            <th>Consignee Address</th>
                                            <th>Consignee GST</th>
                                            <th>Kind Attn</th>
                                            <th>Contact No</th>
                                            <th>Particulars</th>
                                            <th>HSN Code</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Hrs/Job</th>
                                            <th>Cost</th>
                                            <th>Sub Total</th>
                                            <th>SGST</th>
                                            <th>CGST</th>
                                            <th>IGST</th>
                                            <th>Total Tax</th>
                                            <th>Total Amount</th>
                                            <th>Declaration</th> <!-- 👈 new field -->
                                            <th>Note</th>
                                            <th>Bank Details</th>
                                            <th>Amount in Words</th>
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
                                            <td>{{ $i->msme_no }}</td>
                                            <td>{{ $i->buyer_name }}</td>
                                            <td>{{ $i->buyer_address }}</td>
                                            <td>{{ $i->buyer_gst }}</td>
                                            <td>{{ $i->consignee_name }}</td>
                                            <td>{{ $i->consignee_address }}</td>
                                            <td>{{ $i->consignee_gst }}</td>
                                            <td>{{ $i->kind_attn_name }}</td>
                                            <td>{{ $i->contact_no }}</td>
                                            <td>{{ $i->particulars }}</td>
                                            <td>{{ $i->hsn_code }}</td>
                                            <td>{{ $i->qty }}</td>
                                            <td>{{ $i->rate }}</td>
                                            <td>{{ $i->amount }}</td>
                                            <td>{{ $i->hrs_per_job }}</td>
                                            <td>{{ $i->cost }}</td>
                                            <td>{{ $i->sub_total }}</td>
                                            <td>{{ $i->sgst }}</td>
                                            <td>{{ $i->cgst }}</td>
                                            <td>{{ $i->igst }}</td>
                                            <td>{{ $i->total_tax_payable }}</td>
                                            <td>{{ $i->total_amount }}</td>
                                            <td>{{ $i->declaration }}</td> <!-- 👈 new field -->
                                            <td>{{ $i->note }}</td>
                                            <td>{{ $i->bank_details }}</td>
                                            <td>{{ $i->amount_in_words }}</td>
                                            <td>
                                                <a href="{{ route('editInvoice', base64_encode($i->id)) }}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <a href="{{ route('deleteInvoice', base64_encode($i->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <a href="{{ route('printInvoice', base64_encode($i->id)) }}" target="_blank">
                                                    <button type="button" class="btn btn-primary btn-icon waves-effect waves-light">
                                                        <i class="ri-printer-fill align-bottom"></i>
                                                    </button>
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
            </div><!--end row-->

        </div>
    </div>
</div>

@endsection