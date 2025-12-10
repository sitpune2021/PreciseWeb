@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
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
                                    <div class="col-md-3">
                                        <label for="customer_id" class="form-label">Customer Code <span class="text-red small">*</span></label>

                                        <select class="form-select js-example-basic-single"
                                            id="customer_id"
                                            name="customer_id"
                                            data-selected="{{ old('customer_id', $record->customer_id ?? '') }}"
                                            {{ isset($record) ? 'disabled' : '' }}>

                                            <option value="">Select Customer Code</option>

                                            @foreach($codes as $c)
                                            <option value="{{ $c->id }}"
                                                data-code="{{ $c->code }}"
                                                data-details="{{ $c->materialreq }}"
                                                data-id="{{ $c->customer_srno }}"
                                                {{ old('customer_id', $record->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                {{ $c->code }}
                                            </option>
                                            @endforeach
                                        </select>

                                        @error('customer_id')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror

                                        @if(isset($record))
                                        <input type="hidden" name="customer_id" value="{{ $record->customer_id }}">
                                        @endif
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="work_order_no" class="form-label">WO No. <span class="mandatory">*</span></label>
                                            <input type="text" name="work_order_no" id="work_order_no"
                                                class="form-control"
                                                value="{{ old('work_order_no', $record->work_order_no ?? '') }}" readonly>
                                            @error('work_order_no')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Select Material Req</label>
                                        <select id="material_data_dropdown"
                                            class="form-control form-select"
                                            data-selected="{{ old('material_req_id', $record->material_req_id ?? '') }}">
                                            <option value="">Select Material Req</option>
                                        </select>
                                    </div>


                                    <div class="col-md-3">
                                        <label class="form-label">Date <span class="text-red">*</span></label>
                                        <input type="date" name="date" id="date" class="form-control "
                                            value="{{ old('date', isset($record->date) ? \Carbon\Carbon::parse($record->date)->format('Y-m-d') : '') }}">
                                        @error('date')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-7">
                                        <label class="form-label">Work Order Description </label>
                                        <input type="text" name="work_order_desc" id="description" class="form-control"
                                            value="{{ old('work_order_desc', $record->work_order_desc ?? '') }}">
                                        @error('work_order_desc')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold">Finish Size</h5>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Diameter</label>
                                        <input type="number" step="0.01" name="f_diameter" id="f_diameter" class="form-control"
                                            value="{{ old('f_diameter', $materialReq->dia ?? $record->f_diameter ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" id="f_length" name="f_length" class="form-control"
                                            value="{{ old('f_length', $materialReq->length ?? $record->f_length ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Width</label>
                                        <input type="number" step="0.01" id="f_width" name="f_width" class="form-control"
                                            value="{{ old('f_width', $materialReq->width ?? $record->f_width ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Height</label>
                                        <input type="number" step="0.01" name="f_height" id="f_height" class="form-control"
                                            value="{{ old('f_height', $materialReq->height ?? $record->f_height ?? '') }}">
                                    </div>

                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold">Raw Size</h5>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Diameter</label>
                                        <input type="number" step="0.01" name="r_diameter" class="form-control"
                                            value="{{ old('r_diameter', $record->r_diameter ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Length</label>
                                        <input type="number" step="0.01" name="r_length" class="form-control"
                                            value="{{ old('r_length', $record->r_length ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Width</label>
                                        <input type="number" step="0.01" name="r_width" class="form-control"
                                            value="{{ old('r_width', $record->r_width ?? '') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Height</label>
                                        <input type="number" step="0.01" name="r_height" class="form-control"
                                            value="{{ old('r_height', $record->r_height ?? '') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Material<span class="text-red">*</span></label>
                                        <input type="text" name="material" id="material" class="form-control"
                                            value="{{ old('material', $materialReq->material ?? $record->material ?? '') }}">
                                        @error('material')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Quantity <span class="text-red">*</span></label>
                                        <input type="number" name="quantity" id="quantity" class="form-control"
                                            value="{{ old('quantity', $materialReq->qty ?? $record->quantity ?? '') }}">
                                        @error('quantity')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-lg-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($record) ? 'Update' : 'Submit' }}
                                    </button>
                                    &nbsp;
                                    @if(isset($record))
                                    <a href="{{ route('ViewMaterialorder') }}" class="btn btn-info">Cancel</a>
                                    @else
                                    <button type="reset" class="btn btn-info">Reset</button>
                                    @endif
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div> ,
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            var dropdown = $('#material_data_dropdown');
            dropdown.html('<option>Loading...</option>');

            if (customerId) {
                $.ajax({
                    url: '/get-material-requests/' + customerId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        dropdown.empty();

                        if (response.status === 'success') {
                            dropdown.append('<option value="">Select Material Req</option>');
                            $.each(response.data, function(index, item) {
                                // Show SR number in dropdown
                                dropdown.append('<option value="' + item.id + '">SR ' + item.sr_no + ' - ' + item.description + '</option>');
                            });
                        } else if (response.status === 'empty') {
                            dropdown.append('<option>No Material Requests Found</option>');
                        } else {
                            dropdown.append('<option>Error Loading Data</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        dropdown.html('<option>Error fetching data</option>');
                    }
                });
            } else {
                dropdown.html('<option value="">Select Material Req</option>');
            }
        });
    });
</script>




<script>
    $(document).ready(function() {

        $('#material_data_dropdown').on('change', function() {
            var reqId = $(this).val();

            if (reqId) {
                $.ajax({
                    url: '/get-material-request-details/' + reqId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;

                            $('#work_order_no').val(data.work_order_no);
                            $('#date').val(data.date);
                            $('#description').val(data.description);

                            $('#material').val(data.material_name);

                            $('#f_diameter').val(data.dia);
                            $('#f_length').val(data.length);
                            $('#f_width').val(data.width);
                            $('#f_height').val(data.height);
                            $('#quantity').val(data.qty);
                        } else if (response.status === 'not_found') {
                            alert('No data found for this material request.');
                        } else {
                            alert('Error fetching material data.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('AJAX error occurred.');
                    }
                });
            } else {

                $('#work_order_no, #date, #description, #material, #f_diameter, #f_length, #f_width, #f_height, #quantity').val('');
            }
        });

    });
</script>


@endsection