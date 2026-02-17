@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    @if(session('success'))
                    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1055;">
                        <div id="successAlert"
                            class="alert alert-success alert-dismissible fade show py-2 px-3 shadow-sm text-center"
                            style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Customers</h5>

                            <form action="{{ route('ViewCustomer') }}" method="GET" class="d-flex align-items-center gap-2">
                                <select name="financial_year" class="form-control form-select form-select-sm">
                                    <option value="">Select Year</option>
                                    <option value="2025-26" {{ request('financial_year') == '2025-26' ? 'selected' : '' }}>2025-26</option>
                                    <option value="2026-27" {{ request('financial_year') == '2026-27' ? 'selected' : '' }}>2026-27</option>
                                    <option value="2027-28" {{ request('financial_year') == '2027-28' ? 'selected' : '' }}>2027-28</option>
                                </select>

                                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">

                                <button type="submit" class="btn btn-success btn-sm">Filter</button>
                                <a href="{{ route('ViewCustomer') }}" class="btn btn-primary btn-sm">Reset</a>
                            </form>

                            <div class="d-flex gap-2">
                                @if(hasPermission('Customer', 'add'))
                                <a href="{{ route('AddCustomer') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle me-1"></i> Add Customer
                                </a>
                                @endif

                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                                    <i class="ri-upload-2-line align-middle"></i> Customer Import
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr.No</th>
                                            <th>Customer Name</th>
                                            <th>Email Id</th>
                                            <th>Code</th>
                                            <!-- <th>Contact Person</th> -->
                                            <th>Phone No.</th>
                                            <!-- <th>gst no</th> -->
                                            <th>Address</th>
                                            <th style="width: 15%;">Status</th>
                                            @if(hasPermission('Customer', 'edit'))
                                            <th width="12%">Action</th>
                                            @endif

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
                                                           {{ $p->is_active == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                @if(hasPermission('Customer', 'edit'))
                                                <a href="{{ route('editCustomer', base64_encode($c->id)) }}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                                @endif

                                                @if(hasPermission('Customer', 'view'))
                                                <button type="button"
                                                    class="btn btn-primary btn-icon waves-effect waves-light viewCustomerBtn"
                                                    data-name="{{ $c->name }}"
                                                    data-code="{{ $c->code }}"

                                                    data-email_id="{{ $c->email_id }}"
                                                    data-contact="{{ $c->contact_person }}"
                                                    data-phone="{{ $c->phone_no }}"
                                                    data-gst="{{ $c->gst_no }}"
                                                    data-address="{{ $c->address }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>
                                                @endif


                                                <a href="{{ route('deleteCustomer', base64_encode($c->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                </a>


                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Import Button -->

                    <!-- Modal -->
                    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('importCustomers') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Import Customers (Excel/CSV)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="excelFile" class="form-label">Choose Excel/CSV File</label>
                                            <input type="file" name="file" id="excelFile" class="form-control" accept=".xlsx,.xls,.csv" required>
                                        </div>

                                        <a href="{{ route('exportCustomers') }}" class="btn btn-success">
                                            <i class="ri-download-2-line"></i> Download Sample
                                        </a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
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