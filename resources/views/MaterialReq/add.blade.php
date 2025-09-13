@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">

                        <!-- Header -->
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($materialReq) ? 'Edit Material Requirement' : 'Add Material Requirement' }}
                            </h4>
                        </div>


                        <!-- Body -->
                        <div class="card-body">
                            <form action="{{ isset($materialReq) ? route('updateMaterialReq', base64_encode($materialReq->id)) : route('storeMaterialReq') }}" method="POST">
                                @csrf
                                @if(isset($materialReq))
                                @method('PUT')
                                @endif

                                <div class="row">

                                    <!-- Customer -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                            <select class="form-select js-example-basic-single" id="customer_id" name="customer_id">
                                                <option value="">Select Customer</option>
                                                @foreach($codes as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ old('customer_id', $materialReq->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }} - ({{ $c->code }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <span class="text-red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Code -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Code <span class="mandatory">*</span></label>
                                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $materialReq->code ?? '') }}">
                                            @error('code') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Date -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $materialReq->date ?? '') }}">
                                            @error('date') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description <span class="mandatory">*</span></label>
                                            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $materialReq->description ?? '') }}">
                                            @error('description') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Work Order No -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
                                            <input type="text" name="work_order_no" id="work_order_no" class="form-control" value="{{ old('work_order_no', $materialReq->work_order_no ?? '') }}">
                                            @error('work_order_no') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <!-- Dia -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="dia" class="form-label">Dia <span class="mandatory">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="dia"
                                                name="dia"
                                                value="{{ old('dia', $materialReq->dia ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('dia') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Length -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="length" class="form-label">Length <span class="mandatory">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="length"
                                                name="length"

                                                value="{{ old('length', $materialReq->length ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('length') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Width -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="width" class="form-label">Width <span class="mandatory">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="width"
                                                name="width"

                                                value="{{ old('width', $materialReq->width ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('width') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Height -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height <span class="mandatory">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="height"
                                                name="height"
                                                value="{{ old('height', $materialReq->height ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('height') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Material -->
                                    <!-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="material" class="form-label">Material <span class="mandatory">*</span></label>
                                            <select name="material" id="material" class="form-control">
                                                <option value="">-- Select Material --</option>
                                                <option value="Steel" {{ old('material', $materialReq->material ?? '')=='Steel' ? 'selected' : '' }}>Steel</option>
                                                <option value="Aluminium" {{ old('material', $materialReq->material ?? '')=='Aluminium' ? 'selected' : '' }}>Aluminium</option>
                                                <option value="Copper" {{ old('material', $materialReq->material ?? '')=='Copper' ? 'selected' : '' }}>Copper</option>
                                            </select>
                                            @error('material') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div> -->



                                    <div class="col-md-3">
                                        <label for="material" class="form-label">Material <span class="mandatory">*</span></label>
                                        <select name="material" id="material" class="form-control">
                                            <option value="">Select Material</option>
                                            @foreach($materialtype as $mt)
                                            <option value="{{ $mt->material_type }}"
                                                {{ old('material', $materialReq->material ?? '') == $mt->material_type ? 'selected' : '' }}>
                                                {{ $mt->material_type }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('material') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>


                                    <!-- Qty -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="qty" class="form-label">Quantity <span class="mandatory">*</span></label>
                                            <input
                                                type="number"
                                                step="1"
                                                min="1"
                                                class="form-control"
                                                id="qty"
                                                name="qty"
                                                value="{{ old('qty', $materialReq->qty ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,5)">
                                            @error('qty')
                                            <span class="text-red">{{ $message }}</span>
                                            @enderror
                                            <span class="text-red qty"></span>
                                        </div>
                                    </div>


                                    <!-- Weight -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight <span class="mandatory">*</span></label>
                                            <input type="number" step="0.001" name="weight" id="weight" class="form-control" value="{{ old('weight', $materialReq->weight ?? '') }}">
                                            @error('weight') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Machine Processes -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="lathe" class="form-label">Lathe (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="lathe" id="lathe" class="form-control" value="{{ old('lathe', $materialReq->lathe ?? '') }}">
                                            @error('lathe') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="mg4" class="form-label">MG4 (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="mg4" id="mg4" class="form-control" value="{{ old('mg4', $materialReq->mg4 ?? '') }}">
                                            @error('mg4') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="mg2" class="form-label">MG2 (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="mg2" id="mg2" class="form-control" value="{{ old('mg2', $materialReq->mg2 ?? '') }}">
                                            @error('mg2') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="rg2" class="form-label">RG2 (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="rg2" id="rg2" class="form-control" value="{{ old('rg2', $materialReq->rg2 ?? '') }}">
                                            @error('rg2') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="sg4" class="form-label">SG4 (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="sg4" id="sg4" class="form-control" value="{{ old('sg4', $materialReq->sg4 ?? '') }}">
                                            @error('sg4') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="sg2" class="form-label">SG2 (hrs) <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="sg2" id="sg2" class="form-control" value="{{ old('sg2', $materialReq->sg2 ?? '') }}">
                                            @error('sg2') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vmc_hrs" class="form-label">VMC Hours</label>
                                            <input type="number" step="0.01" name="vmc_hrs" id="vmc_hrs" class="form-control" value="{{ old('vmc_hrs', $materialReq->vmc_hrs ?? '') }}">
                                            @error('vmc_hrs') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vmc_cost" class="form-label">VMC Cost <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="vmc_cost" id="vmc_cost" class="form-control" value="{{ old('vmc_cost', $materialReq->vmc_cost ?? '') }}">
                                            @error('vmc_cost') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="hrc" class="form-label">HRC <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="hrc" id="hrc" class="form-control" value="{{ old('hrc', $materialReq->hrc ?? '') }}">
                                            @error('hrc') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- EDM -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="edm_qty" class="form-label">EDM Qty <span class="mandatory">*</span></label>
                                            <input type="number" name="edm_qty" id="edm_qty" class="form-control" value="{{ old('edm_qty', $materialReq->edm_qty ?? '') }}">
                                            @error('edm_qty') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="edm_rate" class="form-label">EDM Rate <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="edm_rate" id="edm_rate" class="form-control" value="{{ old('edm_rate', $materialReq->edm_rate ?? '') }}">
                                            @error('edm_rate') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="cl" class="form-label">CL <span class="mandatory">*</span></label>
                                            <input type="text" name="cl" id="cl" class="form-control" value="{{ old('cl', $materialReq->cl ?? '') }}">
                                            @error('cl') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Total Cost -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="total_cost" class="form-label">Total Cost <span class="mandatory">*</span></label>
                                            <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control" value="{{ old('total_cost', $materialReq->total_cost ?? '') }}">
                                            @error('total_cost') <span class="text-red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">{{ isset($materialReq) ? 'Update' : 'Submit' }}</button>
                                        @if(isset($materialReq))
                                        <a href="{{ route('ViewMaterialReq') }}" class="btn btn-info">Cancel</a>
                                        @else
                                        <button type="reset" class="btn btn-info">Reset</button>
                                        @endif
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

@endsection