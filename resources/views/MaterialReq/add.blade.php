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

                                <!-- Back Button ONLY on Edit -->
                                <a href="{{ route('ViewMaterialReq') }}" class="btn btn-sm btn-outline-success me-2">
                                    ←
                                </a>

                                <h4 class="mb-0 flex-grow-1">
                                    {{ isset($materialReq) ? 'Edit Material Requirement' : 'Add Material Requirement' }}
                                </h4>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <form action="{{ isset($materialReq) ? route('updateMaterialReq', base64_encode($materialReq->id)) : route('storeMaterialReq') }}" method="POST">
                                    @csrf
                                    @if(isset($materialReq))
                                    @method('PUT')
                                    @endif

                                    <div class="row">
                                        <!-- Customer -->
                                        <!-- <div class="col-md-2">
                                            <label for="customer_id" class="form-label">Customer Code <span class="text-red small">*</span></label>
                                            <select class="form-select js-example-basic-single  mt-1"
                                                id="customer_id"
                                                name="customer_id"
                                                data-selected="{{ old('customer_id', $materialReq->customer_id ?? '') }}"
                                                {{ isset($materialReq) ? 'disabled' : '' }}>
                                                <option value="">Select Code</option>
                                                @foreach($codes as $c)
                                                <option value="{{ $c->id }}"
                                                    data-code="{{ $c->code }}"
                                                    data-id="{{ $c->customer_srno }}"
                                                    {{ old('customer_id', $materialReq->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->code }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror

                                            @if(isset($materialReq))
                                            <input type="hidden" name="customer_id" value="{{ $materialReq->customer_id }}">
                                            @endif
                                        </div> -->

                                        <div class="col-md-2">
                                            <label class="form-label">Work Order <span class="mandatory">*</span></label>
                                            <select name="work_order_id" id="work_order_id" class="form-control form-select js-example-basic-single">
                                                <option value="">Select Work Order</option>
                                                @foreach($parts as $wo)
                                                <option value="{{ $wo->id }}"
                                                    {{ (isset($materialReq) && $materialReq->work_order_id == $wo->id) ? 'selected' : (old('work_order_id') == $wo->id ? 'selected' : '') }}

                                                    data-project="{{ $wo->project?->project_no ?? '' }}"
                                                    data-project-name="{{ $wo->project?->project_name ?? '' }}"
                                                    data-part="{{ $wo->part }}"
                                                    data-qty="{{ $wo->quantity }}"
                                                    data-desc="{{ $wo->part_description }}"

                                                    data-dia="{{ $wo->dimeter ?? '' }}"
                                                    data-length="{{ $wo->length ?? '' }}"
                                                    data-width="{{ $wo->width ?? '' }}"
                                                    data-height="{{ $wo->height ?? '' }}">

                                                    {{ ($wo->customer?->code ?? '') }}_{{ ($wo->project?->project_no ?? '') }}_{{ $wo->part }}_{{ $wo->quantity }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label">Project No</label>
                                            <input type="text" id="project_no" class="form-control mt-1" readonly>
                                        </div>

                                        <!-- Customer Code -->
                                        <!-- <div class="col-md-2">
                                            <label for="code" class="form-label">Code</label>
                                            <input type="text" class="form-control" id="code" name="code"
                                                value="{{ old('code', $materialReq->code ?? '') }}" readonly>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <label class="form-label">Part No </label>
                                            <select name="part_no" id="part_no" class="form-control form-select mt-1 js-example-basic-single">
                                                <option value="">Select Part No</option>
                                            </select>
                                            @error('part_no')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                        </div> -->


                                        <input type="hidden" name="work_order_no" id="work_order_no"
                                            value="{{ old('work_order_no', $materialReq->work_order_no ?? '') }}">

                                        <!-- Date -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $materialReq->date ?? '') }}">
                                                @error('date') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description </label>
                                                <input type="text" name="description" id="description" class="form-control mt-1" value="{{ old('description', $materialReq->description ?? '') }}">
                                                @error('description') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Dia -->
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label for="dia" class="form-label">Dia </label>
                                                <input
                                                    type="text"
                                                    class="form-control mt-1"
                                                    id="dia"
                                                    name="dia"
                                                    value="{{ old('dia', $materialReq->dia ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                @error('dia') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Length -->
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label for="length" class="form-label">Length</label>
                                                <input autocomplete="off"
                                                    type="text"
                                                    class="form-control mt-1"
                                                    id="length"
                                                    name="length"

                                                    value="{{ old('length', $materialReq->length ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                @error('length') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Width -->
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label for="width" class="form-label">Width </label>
                                                <input autocomplete="off"
                                                    type="text"
                                                    class="form-control mt-1"
                                                    id="width"
                                                    name="width"

                                                    value="{{ old('width', $materialReq->width ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                @error('width') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Height -->
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label for="height" class="form-label">Height <span class="mandatory">*</span></label>
                                                <input autocomplete="off"
                                                    type="text"
                                                    class="form-control"
                                                    id="height"
                                                    name="height"
                                                    value="{{ old('height', $materialReq->height ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                @error('height') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="material_type" class="form-label">
                                                Material Type <span class="mandatory">*</span>
                                            </label>

                                            <select name="material" id="material_type" class="form-control form-select">
                                                <option value="">Select Material</option>
                                                @foreach($materialtype as $m)
                                                <option
                                                    value="{{ $m->id }}"
                                                    data-gravity="{{ $m->material_gravity }}"
                                                    data-rate="{{ $m->material_rate }}"
                                                    {{ old('material', $materialReq->material ?? '') == $m->id ? 'selected' : '' }}>
                                                    {{ $m->material_type }}
                                                </option>
                                                @endforeach
                                            </select>

                                            @error('material')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <!-- <div class="col-md-2">
                                            <label for="material_gravity" class="form-label">Material Gravity</label>
                                            <input type="text" name="material_gravity" id="material_gravity"
                                                class="form-control mt-1"
                                                value="{{ old('material_gravity', $materialReq->material_gravity ?? '') }}" readonly>
                                        </div> -->

                                        <div class="col-md-1">
                                            <label for="material_rate" class="form-label">M Rate</label>
                                            <input type="text" name="material_rate" id="material_rate"
                                                class="form-control mt-1"
                                                value="{{ old('material_rate', $materialReq->material_rate ?? '') }}" readonly>
                                        </div>

                                        <!-- Qty -->
                                        <div class="col-md-1">
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
                                        <div class="col-md-1">
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">Weight </label>
                                                <input type="number" step="0.001" name="weight" id="weight" class="form-control mt-1" value="{{ old('weight', $materialReq->weight ?? '') }}">
                                                @error('weight') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <label for="material_cost" class="form-label">M Cost</label>
                                            <input type="text"
                                                name="material_cost"
                                                id="material_cost"
                                                class="form-control mt-1"
                                                value="{{ old('material_cost', $materialReq->material_cost ?? '') }}"

                                                readonly>

                                        </div>
                                        <!-- Machine Processes -->
                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="lathe" class="form-label">Lathe (hrs) </label>
                                                <input type="number" step="0.01" name="lathe" id="lathe" placeholder="Turning" class="form-control mt-1" value="{{ old('lathe', $materialReq->lathe ?? '') }}">
                                                @error('lathe') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="mg4" class="form-label">MG4 (hrs) </label>
                                                <input type="number" step="0.01" name="mg4" id="mg4" placeholder="Milling (4 Sides)" class="form-control" value="{{ old('mg4', $materialReq->mg4 ?? '') }}">
                                                @error('mg4') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="mg2" class="form-label">MG2 (hrs) </label>
                                                <input type="number" step="0.01" name="mg2" id="mg2" placeholder="Milling (2 Sides)" class="form-control" value="{{ old('mg2', $materialReq->mg2 ?? '') }}">
                                                @error('mg2') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="rg2" class="form-label">RG2 (hrs) </label>
                                                <input type="number" step="0.01" name="rg2" id="rg2" placeholder="Rotary Granding (2 Sides)" class="form-control" value="{{ old('rg2', $materialReq->rg2 ?? '') }}">
                                                @error('rg2') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="sg4" class="form-label">SG4 (hrs) </label>
                                                <input type="number" step="0.01" name="sg4" id="sg4" placeholder="Surface Granding (4 Sides)" class="form-control" value="{{ old('sg4', $materialReq->sg4 ?? '') }}">
                                                @error('sg4') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="sg2" class="form-label">SG2 (hrs) </label>
                                                <input type="number" step="0.01" name="sg2" id="sg2" placeholder="Surface Granding (2 Sides)" class="form-control" value="{{ old('sg2', $materialReq->sg2 ?? '') }}">
                                                @error('sg2') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="vmc_hrs" class="form-label">VMC Hours</label>
                                                <input type="number" step="0.01" name="vmc_hrs" id="vmc_hrs" class="form-control" value="{{ old('vmc_hrs', $materialReq->vmc_hrs ?? '') }}">
                                                @error('vmc_hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="vmc_cost" class="form-label">VMC Cost </label>
                                                <input type="number" step="0.01" name="vmc_cost" id="vmc_cost" class="form-control" value="{{ old('vmc_cost', $materialReq->vmc_cost ?? '') }}">
                                                @error('vmc_cost') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- EDM -->
                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="edm_qty" class="form-label">EDM Qty </label>
                                                <input type="number" name="edm_qty" id="edm_qty" class="form-control" value="{{ old('edm_qty', $materialReq->edm_qty ?? '') }}">
                                                @error('edm_qty') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="edm_rate" class="form-label">EDM Rate </label>
                                                <input type="number" step="0.01" name="edm_rate" id="edm_rate" class="form-control" value="{{ old('edm_rate', $materialReq->edm_rate ?? '') }}">
                                                @error('edm_rate') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="hrc" class="form-label">HRC </label>
                                                <input type="number" step="0.01" name="hrc" id="hrc" placeholder="Hardening" class="form-control" value="{{ old('hrc', $materialReq->hrc ?? '') }}">
                                                @error('hrc') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="cl" class="form-label">CL </label>
                                                <input type="text" name="cl" id="cl" class="form-control" placeholder="Wirecutting Length" value="{{ old('cl', $materialReq->cl ?? '') }}">
                                                @error('cl') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

                                        <!-- Total Cost -->
                                        <!-- <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="total_cost" class="form-label">Total Cost </label>
                                                <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control mt-1" value="{{ old('total_cost', $materialReq->total_cost ?? '') }}">
                                                @error('total_cost') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#material_type').change(function() {
                // alert("hi");
                var id = $(this).val();

                if (id) {
                    $.ajax({
                        url: '/get-material/' + id,
                        type: 'GET',
                        success: function(data) {
                            console.log(data);
                            $('#material_gravity').val(data.gravity);
                            $('#material_rate').val(data.rate);
                        }
                    });
                } else {
                    $('#material_gravity').val('');
                    $('#material_rate').val('');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            function calculate() {

                let dia = parseFloat($("#dia").val()) || 0;
                let len = parseFloat($("#length").val()) || 0;
                let width = parseFloat($("#width").val()) || 0;
                let height = parseFloat($("#height").val()) || 0;

                let sg = parseFloat($("#material_type option:selected").data("gravity")) || 0;
                let rate = parseFloat($("#material_type option:selected").data("rate")) || 0;

                let qty = parseFloat($("#qty").val()) || 1;


                if (!sg || !rate) {
                    $("#weight").val('');
                    $("#material_cost").val('');
                    return;
                }

                //  Volume
                let volume = dia > 0 ?
                    Math.PI * Math.pow(dia / 2, 2) * height :
                    len * width * height;

                //  Weight (per piece)
                let weight_per_piece = (volume * sg) / 1000000;

                //  Total Weight
                let total_weight = weight_per_piece * qty;

                //  FINAL MATERIAL COST
                let material_cost = weight_per_piece * rate * qty;

                $("#weight").val(total_weight.toFixed(3));
                $("#material_cost").val(material_cost.toFixed(2));
            }

            $("#material_type").on("change", function() {
                let sg = $(this).find(":selected").data("gravity") || 0;
                let rate = $(this).find(":selected").data("rate") || 0;

                $("#material_gravity").val(sg);
                $("#material_rate").val(rate);

                calculate(true);
            });

            $("#vmc_hrs").on("input", function() {
                let hrs = parseFloat($(this).val()) || 0;
                $("#vmc_cost").val((hrs * 450).toFixed(2));
                calculate(false);
            });

            $("#dia, #length, #width, #height, #material_gravity, #material_rate, #qty, #lathe, #vmc_cost, #edm_rate, #cl")
                .on("input change", function() {

                    $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").each(function() {
                        if ($(this).val().trim() === "") {
                            $(this).data("manual", false);
                        }
                    });

                    calculate(true);
                });

            $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").on("input", function() {
                $(this).data("manual", true);
                calculate(false);
            });

            $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").each(function() {
                let val = $(this).val().trim();

                if (val !== "" && !isNaN(parseFloat(val))) {
                    $(this).data("manual", true);
                } else {
                    $(this).data("manual", false);
                }
            });

            calculate(true);

        });
    </script>

    <script>
        $(document).ready(function() {

            function fillWorkOrderData() {
                let selected = $('#work_order_id').find(':selected');
                if (!selected.val()) return;

                $('#description').val(selected.data('desc') || '');
                $('#project_no').val(selected.data('project') || '');
                $('#qty').val(selected.data('qty') || '');
                $('#dia').val(selected.data('dia') || '');
                $('#length').val(selected.data('length') || '');
                $('#width').val(selected.data('width') || '');
                $('#height').val(selected.data('height') || '');
            }

            $('#work_order_id').on('change select2:select', function() {
                fillWorkOrderData();
            });

            // Page load
            fillWorkOrderData();
        });
    </script>

    @endsection