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
                                {{ isset($client) ? 'Edit Client' : 'Add Client' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ isset($client) ? route('updateClient', base64_encode($client->id)) : route('storeClient') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if(isset($client))
                                        @method('PUT')
                                    @endif
                                    <input type="hidden" name="password" value="123">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Client Name <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Client Name" value="{{ old('name', $client->name ?? '') }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="email_id" class="form-label">Email ID</label>
                                                <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Email Id" value="{{ old('email_id', $client->email_id ?? '') }}">
                                                @error('email_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Phone Number" value="{{ old('phone_no', $client->phone_no ?? '') }}">
                                                @error('phone_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gst_no" class="form-label">GST No <span class="mandatory">*</span></label>

                                                <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="GST Number" value="{{ old('gst_no', $client->gst_no ?? '') }}">
                                                @error('gst_no')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="logo" class="form-label">Client Logo {{ isset($client) ? '' : '*' }}</label>
                                                    <input type="file" class="form-control" id="logo" name="logo">

                                                    @error('logo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            @if(isset($client) && $client->logo)
                                                <div class="col-md-2">
                                                    <div class="mb-3">
                                                        <img src="{{ asset($client->logo) }}" alt="Logo" width="100">
                                                    </div>
                                                </div>
                                            @endif


                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address <span class="mandatory">*</span></label>
                                                <textarea class="form-control" id="address" name="address" placeholder="Address...">{{ old('address', $client->address ?? '') }}</textarea>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">{{ isset($client) ? 'Update' : 'Submit' }}</button>
                                                &nbsp;
                                                @if(isset($client))
                                                    <a href="{{ route('ViewClient') }}" class="btn btn-info">Cancel</a>
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
