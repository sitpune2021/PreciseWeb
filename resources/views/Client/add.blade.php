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
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

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
                                                <input type="text"
                                                    class="form-control"
                                                    id="name"
                                                    name="name"
                                                    placeholder="Client Name"
                                                    value="{{ old('name', $client->name ?? '') }}"
                                                    onkeypress="return /[a-zA-Z\s]/.test(event.key)">
                                                @error('name')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="email_id" class="form-label">Email ID<span class="mandatory">*</span></label>
                                                <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Email Id" value="{{ old('email_id', $client->email_id ?? '') }}">
                                                @error('email_id')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="phone_no" class="form-label">Phone No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Phone Number"
                                                    value="{{ old('phone_no', isset($client) ? $client->phone_no : '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)">
                                                @error('phone_no')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="gst_no" class="form-label">GST Number <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" placeholder="GST Number" id="gst_no" name="gst_no" value="{{ old('gst_no', $client->gst_no ?? '') }}" oninput="this.value = this.value.toUpperCase();">
                                                @error('gst_no')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="logo" class="form-label">Client Logo <span class="mandatory">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                                @if(isset($client) && $client->logo)
                                                <a href="{{ asset($client->logo) }}" target="_blank"
                                                    style="width: 50px; height: 40px; overflow: hidden;">
                                                    <img src="{{ asset($client->logo) }}" alt="Logo" style="width: 100%; height: auto;">
                                                </a>
                                                @endif
                                            </div>
                                            @error('logo')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if(isset($client) && $client->logo)
                                        <div class="modal fade" id="logoModal" tabindex="-1" aria-labelledby="logoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                                <div class="modal-content position-relative text-center p-3">
                                                    <button type="button"
                                                        class="btn-close position-absolute top-0 end-0 m-2"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                    <img src="{{ asset($client->logo) }}" alt="Full Logo" style="max-width: 100%; height: auto;">
                                                </div>
                                            </div>
                                        </div>

                                        @endif
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Address <span class="mandatory">*</span></label>
                                                <textarea class="form-control" id="address" name="address" placeholder="Address...">{{ old('address', $client->address ?? '') }}</textarea>
                                                @error('address')
                                                <span class="text-red small">{{ $message }}</span>
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection