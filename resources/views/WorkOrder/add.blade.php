@extends('layouts.header')
@section('content')
@if(hasPermission('WorkOrders','view') || hasPermission('WorkOrders','add'))
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    @if(hasPermission('WorkOrders','add'))
                    <div class="card">
                        <div class="card-header align-items-center d-flex">

                            <!-- Back Button ONLY on Edit -->
                            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">
                                ←
                            </a>

                            <h4 class="mb-0 flex-grow-1"> {{ isset($workorder) ? 'Edit WorkOrder' : 'Add Work Order' }}</h4>
                        </div>

                        <div class="card-body">

                            <div class="live-preview">
                                <form id="workOrderForm" action="{{ isset($workorder) ? route('updateWorkEntry', base64_encode($workorder->id)) : route('storeWorkEntry') }}" method="POST">

                                    @csrf
                                    @if(isset($workorder)) @method('PUT') @endif

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="mb-2">
                                                <label for="customer_id" class="form-label">Customer Code <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single"
                                                    id="customer_id"
                                                    name="customer_id"
                                                    {{ isset($workorder) ? 'disabled' : '' }}>

                                                    <option value="">Select Code</option>

                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ old('customer_id', $workorder->customer_id ?? $lastCustomer ?? '') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->code }}
                                                    </option>
                                                    @endforeach

                                                </select>

                                                @if(isset($workorder))
                                                <input type="hidden" name="customer_id" value="{{ $workorder->customer_id }}">
                                                @endif
                                                @error('customer_id')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                                <span class="text-red small customer"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="project_id" class="form-label">Project Name <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single" id="project_id" name="project_id">
                                                    <option value="">Select Project</option>
                                                    @foreach($projects as $p)
                                                    @if(isset($workorder) && $p->customer_id == $workorder->customer_id)
                                                    <option value="{{ $p->id }}"
                                                        {{ old('project_id', $workorder->project_id ?? '') == $p->id ? 'selected' : '' }}>
                                                        {{ $p->project_no }} - {{ $p->project_name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>

                                                @error('project_id') <span class="text-red">{{ $message }}</span> @enderror
                                                <span class="text-red small project_id"></span>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-2 ">
                                            <div class="mb-2">
                                                <label for="previous_part" class="form-label">Previous Part List</label>
                                                <select class="form-control form-select mt-1" id="previous_part" name="previous_part">
                                                    <option value="">No Previous Part</option>
                                                </select>

                                                <span class="text-red small previous_part"></span>
                                            </div>
                                        </div> -->

                                        <div class="col-md-2">
                                            <div class="mb-2">
                                                <label for="part" class="form-label">Part <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="part" name="part"
                                                    placeholder="Enter Part" value="{{ old('part', $workorder->part ?? '') }}">
                                                @error('part')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                                <span class="text-red small part"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $workorder->date ?? '') }}">
                                                @error('date') <span class="text-red">{{ $message }}</span> @enderror
                                                <span class="text-red date"></span>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="dimeter" class="form-label">Diameter</label>
                                                    <input
                                                        type="text"
                                                        class="form-control  mt-1"
                                                        id="dimeter"
                                                        name="dimeter"
                                                        placeholder="Diameter"
                                                        value="{{ old('dimeter', $workorder->dimeter ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="length" class="form-label">Length</label>
                                                    <input
                                                        type="text"
                                                        class="form-control  mt-1"
                                                        id="length"
                                                        name="length"
                                                        placeholder="Length"
                                                        value="{{ old('length', $workorder->length ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="width" class="form-label">Width</label>
                                                    <input
                                                        type="text"
                                                        class="form-control  mt-1"
                                                        id="width"
                                                        name="width"
                                                        placeholder="Width"
                                                        value="{{ old('width', $workorder->width ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="height" class="form-label">Height</label>
                                                    <input
                                                        type="text"
                                                        class="form-control mt-1"
                                                        id="height"
                                                        name="height"
                                                        placeholder="Height"
                                                        value="{{ old('height', $workorder->height ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); if((this.value.match(/\./g)||[]).length>1) this.value=this.value.slice(0,-1);">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="exp_time" class="form-label">Exp Time (HH:MM) <span class="mandatory">*</span></label>
                                                    <input type="text" name="exp_time" id="exp_time"
                                                        value="{{ old('exp_time', $workorder->exp_time ?? '') }}"
                                                        class="form-control" placeholder="3 min, 3.30 hr">
                                                    @error('exp_time')
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                    <span class="text-red small exp_time"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="1"
                                                        class="form-control"
                                                        id="quantity"
                                                        name="quantity"
                                                        placeholder="Quantity"
                                                        value="{{ old('quantity', $workorder->quantity ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,5)">
                                                    @error('quantity')
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                    <span class="text-red small quantity"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="material" class="form-label">Material Type <span class="mandatory">*</span></label>
                                            <select name="material" id="material" class="form-control form-select">
                                                <option value="">Select Material</option>
                                                @foreach($materialtype as $mat)
                                                <option value="{{ $mat->material_type }}"
                                                    {{ (isset($workorder) && $workorder->material == $mat->material_type) || old('material') == $mat->material_type ? 'selected' : '' }}>
                                                    {{ $mat->material_type }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('material')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                            <span class="text-red small material"></span>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description</label>
                                                <input type="text" class="form-control  mt-1" id="part_description" name="part_description"
                                                    placeholder="Description"
                                                    value="{{ old('part_description', $workorder->part_description ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="text-end mt-3" id="topButtons">
                                            @if(isset($workorder))
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            @else
                                            <button type="button" class="btn btn-success" id="addFirstRowBtn">
                                                <i class="bi bi-plus-lg"></i> Add
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive mt-4" id="workOrderTableWrapper" style="display: none;">
                                        <table class="table table-bordered" id="workOrderTable">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>Sr. No.</th>
                                                    <th>Customer <br>Code</th>
                                                    <th>Part</th>
                                                    <th>Material<br>Type</th>
                                                    <th>Project<br>.name</th>
                                                    <th>Date</th>
                                                    <th>Diameter</th>
                                                    <th>Length</th>
                                                    <th>Width</th>
                                                    <th>Height</th>
                                                    <th>Expected Time</th>
                                                    <th>Quantity</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>

                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            @if(session('success'))
                            <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1055;">
                                <div id="successAlert"
                                    class="alert alert-success alert-dismissible fade show py-2 px-3 shadow-sm text-center"
                                    style="max-width:500px;">
                                    {{ session('success') }}
                                </div>
                            </div>
                            @endif
                            @if(hasPermission('WorkOrders', 'view'))
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">View Work Order Entries</h5>

                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Add WorkOrder Button -->
                                        <!-- @if(hasPermission('WorkOrders', 'add'))
                                        <a href="{{ route('AddWorkOrder') }}" class="btn btn-success btn-sm">
                                            <i class="ri-add-line align-middle"></i> Add WorkOrder
                                        </a>
                                        @endif -->
                                        <!-- View Trash Button -->
                                        <a href="{{ route('trashWorkOrder') }}" class="btn btn-warning btn-sm">
                                            View Trash
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-bordered table-sm" style="width:100%">
                                            <thead>
                                                <tr class="table-light">
                                                    <th>Sr.<br>No</th>
                                                    <!-- <th>Wo<br>Order<br>No.</th> -->
                                                    <!-- <th>Customer <br>Code</th> -->
                                                    <!-- <th>Part<br>No.</th> -->
                                                    <th>Date</th>
                                                    <th>Part Code</th>
                                                    <th>Part Description</th>
                                                    <th>Dia</th>
                                                    <th>Length</th>
                                                    <th>Width</th>
                                                    <th>Height</th>
                                                    <th>Exp Time</th>
                                                    <th>Qty</th>
                                                    <th width="12%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($workorders as $wo)

                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <!-- <td>{{ $wo->project?->project_no ?? '' }}</td> -->
                                                    <!-- <td>{{ $wo->customer?->code ?? '' }}</td> -->
                                                    <!-- <td>{{ $wo->part }}</td> -->
                                                    <td>{{ $wo->date }}</td>
                                                    <td>
                                                        {{ ($wo->customer?->code ?? '') . '_' . ($wo->project?->project_no ?? '') . '_' . ($wo->part ?? '') . '_' . ($wo->quantity ?? '') }}
                                                    </td>
                                                    <td>{{ $wo->part_description }}</td>
                                                    <td>{{ $wo->dimeter }}</td>
                                                    <td>{{ $wo->length }}</td>
                                                    <td>{{ $wo->width }}</td>
                                                    <td>{{ $wo->height }}</td>
                                                    <td>{{ $wo->exp_time }}</td>
                                                    <td>{{ $wo->quantity }}</td>
                                                    <td>
                                                        @if(hasPermission('WorkOrders', 'edit'))
                                                        <a href="{{ route('editWorkOrder', base64_encode($wo->id)) }}">
                                                            <button type="button" class="btn btn-success btn-sm btn-icon">
                                                                <i class="ri-pencil-fill"></i>
                                                            </button>
                                                        </a>
                                                        @endif

                                                        @if(hasPermission('WorkOrders', 'delete'))
                                                        <a href="{{ route('deleteWorkOrder', base64_encode($wo->id)) }}"
                                                            onclick="return confirm('Are you sure you want to delete this record?')">
                                                            <button type="button" class="btn btn-danger btn-sm btn-icon">
                                                                <i class="ri-delete-bin-fill"></i>
                                                            </button>
                                                        </a>
                                                        @endif

                                                        @if(hasPermission('MachineRecord', 'add'))
                                                        <a href="{{ route('AddMachinerecord', base64_encode($wo->id)) }}">
                                                            <button type="button" class="btn btn-info btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-add-circle-line">M</i>
                                                            </button>
                                                        </a>
                                                        @endif

                                                        @if(hasPermission('SetupSheet', 'add'))
                                                        <a href="{{ route('AddSetupSheet', base64_encode($wo->id)) }}">
                                                            <button type="button" class="btn btn-primary btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-add-circle-line">S</i>
                                                            </button>
                                                        </a>
                                                        @endif
                                                        <!-- View Button to open Modal -->
                                                        <!-- @if(hasPermission('WorkOrders', 'view'))
                                                        <button type="button"
                                                            class="btn btn-primary btn-icon viewWorkOrder"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewWorkOrderModal"
                                                            data-wo='@json($wo)'>
                                                            <i class="ri-eye-fill"></i>
                                                        </button>
                                                        @endif -->
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    let isEditPage = {
        {
            isset($workorder) ? 'true' : 'false'
        }
    };
</script>
<script>
    let isEditingRow = false;
    let rowCount = 0;

    function clearErrors() {
        document.querySelectorAll(".text-red").forEach(el => {
            if (!el.classList.contains("error")) el.textContent = "";
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        let customerDropdown = document.getElementById("customer_id");

        if (!customerDropdown.hasAttribute("disabled")) {
            if (!customerDropdown.value) {
                customerDropdown.selectedIndex = 0;
            }
        }
    });

    function validateFields() {
        clearErrors();
        let hasError = false;

        let customerVal = document.querySelector("#customer_id").value;
        let part = document.getElementById("part").value;
        let material = document.getElementById("material").value;
        let project_id = document.getElementById("project_id").value;
        let date = document.getElementById("date").value;
        let exp_time = document.getElementById("exp_time").value;
        let quantity = document.getElementById("quantity").value;
        let description = document.getElementById("part_description").value;

        if (!customerVal) {
            $(".customer").text("The customer field is required");
            hasError = true;
        }
        if (!part) {
            $(".part").text("The Part field is required");
            hasError = true;
        }
        if (!project_id) {
            $(".project_id").text("The Project name field is required");
            hasError = true;
        }
        if (!date) {
            $(".date").text("The Date field is required");
            hasError = true;
        }
        if (!exp_time) {
            $(".exp_time").text("The Exp time field is required");
            hasError = true;
        }
        if (!quantity) {
            $(".quantity").text("The Quantity field is required");
            hasError = true;
        }
        if (!material) {
            $(".material").text("The Material field is required");
            hasError = true;
        }

        return !hasError;
    }

    function attachValidationEvents() {
        document.querySelectorAll("#workOrderForm input, #workOrderForm textarea, #workOrderForm select").forEach(el => {
            el.addEventListener("input", function() {
                let errorClass = "." + this.id;
                $(errorClass).text("");
            });
            el.addEventListener("change", function() {
                let errorClass = "." + this.id;
                $(errorClass).text("");
            });
        });
    }

    function addRow() {
        if (!validateFields()) return false;

        let customer = document.querySelector("#customer_id option:checked").text;
        let customerVal = document.querySelector("#customer_id").value;
        let part = document.getElementById("part").value;

        let material = document.getElementById("material").value;
        let material_name = document.querySelector("#material option:checked").text;

        let project_id = document.getElementById("project_id").value;
        let project_name = document.querySelector("#project_id option:checked").text;
        let date = document.getElementById("date").value;
        let dimeter = document.getElementById("dimeter").value;
        let length = document.getElementById("length").value;
        let width = document.getElementById("width").value;
        let height = document.getElementById("height").value;
        let exp_time = document.getElementById("exp_time").value;
        let quantity = document.getElementById("quantity").value;

        let description = document.getElementById("part_description").value;

        rowCount++;
        let tableBody = document.querySelector("#workOrderTable tbody");

        let newRow = document.createElement("tr");
        newRow.innerHTML = `
                                                <td>${rowCount}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][customer_id]" value="${customerVal}">${customer}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][part]" value="${part}">${part}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][material]" value="${material}">${material_name}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][project_id]" value="${project_id}">${project_name}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][date]" value="${date}">${date}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][dimeter]" value="${dimeter}">${dimeter}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][length]" value="${length}">${length}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][width]" value="${width}">${width}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][height]" value="${height}">${height}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][exp_time]" value="${exp_time}">${exp_time}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][quantity]" value="${quantity}">${quantity}</td>
                                                <td><input type="hidden" name="rows[${rowCount}][part_description]" value="${description}">${description}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm editRow">✏</button>
                                                    <button type="button" class="btn btn-danger btn-sm deleteRow">🗑</button>
                                                </td>
                                            `;
        tableBody.appendChild(newRow);
        clearDimensions();

        newRow.querySelector(".deleteRow").addEventListener("click", function() {
            newRow.remove();
            updateSrNo();
        });

        newRow.querySelector(".editRow").addEventListener("click", function() {
            isEditingRow = true;

            let row = this.closest('tr');

            let customerVal = row.querySelector('input[name*="[customer_id]"]').value;
            let projectId = row.querySelector('input[name*="[project_id]"]').value;
            let part = row.querySelector('input[name*="[part]"]').value;
            let material = row.querySelector('input[name*="[material]"]').value;
            let date = row.querySelector('input[name*="[date]"]').value;
            let dimeter = row.querySelector('input[name*="[dimeter]"]').value;
            let length = row.querySelector('input[name*="[length]"]').value;
            let width = row.querySelector('input[name*="[width]"]').value;
            let height = row.querySelector('input[name*="[height]"]').value;
            let exp_time = row.querySelector('input[name*="[exp_time]"]').value;
            let quantity = row.querySelector('input[name*="[quantity]"]').value;
            let description = row.querySelector('input[name*="[part_description]"]').value;

            $('#customer_id').val(customerVal).trigger('change');

            let interval = setInterval(function() {
                if ($('#project_id option').length > 1) {
                    $('#project_id').val(projectId).trigger('change');
                    clearInterval(interval);
                }
            }, 100);

            $('#part').val(part);
            $('#material').val(material).trigger('change');
            $('#date').val(date);
            $('#dimeter').val(dimeter);
            $('#length').val(length);
            $('#width').val(width);
            $('#height').val(height);
            $('#exp_time').val(exp_time);
            $('#quantity').val(quantity);
            $('#part_description').val(description);

            row.remove();
            updateSrNo();
        });

        document.querySelectorAll("input, textarea").forEach(el => {
            if (el.type !== "hidden" && el.id !== "customer_id") el.value = "";
        });
        // $('#customer_id').val('').trigger('change');
        if (!isEditingRow) {
            $('#customer_id').val('').trigger('change');
        }
        $('#material').val('').trigger('change');
        document.getElementById("workOrderTableWrapper").style.display = "block";
        document.getElementById("submitBtn").style.display = "inline-block";
        return true;

        isEditingRow = false;
    }

    function updateSrNo() {
        document.querySelectorAll("#workOrderTable tbody tr").forEach((tr, index) => {
            tr.querySelector("td:first-child").textContent = index + 1;
        });
        rowCount = document.querySelectorAll("#workOrderTable tbody tr").length;
    }

    document.getElementById("addFirstRowBtn")?.addEventListener("click", addRow);

    document.getElementById("workOrderForm").addEventListener("submit", function(e) {
        if (rowCount === 0) {
            if (!validateFields()) {
                e.preventDefault();
                alert("Please fill required fields and add at least one row.");
                return false;
            }
        }
    });

    attachValidationEvents();
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let diameter = document.getElementById("dimeter");
        let height = document.getElementById("height");
        let length = document.getElementById("length");
        let width = document.getElementById("width");

        function toggleFields() {
            if (diameter.value) {
                length.disabled = true;
                width.disabled = true;
                length.value = "";
                width.value = "";

                diameter.disabled = false;
                height.disabled = false;
            } else if (length.value || width.value) {
                diameter.disabled = true;
                diameter.value = "";

                length.disabled = false;
                width.disabled = false;
                height.disabled = false;
            } else {
                diameter.disabled = false;
                length.disabled = false;
                width.disabled = false;
                height.disabled = false;
            }
        }

        window.clearDimensions = function() {
            diameter.value = "";
            length.value = "";
            width.value = "";
            height.value = "";
            toggleFields();
        }

        diameter.addEventListener("input", toggleFields);
        length.addEventListener("input", toggleFields);
        width.addEventListener("input", toggleFields);

        toggleFields();
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        let isEditPage = @json(isset($workorder));


        // PAGE LOAD PROJECT AUTO LOAD
        let customerId = $('#customer_id').val();
        let projectId = $('#project_id').val();

        if (customerId) {
            loadProjects(customerId, projectId);
        }

        // CUSTOMER CHANGE
        $('#customer_id').on('change', function() {
            let customerId = $(this).val();
            loadProjects(customerId);
        });

        // PROJECT CHANGE
        $('#project_id').on('change', function() {

            let selectedOption = $(this).find(':selected');
            let projectId = selectedOption.val();
            let customerId = $('#customer_id').val();

            let qty = selectedOption.data('quantity');
            let selectedText = selectedOption.text();

            loadNextPart(customerId, projectId);
            loadParts(projectId);

            // description
            if (selectedText && selectedText !== "Select Project") {
                $('#part_description').val(selectedText).prop('readonly', true);
            } else {
                $('#part_description').val('').prop('readonly', false);
            }

            // quantity auto only on insert
            if (!isEditPage) {
                if ($('#workOrderTableWrapper').is(':hidden')) {
                    $('#quantity').val(qty || '');
                }
            }

        });

        // LOAD PROJECTS
        function loadProjects(customerId, selectedProjectId = null) {

            if (!customerId) {
                $('#project_id').html('<option value="">Select Project</option>');
                return;
            }

            $.ajax({
                url: '/get-projects/' + customerId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {

                    let projectDropdown = $('#project_id');
                    projectDropdown.empty();
                    projectDropdown.append('<option value="">Select Project</option>');

                    if (data.length > 0) {

                        $.each(data, function(index, project) {

                            let selected = '';

                            if (selectedProjectId && selectedProjectId == project.id) {
                                selected = 'selected';
                            }

                            let projectNo = project.project_no ?? '';
                            let projectName = project.project_name ?? '';

                            projectDropdown.append(
                                `<option value="${project.id}" 
                                                                    data-quantity="${project.quantity || ''}" 
                                                                    ${selected}>
                                                                    ${projectNo} ${projectName}
                                                                </option>`
                            );

                        });

                    } else {

                        projectDropdown.append('<option value="">No Project Found</option>');

                    }

                }
            });

        }

        // LOAD PREVIOUS PARTS
        function loadParts(projectId) {

            if (!projectId) {
                $('#previous_part').html('<option value="">No Previous Part</option>');
                return;
            }

            $.ajax({
                url: '/get-parts/' + projectId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {

                    $('#previous_part').empty();

                    if (data.length > 0) {

                        $.each(data, function(index, part) {
                            $('#previous_part').append(`<option value="${part}">${part}</option>`);
                        });

                    } else {

                        $('#previous_part').append('<option value="">No Previous Part</option>');

                    }

                }
            });

        }

        // NEXT PART
        function loadNextPart(customerId, projectId) {

            if (!customerId || !projectId) return;

            $.ajax({
                url: '/get-next-part/' + customerId + '/' + projectId,
                type: 'GET',
                success: function(data) {
                    $('#part').val(data.next_part);
                }
            });

        }

        // TEXTAREA EDIT ENABLE
        $('#part_description').on('dblclick', function() {
            $(this).prop('readonly', false);
        });


        // EDIT PAGE DATA LOAD
        @if(isset($workorder))

        loadProjects('{{ $workorder->customer_id }}', '{{ $workorder->project_id }}');
        loadParts('{{ $workorder->project_id }}');

        setTimeout(function() {

            let qty = $("#project_id option:selected").data('quantity');

            if (!isEditPage && qty) {
                $('#quantity').val(qty);
            }

        }, 600);

        @endif

    });
</script>


@endsection