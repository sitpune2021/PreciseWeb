@extends('layouts.header')
@section('content')
@if(hasPermission('Operator','view') || hasPermission('Operator','add'))
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            @if(hasPermission('Operator','add'))
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex align-items-center">
                    <!-- Back Button ONLY on Edit -->
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">
                        ←
                    </a>
                    <h5 class="mb-0">{{ isset($operator) ? 'Edit Operator' : 'Add Operator' }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="d-flex">
                        <div id="successAlert"
                            class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2"
                            style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif
                    <form action="{{ isset($operator) ? route('updateOperator', base64_encode($operator->id)) : route('storeOperator') }}" method="POST">
                        @csrf
                        @if(isset($operator))
                        @method('PUT')
                        @endif

                        <div class="row align-items-end">

                            <!-- Operator Name -->
                            <div class="col-md-4 col-sm-6 mb-3 position-relative">
                                <label for="operator_name" class="form-label">
                                    Operator Name <span class="mandatory"> *</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="operator_name"
                                    name="operator_name"
                                    value="{{ old('operator_name', isset($operator) ? $operator->operator_name : '') }}"
                                    placeholder="Enter Operator Name"
                                    style="background-image: none !important;"
                                    onkeypress="return /[A-Za-z\u0900-\u097F\s]/.test(event.key)">
                                @error('operator_name')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-4 col-sm-6 mb-3 position-relative">
                                <label for="phone_no" class="form-label">
                                    Phone Number <span class="mandatory"> *</span>
                                </label>
                                <input type="text" class="form-control form-control-sm px-3 py-2" id="phone_no" name="phone_no"
                                    value="{{ old('phone_no', isset($operator) ? $operator->phone_no : '') }}"
                                    placeholder="Phone Number"
                                    style="background-image: none !important;"
                                    onkeypress="return isNumberKey(event)">
                                @error('phone_no')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($operator) ? 'Update' : 'Add' }}
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
            @endif
            <!-- Form End -->
            <script>
                function isNumberKey(evt) {

                    if (!/[0-9]/.test(evt.key)) {
                        return false;
                    }
                    let input = evt.target.value;

                    if (input.length >= 10) {
                        return false;
                    }
                    return true;
                }
            </script>
            <!-- List Start -->
            @if(hasPermission('Operator', 'view'))
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Operator List</h5>
                    <a href="{{ route('trashOperator') }}" class="btn btn-warning btn-sm">
                        View Trash
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sr.No</th>
                                    <th style="width: 45%; text-align: center;">Operator Name</th>
                                    <th style="width: 25%; text-align: center;">Phone Number</th>
                                    <th style="width: 15%;">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operators as $o)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ $o->operator_name }}
                                        @if($o->is_active == 0)
                                        <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $o->phone_no }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('updateOperatorStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $o->id }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch{{ $o->id }}" name="status"
                                                    value="1" onchange="this.form.submit()" {{ $o->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        @if(hasPermission('Operator', 'edit'))
                                        <a href="{{ route('editOperator', base64_encode($o->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                        @endif

                                        @if(hasPermission('Operator', 'delete'))
                                        <a href="{{ route('deleteOperator', base64_encode($o->id)) }}"
                                            onclick="return confirm('Are you sure you want to delete this record?')"
                                            class="btn btn-danger btn-sm">
                                            <i class="ri-delete-bin-fill align-bottom"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No operators found.</td>
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