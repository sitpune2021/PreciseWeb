@extends('layouts.header')
@section('content')
 
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
 
            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($operator) ? 'Edit Operator ' : 'Add Operator ' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($operator) ? route('updateOperator', base64_encode($operator->id)) : route('storeOperator') }}" method="POST">
                        @csrf
                        @if(isset($operator))
                        @method('PUT')
                        @endif
 
                        <div class="row align-items-end">
                            <div class="col-md-4 col-sm-6 mb-3">
                                <label for="operator_name" class="form-label">Operator Name <span class="mandatory"> *</span></label>
                                <input type="text" class="form-control form-control-sm px-3 py-2" id="operator_name" name="operator_name"
                                    value="{{ old('operator_name', isset($operator) ? $operator->operator_name : '') }}"
                                    placeholder="Enter Operator Name">
                                @error('operator_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
 
                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($operator) ? 'Update' : 'Add' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Form End -->
 
            <!-- List Start -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Operator List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">SrNo.</th>
                                    <th style="width: 60%; text-align: center;">Operator Name</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operators as $o)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $o->operator_name }}</td>
                                    <td>
                                        <form action="{{ route('updateOperatorStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $o->id }}">
                                            <div class="form-check form-switch">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $o->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $o->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
 
 
                                    <td>
                                        <a href="{{ route('editOperator', base64_encode($o->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
 
                                        <a href="{{route('deleteOperator', base64_encode($o->id))}}">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No operators found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
 
            <!-- List End -->
 
        </div>
    </div>
</div>
 
@endsection