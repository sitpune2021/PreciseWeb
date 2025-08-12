@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Vendors</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Code</th>
                                            <th>Contact Person</th>
                                            <th>Phone No.</th>
                                            <th>Email ID</th>
                                            <th>GST No.</th>
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
                                           
                                             <a href="{{route('editVendor', base64_encode($vendor->id))}}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                              
                                                  <a href="{{route('deleteVendor', base64_encode($vendor->id))}}">
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
            </div><!--end row-->

        </div>
    </div>
</div>

@endsection
