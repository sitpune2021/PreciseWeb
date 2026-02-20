@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm mb-4">         
                <div class="card-header d-flex align-items-center">
                    <!-- Back Button ONLY on Edit -->
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">
                        ←
                    </a>
                    <h5 class="mb-0">{{ isset($year) ? 'Edit Financial Year' : 'Add Financial Year' }}</h5>
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

                    <form action="{{ isset($year) ? route('UpdateFinancialYear', base64_encode($year->id)) : route('StoreFinancialYear') }}" method="POST">
                        @csrf
                        @if(isset($year))
                        @method('PUT')
                        @endif

                        <div class="row align-items-end">
                            <div class="col-md-4 col-sm-6 mb-3 position-relative">
                                <label for="year" class="form-label">
                                    Financial Year <span class="mandatory">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="year"
                                    name="year"
                                    value="{{ old('year', isset($year) ? $year->year : '') }}"
                                    placeholder="Ex: 2024-25"
                                    style="background-image: none !important;">
                                @error('year')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($year) ? 'Update' : 'Add' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Financial Year List</h5>

                    <a href="{{ route('trashFinancial') }}" class="btn btn-warning btn-sm">
                        View Trash
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered align-middle text-center" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sr.No</th>
                                    <th style="width: 50%;">Year</th>
                                    <th style="width: 15%;">Status</th>
                                    @if(
                                    hasPermission('FinancialYear', 'edit') ||
                                    hasPermission('FinancialYear', 'delete')
                                    )
                                    <th width="12%">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($years as $y)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $y->year }}</td>
                                    <td>
                                        <form action="{{ route('FinancialYearStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $y->id }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $y->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $y->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        @if(hasPermission('FinancialYear', 'edit'))
                                        <a href="{{ route('EditFinancialYear', base64_encode($y->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                        @endif

                                        @if(hasPermission('FinancialYear', 'delete'))
                                        <a href="{{ route('DeleteFinancialYear', base64_encode($y->id)) }}"
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
                                    <td colspan="4" class="text-center text-muted">No financial years found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection