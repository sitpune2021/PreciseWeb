@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="mb-0 flex-grow-1">

                                <!-- Back Button ONLY on Edit -->
                                <a href="{{ route('Viewquotation') }}" class="btn btn-sm btn-outline-success me-2">
                                    ←
                                </a>
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
                                <input type="hidden" id="isEditMode" value="{{ isset($record) ? 1 : 0 }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Customer Code <span class="text-red">*</span></label>
                                        <select name="customer_id" class="form-select js-example-basic-single">
                                            <option value="">Select</option>
                                            @foreach($codes as $c)
                                            <option value="{{ $c->id }}"
                                                {{ old('customer_id', $quotation->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                {{ $c->code }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="text-red">
                                            @error('customer_id') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Quotation No</span></label>
                                        <input type="text" name="quotation_no"
                                            value="{{ old('quotation_no', $quotation->quotation_no ?? $quotation_no ?? '') }}"
                                            class="form-control">
                                        <small class="text-red">
                                            @error('quotation_no') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Date <span class="text-red">*</span></label>
                                        <input type="date" name="date"
                                            value="{{ old('date', $quotation->date ?? '') }}"
                                            class="form-control">
                                        <small class="text-red">
                                            @error('date') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="col-md-5">
                                        <label>Project Name <span class="text-red">*</span></label>
                                        <input type="text" name="project_name" class="form-control"
                                            value="{{ old('project_name',$quotation->project_name ?? '') }}">
                                        <small class="text-red">
                                            @error('project_name') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <hr class="mt-3">
                                    <h5 class="mt-1">Item Details</h5>
                                    <div id="itemBlocks">

                                        @php
                                        $items = old('items', isset($quotation) ? $quotation->items->toArray() : [[]]);
                                        @endphp

                                        @foreach($items as $index => $item)
                                        {{-- Hidden Fields --}}
                                        <input type="hidden" name="items[{{ $index }}][vmc_soft_cost]" class="vmc_soft_cost">
                                        <input type="hidden" name="items[{{ $index }}][vmc_hard_cost]" class="vmc_hard_cost">

                                        <div class="item-block border p-3 mb-3 {{ $loop->first ? 'first-block' : '' }}">

                                            <div class="row mb-2">

                                                {{-- Description --}}
                                                <div class="col-md-5">
                                                    <label>Description <span class="text-red">*</span></label>
                                                    <input type="text"
                                                        name="items[{{ $index }}][Description]"
                                                        class="form-control"
                                                        value="{{ $item['description'] ?? $item->description ?? '' }}">
                                                </div>

                                                {{-- Dia --}}
                                                <div class="col-md-1">
                                                    <label>Dia</label>
                                                    <input type="text" name="items[{{ $index }}][dia]" class="form-control"
                                                        value="{{ isset($item['dia']) ? +$item['dia'] : (isset($item->dia) ? +$item->dia : '') }}">
                                                </div>

                                                {{-- Length --}}
                                                <div class="col-md-1">
                                                    <label>Length</label>
                                                    <input type="text" name="items[{{ $index }}][length]" class="form-control"
                                                        value="{{ isset($item['length']) ? +$item['length'] : (isset($item->length) ? +$item->length : '') }}">
                                                </div>

                                                {{-- Width --}}
                                                <div class="col-md-1">
                                                    <label>Width</label>
                                                    <input type="text" name="items[{{ $index }}][width]" class="form-control"
                                                        value="{{ isset($item['width']) ? +$item['width'] : (isset($item->width) ? +$item->width : '') }}">
                                                </div>

                                                {{-- Height --}}
                                                <div class="col-md-1">
                                                    <label>Height</label>
                                                    <input type="number" name="items[{{ $index }}][height]" class="form-control"
                                                        value="{{ isset($item['height']) ? +$item['height'] : (isset($item->height) ? +$item->height : '') }}">
                                                </div>

                                                {{-- Hidden Qty KG --}}
                                                <input type="hidden" name="items[{{ $index }}][qty_in_kg]"
                                                    value="{{ $item['qty_in_kg'] ?? $item->qty_in_kg ?? '' }}">

                                                {{-- Material Type --}}
                                                <div class="col-md-2">
                                                    <label>Material Type <span class="text-red">*</span></label>
                                                    <select name="items[{{ $index }}][material_type_id]" class="form-select material_type">
                                                        <option value="">Select</option>
                                                        @foreach($materialtype as $m)
                                                        <option value="{{ $m->id }}"
                                                            data-rate="{{ $m->material_rate }}"
                                                            data-gravity="{{ $m->material_gravity }}"
                                                            {{ (isset($item['material_type_id']) && $item['material_type_id'] == $m->id) 
                    || (isset($item->material_type_id) && $item->material_type_id == $m->id) ? 'selected' : '' }}>
                                                            {{ $m->material_type }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                    @error('items.' . $index . '.material_type_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Material Rate --}}
                                                <div class="col-md-1">
                                                    <label>Rate</label>
                                                    <input type="text" name="items[{{ $index }}][material_rate]"
                                                        class="form-control material_rate"
                                                        value="{{ isset($item['material_rate']) ? +$item['material_rate'] : (isset($item->material_rate) ? +$item->material_rate : '') }}">
                                                </div>

                                                {{-- Qty --}}
                                                <div class="col-md-2">
                                                    <label>Qty</label>
                                                    <input type="number" name="items[{{ $index }}][qty]"
                                                        class="form-control"
                                                        value="{{ $item['qty'] ?? $item->qty ?? '' }}">
                                                    @error('items.' . $index . '.qty')
                                                    <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Material Cost --}}
                                                <div class="col-md-1">
                                                    <label>Mat Cost</label>
                                                    <input type="text" name="items[{{ $index }}][material_cost]"
                                                        class="form-control"
                                                        value="{{ isset($item['material_cost']) ? +$item['material_cost'] : (isset($item->material_cost) ? +$item->material_cost : '') }}">
                                                </div>

                                                {{-- Lathe --}}
                                                <div class="col-md-1">
                                                    <label>Lathe</label>
                                                    <input type="text" name="items[{{ $index }}][lathe]"
                                                        class="form-control"
                                                        value="{{ isset($item['lathe']) ? +$item['lathe'] : (isset($item->lathe) ? +$item->lathe : '') }}">
                                                </div>

                                                {{-- MG --}}
                                                <div class="col-md-1">
                                                    <label>MG</label>
                                                    <input type="text" name="items[{{ $index }}][mg]"
                                                        class="form-control"
                                                        value="{{ isset($item['mg']) ? +$item['mg'] : (isset($item->mg) ? +$item->mg : '') }}">
                                                </div>

                                                {{-- RG --}}
                                                <div class="col-md-1">
                                                    <label>RG</label>
                                                    <input type="text" name="items[{{ $index }}][rg]"
                                                        class="form-control"
                                                        value="{{ isset($item['rg']) ? +$item['rg'] : (isset($item->rg) ? +$item->rg : '') }}">
                                                </div>

                                                {{-- CG --}}
                                                <div class="col-md-1">
                                                    <label>CG</label>
                                                    <input type="text" name="items[{{ $index }}][cg]"
                                                        class="form-control"
                                                        value="{{ isset($item['cg']) ? +$item['cg'] : (isset($item->cg) ? +$item->cg : '') }}">
                                                </div>

                                                {{-- SG --}}
                                                <div class="col-md-1">
                                                    <label>SG</label>
                                                    <input type="text" name="items[{{ $index }}][sg]"
                                                        class="form-control"
                                                        value="{{ isset($item['sg']) ? +$item['sg'] : (isset($item->sg) ? +$item->sg : '') }}">
                                                </div>

                                                {{-- VMC Soft --}}
                                                <!-- <div class="col-md-1">
                                                    <label>VMC S</label>
                                                    <input type="text" name="items[{{ $index }}][vmc_soft]"
                                                        class="form-control"
                                                         value="{{ $item['vmc_soft'] ?? $item->vmc_soft ?? '' }}">
                                                </div>

                                                {{-- VMC Hard --}}
                                                <div class="col-md-1">
                                                    <label>VMC H</label>
                                                    <input type="text" name="items[{{ $index }}][vmc_hard]"
                                                        class="form-control"
                                                         value="{{ isset($item['vmc_hard']) ? +$item['vmc_hard'] : (isset($item->vmc_hard) ? +$item->vmc_hard : '') }}">
                                                </div> -->


                                                <div class="col-md-1">
                                                    <label>VMC S</label>

                                                    @php
                                                    $vmcSoftRate = $rates['Vmc Soft'] ?? 1;

                                                    $vmcSoftValue = $item['vmc_soft'] ?? ($item->vmc_soft ?? 0);
                                                    $vmcSoftHours = $vmcSoftRate != 0 ? ($vmcSoftValue / $vmcSoftRate) : 0;
                                                    @endphp

                                                    <input type="text" name="items[{{ $index }}][vmc_soft]"
                                                        class="form-control"
                                                        value="{{ round($vmcSoftHours, 2) }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <label>VMC H</label>
                                                    @php
                                                    $vmcHardRate = $rates['Vmc Hard'] ?? 1;

                                                    $vmcHardValue = $item['vmc_hard'] ?? ($item->vmc_hard ?? 0);
                                                    $vmcHardHours = $vmcHardRate != 0 ? ($vmcHardValue / $vmcHardRate) : 0;
                                                    @endphp

                                                    <input type="text" name="items[{{ $index }}][vmc_hard]"
                                                        class="form-control"
                                                        value="{{ round($vmcHardHours, 2) }}">
                                                </div>

                                                {{-- EDM --}}
                                                <div class="col-md-1">
                                                    <label>EDM Qty</label>
                                                    <input type="text" name="items[{{ $index }}][edm_qty]"
                                                        class="form-control"
                                                        value="{{ isset($item['edm_qty']) ? +$item['edm_qty'] : (isset($item->edm_qty) ? +$item->edm_qty : '') }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <label>EDM Hole</label>
                                                    <input type="text" name="items[{{ $index }}][edm_hole]"
                                                        class="form-control"
                                                        value="{{ isset($item['edm_hole']) ? +$item['edm_hole'] : (isset($item->edm_hole) ? +$item->edm_hole : '') }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <label>H&T</label>
                                                    <input type="text" name="items[{{ $index }}][ht]"
                                                        class="form-control"
                                                        value="{{ isset($item['ht']) ? +$item['ht'] : (isset($item->ht) ? +$item->ht : '') }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <label>Wirecut</label>
                                                    <input type="text" name="items[{{ $index }}][wirecut]"
                                                        class="form-control"
                                                        value="{{ isset($item['wirecut']) ? +$item['wirecut'] : (isset($item->wirecut) ? +$item->wirecut : '') }}">
                                                </div>

                                                {{-- Hidden Gravity --}}
                                                <input type="hidden" name="items[{{ $index }}][gravity]"
                                                    class="material_gravity"
                                                    value="{{ $item['gravity'] ?? $item->material_gravity ?? '' }}">

                                                {{-- Machining Cost --}}
                                                <div class="col-md-2">
                                                    <label>Machining Cost</label>
                                                    <input type="text" name="items[{{ $index }}][machining_cost]"
                                                        class="form-control"
                                                        value="{{ $item['machining_cost'] ?? $item->machining_cost ?? '' }}">
                                                </div>

                                            </div>

                                            {{-- Remove Button --}}
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm removeBlock {{ $loop->first ? 'd-none' : '' }}">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                        @endforeach
                                    </div>
                                    <button type="button" id="addBlock" class="btn btn-success btn-sm " style="width: 10%;">
                                        + Add Row
                                    </button>

                                    <hr class="mt-4">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Total Manufacturing Cost</label>
                                            <input type="text" name="total_manufacturing_cos" class="form-control"
                                                value="{{ old('total_manufacturing_cos', $quotation->total_manufacturing_cos ?? '') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Profit (%)</label>
                                            <input type="number" step="0.01" name="profit_percent"
                                                class="form-control"
                                                value="{{ old('profit_percent', $quotation->profit_percent ?? '') }}">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Overhead (%)</label>
                                            <input type="number" step="0.01" name="overhead_percent"
                                                class="form-control"
                                                value="{{ old('overhead_percent', $quotation->overhead_percent ?? '') }}">
                                        </div>


                                        <div class="col-md-6">
                                            <label>Terms & Conditions</label>
                                            <input type="text" name="terms_conditions" class="form-control"
                                                value="{{ old('terms_conditions', $quotation->terms_conditions ?? '') }}">
                                        </div>
                                        <div class="col-12 text-end mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ isset($quotation) ? 'Update' : 'Submit' }}
                                            </button>
                                        </div>

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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    let rateList = @json($rates);
</script>
<!-- <script>
    $(document).ready(function() {

        let blockIndex = $('.item-block').length;
        $('#addBlock').on('click', function() {

            let block = $('.first-block').first().clone();

            block.find('input').val('');
            block.find('select').val('');

            block.find('input, select').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + blockIndex + ']'));
                }
            });

            block.find('.removeBlock').removeClass('d-none');

            $('#itemBlocks').append(block);

            // default qty
            block.find('[name$="[qty]"]').val(0);

            // delayed calculation
            setTimeout(() => {
                calculateBlock(block, true);
            }, 100);

            blockIndex++;
        });

        /* REMOVE BLOCK */

        $(document).on('click', '.removeBlock', function() {
            $(this).closest('.item-block').remove();
            calculateGrandTotal();
        });

        /*MATERIAL CHANGE*/

        $(document).on('change', '.material_type', function() {

            let block = $(this).closest('.item-block');

            let rate = $(this).find(':selected').data('rate') || 0;
            let gravity = $(this).find(':selected').data('gravity') || 0;

            block.find('.material_rate').val(rate);
            block.find('.material_gravity').val(gravity);

            calculateBlock(block, true);
        });

        /* DIMENSION CHANGE (AUTO ALLOWED)*/

        $(document).on('input',
            '[name$="[dia]"],[name$="[length]"],[name$="[width]"],[name$="[height]"]',
            function() {
                let block = $(this).closest('.item-block');
                calculateBlock(block, true);
            });

        /*ALL OTHER FIELD CHANGE*/

        $(document).on('input', '.item-block input', function() {
            let block = $(this).closest('.item-block');
            calculateBlock(block, false);
        });

        // function formatNumber(val) {
        //     val = parseFloat(val) || 0;

        //     // If decimal part is zero remove it
        //     if (val % 1 === 0) {
        //         return val.toString();
        //     } else {
        //         return val.toFixed(2);
        //     }
        // }

        function formatNumber(val) {
            val = parseFloat(val) || 0;

            return val.toFixed(1).replace(/\.0$/, '.0').replace(/\.?0+$/, '');
        }
        /* MAIN CALCULATION */

        function calculateBlock(block, allowAuto = false) {
            let die = parseFloat(block.find('[name$="[dia]"]').val()) || 0;
            let length = parseFloat(block.find('[name$="[length]"]').val()) || 0;
            let width = parseFloat(block.find('[name$="[width]"]').val()) || 0;
            let height = parseFloat(block.find('[name$="[height]"]').val()) || 0;
            let qty = parseFloat(block.find('[name$="[qty]"]').val()) || 1;

            let rate = parseFloat(block.find('.material_rate').val()) || 0;
            let gravity = parseFloat(block.find('.material_gravity').val()) || 0;

            let lathe = parseFloat(block.find('[name$="[lathe]"]').val()) || 0;
            let vmcSoftInput = parseFloat(block.find('[name$="[vmc_soft]"]').val()) || 0;
            let vmcHardInput = parseFloat(block.find('[name$="[vmc_hard]"]').val()) || 0;

            let vmcSoftRate = rateList['Vmc Soft'] || 0;
            let vmcHardRate = rateList['Vmc Hard'] || 0;

            // user input = hours
            let vmcSoftHours = vmcSoftInput;
            let vmcHardHours = vmcHardInput;

            let edmQty = parseFloat(block.find('[name$="[edm_qty]"]').val()) || 0;
            let wirecutInput = parseFloat(block.find('[name$="[wirecut]"]').val()) || 0;
            let cg = parseFloat(block.find('[name$="[cg]"]').val()) || 0;

            // QTY in KG calculation
            let cylWt = (Math.PI * Math.pow(die / 2, 2) * height / 1000000) * gravity;
            let boxWt = (length * width * height / 1000000) * gravity;
            let qtyKg = cylWt + boxWt;
            block.find('[name$="[qty_in_kg]"]').val(formatNumber(qtyKg));

            // MATERIAL COST
            let materialCost = (qtyKg * rate) * 1.30;
            block.find('[name$="[material_cost]"]').val(formatNumber(materialCost));

            // AUTO FORMULA (Excel Match)
            let autoMG = (((length * height + width * height) * 2 * 0.5) / 100) + ((length * width) * 2 * 0.5 / 100);
            let autoRG = (length * width) * 2 * 0.3 / 100;
            let autoSG = (((length * height + width * height) * 2) / 100) + ((length * width) * 2 / 100);
            let autoHT = qtyKg * 80;

            // APPLY AUTO ONLY ON DIMENSION CHANGE
            // if (allowAuto) {
            //     block.find('[name$="[mg]"]').val(formatNumber(autoMG));
            //     block.find('[name$="[rg]"]').val(formatNumber(autoRG));
            //     block.find('[name$="[sg]"]').val(formatNumber(autoSG));
            //     block.find('[name$="[ht]"]').val(formatNumber(autoHT));

            //     block.find('.vmc_soft_cost').val(formatNumber(vmcSoft));
            //     block.find('.vmc_hard_cost').val(formatNumber(vmcHard));
            // }

            // Use manual values if user has entered them
            let mg = parseFloat(block.find('[name$="[mg]"]').val()) || 0;
            let rg = parseFloat(block.find('[name$="[rg]"]').val()) || 0;
            let sg = parseFloat(block.find('[name$="[sg]"]').val()) || 0;
            let ht = parseFloat(block.find('[name$="[ht]"]').val()) || 0;

            // let vmcSoft = vmcSoftInput * 500;
            // let vmcHard = vmcHardInput * 600;

            // final cost
            let vmcSoft = vmcSoftHours * vmcSoftRate;
            let vmcHard = vmcHardHours * vmcHardRate;

            let edmCost = height * edmQty * 6;
            let wirecutCost = wirecutInput * height * 0.25;

            // MACHINING COST
            let machiningCost = (
                materialCost +
                lathe +
                mg + rg + cg + sg +
                vmcSoft + vmcHard +
                edmCost + ht + wirecutCost
            ) * qty;
            block.find('[name$="[machining_cost]"]').val(formatNumber(machiningCost));

            // GRAND TOTAL
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            $('.item-block').each(function() {
                total += parseFloat($(this).find('[name$="[machining_cost]"]').val()) || 0;
            });
            $('input[name="total_manufacturing_cos"]').val(formatNumber(total));
        }

        /*  INITIAL LOAD (EDIT MODE FIX) */

        $('.item-block').each(function() {

            let matCost = $(this).find('[name$="[material_cost]"]').val();
            let machiningCost = $(this).find('[name$="[machining_cost]"]').val();

            if (!matCost || matCost == 0) {
                calculateBlock($(this), false);
            }
        });

    });
</script> -->

<script>
    $(document).ready(function() {
        let blockIndex = $('.item-block').length;
        let rateList = @json($rates); // VMC Soft/Hard rates

        /* ===== FORMAT NUMBER ===== */
        function formatNumber(val) {
            val = parseFloat(val) || 0;
            return val % 1 === 0 ? val.toString() : val.toFixed(2);
        }

        /* ===== ADD BLOCK ===== */
        $('#addBlock').on('click', function() {
            let block = $('.first-block').first().clone();

            // Clear values
            block.find('input').val('');
            block.find('select').val('');

            // Update names
            block.find('input, select').each(function() {
                let name = $(this).attr('name');
                if (name) $(this).attr('name', name.replace(/\[\d+\]/, `[${blockIndex}]`));
            });

            block.find('.removeBlock').removeClass('d-none');

            $('#itemBlocks').append(block);
            block.find('[name$="[qty]"]').val(0);

            calculateBlock(block);

            blockIndex++;
        });

        /* ===== REMOVE BLOCK ===== */
        $(document).on('click', '.removeBlock', function() {
            $(this).closest('.item-block').remove();
            calculateGrandTotal();
        });

        /* ===== MATERIAL CHANGE ===== */
        $(document).on('change', '.material_type', function() {
            let block = $(this).closest('.item-block');
            let rate = $(this).find(':selected').data('rate') || 0;
            let gravity = $(this).find(':selected').data('gravity') || 0;

            block.find('.material_rate').val(rate);
            block.find('.material_gravity').val(gravity);

            calculateBlock(block);
        });

        /* ===== LIVE EDIT ===== */
        $(document).on('input change', '.item-block input, .item-block select', function() {
            let block = $(this).closest('.item-block');
            calculateBlock(block);
        });

        /* ===== CALCULATE BLOCK ===== */
        function calculateBlock(block) {
            let dia = parseFloat(block.find('[name$="[dia]"]').val()) || 0;
            let length = parseFloat(block.find('[name$="[length]"]').val()) || 0;
            let width = parseFloat(block.find('[name$="[width]"]').val()) || 0;
            let height = parseFloat(block.find('[name$="[height]"]').val()) || 0;
            let qty = parseFloat(block.find('[name$="[qty]"]').val()) || 1;

            let rate = parseFloat(block.find('.material_rate').val()) || 0;
            let gravity = parseFloat(block.find('.material_gravity').val()) || 0;

            let lathe = parseFloat(block.find('[name$="[lathe]"]').val()) || 0;
            let mg = parseFloat(block.find('[name$="[mg]"]').val()) || 0;
            let rg = parseFloat(block.find('[name$="[rg]"]').val()) || 0;
            let cg = parseFloat(block.find('[name$="[cg]"]').val()) || 0;
            let sg = parseFloat(block.find('[name$="[sg]"]').val()) || 0;
            let ht = parseFloat(block.find('[name$="[ht]"]').val()) || 0;

            let vmcSoftHours = parseFloat(block.find('[name$="[vmc_soft]"]').val()) || 0;
            let vmcHardHours = parseFloat(block.find('[name$="[vmc_hard]"]').val()) || 0;
            let vmcSoftRate = rateList['Vmc Soft'] || 0;
            let vmcHardRate = rateList['Vmc Hard'] || 0;

            let edmQty = parseFloat(block.find('[name$="[edm_qty]"]').val()) || 0;
            let wire = parseFloat(block.find('[name$="[wirecut]"]').val()) || 0;

            /* ===== WEIGHT ===== */
            let cylWt = (Math.PI * Math.pow(dia / 2, 2) * height / 1000000) * gravity;
            let boxWt = (length * width * height / 1000000) * gravity;
            let qtyKg = cylWt + boxWt;
            block.find('[name$="[qty_in_kg]"]').val(formatNumber(qtyKg));

            /* ===== MATERIAL COST ===== */
            let materialCost = qtyKg * rate * 1.30;
            block.find('[name$="[material_cost]"]').val(formatNumber(materialCost));

            /* ===== VMC COST ===== */
            let vmcSoft = vmcSoftHours * vmcSoftRate;
            let vmcHard = vmcHardHours * vmcHardRate;

            /* ===== EDM / WIRECUT ===== */
            let edmCost = height * edmQty * 6;
            let wireCost = wire * height * 0.25;

            /* ===== FINAL MACHINING COST ===== */
            let machiningCost = (materialCost + lathe + mg + rg + cg + sg + vmcSoft + vmcHard + edmCost + ht + wireCost) * qty;
            block.find('[name$="[machining_cost]"]').val(formatNumber(machiningCost));

            calculateGrandTotal();
        }

        /* ===== GRAND TOTAL ===== */
        function calculateGrandTotal() {
            let total = 0;
            $('.item-block').each(function() {
                total += parseFloat($(this).find('[name$="[machining_cost]"]').val()) || 0;
            });
            $('input[name="total_manufacturing_cos"]').val(formatNumber(total));
        }

        /* ===== INITIAL LOAD ===== */
        $('.item-block').each(function() {
            let block = $(this);
            let rate = block.find('.material_type option:selected').data('rate') || 0;
            let gravity = block.find('.material_type option:selected').data('gravity') || 0;

            block.find('.material_rate').val(rate);
            block.find('.material_gravity').val(gravity);

            calculateBlock(block);
        });
    });
</script>

@endsection