@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">

                        <!-- Back Button ONLY on Edit -->
                        <a href="{{ route('ViewMaterialorder') }}" class="btn btn-sm btn-outline-success me-2">
                            ←
                        </a>

                        {{ isset($record) ? 'Edit Material Order' : 'Add Material Order' }}
                    </h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ isset($record) ? route('updateMaterialorder', base64_encode($record->id)) : route('storeMaterialorder') }}" method="POST">
                        @csrf
                        @if(isset($record))
                        @method('PUT')
                        @endif

                        <div class="row g-3">

                            <!-- CUSTOMER -->
                            <div class="col-md-2">
                                <label>Customer Code <span class="text-red">*</span></label>

                                <select id="customer_id" name="customer_id"
                                    class="form-select js-example-basic-single"
                                    {{ isset($record) ? 'disabled' : '' }}>

                                    <option value="">Select</option>
                                    @foreach($customers as $c)
                                    <option value="{{ $c->id }}"
                                        {{ isset($record) && $record->customer_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->code }}
                                    </option>
                                    @endforeach
                                </select>

                                {{-- Disabled field value send to backend --}}
                                @if(isset($record))
                                <input type="hidden" name="customer_id" value="{{ $record->customer_id }}">
                                @endif

                                @error('customer_id')
                                <span class="text-red small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- WO NO -->
                            <div class="col-md-3 d-none">
                                <label>WO No</label>
                                <input type="text" id="work_order_no" name="work_order_no"
                                    class="form-control"
                                    value="{{ $record->work_order_no ?? '' }}">
                            </div>

                            <!-- DATE -->
                            <div class="col-md-2">
                                <label>Date <span class="text-red">*</span></label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ $record->date ?? old('date') }}">
                                @error('date')
                                <span class="text-red small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- SR -->
                            <div class="col-md-8">
                                <label>Material Requests (SR)<span class="text-red">*</span></label>
                                <select id="material_data_dropdown" name="material_req_ids[]"
                                    class="form-select js-example-basic-single select2-hidden"
                                    multiple>
                                    @if(isset($materialRequests))
                                    @foreach($materialRequests as $mr)
                                    <option value="{{ $mr->id }}"
                                        @if(isset($selectedIds) && in_array($mr->id, $selectedIds)) selected @endif>
                                        SR-{{ $mr->sr_no }}
                                    </option>
                                    @endforeach
                                    @endif

                                </select>
                                <input type="hidden" name="material_req_ids_dummy" value="1">
                                @error('material_req_ids')
                                <span class="text-red small">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- ADD BUTTON (only for add mode) -->
                        <!-- @if(!isset($record))
                        <div class="text-start mt-3">
                            <button type="button" id="addMaterialBtn" class="btn btn-success">
                                Add
                            </button>
                        </div>
                        @endif -->

                        <h6 class="mt-4">Material Requests detils</h6>
                        <!-- TABLE -->
                        <div class="mt-4">
                            <table class="table table-bordered {{ isset($record) ? '' : 'd-none' }}" id="previewTable">
                                <thead class="table-light text-center">
                                    <!-- TOP HEADER -->
                                    <tr>
                                        <th rowspan="2" style="width:60px">SR</th>
                                        <th rowspan="2" style="width:25%">Description</th>

                                        <th colspan="4">FINISH SIZE</th>
                                        <th colspan="4">RAW SIZE</th>

                                        <th rowspan="2" style="width:120px">Material</th>
                                        <th rowspan="2" style="width:65px">Qty</th>
                                        <th rowspan="2" style="width:20px">Action</th>
                                    </tr>

                                    <!-- SUB HEADER -->
                                    <tr>
                                        <!-- FINISH -->
                                        <th style="width:80px">Dia</th>
                                        <th style="width:80px">L</th>
                                        <th style="width:80px">W</th>
                                        <th style="width:80px">H</th>

                                        <!-- RAW -->
                                        <th style="width:80px">Dia</th>
                                        <th style="width:80px">L</th>
                                        <th style="width:80px">W</th>
                                        <th style="width:80px">H</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- EDIT MODE --}}
                                    @if(isset($records))
                                    @foreach($records as $rec)
                                    <tr id="row_{{ $rec->material_req_id }}">

                                        <td>{{ $rec->sr_no }}</td>

                                        <td>
                                            <input type="text" name="work_order_desc[]" class="form-control"
                                                value="{{ $rec->work_order_desc }}">
                                        </td>

                                        <!-- FINISH -->
                                        <td><input type="text" name="f_diameter[]" class="form-control" value="{{ $rec->f_diameter }}"></td>
                                        <td><input type="text" name="f_length[]" class="form-control" value="{{ $rec->f_length }}"></td>
                                        <td><input type="text" name="f_width[]" class="form-control" value="{{ $rec->f_width }}"></td>
                                        <td><input type="text" name="f_height[]" class="form-control" value="{{ $rec->f_height }}"></td>

                                        <!-- RAW -->
                                        <td><input type="text" name="r_diameter[]" class="form-control" value="{{ $rec->r_diameter }}"></td>
                                        <td><input type="text" name="r_length[]" class="form-control" value="{{ $rec->r_length }}"></td>
                                        <td><input type="text" name="r_width[]" class="form-control" value="{{ $rec->r_width }}"></td>
                                        <td><input type="text" name="r_height[]" class="form-control" value="{{ $rec->r_height }}"></td>

                                        <!-- MATERIAL -->
                                        <td>
                                            <input type="text" name="material[]" class="form-control"
                                                value="{{ $rec->material }}">
                                        </td>

                                        <!-- QTY -->
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control"
                                                value="{{ $rec->quantity }}">
                                        </td>

                                        <td>-</td>

                                        <input type="hidden" name="material_req_ids[]" value="{{ $rec->material_req_id }}">
                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <!-- SUBMIT -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($record) ? 'Update' : 'Submit' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let allRequests = {}; // store all material requests for selected customer
        let addedRows = {}; // track which rows are already added
        let selectedIds = []; // currently selected SR ids

        // Initialize Select2
        $('#material_data_dropdown').select2({
            placeholder: 'Select SR',
            width: '100%',
            closeOnSelect: false,
            allowClear: true
        });

        // ===== EDIT MODE =====
        @if(isset($record))

        // already selected SR
        let existingId = '{{ $record->material_req_id }}';

        // mark as selected
        addedRows[existingId] = true;
        selectedIds = [existingId];

        // set select2 value
        $('#material_data_dropdown').val([existingId]).trigger('change');

        // show table
        $('#previewTable').removeClass('d-none');

        @endif

        // ===== ADD MODE =====
        @if(!isset($record))
        // When customer changes, fetch material requests
        $('#customer_id').change(function() {
            let cid = $(this).val();

            // reset
            $('#material_data_dropdown').empty().trigger('change');
            $('#previewTable tbody').empty();
            $('#previewTable').addClass('d-none');
            allRequests = {};
            addedRows = {};
            selectedIds = [];

            if (!cid) return;

            // Fetch material requests
            $.get('/get-material-requests/' + cid, function(res) {
                if (res.status === 'success') {
                    res.data.forEach(item => {
                        allRequests[item.id] = item;
                        $('#material_data_dropdown').append(
                            `<option value="${item.id}">SR-${item.sr_no}</option>`
                        );
                    });
                }
            });

            // Fetch latest work order no
            $.get('/get-customer-wo/' + cid, function(res) {
                $('#work_order_no').val(res.work_order_no ?? '');
            });
        });

        // ✅ ALWAYS RUN (ADD + EDIT)
        $('#material_data_dropdown').on('change', function() {
            let ids = $(this).val() || [];
            let tbody = $('#previewTable tbody');

            ids.forEach(id => {
                if (!addedRows[id] && $('#row_' + id).length === 0) {
                    addedRows[id] = true;
                    let d = allRequests[id] || {}; // fallback

                    tbody.append(`
<tr id="row_${id}">
    <td>SR-${d.sr_no ?? id}</td>
    <td><input type="text" name="work_order_desc[]" class="form-control form-control-sm" value="${d.description ?? ''}"></td>

<td><input type="number" step="0.01" name="f_diameter[]" class="form-control form-control-sm" value="${d.dia ?? ''}"></td>
<td><input type="number" step="0.01" name="f_length[]" class="form-control form-control-sm" value="${d.length ?? ''}"></td>
<td><input type="number" step="0.01" name="f_width[]" class="form-control form-control-sm" value="${d.width ?? ''}"></td>
<td><input type="number" step="0.01" name="f_height[]" class="form-control form-control-sm" value="${d.height ?? ''}"></td>

<td><input type="number" step="0.01" name="r_diameter[]" class="form-control form-control-sm"></td>
<td><input type="number" step="0.01" name="r_length[]" class="form-control form-control-sm"></td>
<td><input type="number" step="0.01" name="r_width[]" class="form-control form-control-sm"></td>
<td><input type="number" step="0.01" name="r_height[]" class="form-control form-control-sm"></td>

<td><input type="text" name="material[]" class="form-control form-control-sm" value="${d.material_name ?? ''}"></td>
<td><input type="number" name="quantity[]" class="form-control form-control-sm" value="${d.qty ?? ''}"></td>

<td>
    <button type="button" class="btn btn-sm btn-danger removeRow" data-id="${id}">
        <i class="fa fa-trash"></i>
    </button>
</td>

<input type="hidden" name="material_req_ids[]" value="${id}">
</tr>
            `);
                }
            });

            selectedIds.forEach(oldId => {
                if (!ids.includes(oldId)) {
                    delete addedRows[oldId];
                    $('#row_' + oldId).remove();
                }
            });

            selectedIds = ids;

            if (selectedIds.length > 0) $('#previewTable').removeClass('d-none');
            else $('#previewTable').addClass('d-none');
        });

        // Remove row button click
        $(document).on('click', '.removeRow', function() {
            let id = $(this).data('id');

            // Remove from tracking objects
            delete addedRows[id];
            selectedIds = selectedIds.filter(i => i != id);

            // Remove row
            $('#row_' + id).remove();

            // Remove from select2
            let selected = $('#material_data_dropdown').val() || [];
            selected = selected.filter(item => item != id);
            $('#material_data_dropdown').val(selected).trigger('change');

            // Hide table if empty
            if ($('#previewTable tbody tr').length == 0) {
                $('#previewTable').addClass('d-none');
            }
        });
        @endif

    });
</script>

@endsection