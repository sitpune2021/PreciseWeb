@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($materialtype) ? 'Edit Material Type ' : 'Add Material Type ' }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ isset($materialtype) ? route('updateMaterialType', base64_encode($materialtype->id)) : route('storeMaterialType') }}" method="POST">
                        @csrf
                        @if(isset($materialtype))
                        @method('PUT')
                        @endif

                        <div class="row align-items-end">
                            <!-- Material Type -->
                            <div class="col-md-3 col-sm-6 mb-3 position-relative">
                                <label for="material_type" class="form-label">
                                    Material Type <span class="mandatory"> *</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="material_type"
                                    name="material_type"
                                    value="{{ old('material_type', isset($materialtype) ? $materialtype->material_type : '') }}"
                                    placeholder="Enter Material Type">
                                @error('material_type')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;"">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                             <div class=" col-md-3 col-sm-6 mb-3 position-relative">
                                    <label for="material_gravity" class="form-label">
                                        Material Gravity <span class="mandatory">*</span>
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control form-control-sm px-3 py-2"
                                        id="material_gravity"
                                        name="material_gravity"
                                        value="{{ old('material_gravity', isset($materialtype) ? $materialtype->material_gravity : '') }}"
                                        placeholder="Enter Material Gravity">
                                    @error('material_gravity')
                                    <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                        {{ $message }}
                                    </small>
                                    @enderror
                            </div>

                            <!-- Material Rate -->
                            <div class=" col-md-3 col-sm-6 mb-3 position-relative">
                                <label for="material_rate" class="form-label">
                                    Material Rate <span class="mandatory"> *</span>
                                </label>
                                <input type="number"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="material_rate"
                                    name="material_rate"
                                    value="{{ old('material_rate', isset($materialtype) ? $materialtype->material_rate : '') }}"
                                    placeholder="Enter Material Rate">
                                @error('material_rate')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;"">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
 
                            <div class=" col-md-2 col-sm-6 mb-3">
                                    <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                        {{ isset($materialtype) ? 'Update' : 'Add' }}
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
                    <h5 class="mb-0">Material Type List</h5>
                    <a href="{{ route('trashMaterialType') }}" class="btn btn-warning btn-sm">View Trash</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Material Type</th>
                                    <th class="text-center">Material Gravity</th>
                                    <th class="text-center">Material Rate</th>
                                    <th style="width: 15%;">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materialtypes as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $m->material_type }}</td>
                                    <td class="text-center">{{ $m->material_gravity }}</td>
                                    <td class="text-center">₹ {{ number_format($m->material_rate, 2) }}</td>
                                    <td>
                                        <form action="{{ route('updateMaterialStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $m->id }}">
                                            <input type="hidden" name="status" value="0"> {{-- default value --}}
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $m->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $m->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>

                                    </td>
                                    <td>
                                        @if(hasPermission('MaterialType', 'edit'))
                                        <a href="{{ route('editMaterialType', base64_encode($m->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                        @endif
                                        
                                        @if(hasPermission('MaterialType', 'delete'))
                                        <a href="{{route('deleteMaterialType', base64_encode($m->id))}}"
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
                                    <td colspan="4" class="text-center text-muted">No Material Types found.</td>
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