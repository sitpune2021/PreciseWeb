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



                                    <div class="col-md-4">
                                        <label class="form-label">Part No <span class="text-red">*</span></label>

                                        <select name="part_no" id="part_no" class="form-control form-select "
                                            {{ isset($record) ? 'disabled' : '' }}>
                                            <option value="">Select Part No</option>
                                            @foreach($workorders as $wo)
                                            @php
                                            $partNo = ($wo->customer?->code ?? '') . '_' . ($wo->project?->project_no ?? '') . '_' . ($wo->part ?? '');
                                            @endphp

                                            <option value="{{ $partNo }}"
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

                                    <div class="col-md-2">
                                        <div class="">
                                            <label for="code" class="form-label">Customer Code</label>
                                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $record->code ?? '') }}" readonly>
                                            @error('code')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Work Order No -->
                                    <div class="col-md-2">
                                        <label class="form-label">Work Order No</label>
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
                                        <input type="number" name="qty" id="qty" class="form-control mt-1"
                                            value="{{ old('qty', $record->qty ?? '') }}">
                                        @error('qty') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Machine <span class="text-red">*</span></label>
                                        <select name="machine" class="form-control form-select mt-1">
                                            <option value=""> Select Machine </option>
                                            @foreach($machines->where('status', 1) as $machine)
                                            <option value="{{ $machine->machine_name }}"
                                                {{ old('machine', $record->machine ?? '') == $machine->machine_name ? 'selected' : '' }}>
                                                {{ $machine->machine_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('machine') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Operator <span class="text-red">*</span></label>
                                        <select name="operator" class="form-control form-select mt-1">
                                            <option value="">Select Operator</option>
                                            @foreach($operators->where('status', 1) as $operator)
                                            <option value="{{ $operator->operator_name }}"
                                                {{ old('operator', $record->operator ?? '') == $operator->operator_name ? 'selected' : '' }}>
                                                {{ $operator->operator_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('operator') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Setting <span class="text-red">*</span></label>
                                        <select name="setting_no" class="form-control form-select mt-1">
                                            <option value="">Select Setting</option>

                                            @foreach($settings->where('status', 1) as $setting)
                                            <option value="{{ $setting->setting_name }}"
                                                {{ old('setting_no', $record->setting_no ?? '') == $setting->setting_name ? 'selected' : '' }}>
                                                {{ $setting->setting_name }}
                                            </option>
                                            @endforeach

                                        </select>
                                        @error('setting_no') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="material" class="form-label">Material type <span class="mandatory">*</span></label>
                                        <select name="material" id="material" class="form-control form-select">
                                            <option value="">Select Material</option>
                                            @foreach($materialtype->where('status', 1) as $mat)
                                            <option value="{{ $mat->material_type }}"
                                                {{ old('material', $record->material ?? '') == $mat->material_type ? 'selected' : '' }}>
                                                {{ $mat->material_type }}
                                            </option>
                                            @endforeach

                                        </select>
                                        @error('material') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Estimated Time (hrs) <span class="text-red">*</span></label>
                                        <input type="text" step="0.01" name="est_time" id="e_time" class="form-control"
                                            value="{{ old('est_time', $record->est_time ?? '') }}">

                                        @error('est_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Start Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="start_time" class="form-control"
                                            value="{{ old('start_time', isset($record->start_time) ? date('Y-m-d\TH:i', strtotime($record->start_time)) : '') }}">
                                        @error('start_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- End Time -->
                                    <div class="col-md-3">
                                        <label class="form-label">End Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="end_time" class="form-control"
                                            value="{{ old('end_time', isset($record->end_time) ? date('Y-m-d\TH:i', strtotime($record->end_time)) : '') }}">
                                        @error('end_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Adjustment</label>
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
                                        <input type="number" step="0.01" name="hrs" class="form-control"
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
                                    <div class="col-md-3">
                                        <label class="form-label">Invoice No</label>
                                        <input type="text" name="invoice_no" class="form-control"
                                            value="{{ old('invoice_no', $record->invoice_no ?? '') }}">
                                        @error('invoice_no') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>


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
    document.getElementById('part_no').addEventListener('change', function() {


        let selected = this.options[this.selectedIndex];

        document.getElementById('code').value = selected.getAttribute('data-code') || '';
        document.getElementById('work_order').value = selected.getAttribute('data-workorder') || '';
        document.getElementById('first_set').value = selected.getAttribute('data-partdesc') || '';
        document.getElementById('qty').value = selected.getAttribute('data-qty') || '';
        document.getElementById('e_time').value = selected.getAttribute('data-e_time') || '';
    });


    function calculateHours() {
        let start = document.querySelector('[name="start_time"]').value;
        let end = document.querySelector('[name="end_time"]').value;

        if (start && end) {
            let startTime = new Date(start);
            let endTime = new Date(end);

            if (endTime >= startTime) {
                let diffMinutes = (endTime - startTime) / (1000 * 60);
                let actualHrs = diffMinutes / 60;
                let hrs = diffMinutes * 0.02;
                let timeTaken = hrs;

                document.querySelector('[name="hrs"]').value = hrs.toFixed(2);
                document.querySelector('[name="time_taken"]').value = timeTaken.toFixed(2);
                document.querySelector('[name="actual_hrs"]').value = actualHrs.toFixed(2);


                if (document.querySelector('[name="minute"]')) {
                    document.querySelector('[name="minute"]').value = diffMinutes.toFixed(0);
                }
            }
        }
    }

    // Event binding
    document.querySelector('[name="start_time"]').addEventListener('change', calculateHours);
    document.querySelector('[name="end_time"]').addEventListener('change', calculateHours);
</script>

<script>
    document.getElementById('customer_id').addEventListener('change', function() {
        let customerId = this.value;
        let partSelect = document.getElementById('part_no');

        partSelect.innerHTML = '<option value="">Select Part No</option>'; // reset

        if (customerId) {
            fetch(`/get-customer-parts/${customerId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(part => {
                        let option = document.createElement('option');
                        option.value = part.part_no;
                        option.textContent = part.part_no;
                        option.setAttribute('data-code', part.code);
                        option.setAttribute('data-workorder', part.project_id);
                        option.setAttribute('data-partdesc', part.part_description);
                        option.setAttribute('data-qty', part.quantity);
                        option.setAttribute('data-e_time', part.exp_time);
                        partSelect.appendChild(option);
                    });
                });
        }
    });

    document.getElementById('part_no').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];

        document.getElementById('code').value = selected.getAttribute('data-code') || '';
        document.getElementById('work_order').value = selected.getAttribute('data-workorder') || '';
        document.getElementById('first_set').value = selected.getAttribute('data-partdesc') || '';
        document.getElementById('qty').value = selected.getAttribute('data-qty') || '';
        document.getElementById('e_time').value = selected.getAttribute('data-e_time') || '';

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