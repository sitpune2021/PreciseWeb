@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Material Order Add / Edit Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                {{ isset($record) ? 'Edit Material Order' : 'Add Material Order' }}
                            </h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ isset($record) ? route('updateMaterialorder', base64_encode($record->id)) : route('storeMaterialorder') }}" method="POST">
                                @csrf
                                @if(isset($record))
                                    @method('PUT')
                                @endif

                                <div class="row g-3">

                                    <!-- SR NO -->
                                    <div class="col-md-2">
                                        <label class="form-label">SR NO <span class="text-red">*</span></label>
                                        <input type="number" name="sr_no" class="form-control"
                                            value="{{ old('sr_no', $record->sr_no ?? '') }}">
                                        @error('sr_no')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- DATE -->
                                    <div class="col-md-3">
                                        <label class="form-label">Date <span class="text-red">*</span></label>
                                        <input type="date" name="date" class="form-control"
                                            value="{{ old('date', isset($record->date) ? \Carbon\Carbon::parse($record->date)->format('Y-m-d') : '') }}">
                                        @error('date')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- WORK ORDER -->
                                    <div class="col-md-7">
                                        <label class="form-label">Work Order Description <span class="text-red">*</span></label>
                                        <input type="text" name="work_order_desc" class="form-control"
                                            value="{{ old('work_order_desc', $record->work_order_desc ?? '') }}">
                                        @error('work_order_desc')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- FINISH SIZE SECTION -->
                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold">Finish Size</h5>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Diameter</label>
                                        <input type="number" step="0.01" name="f_diameter" class="form-control"
                                            value="{{ old('f_diameter', $record->f_diameter ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" name="f_length" class="form-control"
                                            value="{{ old('f_length', $record->f_length ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Width</label>
                                        <input type="number" step="0.01" name="f_width" class="form-control"
                                            value="{{ old('f_width', $record->f_width ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Height</label>
                                        <input type="number" step="0.01" name="f_height" class="form-control"
                                            value="{{ old('f_height', $record->f_height ?? '') }}">
                                    </div>

                                     <!-- MATERIAL -->
                                    

                                    <!-- RAW SIZE SECTION -->
                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold">Raw Size</h5>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Diameter</label>
                                        <input type="number" step="0.01" name="r_diameter" class="form-control"
                                            value="{{ old('r_diameter', $record->r_diameter ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" name="r_length" class="form-control"
                                            value="{{ old('r_length', $record->r_length ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Width</label>
                                        <input type="number" step="0.01" name="r_width" class="form-control"
                                            value="{{ old('r_width', $record->r_width ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Height</label>
                                        <input type="number" step="0.01" name="r_height" class="form-control"
                                            value="{{ old('r_height', $record->r_height ?? '') }}">
                                    </div>

                                   <div class="col-md-3">
                                        <label class="form-label">Material<span class="text-red">*</span></label>
                                        <input type="text" name="material" class="form-control"
                                            value="{{ old('material', $record->material ?? '') }}">
                                        @error('material')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- QTY -->
                                    <div class="col-md-2">
                                        <label class="form-label">Quantity <span class="text-red">*</span></label>
                                        <input type="number" name="quantity" class="form-control"
                                            value="{{ old('quantity', $record->quantity ?? '') }}">
                                        @error('quantity')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                 <!-- Buttons -->
                                    <div class="col-lg-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            {{ isset($record) ? 'Update' : 'Submit' }}
                                        </button>
                                        &nbsp;
                                        @if(isset($record))
                                        <a href="{{ route('ViewMaterialorder') }}" class="btn btn-info">Cancel</a>
                                        @else
                                        <button type="reset" class="btn btn-info">Reset</button>
                                        @endif
                                    </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
</div> <!-- main-content -->

@endsection
