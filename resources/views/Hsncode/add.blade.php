@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($hsn) ? 'Edit HSN Code' : 'Add HSN/SAC Code' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($hsn) ? route('updateHsn', base64_encode($hsn->id)) : route('storeHsn') }}" method="POST">
                        @csrf
                        @if(isset($hsn))
                        @method('PUT')
                        @endif

                        <div class="row g-3">
                            <!-- HSN Code -->
                            <div class="col-md-3">
                                <label for="hsn_code" class="form-label">HSN Code <span class="text-red">*</span></label>
                                <input type="text" class="form-control"
                                    name="hsn_code" value="{{ old('hsn_code', $hsn->hsn_code ?? '') }}"
                                    placeholder="Enter HSN Code">
                                <small class="text-red d-block" style="min-height:18px;">
                                    @error('hsn_code') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- SGST -->
                            <!-- SGST -->
                            <div class="col-md-3">
                                <label for="sgst" class="form-label">SGST % <span class="text-red">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control only-positive"
                                    name="sgst" value="{{ old('sgst', $hsn->sgst ?? '') }}"
                                    placeholder="SGST %">
                                <small class="text-red d-block" style="min-height:18px;">
                                    @error('sgst') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- CGST -->
                            <div class="col-md-3">
                                <label for="cgst" class="form-label">CGST % <span class="text-red">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control only-positive"
                                    name="cgst" value="{{ old('cgst', $hsn->cgst ?? '') }}"
                                    placeholder="CGST %">
                                <small class="text-red d-block" style="min-height:18px;">
                                    @error('cgst') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- IGST -->
                            <div class="col-md-3">
                                <label for="igst" class="form-label">IGST % <span class="text-red">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control only-positive"
                                    name="igst" value="{{ old('igst', $hsn->igst ?? '') }}"
                                    placeholder="IGST %">
                                <small class="text-red d-block" style="min-height:18px;">
                                    @error('igst') {{ $message }} @enderror
                                </small>
                            </div>
                            <!-- Invoice Description -->
                            <div class="col-md-6">
                                <label for="invoice_desc" class="form-label">Invoice Description<span class="text-red">*</span></label>
                                <input type="text" class="form-control"
                                    name="invoice_desc" value="{{ old('invoice_desc', $hsn->invoice_desc ?? '') }}"
                                    placeholder="Enter Invoice Description">
                                <small class="text-red d-block" style="min-height:18px;">
                                    @error('invoice_desc') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-2 d-flex">
                                <button type="submit" class="btn btn-primary mt-2 w-100 align-self-center">
                                    <i class="fas fa-save me-1"></i> {{ isset($hsn) ? 'Update' : 'Add' }}
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
                    <h5 class="mb-0">HSN Code List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>HSN Code</th>
                                    <th>SGST %</th>
                                    <th>CGST %</th>
                                    <th>IGST %</th>
                                    <th>Invoice Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hsncodes as $h)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $h->hsn_code }}</td>
                                    <td>{{ $h->sgst }}%</td>
                                    <td>{{ $h->cgst }}%</td>
                                    <td>{{ $h->igst }}%</td>
                                    <td>{{ $h->invoice_desc }}</td>
                                    <td>
                                        <form action="{{ route('updateHsnStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $h->id }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    onchange="this.form.submit()" {{ $h->status ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('editHsn', base64_encode($h->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                        <a href="{{ route('deleteHsn', base64_encode($h->id)) }}"
                                            onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                                            <i class="ri-delete-bin-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-muted">No HSN Codes Found</td>
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
<script>
    document.querySelectorAll('.only-positive').forEach(input => {
        input.addEventListener('input', function() {

            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    });
</script>
@endsection