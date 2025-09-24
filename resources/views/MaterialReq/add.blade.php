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
                                        <label for="customer_id" class="form-label">Customer Name <span class="text-red small">*</span></label>
                                        <select class="form-select js-example-basic-single" id="customer_id" name="customer_id">
                                            <option value="">Select Customer</option>
                                            @foreach($codes as $c)
                                            <option value="{{ $c->id }}"
                                                data-code="{{ $c->code }}"
                                                data-id="{{ $c->id }}"
                                                {{ old('customer_id', $materialReq->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }} - ({{ $c->code }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Customer Code -->
                                    <div class="col-md-2">
                                        <label for="code" class="form-label">Customer Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', $materialReq->code ?? '') }}" readonly>
                                    </div>

                                    <!-- Work Order No -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
                                            <input type="text" name="work_order_no" id="work_order_no"
                                                class="form-control"
                                                value="{{ old('work_order_no', $materialReq->work_order_no ?? '') }}" readonly>
                                            @error('work_order_no') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>


                                    <!-- Date -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $materialReq->date ?? '') }}">
                                            @error('date') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description <span class="mandatory">*</span></label>
                                            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $materialReq->description ?? '') }}">
                                            @error('description') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>


                                    <!-- Dia -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="dia" class="form-label">Dia </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="dia"
                                                name="dia"
                                                value="{{ old('dia', $materialReq->dia ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('dia') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Length -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="length" class="form-label">Length</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="length"
                                                name="length"

                                                value="{{ old('length', $materialReq->length ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('length') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Width -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="width" class="form-label">Width </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="width"
                                                name="width"

                                                value="{{ old('width', $materialReq->width ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('width') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Height -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="height" class="form-label">Height <span class="mandatory">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="height"
                                                name="height"
                                                value="{{ old('height', $materialReq->height ?? '') }}"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                            @error('height') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="material_type" class="form-label">Material Type <span class="mandatory">*</span></label>
                                        <select name="material" id="material_type" class="form-control form-select">
                                            <option value="">Select Material</option>
                                            @foreach($materialtype as $mt)
                                            <option value="{{ $mt->id }}"
                                                data-gravity="{{ $mt->material_gravity }}"
                                                data-rate="{{ $mt->material_rate }}"
                                                {{ old('material', $materialReq->material ?? '') == $mt->id ? 'selected' : '' }}>
                                                {{ $mt->material_type }}
                                            </option>
                                            @endforeach
                                        </select>

                                        @error('material')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-2">
                                        <label for="material_gravity" class="form-label">Material Gravity</label>
                                        <input type="text" name="material_gravity" id="material_gravity"
                                            class="form-control"
                                            value="{{ old('material_gravity', $materialReq->material_gravity ?? '') }}" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="material_rate" class="form-label">Material Rate</label>
                                        <input type="text" name="material_rate" id="material_rate"
                                            class="form-control"
                                            value="{{ old('material_rate', $materialReq->material_rate ?? '') }}" readonly>
                                    </div>

                                    <!-- Qty -->
                                    <div class="col-md-2">
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
                                            @error('qty')<span class="text-red small">{{ $message }}</span> @enderror

                                        </div>
                                    </div>


                                    <!-- Weight -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight </label>
                                            <input type="number" step="0.001" name="weight" id="weight" class="form-control" value="{{ old('weight', $materialReq->weight ?? '') }}">
                                            @error('weight') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <label for="cost" class="form-label">Material Cost</label>
                                        <input type="text" name="cost" id="cost"
                                            class="form-control"
                                            value="{{ old('cost', $materialReq->cost ?? '') }}" readonly>
                                    </div>
                                    <!-- Machine Processes -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="lathe" class="form-label">Lathe (hrs) </label>
                                            <input type="number" step="0.01" name="lathe" id="lathe" class="form-control" value="{{ old('lathe', $materialReq->lathe ?? '') }}">
                                            @error('lathe') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="mg4" class="form-label">MG4 (hrs) </label>
                                            <input type="number" step="0.01" name="mg4" id="mg4" class="form-control" value="{{ old('mg4', $materialReq->mg4 ?? '') }}">
                                            @error('mg4') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="mg2" class="form-label">MG2 (hrs) </label>
                                            <input type="number" step="0.01" name="mg2" id="mg2" class="form-control" value="{{ old('mg2', $materialReq->mg2 ?? '') }}">
                                            @error('mg2') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="rg2" class="form-label">RG2 (hrs) </label>
                                            <input type="number" step="0.01" name="rg2" id="rg2" class="form-control" value="{{ old('rg2', $materialReq->rg2 ?? '') }}">
                                            @error('rg2') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="sg4" class="form-label">SG4 (hrs) </label>
                                            <input type="number" step="0.01" name="sg4" id="sg4" class="form-control" value="{{ old('sg4', $materialReq->sg4 ?? '') }}">
                                            @error('sg4') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="sg2" class="form-label">SG2 (hrs) </label>
                                            <input type="number" step="0.01" name="sg2" id="sg2" class="form-control" value="{{ old('sg2', $materialReq->sg2 ?? '') }}">
                                            @error('sg2') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="vmc_hrs" class="form-label">VMC Hours</label>
                                            <input type="number" step="0.01" name="vmc_hrs" id="vmc_hrs" class="form-control" value="{{ old('vmc_hrs', $materialReq->vmc_hrs ?? '') }}">
                                            @error('vmc_hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="vmc_cost" class="form-label">VMC Cost </label>
                                            <input type="number" step="0.01" name="vmc_cost" id="vmc_cost" class="form-control" value="{{ old('vmc_cost', $materialReq->vmc_cost ?? '') }}">
                                            @error('vmc_cost') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>


                                    <!-- EDM -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="edm_qty" class="form-label">EDM Qty </label>
                                            <input type="number" name="edm_qty" id="edm_qty" class="form-control" value="{{ old('edm_qty', $materialReq->edm_qty ?? '') }}">
                                            @error('edm_qty') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="edm_rate" class="form-label">EDM Rate </label>
                                            <input type="number" step="0.01" name="edm_rate" id="edm_rate" class="form-control" value="{{ old('edm_rate', $materialReq->edm_rate ?? '') }}">
                                            @error('edm_rate') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="hrc" class="form-label">HRC </label>
                                            <input type="number" step="0.01" name="hrc" id="hrc" class="form-control" value="{{ old('hrc', $materialReq->hrc ?? '') }}">
                                            @error('hrc') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="cl" class="form-label">CL </label>
                                            <input type="text" name="cl" id="cl" class="form-control" value="{{ old('cl', $materialReq->cl ?? '') }}">
                                            @error('cl') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Total Cost -->
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="total_cost" class="form-label">Total Cost </label>
                                            <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control" value="{{ old('total_cost', $materialReq->total_cost ?? '') }}">
                                            @error('total_cost') <span class="text-red small">{{ $message }}</span> @enderror
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
 
<script>
    $('#material_type').change(function() {
        var id = $(this).val();
        if (id) {
            $.ajax({
                url: '/get-material-details/' + id,
                type: 'GET',
                success: function(data) {
                    $('#material_gravity').val(data.gravity);
                    $('#material_rate').val(data.rate);
                }
            });
        } else {
            $('#material_gravity').val('');
            $('#material_rate').val('');
        }
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Function to round like Excel
        function roundTo2(num) {
            return Math.round(num * 100) / 100;
        }

        function calculate() {
            let length = parseFloat(document.getElementById("length").value) || 0;
            let width = parseFloat(document.getElementById("width").value) || 0;
            let height = parseFloat(document.getElementById("height").value) || 0;
            let qty = parseFloat(document.getElementById("qty").value) || 1;

            let gravity = parseFloat(document.getElementById("material_gravity").value) || 0;
            let rate = parseFloat(document.getElementById("material_rate").value) || 0;

            // Machine / EDM costs
            let edm_qty = parseFloat(document.getElementById("edm_qty")?.value) || 0;
            let edm_rate = parseFloat(document.getElementById("edm_rate")?.value) || 0;

            let lathe = parseFloat(document.getElementById("lathe")?.value) || 0;
            let mg4 = parseFloat(document.getElementById("mg4")?.value) || 0;
            let mg2 = parseFloat(document.getElementById("mg2")?.value) || 0;
            let rg2 = parseFloat(document.getElementById("rg2")?.value) || 0;
            let sg4 = parseFloat(document.getElementById("sg4")?.value) || 0;
            let sg2 = parseFloat(document.getElementById("sg2")?.value) || 0;
            let vmc = parseFloat(document.getElementById("vmc_cost")?.value) || 0;
            let hrc = parseFloat(document.getElementById("hrc")?.value) || 0;

            // 🔹 Volume in mm³
            let volume = length * width * height;

            // 🔹 Weight per piece (Kg)
            let weightPerPiece = (volume * gravity) / 1000000;
            document.getElementById("weight").value = weightPerPiece.toFixed(3);

            // 🔹 Material Cost per piece
            let materialCostPerPiece = weightPerPiece * rate;
            document.getElementById("cost").value = materialCostPerPiece.toFixed(2);

            // 🔹 EDM Cost per piece
            let edmCostPerPiece = edm_qty * edm_rate;

            // 🔹 Machine Cost per piece
            let machineCostPerPiece = lathe + mg4 + mg2 + rg2 + sg4 + sg2 + vmc + hrc;

            // 🔹 Final Total (with qty)
            let totalCost = roundTo2((materialCostPerPiece + edmCostPerPiece + machineCostPerPiece) * qty);
            document.getElementById("total_cost").value = totalCost.toFixed(2);
        }

        // Material select → gravity + rate set
        document.getElementById("material_type").addEventListener("change", function() {
            let gravity = this.options[this.selectedIndex].getAttribute("data-gravity");
            let rate = this.options[this.selectedIndex].getAttribute("data-rate");

            document.getElementById("material_gravity").value = gravity || "";
            document.getElementById("material_rate").value = rate || "";

            calculate();
        });

        // Add input listeners
        ["dia", "length", "width", "height", "qty", "edm_qty", "edm_rate",
            "lathe", "mg4", "mg2", "rg2", "sg4", "sg2", "vmc_cost", "hrc"
        ].forEach(id => {
            let el = document.getElementById(id);
            if (el) el.addEventListener("input", calculate);
        });

        // 🔹 Run on page load (for edit case)
        calculate();
    });
</script>






@endsection