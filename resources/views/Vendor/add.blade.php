@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- <div class="row g-2">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quick Menu</h5>
                    </div>

                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">

                            @if(hasPermission('Customer', 'add'))
                            <a href="{{ route('AddCustomer') }}" class="btn btn-sm btn-info ">
                                <i class="ri-group-line"></i> Add Customer
                            </a>
                            @endif

                            @if(hasPermission('Vendors', 'add'))
                            <a href="{{ route('AddVendor') }}" class="btn btn-sm btn-dark ">
                                <i class="ri-store-line"></i> Add Vendor
                            </a>
                            @endif

                            @if(hasPermission('Projects', 'add'))
                            <a href="{{ route('AddProject') }}" class="btn btn-sm btn-success ">
                                <i class="ri-folder-add-line"></i> Add Project
                            </a>
                            @endif

                            @if(hasPermission('WorkOrders', 'add'))
                            <a href="{{ route('AddWorkOrder') }}" class="btn btn-sm btn-warning ">
                                <i class="ri-file-add-line"></i>Add Work
                            </a>
                            @endif

                            @if(hasPermission('SetupSheet', 'add'))
                            <a href="{{ route('AddSetupSheet') }}" class="btn btn-sm btn-secondary ">
                                <i class="ri-settings-3-line"></i> Add Setup
                            </a>
                            @endif

                            @if(hasPermission('MachineRecord', 'add'))
                            <a href="{{ route('AddMachinerecord') }}" class="btn btn-sm btn-info ">
                                <i class="ri-cpu-line"></i> Add Machine Rec
                            </a>
                            @endif

                            @if(hasPermission('MaterialReq', 'add'))
                            <a href="{{ route('AddMaterialReq') }}" class="btn btn-sm btn-danger ">
                                <i class="ri-stack-line"></i> Add Material Req
                            </a>
                            @endif

                            @if(hasPermission('MaterialOrder', 'add'))
                            <a href="{{ route('AddMaterialorder') }}" class="btn btn-sm btn-warning ">
                                <i class="ri-stack-line"></i> Add Material O
                            </a>
                            @endif

                            @if(hasPermission('Invoice', 'add'))
                            <a href="{{ route('proforma.add') }}" class="btn btn-sm btn-primary ">
                                <i class="ri-file-text-line"></i> Add Proforma In
                            </a>
                            @endif

                            @if(hasPermission('Quotation', 'add'))
                            <a href="{{ route('Addquotation') }}" class="btn btn-sm btn-info ">
                                <i class="ri-folder-add-line"></i> Add Quotation
                            </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">

                            <!-- Back Button ONLY on Edit -->
                            <a href="{{ route('ViewVendor') }}" class="btn btn-sm btn-outline-success me-2">
                                ←
                            </a>

                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($vendor) ? 'Edit Vendor' : 'Add Vendor' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="live-preview">
                                <form action="{{ isset($vendor) ? route('updateVendor',  base64_encode($vendor->id)) : route('storeVendor') }}" method="POST">
                                    @csrf
                                    @if (isset($vendor) && $method == "PUT")
                                    @method('PUT')

                                    @endif

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="vendor_name" class="form-label">Vendor Name <span class="mandatory">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="vendor_name"
                                                    name="vendor_name"
                                                    placeholder="Vendor Name"
                                                    value="{{ old('vendor_name', $vendor->vendor_name ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[0-9]/g, '');">
                                                @error('vendor_name')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="vendor_code" class="form-label">Vendor Code <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="vendor_code" name="vendor_code" placeholder="Vendor Code" value="{{ old('vendor_code', $vendor->vendor_code ?? '') }}">
                                                @error('vendor_code')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Phone Number"
                                                    value="{{ old('phone_no', $vendor->phone_no ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)">
                                                @error('phone_no')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="contact_person" class="form-label">Contact Person <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                                    placeholder="Contact Person"
                                                    value="{{ old('contact_person', $vendor->contact_person ?? '') }}">
                                                @error('contact_person')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gst_no" class="form-label">GST Number <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" placeholder="GST Number" id="gst_no" name="gst_no" value="{{ old('gst_no', $vendor->gst_no ?? '') }}" oninput="this.value = this.value.toUpperCase();">
                                                @error('gst_no')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="email_id" class="form-label">Email ID</label>
                                                <input type="email" class="form-control mt-1" id="email_id" name="email_id" placeholder="Email ID" value="{{ old('email_id', $vendor->email_id ?? '') }}">
                                                @error('email_id')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="statusToggle" class="form-label  mt-1">
                                                    Status <span class="mandatory">*</span>
                                                </label><br>
                                                <div class="form-check form-switch  mt-1">
                                                    <!-- Fixed Active button -->
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="statusToggle"
                                                        checked
                                                        onclick="return false;"> <!-- user cannot change -->
                                                    <label class="form-check-label" for="statusToggle">
                                                        Active
                                                    </label>
                                                </div>

                                                <input type="hidden" name="status" value="Active"> <!-- Form value -->
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address <span class="mandatory">*</span></label>
                                                <textarea class="form-control" id="address" name="address" placeholder="Address...">{{ old('address', $vendor->address ?? '') }}</textarea>
                                                @error('address')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">{{ isset($vendor) ? 'Update' : 'Submit' }}</button>
                                                &nbsp;
                                                @if(isset($vendor))
                                                <a href="{{ route('ViewVendor') }}" class="btn btn-info">Cancel</a>
                                                @else
                                                <button type="reset" class="btn btn-info">Reset</button>
                                                @endif
                                            </div>
                                        </div>

                                    </div><!-- end row -->
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- end row -->
        </div>
    </div>
</div>

@endsection