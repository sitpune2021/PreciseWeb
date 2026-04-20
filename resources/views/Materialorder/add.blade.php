@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <!-- Back Button ONLY on Edit -->
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">
                            ←
                        </a>
                        {{ isset($record) ? 'Edit Material Order' : 'Add Material Order' }}
                    </h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1055;">
                        <div id="successAlert"
                            class="alert alert-success alert-dismissible fade show py-2 px-3 shadow-sm text-center"
                            style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
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
                                        {{ $mr->project_id ? 'WO NO-'.$mr->project_id : '-' }}
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
                                <thead class="text-center">
                                    <!-- TOP HEADER -->
                                    <tr>
                                        <th rowspan="2" class="bg-finish" style="width:60px">Wo.NO</th>
                                        <th rowspan="2" class="bg-finish" style="width:25%">Description</th>

                                        <th colspan="4" class="bg-finish">FINISH SIZE</th>
                                        <th colspan="4" class="bg-raw">RAW SIZE</th>

                                        <th rowspan="2" class="bg-raw" style="width:120px">Material</th>
                                        <th rowspan="2" class="bg-raw" style="width:65px">Qty</th>
                                        <th rowspan="2" class="bg-raw" style="width:20px">Action</th>
                                    </tr>

                                    <!-- SUB HEADER -->
                                    <tr>
                                        <!-- FINISH -->
                                        <th class="bg-finish">Dia</th>
                                        <th class="bg-finish">L</th>
                                        <th class="bg-finish">W</th>
                                        <th class="bg-finish">H</th>

                                        <!-- RAW -->
                                        <th class="bg-raw">Dia</th>
                                        <th class="bg-raw">L</th>
                                        <th class="bg-raw">W</th>
                                        <th class="bg-raw">H</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- EDIT MODE --}}
                                    @if(isset($records))
                                    @foreach($records as $rec)
                                    <tr id="row_{{ $rec->material_req_id }}">

                                        <td>{{ $rec->materialReq->project_id ?? '-' }}</td>

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
            <style>
                /* FORCE APPLY COLOR */
                #previewTable thead th.bg-finish {
                    background-color: #e8f3fb !important;
                    color: #353232;
                }

                #previewTable thead th.bg-raw {
                    background-color: #dadad7 !important;
                    color: #333333;

                }

                /* Table border fix */
                #previewTable {
                    border-collapse: collapse;
                }

                #previewTable,
                #previewTable th,
                #previewTable td {
                    border: 1px solid #797878 !important;
                }
            </style>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Material Orders</h5>
                            <div class="d-flex gap-2">

                                <!-- @if(hasPermission('MaterialOrder', 'add'))
                                <a href="{{ route('AddMaterialorder') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Material Order
                                </a>
                                @endif -->

                                <a href="{{ route('trashMaterialorder') }}" class="btn btn-warning btn-sm">
                                    <i class="ri-delete-bin-line align-middle"></i> View Trash
                                </a>
                            </div>
                        </div>

                        @if(hasPermission('MaterialOrder', 'view'))
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables-decs" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>#</th>
                                            <th>Wo.No</th>
                                            <th>Date</th>
                                            <th style="width: 40px;">Customer Code</th>
                                            <th>Work Order desc</th>
                                            <th>Material</th>
                                            <th>Qty</th>
                                            <th width="12%">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @forelse($orders->reverse() as $order)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ $order->project_id ?? $order->materialReq->project_id ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>

                                            <td class="text-center">{{ $order->customer->code ?? 'N/A' }}</td>

                                            <td class="text-start">{{ $order->work_order_desc }}</td>

                                            <td>
                                                {{ $order->material }}
                                            </td>
                                            <td class="text-end">{{ $order->quantity }}</td>

                                            <td class="text-center">

                                                @if(hasPermission('MaterialOrder', 'edit'))
                                                <a href="{{ route('editMaterialorder', base64_encode($order->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                @endif

                                                <!-- View Button -->
                                                <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn"
                                                    data-no="{{ $order->project_id ?? '-' }}"
                                                    data-name="{{ $order->customer->code ?? 'N/A' }}"
                                                    data-date="{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}"
                                                    data-desc="{{ $order->work_order_desc }}"
                                                    data-fdia="{{ rtrim(rtrim($order->f_diameter, '0'), '.') }}"
                                                    data-flen="{{ rtrim(rtrim($order->f_length, '0'), '.') }}"
                                                    data-fwid="{{ rtrim(rtrim($order->f_width, '0'), '.') }}"
                                                    data-fhei="{{ rtrim(rtrim($order->f_height, '0'), '.') }}"
                                                    data-rdia="{{ rtrim(rtrim($order->r_diameter, '0'), '.') }}"
                                                    data-rlen="{{ rtrim(rtrim($order->r_length, '0'), '.') }}"
                                                    data-rwid="{{ rtrim(rtrim($order->r_width, '0'), '.') }}"
                                                    data-rhei="{{ rtrim(rtrim($order->r_height, '0'), '.') }}"
                                                    data-mat="{{ $order->material }}"

                                                    data-qty="{{ $order->quantity }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>


                                                @if(hasPermission('MaterialOrder', 'delete'))
                                                <a href="{{ route('deleteMaterialorder', base64_encode($order->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="15" class="text-center text-muted">No records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div><!--end row-->

        </div>
    </div>
