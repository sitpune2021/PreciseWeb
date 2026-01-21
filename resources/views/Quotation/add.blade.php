@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">

                        <!-- ================= HEADER ================= -->
                        <div class="card-header d-flex align-items-center">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($quotation) ? 'Edit Quotation' : 'Add Quotation' }}
                            </h4>
                        </div>

                        <div class="card-body">

                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ isset($quotation) ? route('updatequotation', base64_encode($quotation->id)) : route('storequotation') }}" method="POST">
                                @csrf
                                @if(isset($quotation)) @method('PUT') @endif

                                <div class="row">

                                    <!-- ================= HEADER FIELDS ================= -->
                                    <div class="col-md-3">
                                        <label>Customer Code *</label>
                                        <select name="customer_id" class="form-select">
                                            <option value="">Select</option>
                                            @foreach($codes as $c)
                                            <option value="{{ $c->id }}"
                                                {{ old('customer_id', $quotation->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                {{ $c->code }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Quotation No *</label>
                                        <input type="text" name="quotation_no"
                                            value="{{ old('quotation_no', $quotation->quotation_no ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Date *</label>
                                        <input type="date" name="date"
                                            value="{{ old('date', $quotation->date ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Project Name</label>
                                        <input type="text" name="project_name" class="form-control"
                                            value="{{ old('project_name',$quotation->project_name ?? '') }}">
                                    </div>
                                    <hr class="mt-4">

                                    <!-- ================= DYNAMIC ROWS ================= -->

                                    <h5 class="mt-3">Item Details</h5>

                                    <div id="itemBlocks">
                                        <!-- FIRST BLOCK -->
                                        <div class="item-block border p-3 mb-3 first-block">

                                            {{-- ROW 1 --}}
                                            <div class="row mb-2">
                                                <div class="col-md-4">
                                                    <label for="">Description</label>
                                                    <input type="text" name="items[0][Description]" class="form-control" placeholder="Description"
                                                        value="{{ old('items.0.Description', isset($quotation->items[0]) ? $quotation->items[0]->description : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">Dia</label>
                                                    <input type="text" name="items[0][dia]" class="form-control" placeholder="Dia"
                                                        value="{{ old('items.0.dia', isset($quotation->items[0]) ? $quotation->items[0]->dia : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">Length</label>
                                                    <input type="text" name="items[0][length]" class="form-control" placeholder="Length"
                                                        value="{{ old('items.0.length', isset($quotation->items[0]) ? $quotation->items[0]->length : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">Width</label>
                                                    <input type="text" name="items[0][WIDTH]" class="form-control" placeholder="Width"
                                                        value="{{ old('items.0.WIDTH', isset($quotation->items[0]) ? $quotation->items[0]->width : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">Height</label>
                                                    <input type="number" name="items[0][HEIGHT]" class="form-control" placeholder="Height"
                                                        value="{{ old('items.0.HEIGHT', isset($quotation->items[0]) ? $quotation->items[0]->height : '') }}">
                                                </div>
                                            </div>

                                            {{-- ROW 2 --}}
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label>Qty in Kg</label>
                                                    <input type="text" name="items[0][qty_in_kg]" class="form-control" placeholder="Qty in Kg"
                                                        value="{{ old('items.0.qty_in_kg', isset($quotation->items[0]) ? $quotation->items[0]->qty_in_kg : '') }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Material Type</label>
                                                    <select name="items[0][material_type_id]" class="form-select material_type">
                                                        <option value="">Select Material</option>
                                                        @foreach($materialtype as $m)
                                                        <option value="{{ $m->id }}"
                                                            data-rate="{{ $m->material_rate }}"
                                                            data-gravity="{{ $m->material_gravity }}"
                                                            {{ (isset($quotation->items[0]) && $quotation->items[0]->material_type_id == $m->id) ? 'selected' : '' }}>
                                                            {{ $m->material_type }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-2">
                                                    <label>Material Rate</label>
                                                    <input type="text" name="items[0][material_rate]" class="form-control mt-1 material_rate" placeholder="Material Rate"
                                                        value="{{ old('items.0.material_rate', isset($quotation->items[0]) ? $quotation->items[0]->material_rate : '') }}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qty</label>
                                                    <input type="number" name="items[0][qty]" class="form-control" placeholder="Qty"
                                                        value="{{ old('items.0.qty', isset($quotation->items[0]) ? $quotation->items[0]->qty : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Material Cost</label>
                                                    <input type="text" name="items[0][material_cost]" class="form-control" placeholder="Material Cost"
                                                        value="{{ old('items.0.material_cost', isset($quotation->items[0]) ? $quotation->items[0]->material_cost : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Lathe</label>
                                                    <input type="text" name="items[0][lathe]" class="form-control" placeholder="Lathe"
                                                        value="{{ old('items.0.lathe', isset($quotation->items[0]) ? $quotation->items[0]->lathe : '') }}">
                                                </div>
                                            </div>

                                            {{-- ROW 3 --}}
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label>MG</label>
                                                    <input type="text" name="items[0][mg]" class="form-control" placeholder="MG"
                                                        value="{{ old('items.0.mg', isset($quotation->items[0]) ? $quotation->items[0]->mg : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>RG</label>
                                                    <input type="text" name="items[0][rg]" class="form-control" placeholder="RG"
                                                        value="{{ old('items.0.rg', isset($quotation->items[0]) ? $quotation->items[0]->rg : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>CG</label>
                                                    <input type="text" name="items[0][cg]" class="form-control" placeholder="CG"
                                                        value="{{ old('items.0.cg', isset($quotation->items[0]) ? $quotation->items[0]->cg : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>SG</label>
                                                    <input type="text" name="items[0][sg]" class="form-control" placeholder="SG"
                                                        value="{{ old('items.0.sg', isset($quotation->items[0]) ? $quotation->items[0]->sg : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Vmc Soft</label>
                                                    <input type="text" name="items[0][vmc_soft]" class="form-control" placeholder="Vmc Soft"
                                                        value="{{ old('items.0.vmc_soft', isset($quotation->items[0]) ? $quotation->items[0]->vmc_soft : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Vmc Hard</label>
                                                    <input type="text" name="items[0][vmc_hard]" class="form-control" placeholder="Vmc Hard"
                                                        value="{{ old('items.0.vmc_hard', isset($quotation->items[0]) ? $quotation->items[0]->vmc_hard : '') }}">
                                                </div>
                                            </div>

                                            {{-- ROW 4 --}}
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>EDM Qty</label>
                                                    <input type="text" name="items[0][edm_qty]" class="form-control" placeholder="Qty"
                                                        value="{{ old('items.0.edm_qty', isset($quotation->items[0]) ? $quotation->items[0]->edm_qty : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>EDM Hole</label>
                                                    <input type="text" name="items[0][edm_hole]" class="form-control" placeholder="EDM Hole"
                                                        value="{{ old('items.0.edm_hole', isset($quotation->items[0]) ? $quotation->items[0]->edm_hole : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>H&T</label>
                                                    <input type="text" name="items[0][h_t]" class="form-control" placeholder="H&T"
                                                        value="{{ old('items.0.h_t', isset($quotation->items[0]) ? $quotation->items[0]->ht : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Wirecut</label>
                                                    <input type="text" name="items[0][wirecut]" class="form-control" placeholder="Wirecut"
                                                        value="{{ old('items.0.wirecut', isset($quotation->items[0]) ? $quotation->items[0]->wirecut : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Material Gravity</label>
                                                    <input type="text" name="items[0][gravity]" class="form-control material_gravity" readonly
                                                        value="{{ old('items.0.gravity', isset($quotation->items[0]) ? $quotation->items[0]->material_gravity : '') }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Machining Cost</label>
                                                    <input type="text" name="items[0][machining_cost]" class="form-control" placeholder="Machining Cost"
                                                        value="{{ old('items.0.machining_cost', isset($quotation->items[0]) ? $quotation->items[0]->machining_cost : '') }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm removeBlock mt-2 {{ count($quotation->items ?? []) > 1 ? '' : 'd-none' }}">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <button type="button" id="addBlock" class="btn btn-success btn-sm " style="width: 10%;">
                                        ➕ Add Row
                                    </button>

                                    <hr class="mt-4">
                                    <!-- ================= TOTAL ================= -->

                                    <div class="col-md-2">
                                        <label>Total Manufacturing Cost</label>
                                        <input type="text" name="total_manufacturing_cos" class="form-control" readonly
                                            value="{{ old('total_manufacturing_cos', $quotation->total_manufacturing_cos ?? '') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Profit</label>
                                        <input type="text" name="profit" class="form-control" readonly
                                            value="{{ old('profit', $quotation->profit ?? '') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Overhead</label>
                                        <input type="text" name="overhead" class="form-control" readonly
                                            value="{{ old('overhead', $quotation->overhead ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Terms & Conditions</label>
                                        <input type="text" name="terms_conditions" class="form-control"
                                            value="{{ old('terms_conditions', $quotation->terms_conditions ?? '') }}">
                                    </div>


                                    <!-- ================= SUBMIT ================= -->
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ isset($quotation) ? 'Update' : 'Submit' }}
                                        </button>
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

<!-- ================= JS ================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        let blockIndex = 1;

        /* ===== ADD BLOCK ===== */
        $('#addBlock').on('click', function() {
            let block = $('.first-block').first().clone();

            // Clear all inputs/selects
            block.find('input').val('');
            block.find('select').val('');

            // Update name indexes
            block.find('input, select').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + blockIndex + ']'));
                }
            });

            // Show remove button
            block.find('.removeBlock').removeClass('d-none');

            $('#itemBlocks').append(block);
            blockIndex++;
        });

        /* ===== REMOVE BLOCK ===== */
        $(document).on('click', '.removeBlock', function() {
            $(this).closest('.item-block').remove();
            calculateGrandTotal();
        });

        /* ===== MATERIAL TYPE CHANGE ===== */
        $(document).on('change', '.material_type', function() {
            let block = $(this).closest('.item-block');
            let rate = $(this).find(':selected').data('rate') || 0;
            let gravity = $(this).find(':selected').data('gravity') || 0;

            block.find('.material_rate').val(rate);
            block.find('.material_gravity').val(gravity);

            calculateBlock(block);
        });

        /* ===== AUTO CALCULATION ON BASE INPUTS ===== */
        $(document).on('input change', '.item-block input, .item-block select', function() {
            let block = $(this).closest('.item-block');
            let name = $(this).attr('name');

            // Only recalc if base fields change
            if (
                name.includes('[dia]') ||
                name.includes('[length]') ||
                name.includes('[WIDTH]') ||
                name.includes('[HEIGHT]') ||
                name.includes('[qty]') ||
                name.includes('[material_type_id]') ||
                name.includes('[vmc_soft]') ||
                name.includes('[vmc_hard]') ||
                name.includes('[edm_hole]') ||
                name.includes('[edm_qty]') ||
                name.includes('[wirecut]')
            ) {
                calculateBlock(block);
            }
        });

        /* ===== CALCULATE SINGLE BLOCK ===== */
        function calculateBlock(block) {

            let d = +block.find('[name$="[dia]"]').val() || 0;
            let l = +block.find('[name$="[length]"]').val() || 0;
            let w = +block.find('[name$="[WIDTH]"]').val() || 0;
            let h = +block.find('[name$="[HEIGHT]"]').val() || 0;
            let q = +block.find('[name$="[qty]"]').val() || 1;

            let rate = +block.find('.material_rate').val() || 0;
            let g = +block.find('.material_gravity').val() || 0;

            let lathe = +block.find('[name$="[lathe]"]').val() || 0;
            let vs = +block.find('[name$="[vmc_soft]"]').val() || 0;
            let vh = +block.find('[name$="[vmc_hard]"]').val() || 0;

            let edm = +block.find('[name$="[edm_hole]"]').val() || 0;
            let wc = +block.find('[name$="[wirecut]"]').val() || 0;

            /* ===== QTY IN KG ===== */
            let cylWt = (Math.PI * Math.pow(d / 2, 2) * h / 1000000) * g;
            let boxWt = (l * w * h / 1000000) * g;
            let qtyKg = cylWt + boxWt;

            /* ===== MATERIAL COST ===== */
            let materialCost = qtyKg * rate * 1.30;

            /* ===== MG RG SG ===== */
            let mg = (((l * h + w * h) * 2 * 0.5) / 100) + ((l * w) * 2 * 0.5 / 100);
            let rg = (l * w) * 2 * 0.3 / 100;
            let sg = (((l * h + w * h) * 2) / 100) + ((l * w) * 2 / 100);

            /* ===== H&T ===== */
            let htCost = qtyKg * 80;

            /* ===== EXTRA ===== */
            let edmCost = h * edm * 6;
            let wireCost = wc * h * 0.25;

            /* ===== MACHINING COST ===== */
            let machiningCost = (lathe + mg + rg + sg + htCost + edmCost + wireCost + vs + vh) * q;

            /* ===== ITEM TOTAL ===== */
            let itemTotal = materialCost + machiningCost;

            /* ===== SET VALUES ===== */
            block.find('[name$="[qty_in_kg]"]').val(qtyKg.toFixed(3));
            block.find('[name$="[material_cost]"]').val(materialCost.toFixed(2));
            block.find('[name$="[mg]"]').val(mg.toFixed(2));
            block.find('[name$="[rg]"]').val(rg.toFixed(2));
            block.find('[name$="[sg]"]').val(sg.toFixed(2));
            block.find('[name$="[h_t]"]').val(htCost.toFixed(2));
            block.find('[name$="[machining_cost]"]').val(itemTotal.toFixed(2));

            calculateGrandTotal();
        }

        /* ===== GRAND TOTAL ===== */
        function calculateGrandTotal() {
            let total = 0;
            $('.item-block').each(function() {
                total += parseFloat($(this).find('[name$="[machining_cost]"]').val()) || 0;
            });

            $('input[name="total_manufacturing_cos"]').val(total.toFixed(2));
            $('input[name="profit"]').val((total * 0.10).toFixed(2));
            $('input[name="overhead"]').val((total * 0.05).toFixed(2));
        }

    });
</script>





@endsection