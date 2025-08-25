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


                            <form action="{{ isset($record) ? route('UpdateMachinerecord', base64_encode($record->id)) : route('StoreMachinerecord') }}" method="POST">
                                @csrf
                                @if(isset($record))
                                @method('PUT')
                                @endif

                                <div class="row g-3">

                                    <!-- Part No -->
                                    <div class="col-md-4">
                                        <label class="form-label">Part No <span class="text-red">*</span></label>
                                        <select name="part_no" id="part_no" class="form-control">
                                            <option value="">Select Part No</option>
                                            @foreach($workorders as $wo)
                                            @php
                                            $partNo = ($wo->customer?->code ?? '') . '_' . ($wo->customer_id ?? '') . '_' . ($wo->part ?? '');
                                            @endphp
                                            <option value="{{ $partNo }}"
                                                data-workorder="{{ $wo->customer_id }}"
                                                data-partdesc="{{ $wo->part_description }}"
                                                {{ old('part_no', $record->part_no ?? '') == $partNo ? 'selected' : '' }}>
                                                {{ $partNo }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('part_no') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Work Order No -->
                                    <div class="col-md-2">
                                        <label class="form-label">Work Order No</label>
                                        <input type="text" id="work_order" name="work_order"
                                            class="form-control" value="{{ old('work_order', $record->work_order ?? '') }}" readonly>
                                    </div>

                                    <!-- Part Description -->
                                    <div class="col-md-4">
                                        <label class="form-label">First Set <span class="text-red">*</span></label>
                                        <input type="text" name="first_set" id="first_set" class="form-control"
                                            value="{{ old('first_set', $record->first_set ?? '') }}">
                                        @error('first_set') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>



                                    <!-- Qty -->
                                    <div class="col-md-2">
                                        <label class="form-label">Qty <span class="text-red">*</span></label>
                                        <input type="number" name="qty" class="form-control"
                                            value="{{ old('qty', $record->qty ?? '') }}">
                                        @error('qty') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Machine -->
                                    <div class="col-md-4">
                                        <label class="form-label">Machine <span class="text-danger">*</span></label>
                                        <select name="machine" class="form-control">
                                            <option value=""> Select Machine </option>
                                            @foreach($machines as $machine)
                                            <option value="{{ $machine->machine_name }}"
                                                {{ old('machine', $record->machine ?? '') == $machine->machine_name ? 'selected' : '' }}>
                                                {{ $machine->machine_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('machine') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Operator -->
                                    <div class="col-md-4">
                                        <label class="form-label">Operator <span class="text-danger">*</span></label>
                                        <select name="operator" class="form-control">
                                            <option value="">Select Operator</option>
                                            @foreach($operators as $operator)
                                            <option value="{{ $operator->operator_name }}"
                                                {{ old('operator', $record->operator ?? '') == $operator->operator_name ? 'selected' : '' }}>
                                                {{ $operator->operator_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('operator') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Setting -->
                                    <div class="col-md-4">
                                        <label class="form-label">Setting <span class="text-danger">*</span></label>
                                        <select name="setting_no" class="form-control">
                                            <option value="">Select Setting</option>
                                            @foreach($settings as $setting)
                                            <option value="{{ $setting->setting_name }}"
                                                {{ old('setting_no', $record->setting_no ?? '') == $setting->setting_name ? 'selected' : '' }}>
                                                {{ $setting->setting_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('setting_no') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>



                                    <!-- Estimated Time -->
                                    <div class="col-md-4">
                                        <label class="form-label">Estimated Time (hrs) <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="est_time" class="form-control"
                                            value="{{ old('est_time', $record->est_time ?? '') }}">
                                        @error('est_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Start Time -->
                                    <div class="col-md-4">
                                        <label class="form-label">Start Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="start_time" class="form-control"
                                            value="{{ old('start_time', isset($record->start_time) ? date('Y-m-d\TH:i', strtotime($record->start_time)) : '') }}">
                                        @error('start_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- End Time -->
                                    <div class="col-md-4">
                                        <label class="form-label">End Time <span class="text-red">*</span></label>
                                        <input type="datetime-local" name="end_time" class="form-control"
                                            value="{{ old('end_time', isset($record->end_time) ? date('Y-m-d\TH:i', strtotime($record->end_time)) : '') }}">
                                        @error('end_time') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- HRS -->
                                    <div class="col-md-3">
                                        <label class="form-label">HRS <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="hrs" class="form-control"
                                            value="{{ old('hrs', $record->hrs ?? '') }}">
                                        @error('hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Time Taken -->
                                    <div class="col-md-3">
                                        <label class="form-label">Time Taken <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="time_taken" class="form-control"
                                            value="{{ old('time_taken', $record->time_taken ?? '') }}">
                                        @error('time_taken') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Actual HRS -->
                                    <div class="col-md-3">
                                        <label class="form-label">Actual HRS <span class="text-red">*</span></label>
                                        <input type="number" step="0.01" name="actual_hrs" class="form-control"
                                            value="{{ old('actual_hrs', $record->actual_hrs ?? '') }}">
                                        @error('actual_hrs') <span class="text-red small">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Invoice No -->
                                    <div class="col-md-3">
                                        <label class="form-label">Invoice No <span class="text-red">*</span></label>
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

        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
</div> <!-- main-content -->
<script>
    document.getElementById('part_no').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];

        document.getElementById('work_order').value = selected.getAttribute('data-workorder') || '';
        document.getElementById('first_set').value = selected.getAttribute('data-partdesc') || '';
    });
</script>


@endsection