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
                                    <input type="hidden"
                                        id="material_gravity"
                                        name="material_gravity"
                                        value="{{ old('material_gravity', $materialReq->material_gravity ?? '') }}">
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
                                                value="{{ old('material_rate', $materialReq->material_rate ?? '') }}">
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
                                                <input type="text" name="weight" id="weight" class="form-control mt-1" value="{{ old('weight', $materialReq->weight ?? '') }}" readonly>
                                                @error('weight') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <label for="material_cost" class="form-label">M Cost</label>
                                            <input type="text"
                                                name="material_cost"
                                                id="material_cost"
                                                class="form-control mt-1"
                                                value="{{ old('material_cost', $materialReq->material_cost ?? '') }}" readonly>

                                        </div>
                                        <!-- Machine Processes -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="lathe" class="form-label">Lathe (hrs) </label>
                                                <input type="number" step="0.01" name="lathe" id="lathe" placeholder="Turning" class="form-control mt-1" value="{{ old('lathe', $materialReq->lathe ?? '') }}">
                                                @error('lathe') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="mg4" class="form-label">MG4 (hrs) </label>
                                                <input type="number" step="0.01" name="mg4" id="mg4" placeholder="Milling" class="form-control" value="{{ old('mg4', $materialReq->mg4 ?? '') }}">
                                                @error('mg4') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="mg2" class="form-label">MG2 (hrs) </label>
                                                <input type="number" step="0.01" name="mg2" id="mg2" placeholder="Milling" class="form-control" value="{{ old('mg2', $materialReq->mg2 ?? '') }}">
                                                @error('mg2') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="rg2" class="form-label">RG2 (hrs) </label>
                                                <input type="number" step="0.01" name="rg2" id="rg2" placeholder="Rotary Granding" class="form-control" value="{{ old('rg2', $materialReq->rg2 ?? '') }}">
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

                                        @php
                                        $vmcRate = $rates->first() ?? 0;
                                        @endphp
                                        <input type="hidden" id="vmc_rate" value="{{ $vmcRate }}">

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
                                                <input type="number" step="0.01" name="vmc_cost" id="vmc_cost" class="form-control" value="{{ old('vmc_cost', $materialReq->vmc_cost ?? '') }}" readonly>
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
                                                <input type="text" name="edm_rate" id="edm_rate" class="form-control" value="{{ old('edm_rate', $materialReq->edm_rate ?? '') }}" readonly>
                                                @error('edm_rate') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="hrc" class="form-label">HRC </label>
                                                <input type="number" step="0.01" name="hrc" id="hrc" placeholder="Hardening" class="form-control" value="{{ old('hrc', $materialReq->hrc ?? '') }}">
                                                @error('hrc') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="cl" class="form-label">CL </label>
                                                <input type="text" name="cl" id="cl" class="form-control" placeholder="Wirecutting Length" value="{{ old('cl', $materialReq->cl ?? '') }}">
                                                @error('cl') <span class="text-red small">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label>Wirecut Rate</label>
                                                <input type="text" name="wirecut_rate" id="wirecut_rate"
                                                    class="form-control mt-1"
                                                    value="{{ old('wirecut_rate', $materialReq->wirecut_rate ?? '') }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label>Column 1</label>
                                                <input type="number" step="0.01" name="column1" id="column1"
                                                    class="form-control mt-1"
                                                    value="{{ old('column1', $materialReq->column1 ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label>Column 2</label>
                                                <input type="number" step="0.01" name="column2" id="column2"
                                                    class="form-control mt-1"
                                                    value="{{ old('column2', $materialReq->column2 ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- Total Cost -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="total_cost" class="form-label">Total Cost </label>
                                                <input type="text" name="total_cost" id="total_cost" class="form-control mt-1" value="{{ old('total_cost', $materialReq->total_cost ?? '') }}">
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
                            // $('#material_rate').val(data.rate);
                        }
                    });
                } else {
                    $('#material_gravity').val('');
                    // $('#material_rate').val('');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            function calculate(auto = true) {

                let dia = parseFloat($("#dia").val()) || 0;
                let len = parseFloat($("#length").val()) || 0;
                let withs = parseFloat($("#width").val()) || 0;
                let heigh = parseFloat($("#height").val()) || 0;
                let sg = parseFloat($("#material_gravity").val()) || 0;
                let rate = parseFloat($("#material_rate").val()) || 0;
                let qty = parseFloat($("#qty").val()) || 1;

                let lathe = parseFloat($("#lathe").val()) || 0;
                let vmc = parseFloat($("#vmc_cost").val()) || 0;
                let edmqty = parseFloat($("#edm_qty").val()) || 0;
                let cl = parseFloat($("#cl").val()) || 0;

                let mg4 = parseFloat($("#mg4").val()) || 0;
                let mg2 = parseFloat($("#mg2").val()) || 0;
                let rg2 = parseFloat($("#rg2").val()) || 0;
                let sg4 = parseFloat($("#sg4").val()) || 0;
                let sg2 = parseFloat($("#sg2").val()) || 0;

                let col1 = parseFloat($("#column1").val()) || 0;
                let col2 = parseFloat($("#column2").val()) || 0;

                // HRC VALUE
                let hrcVal = parseFloat($("#hrc").val()) || 0;

                // MATERIAL WEIGHT
                let material_wt = 0;

                // ROUND MATERIAL
                if (dia > 0) {
                    material_wt =
                        ((Math.PI * (dia / 2) * (dia / 2) * heigh) / 1000000) * sg;
                }
                // BLOCK MATERIAL
                else {
                    material_wt =
                        ((len * withs * heigh) / 1000000) * sg;
                }

                // MATERIAL COST
                let mt_cost = material_wt * rate;

                // AUTO FORMULA
                if (auto && !$("#mg4").data("manual"))
                    mg4 = (((len * heigh) + (withs * heigh)) * 2 * 0.5 / 100);

                if (auto && !$("#mg2").data("manual"))
                    mg2 = ((len * withs) * 2 * 0.5 / 100);

                if (auto && !$("#rg2").data("manual"))
                    rg2 = ((len * withs) * 2 * 0.3 / 100);

                if (auto && !$("#sg4").data("manual"))
                    sg4 = (((len * heigh) + (withs * heigh)) * 2 * 0.6 / 100);

                if (auto && !$("#sg2").data("manual"))
                    sg2 = ((len * withs) * 2 * 0.6 / 100);

                // AUTO HRC
                if (auto && !$("#hrc").data("manual"))
                    hrcVal = Math.round((material_wt * 70) * 10) / 10;

                // EDM
                let edm = edmqty * heigh * 6;

                // WIRECUT
                let wirecut = cl * 0.2 * heigh;

                // TOTAL PER PIECE
                let total_per_piece =
                    lathe +
                    mg4 +
                    mg2 +
                    rg2 +
                    sg4 +
                    sg2 +
                    vmc +
                    edm +
                    wirecut +
                    hrcVal +
                    mt_cost +
                    col1 +
                    col2;

                // FINAL TOTAL
                let total_cost = total_per_piece * qty;

                // UI UPDATE
                $("#weight").val(parseFloat(material_wt.toFixed(3)));
                $("#material_cost").val(parseFloat(mt_cost.toFixed(2)));
                $("#edm_rate").val(parseFloat(edm.toFixed(2)));
                $("#wirecut_rate").val(parseFloat(wirecut.toFixed(2)));
                $("#total_cost").val(parseFloat(total_cost.toFixed(2)));

                // AUTO FIELD UPDATE
                if (auto) {

                    if (!$("#mg4").data("manual"))
                        $("#mg4").val(mg4.toFixed(2));

                    if (!$("#mg2").data("manual"))
                        $("#mg2").val(mg2.toFixed(2));

                    if (!$("#rg2").data("manual"))
                        $("#rg2").val(rg2.toFixed(2));

                    if (!$("#sg4").data("manual"))
                        $("#sg4").val(sg4.toFixed(2));

                    if (!$("#sg2").data("manual"))
                        $("#sg2").val(sg2.toFixed(2));

                    if (!$("#hrc").data("manual"))
                        $("#hrc").val(hrcVal.toFixed(2));
                }
            }
            // MATERIAL CHANG
            $('#material_type').change(function() {

                let sg = $(this).find(":selected").data("gravity") || 0;

                $("#material_gravity").val(sg);

                calculate(true);
            });
            // VM
            $("#vmc_hrs").on("input", function() {

                let hrs = parseFloat($(this).val()) || 0;
                let rate = parseFloat($("#vmc_rate").val()) || 0;

                $("#vmc_cost").val((hrs * rate).toFixed(2));

                calculate(true);
            });
            // AUTO INPUT
            $("#dia, #length, #width, #height, #material_gravity, #material_rate, #qty, #lathe, #vmc_cost, #edm_qty, #cl, #column1, #column2")
                .on("input change", function() {

                    $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").each(function() {

                        if ($(this).val().trim() === "") {
                            $(this).data("manual", false);
                        }
                    });

                    calculate(true);
                });
            // MANUAL INPUT
            $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").on("input", function() {

                $(this).data("manual", true);

                calculate(false);
            });
            // EDIT MODE HANDL
            $("#mg4, #mg2, #rg2, #sg4, #sg2, #hrc").each(function() {

                let val = $(this).val().trim();

                if (val !== "" && !isNaN(parseFloat(val))) {
                    $(this).data("manual", true);
                } else {
                    $(this).data("manual", false);
                }
            });
            // INITIAL LOA
            calculate(true);

        });
    </script>

    <script>
        $(document).ready(function() {

            let isAutoFilled = false;

            function fillWorkOrderData() {

                let selected = $('#work_order_id').find(':selected');

                if (!selected.val()) return;

                // EDIT mode la existing DB values overwrite nako
                let isEdit = "{{ isset($materialReq) ? 1 : 0 }}";

                if (isEdit == 1) {

                    $('#project_no').val(selected.data('project') || '');

                    return;
                }

                // ADD mode only
                $('#description').val(selected.data('desc') || '');
                $('#project_no').val(selected.data('project') || '');
                $('#qty').val(selected.data('qty') || '');
                $('#dia').val(selected.data('dia') || '');
                $('#length').val(selected.data('length') || '');
                $('#width').val(selected.data('width') || '');
                $('#height').val(selected.data('height') || '');
            }

            // When user changes Work Order → reset autofill
            $('#work_order_id').on('change select2:select', function() {
                isAutoFilled = false; // reset
                fillWorkOrderData();
            });

            // Page load (edit mode handle)
            fillWorkOrderData();
        });
    </script>

    @endsection