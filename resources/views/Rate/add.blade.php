@extends('layouts.header')
@section('content')
@if(hasPermission('VmcRate','view') || hasPermission('VmcRate','add'))
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            @if(hasPermission('VmcRate','add'))
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex align-items-center">
                    <!-- Back Button ONLY on Edit -->
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">←</a>
                    <h5 class="mb-0">{{ isset($rate) ? 'Edit Vmc Rate' : 'Add Vmc Rate' }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="d-flex">
                        <div id="successAlert" class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2" style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

                    <form action="{{ isset($rate) ? route('updaterate', base64_encode($rate->id)) : route('storerate') }}" method="POST">
                        @csrf
                        @if(isset($rate)) @method('PUT') @endif

                        <div class="row align-items-end">
                            <!-- Rate Name -->
                            <div class="col-md-3 col-sm-6 mb-3 position-relative">
                                <label for="name" class="form-label">Name <span class="mandatory"> *</span></label>
                                <input type="text" class="form-control form-control-sm px-3 py-2" id="name" name="name"
                                    value="{{ old('name', isset($rate) ? $rate->name : '') }}" placeholder="Enter Name">
                                @error('name')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <!-- Hour -->
                            <div class="col-md-3 col-sm-6 mb-3 position-relative">
                                <label for="hour" class="form-label">Hour <span class="mandatory">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm px-3 py-2"
                                    id="hour" name="hour"
                                    value="{{ old('hour', isset($rate) ? $rate->hour : '') }}"
                                    placeholder="Enter Hours">
                                @error('hour')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <!-- Rate -->
                            <div class="col-md-3 col-sm-6 mb-3 position-relative">
                                <label for="rate" class="form-label">Rate <span class="mandatory"> *</span></label>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm px-3 py-2"
                                    id="rate" name="rate"
                                    value="{{ old('rate', isset($rate) ? $rate->rate : '') }}"
                                    placeholder="Enter Rate">
                                @error('rate')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($rate) ? 'Update' : 'Add' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            <!-- Form End -->

            <!-- List Start -->
            @if(hasPermission('VmcRate','view'))
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vmc Rate List</h5>
                    <a href="{{ route('trashrate') }}" class="btn btn-warning btn-sm">View Trash</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Hour</th>
                                    <th class="text-center">Rate</th>
                                    <th style="width: 15%;">Status</th>
                                    <th width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $r)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $r->name }}</td>
                                    <td class="text-center">{{ $r->hour }}</td>
                                    <td class="text-center">₹ {{ number_format($r->rate, 2) }}</td>
                                    <td>
                                        <form action="{{ route('updateratestatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $r->id }}">
                                            <input type="hidden" name="status" value="0">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch{{ $r->id }}" name="status" value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $r->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        @if(hasPermission('VmcRate','edit'))
                                        <a href="{{ route('editrate', base64_encode($r->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                        @endif

                                        @if(hasPermission('VmcRate','delete'))
                                        <a href="{{ route('deleterate', base64_encode($r->id)) }}"
                                            onclick="return confirm('Are you sure you want to delete this record?')">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                            </button>
                                        </a>
                                        @endif

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No Rates found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <!-- List End -->

        </div>
    </div>
</div>
@endif
@endsection