@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($customer) ? 'Edit Customer' : 'Add Customer' }}
                            </h4>

                        </div>

                        <div class="card-body">

                            <div class="live-preview">
                                <form action="{{ isset($customer) ? route('updateCustomer', base64_encode($customer->id)) : route('storeCustomer') }}" method="POST">
                                    @csrf
                                    @if(isset($customer))
                                    @method('PUT')
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" placeholder="Customer Name" id="name" name="name"
                                                    value="{{ old('name', $customer->name ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '')">
                                                @error('name')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                        @if(isset($customer))
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">Customer Code</label>
                                                <input type="text" class="form-control" placeholder="Customer Code"
                                                    id="code" name="code"
                                                    value="{{ old('code', $customer->code ?? '') }}">
                                                @error('code')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="email_id" class="form-label">Email Id</label>
                                                <input type="email" class="form-control" placeholder="Email Id" id="email_id" name="email_id" value="{{ old('email_id', $customer->email_id ?? '') }}">

                                            </div>
                                        </div>

                                        <!--end col-->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="contact_person" class="form-label">Contact Person </label>
                                                <input type="text" class="form-control" id="contact_person" name="contact_person"
                                                    placeholder="Contact Person"
                                                    value="{{ old('contact_person', $customer->contact_person ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^A-Za-z.\s]/g, '');">
                                                @error('contact_person')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!--end col-->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone Number </label>
                                                <input type="tel" class="form-control" placeholder="Phone Number" maxlength="10" id="phone_no" name="phone_no" value="{{ old('phone_no', $customer->phone_no ?? '') }}">
                                                @error('phone_no')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gst_no" class="form-label">GST Number </label>
                                                <input type="text" class="form-control" placeholder="GST Number" id="gst_no" name="gst_no" value="{{ old('gst_no', $customer->gst_no ?? '') }}" oninput="this.value = this.value.toUpperCase();">
                                                @error('gst_no')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address</label>
                                                <textarea class="form-control" placeholder="Address..." id="address" name="address">{{ old('address', $customer->address ?? '') }}</textarea>
                                                @error('address')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ isset($customer) ? 'Update' : 'Submit' }}
                                                </button>
                                                &nbsp;
                                                @if(isset($customer))
                                                <a href="{{ route('ViewCustomer') }}" class="btn btn-info">Cancel</a>

                                                @else
                                                <button type="reset" class="btn btn-info">Reset</button>
                                                @endif

                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>

                        </div>
                    </div>
                </div> <!-- end col -->


            </div>
            <!--end row-->

        </div>
    </div>
</div>

@endsection