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
                            ← Back
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
                        @if(isset($record)) @method('PUT') @endif

                        <div class="row g-3">

                            <!-- CUSTOMER -->
                            <div class="col-md-2">
                                <label>Customer <span class="text-red">*</span></label>

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

                                {{-- 🔥 Disabled field value send to backend --}}
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
                            <div class="col-md-3">
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
                                <select id="material_data_dropdown"
                                    class="form-select"
                                    {{ isset($record) ? 'disabled' : '' }}
                                    multiple>
                                </select>

                                <input type="hidden" name="material_req_ids_dummy" value="1">

                                @error('material_req_ids')
                                <span class="text-red small">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- ADD BUTTON (only for add mode) -->
                        @if(!isset($record))
                        <div class="text-start mt-3">
                            <button type="button" id="addMaterialBtn" class="btn btn-success">
                                Add
                            </button>
                        </div>
                        @endif

                        <h6 class="mt-4">Material Requests detils</h6>
                        <!-- TABLE -->
                        <div class="mt-4">
                            <table class="table table-bordered {{ isset($record) ? '' : 'd-none' }}" id="previewTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px">SR</th>
                                        <th style="width:35%">Description</th>
                                        <th style="width:65px">Die</th>
                                        <th style="width:65px">Len</th>
                                        <th style="width:65px">Wid</th>
                                        <th style="width:65px">Hei</th>
                                        <th style="width:120px">Material</th>
                                        <th style="width:65px; text-align:center">Qty</th>
                                        <th style="width:80px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- EDIT MODE --}}
                                    @if(isset($record))
                                    <tr id="row_{{ $record->material_req_id }}">
                                        <td>SR-{{ $materialReq->sr_no ?? '' }}</td>

                                        <td>
                                            <input type="text" name="work_order_desc[]" class="form-control"
                                                value="{{ $record->work_order_desc }}">
                                        </td>

                                        <td>
                                            <input type="text" name="r_diameter[]" class="form-control"
                                                value="{{ rtrim(rtrim($record->r_diameter, '0'), '.') }}">
                                        </td>

                                        <td>
                                            <input type="text" name="r_length[]" class="form-control"
                                                value="{{ rtrim(rtrim($record->r_length, '0'), '.') }}">
                                        </td>

                                        <td>
                                            <input type="text" name="r_width[]" class="form-control"
                                                value="{{ rtrim(rtrim($record->r_width, '0'), '.') }}">
                                        </td>

                                        <td>
                                            <input type="text" name="r_height[]" class="form-control"
                                                value="{{ rtrim(rtrim($record->r_height, '0'), '.') }}">
                                        </td>


                                        <td><input type="text" name="material[]" class="form-control"
                                                value="{{ $record->material }}"></td>

                                        <td><input type="number" name="quantity[]" class="form-control"
                                                value="{{ $record->quantity }}"></td>

                                        <td>
                                            {{-- No remove button in edit mode --}}
                                            -
                                        </td>

                                        <input type="hidden" name="material_req_ids[]"
                                            value="{{ $record->material_req_id }}">
                                    </tr>
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

        let allRequests = {};
        let addedRows = {};

        $('#material_data_dropdown').select2({
            placeholder: 'Select SR',
            width: '100%'
        });

        @if(!isset($record))
        // CUSTOMER CHANGE (only in add mode)
        $('#customer_id').change(function() {
            let cid = $(this).val();

            $('#material_data_dropdown').empty().trigger('change');
            $('#previewTable tbody').empty();
            $('#previewTable').addClass('d-none');
            allRequests = {};
            addedRows = {};

            if (!cid) return;

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

            $.get('/get-customer-wo/' + cid, function(res) {
                $('#work_order_no').val(res.work_order_no ?? '');
            });
        });

        // ADD SR
        $('#addMaterialBtn').click(function() {
            let ids = $('#material_data_dropdown').val();
            if (!ids) return;

            let tbody = $('#previewTable tbody');

            ids.forEach(id => {
                if (addedRows[id]) return;
                addedRows[id] = true;

                let d = allRequests[id];

                tbody.append(`
                <tr id="row_${id}">
                    <td>SR-${d.sr_no}</td>
                    <td><input type="text" name="work_order_desc[]" class="form-control" value="${d.description}"></td>
                    <td><input type="text" name="r_diameter[]" class="form-control" value="${d.dia ?? ''}"></td>
                    <td><input type="text" name="r_length[]" class="form-control" value="${d.length ?? ''}"></td>
                    <td><input type="text" name="r_width[]" class="form-control" value="${d.width ?? ''}"></td>
                    <td><input type="text" name="r_height[]" class="form-control" value="${d.height ?? ''}"></td>
                    <td><input type="text" name="material[]" class="form-control" value="${d.material_name}"></td>
                    <td><input type="number" name="quantity[]" class="form-control" value="${d.qty}"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger removeRow" data-id="${id}">Remove</button>
                    </td>
                    <input type="hidden" name="material_req_ids[]" value="${id}">
                </tr>
                `);
            });

            $('#previewTable').removeClass('d-none');
        });

        // REMOVE
        $(document).on('click', '.removeRow', function() {
            let id = $(this).data('id');
            delete addedRows[id];
            $('#row_' + id).remove();
        });
        @endif

    });
</script>

@endsection