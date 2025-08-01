@extends('layouts.header')
@section('content')
 
<div class="main-content">
 
    <div class="page-content">
        <div class="container-fluid">
 
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Add Client</h4>
                           
                        </div><!-- end card header -->
 
                        <div class="card-body">
                           
                            <div class="live-preview">
                                <form action="{{route('storeClient')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Client Name <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" placeholder="Customer Name" id="name" name="name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-red">{{ $message }}</span>
                                            @enderror
                                            </div>
                                        </div>
                                         <!--end col-->
                                     
                                                                               
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email_id" class="form-label">Email Id</label>
                                                <input type="email" class="form-control" placeholder="Email Id" id="email_id" name="email_id" value="{{ old('email_id') }}">
                                           
                                            </div>
                                        </div>
                                        <!--end col-->
                                       
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone Number <span class="mandatory">*</span></label>
                                                <input type="tel" class="form-control" placeholder="Phone Number" id="phone_no" name="phone_no" value="{{ old('phone_no') }}">
                                            @error('phone_no')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gst_no" class="form-label">GST Number <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" placeholder="GST Number" id="gst_no" name="gst_no" value="{{ old('gst_no') }}">
                                            @error('gst_no')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
 
                                         <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">Client Logo <span class="mandatory">*</span></label>
                                                <input type="file" class="form-control"  id="logo" name="logo" value="{{ old('logo') }}">
                                            @error('logo')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                       
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address <span class="mandatory">*</span></label>
                                                <textarea class="form-control" placeholder="Address..." id="address" name="address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                       
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                &nbsp;
                                                <button type="reset" class="btn btn-info">Reset</button>
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