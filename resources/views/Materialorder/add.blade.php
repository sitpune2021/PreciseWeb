@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Machine Record Add / Edit Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                {{ isset($record) ? 'Edit Material Order' : 'Add Material Order' }}
                            </h5>
                        </div>
                        <div class="card-body">

                            <form action="{{ isset($record) ? route('UpdateMachinerecord', base64_encode($record->id)) : route('StoreMachinerecord') }}" method="POST">
                                @csrf
                                @if(isset($record))
                                @method('PUT')
                                @endif

                                <div class="row g-3">

                                    <!-- SR NO -->
                                    <div class="col-md-2">
                                        <label class="form-label">SR NO <span class="text-danger">*</span></label>
                                        <input type="number" name="sr_no" class="form-control"
                                            value="{{ old('sr_no', $record->sr_no ?? '') }}">
                                        @error('sr_no')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- DATE -->
                                    <div class="col-md-3">
                                        <label class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control"
                                            value="{{ old('date', isset($record->date) ? \Carbon\Carbon::parse($record->date)->format('Y-m-d') : '') }}">
                                        @error('date')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- WORK ORDER -->
                                    <div class="col-md-4">
                                        <label class="form-label">Work Order <span class="text-danger">*</span></label>
                                        <input type="text" name="work_order" class="form-control"
                                            value="{{ old('work_order', $record->work_order ?? '') }}">
                                        @error('work_order')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-2">
                                        <label class="form-label">DIA</label>
                                        <input type="number" step="0.01" name="dia1" class="form-control"
                                            value="{{ old('dia1', $record->dia1 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" name="l1" class="form-control"
                                            value="{{ old('l1', $record->l1 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">W</label>
                                        <input type="number" step="0.01" name="w1" class="form-control"
                                            value="{{ old('w1', $record->w1 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">H</label>
                                        <input type="number" step="0.01" name="h1" class="form-control"
                                            value="{{ old('h1', $record->h1 ?? '') }}">
                                    </div>

                                    <!-- FINISH SIZE -->

                                    <div class="col-md-2">
                                        <label class="form-label">DIA</label>
                                        <input type="number" step="0.01" name="dia2" class="form-control"
                                            value="{{ old('dia2', $record->dia2 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" name="l2" class="form-control"
                                            value="{{ old('l2', $record->l2 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">W</label>
                                        <input type="number" step="0.01" name="w2" class="form-control"
                                            value="{{ old('w2', $record->w2 ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">H</label>
                                        <input type="number" step="0.01" name="h2" class="form-control"
                                            value="{{ old('h2', $record->h2 ?? '') }}">
                                    </div>

                                    <!-- MATERIAL -->
                                    <div class="col-md-3">
                                        <label class="form-label">Material <span class="text-danger">*</span></label>
                                        <input type="text" name="material" class="form-control"
                                            value="{{ old('material', $record->material ?? '') }}">
                                        @error('material')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- QTY -->
                                    <div class="col-md-2">
                                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                                        <input type="number" name="qty" class="form-control"
                                            value="{{ old('qty', $record->qty ?? '') }}">
                                        @error('qty')
                                        <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <!-- Buttons -->
                                <div class="mt-4 d-flex justify-content-end">
                                    <a href="{{ route('ViewMaterialorder') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($record) ? 'Update' : 'Save' }}
                                    </button>
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