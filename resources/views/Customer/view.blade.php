@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">View Customers</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Customer Name</th>
                                            <th>Email Id</th>
                                            <th>Code</th>
                                            <!-- <th>Contact Person</th> -->
                                            <th>Phone No.</th>
                                            <!-- <th>gst no</th> -->
                                            <th>Address</th>
                                             <th style="width: 15%;">Status</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer as $c)
                                        <tr>
                                             <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ $c->email_id }}</td>
                                            <td>{{ $c->code }}</td>
                                            <!-- <td>{{ $c->contact_person }}</td> -->
                                            <td>{{ $c->phone_no }}</td>
                                            <!-- <td>{{ $c->gst_no }}</td> -->
                                            <td>{{ $c->address }}</td>
                                             <td>
                                        <form action="{{ route('updateCustomerStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $c->id }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $c->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $c->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                            <td>
                                                <a href="{{route('editCustomer', base64_encode($c->id))}}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-primary btn-icon waves-effect waves-light viewCustomerBtn"
                                                    data-name="{{ $c->name }}"
                                                    data-code="{{ $c->code }}"
                                                    data-rate="{{ $c->per_hour_rate }}"                                      
                                                    data-email_id="{{ $c->email_id }}"
                                                    data-contact="{{ $c->contact_person }}"
                                                    data-phone="{{ $c->phone_no }}"
                                                    data-gst="{{ $c->gst_no }}"
                                                    data-address="{{ $c->address }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>
                                                <a href="{{ route('deleteCustomer', base64_encode($c->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">

                                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
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
            </div>
            <!-- Customer View Modal -->
            <div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Customer Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td id="cust_name"></td>
                                </tr>
                                <tr>
                                    <th>Per hour rate</th>
                                    <td id="cust_hour_rate"></td>
                                </tr>
                              
                                 <tr>
                                    <th>Code</th>
                                    <td id="cust_code"></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id="email_id"></td>
                                </tr>
                                <tr>
                                    <th>Contact Person</th>
                                    <td id="cust_contact"></td>
                                </tr>
                                <tr>
                                    <th>Phone No</th>
                                    <td id="cust_phone"></td>
                                </tr>
                                <tr>
                                    <th>GST No</th>
                                    <td id="cust_gst"></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td id="cust_address"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll(".viewCustomerBtn").forEach(btn => {
                        btn.addEventListener("click", function() {
                            document.getElementById("cust_name").textContent = this.dataset.name;
                            document.getElementById("cust_code").textContent = this.dataset.code;
                            document.getElementById("cust_hour_rate").textContent = this.dataset.rate;
                           
                            document.getElementById("email_id").textContent = this.dataset.email_id;
                            document.getElementById("cust_contact").textContent = this.dataset.contact;
                            document.getElementById("cust_phone").textContent = this.dataset.phone;
                            document.getElementById("cust_gst").textContent = this.dataset.gst;
                            document.getElementById("cust_address").textContent = this.dataset.address;

                            let modal = new bootstrap.Modal(document.getElementById("viewCustomerModal"));
                            modal.show();
                        });
                    });
                });
            </script>

        </div>
    </div>
</div>
@endsection