</div>

<!-- 🔹 VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">Material Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered mb-3">
                    <tr>
                        <th>Customer Code</th>
                        <td id="v_name"></td>
                    </tr>
                    <tr>
                        <th>Sr.No</th>
                        <td id="v_no"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="v_date"></td>
                    </tr>
                    <tr>
                        <th>Work Order Desc</th>
                        <td id="v_desc"></td>
                    </tr>
                    <tr>
                        <th>Material</th>
                        <td id="v_mat"></td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td id="v_qty"></td>
                    </tr>
                </table>

                <!-- Finish & Raw Size side-by-side -->
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <table class="table table-bordered">
                            <tr class="bg-light">
                                <th colspan="2">Finish Size</th>
                            </tr>
                            <tr>
                                <th>DIA</th>
                                <td id="v_fdia"></td>
                            </tr>
                            <tr>
                                <th>Length</th>
                                <td id="v_flen"></td>
                            </tr>
                            <tr>
                                <th>Width</th>
                                <td id="v_fwid"></td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td id="v_fhei"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-bordered">
                            <tr class="bg-light">
                                <th colspan="2">Raw Size</th>
                            </tr>
                            <tr>
                                <th>DIA</th>
                                <td id="v_rdia"></td>
                            </tr>
                            <tr>
                                <th>Length</th>
                                <td id="v_rlen"></td>
                            </tr>
                            <tr>
                                <th>Width</th>
                                <td id="v_rwid"></td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td id="v_rhei"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.viewBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Basic info
                document.getElementById('v_name').innerText = this.dataset.name;
                document.getElementById('v_no').innerText = this.dataset.no;
                document.getElementById('v_date').innerText = this.dataset.date;
                document.getElementById('v_desc').innerText = this.dataset.desc;
                document.getElementById('v_mat').innerText = this.dataset.mat;
                document.getElementById('v_qty').innerText = this.dataset.qty;

                // Finish Size
                document.getElementById('v_fdia').innerText = this.dataset.fdia;
                document.getElementById('v_flen').innerText = this.dataset.flen;
                document.getElementById('v_fwid').innerText = this.dataset.fwid;
                document.getElementById('v_fhei').innerText = this.dataset.fhei;

                // Raw Size
                document.getElementById('v_rdia').innerText = this.dataset.rdia;
                document.getElementById('v_rlen').innerText = this.dataset.rlen;
                document.getElementById('v_rwid').innerText = this.dataset.rwid;
                document.getElementById('v_rhei').innerText = this.dataset.rhei;

                new bootstrap.Modal(document.getElementById('viewModal')).show();
            });
        });
    });
</script>

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
                            `<option value="${item.id}">WO -${item.project_id}</option>`
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
                        <td>${d.project_id ? '' + d.project_id : '-'}</td>
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