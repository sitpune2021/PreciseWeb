@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Machine Record Add / Edit Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">

                                <!-- Back Button ONLY on Edit -->
                                <a href="{{ route('ViewMachinerecord') }}" class="btn btn-sm btn-outline-success me-2">
                                    ←
                                </a>
                                {{ isset($record) ? 'Edit Machine Record' : 'Add Machine Record' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form action="{{ isset($record) ? route('UpdateMachinerecord', base64_encode($record->id)) : route('StoreMachinerecord') }}" method="POST">
                                @csrf
                                @if(isset($record))
                                @method('PUT')
                                @endif
                                <div class="row g-3">
                                    <input type="hidden" name="work_order_id" id="work_order_id">
                                    <div class="col-md-2">
                                        <label class="form-label">Part No <span class="text-red">*</span></label>

                                        <select name="part_no" id="part_no" class="form-control form-select js-example-basic-single"
                                            {{ isset($record) ? 'disabled' : '' }}>
                                            <option value="">Select Part No</option>
                                            @foreach($workorders as $wo)
                                            @php
                                            $partNo = ($wo->customer?->code ?? '') . '_' . ($wo->project?->project_no ?? '') . '_' . ($wo->part ?? '');
                                            @endphp

                                            <option value="{{ $partNo }}"
                                                data-id="{{ $wo->id }}"
                                                data-code="{{ $wo->customer?->code ?? '' }}"
                                                data-workorder="{{ $wo->project?->project_no }}"

                                                data-partdesc="{{ $wo->part_description ?? '' }}"
                                                data-qty="{{ $wo->quantity ?? '' }}"
                                                data-e_time="{{ $wo->exp_time ?? '' }}"
                                                data-customer="{{ $wo->customer_id ?? '' }}"
                                                {{ old('part_no', $record->part_no ?? '') == $partNo ? 'selected' : '' }}>
                                                {{ $partNo }}
                                            </option>

                                            @endforeach
                                        </select>
                                        {{-- Hidden input for Edit --}}
                                        @if(isset($record))
                                        <input type="hidden" name="part_no" value="{{ $record->part_no }}">
                                        @endif

                                        @error('part_no')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <!-- <label for="code" class="form-label">Customer Code</label> -->
                                    <input type="hidden" class="form-control" id="code" name="code" value="{{ old('code', $record->code ?? '') }}" readonly>
                                    @error('code')
                                    <span class="text-red small">{{ $message }}</span>
                                    @enderror


                                    <!-- Work Order No -->
                                    <div class="col-md-1">
                                        <label class="form-label">Wo No</label>
                                        <input type="text" id="work_order" name="work_order"
                                            class="form-control" value="{{ old('work_order', $record->work_order ?? '') }}" readonly>
                                    </div>

                                    <!-- Part Description -->
                                    <div class="col-md-4">
                                        <label class="form-label">Part Description</label>
                                        <input type="text" name="first_set" id="first_set" class="form-control"
                                            value="{{ old('first_set', $record->first_set ?? '') }}">
                                        @error('first_set') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Qty -->
                                    <div class="col-md-2">
                                        <label class="form-label">Qty <span class="text-red">*</span></label>
                                        <input type="number" name="qty" id="qty" class="form-control"
                                            value="{{ old('qty', $record->qty ?? '') }}">
                                        @error('qty') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Machine <span class="text-red">*</span></label>
                                        <select name="machine_id" class="form-control form-select mt-1">
                                            <option value=""> Select Machine </option>
                                            @foreach($machines->where('status', 1) as $machine)
                                            <option value="{{ $machine->id }}"
                                                {{ old('machine', $record->machine_id ?? '') == $machine->id ? 'selected' : '' }}>
                                                {{ $machine->machine_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('machine_id') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Operator <span class="text-red">*</span></label>
                                        <select name="operator_id" class="form-control form-select mt-1">
                                            <option value="">Select Operator</option>
                                            @foreach($operators->where('status', 1) as $operator)
                                            <option value="{{ $operator->id }}"
                                                {{ old('operator_id', $record->operator_id ?? '') == $operator->id ? 'selected' : '' }}>
                                                {{ $operator->operator_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('operator_id') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Operator <span class="text-red">*</span></label>
                                        <select name="operator" class="form-control form-select mt-1">
                                            <option value="">Select Operator</option>

                                            @foreach($operators as $operator)
                                            <option value="{{ $operator->operator_name }}"
                                                {{ old('operator', $record->operator ?? '') == $operator->operator_name ? 'selected' : '' }}>

                                                {{ $operator->operator_name }}

                                            </option>
                                            @endforeach

                                        </select>
                                        @error('operator') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div> -->

                                    <div class="col-md-2">
                                        <label class="form-label">Setting <span class="text-red">*</span></label>
                                        <select name="setting_id" class="form-control form-select mt-1">
                                            <option value="">Select Setting</option>

                                            @foreach($settings->where('status', 1) as $setting)
                                            <option value="{{ $setting->id }}"
                                                {{ old('setting_id', $record->setting_id ?? '') == $setting->id ? 'selected' : '' }}>
                                                {{ $setting->setting_name }}
                                            </option>
                                            @endforeach

                                        </select>
                                        @error('setting_id') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Setting <span class="text-red">*</span></label>

                                        <select name="setting" class="form-control form-select mt-1">
                                            <option value="">Select Setting</option>

                                            @foreach($settings as $setting)
                                            <option value="{{ $setting->setting_name }}"
                                                {{ old('setting', $record->setting ?? '') == $setting->setting_name ? 'selected' : '' }}>

                                                {{ $setting->setting_name }}

                                            </option>
                                            @endforeach

                                        </select>

                                        @error('setting')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div> -->

                                    <div class="col-md-2">
                                        <label for="material" class="form-label">Material type <span class="mandatory">*</span></label>
                                        <select name="material_id" id="material" class="form-control form-select">
                                            <option value="">Select Material</option>
                                            @foreach($materialtype->where('status', 1) as $mat)
                                            <option value="{{ $mat->id }}"
                                                {{ old('material', $record->material_id ?? '') == $mat->id ? 'selected' : '' }}>
                                                {{ $mat->material_type }}
                                            </option>
                                            @endforeach

                                        </select>
                                        @error('material_id') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Estimated Time (hrs) <span class="text-red">*</span></label>
                                        <input type="text" step="0.01" name="est_time" id="e_time" class="form-control mt-1"
                                            value="{{ old('est_time', $record->est_time ?? '') }}">

                                        @error('est_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Start Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="start_time" class="form-control"
                                            value="{{ old('start_time', isset($record->start_time) ? date('Y-m-d\TH:i', strtotime($record->start_time)) : '') }}">
                                        @error('start_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- End Time -->
                                    <div class="col-md-2">
                                        <label class="form-label">End Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="end_time" class="form-control"
                                            value="{{ old('end_time', isset($record->end_time) ? date('Y-m-d\TH:i', strtotime($record->end_time)) : '') }}">
                                        @error('end_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">IDL Time<span class="text-red"></span></label>
                                        <input type="text" step="0.01" name="idl_time" id="idl_time" class="form-control"
                                            value="{{ old('idl_time', $record->idl_time ?? '') }}">
                                             <small class="text-red">Ex: 1:00 hrs</small>
                                        @error('idl_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Discount</label>
                                        <input type="text"
                                            name="adjustment"
                                            id="adjustment"
                                            class="form-control"
                                            value="{{ old('adjustment', $record->adjustment ?? '20%') }}">
                                        @error('adjustment')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Minute <span class="text-red">*</span></label>
                                        <input type="number" name="minute" class="form-control"
                                            value="{{ old('minute', $record->minute ?? '') }}">

                                        @error('minute')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div> -->
                                    <!-- HRS -->

                                    <div class="col-md-2">
                                        <label class="form-label">HRS <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" id="hrs" name="hrs" class="form-control"
                                            value="{{ old('hrs', $record->hrs ?? '') }}">
                                        @error('hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Time Taken -->
                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Time Taken <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="time_taken" class="form-control"
                                            value="{{ old('time_taken', $record->time_taken ?? '') }}">
                                        @error('time_taken') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div> -->

                                    <!-- Actual HRS -->
                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Actual HRS <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="actual_hrs" class="form-control"
                                            value="{{ old('actual_hrs', $record->actual_hrs ?? '') }}">
                                        @error('actual_hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div> -->

                                    <!-- Invoice No -->
                                    <!-- <div class="col-md-2">
                                        <label class="form-label">Invoice No</label>
                                        <input type="text" name="invoice_no" class="form-control"
                                            value="{{ old('invoice_no', $record->invoice_no ?? '') }}">
                                        @error('invoice_no') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div> -->
                                    <!-- Buttons -->
                                    <div class="col-lg-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            {{ isset($record) ? 'Update' : 'Submit' }}
                                        </button>
                                        &nbsp;
                                        @if(isset($record))
                                        <a href="{{ route('ViewMachinerecord') }}" class="btn btn-info">Cancel</a>
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
    $(document).ready(function() {

        $('#part_no').on('select2:select', function() {

            let selected = $(this).find(':selected');

            $('#work_order_id').val(selected.data('id') || '');
            $('#code').val(selected.data('code') || '');
            $('#work_order').val(selected.data('workorder') || '');
            $('#first_set').val(selected.data('partdesc') || '');
            $('#qty').val(selected.data('qty') || '');
            $('#e_time').val(selected.data('e_time') || '');

        });

        // Edit mode auto-fill
        if ($('#part_no').val()) {
            $('#part_no').trigger('change');
        }

    });


    //  CALCULATE HOURS 
    // function calculateHours() {

    //     let start = document.querySelector('[name="start_time"]').value;
    //     let end = document.querySelector('[name="end_time"]').value;

    //     if (start && end) {

    //         let startTime = new Date(start);
    //         let endTime = new Date(end);

    //         if (endTime >= startTime) {

    //             let diffMinutes = (endTime - startTime) / (1000 * 60);
    //             let actualHrs = diffMinutes / 60;
    //             let hrs = diffMinutes * 0.02;
    //             let timeTaken = hrs;

    //             document.querySelector('[name="hrs"]').value = hrs.toFixed(2);
    //             document.querySelector('[name="time_taken"]').value = timeTaken.toFixed(2);
    //             document.querySelector('[name="actual_hrs"]').value = actualHrs.toFixed(2);

    //             if (document.querySelector('[name="minute"]')) {
    //                 document.querySelector('[name="minute"]').value = diffMinutes.toFixed(0);
    //             }
    //         }
    //     }
    // }

    // document.querySelector('[name="start_time"]').addEventListener('change', calculateHours);
    // document.querySelector('[name="end_time"]').addEventListener('change', calculateHours);

    let totalMinutesGlobal = 0;

    // 🔹 Convert input (1:10 OR 30 OR 2:00) → minutes
    function parseIdleTime(value) {

        if (!value) return 0;

        value = value.trim();

        let hours = 0;
        let minutes = 0;

        if (value.includes(":")) {

            let parts = value.split(":");

            hours = parseInt(parts[0]) || 0;
            minutes = parseInt(parts[1]) || 0;

        } else {

            minutes = parseInt(value) || 0;
        }

        return (hours * 60) + minutes;
    }

    // 🔹 Convert minutes → decimal hours (28.8 format)
    function formatToDecimal(minutes) {
        return (minutes / 60).toFixed(1); // change to .toFixed(2) if needed
    }

    // 🔹 Start-End difference
    function calculateHours() {

        let start = document.querySelector('[name="start_time"]').value;
        let end = document.querySelector('[name="end_time"]').value;

        if (start && end) {

            let startTime = new Date(start);
            let endTime = new Date(end);

            if (endTime >= startTime) {

                totalMinutesGlobal = Math.floor((endTime - startTime) / (1000 * 60));

                applyCalculation();
            }
        }
    }

    // 🔥 MAIN CALCULATION
    function applyCalculation() {

        let idleValue = document.getElementById('idl_time').value;
        let adjValue = document.getElementById('adjustment').value;

        let idleMinutes = parseIdleTime(idleValue);

        // Step 1: Machine Time
        let machineMinutes = totalMinutesGlobal - idleMinutes;

        if (machineMinutes < 0) machineMinutes = 0;

        // Step 2: Adjustment %
        let percent = 0;

        if (adjValue) {
            percent = parseFloat(adjValue.replace('%', '')) || 0;
        }

        // Step 3: Add Adjustment
        let adjustmentMinutes = (machineMinutes * percent) / 100;

        let finalMinutes = machineMinutes + adjustmentMinutes;

        // Step 4: Convert to decimal hours
        document.getElementById('hrs').value = formatToDecimal(finalMinutes);
    }

    // 🔹 Events
    document.getElementById('idl_time').addEventListener('input', applyCalculation);
    document.getElementById('adjustment').addEventListener('input', applyCalculation);

    document.querySelector('[name="start_time"]').addEventListener('change', calculateHours);
    document.querySelector('[name="end_time"]').addEventListener('change', calculateHours);

    window.addEventListener('load', function() {
        calculateHours();
    });
    //  CUSTOMER CHANGE 
    document.getElementById('customer_id').addEventListener('change', function() {

        let customerId = this.value;
        let partSelect = document.getElementById('part_no');

        partSelect.innerHTML = '<option value="">Select Part No</option>';

        if (customerId) {

            fetch(`/get-customer-parts/${customerId}`)
                .then(response => response.json())
                .then(data => {

                    data.forEach(part => {

                        let option = new Option(part.part_no, part.part_no, false, false);

                        $(option).attr('data-id', part.id);
                        $(option).attr('data-code', part.code);
                        $(option).attr('data-workorder', part.project_id);
                        $(option).attr('data-partdesc', part.part_description);
                        $(option).attr('data-qty', part.quantity);
                        $(option).attr('data-e_time', part.exp_time);

                        $('#part_no').append(option);
                    });

                    // VERY IMPORTANT
                    $('#part_no').trigger('change.select2');

                });

        }

    });
</script>

<script>
    $(document).ready(function() {
        $('select[name="part_no"]').change(function() {

            let selected = $(this).find('option:selected');
            let customerId = selected.data('customer');
            if (customerId) {
                $.ajax({
                    url: '/get-invoice-by-customer/' + customerId,
                    type: 'GET',
                    success: function(data) {
                        if (data.invoice_no) {
                            $('input[name="invoice_no"]').val(data.invoice_no);
                        } else {
                            $('input[name="invoice_no"]').val('');
                        }
                    }
                });
            } else {
                $('input[name="invoice_no"]').val('');
            }
        });
    });
</script>


@endsection