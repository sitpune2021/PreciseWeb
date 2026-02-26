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
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">View Vendors</h5>
                            <div class="ms-auto">
                                @if(hasPermission('Vendors', 'add'))
                                <a href="{{ route('AddVendor') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Vendor
                                </a>
                                @endif

                                <a href="{{ route('trashVendor') }}" class="btn btn-warning btn-sm">
                                    View Trash
                                </a>
                            </div>
                        </div>

                        @if(hasPermission('Vendors', 'view'))
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr.No</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Code</th>
                                            <th>Contact Person</th>
                                            <th>Phone.No</th>
                                            <th>Email ID</th>
                                            <th>GST.No</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->vendor_name }}</td>
                                            <td>{{ $vendor->vendor_code }}</td>
                                            <td>{{ $vendor->contact_person }}</td>
                                            <td>{{ $vendor->phone_no }}</td>
                                            <td>{{ $vendor->email_id }}</td>
                                            <td>{{ $vendor->gst_no ?? 'N/A' }}</td>
                                            <td>{{ $vendor->address }}</td>
                                            <td>
                                                @if($vendor->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                                @else
                                                <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(hasPermission('Vendors', 'edit'))
                                                <a href="{{route('editVendor', base64_encode($vendor->id))}}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                                @endif
                                                @if(hasPermission('Vendors', 'delete'))
                                                <a href="{{route('deleteVendor', base64_encode($vendor->id))}}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            @endif

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div><!--end row-->

        </div>
    </div>
</div>

@endsection