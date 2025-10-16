@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1"> {{ isset($workorder) ? 'Edit WorkOrder' : 'Add Work Order' }}</h4>
                        </div>

                        <div class="card-body">
                            <div class="live-preview">
                                <form id="workOrderForm" action="{{ isset($workorder) ? route('updateWorkEntry', base64_encode($workorder->id)) : route('storeWorkEntry') }}" method="POST">

                                    @csrf
                                    @if(isset($workorder)) @method('PUT') @endif

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer Code <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single"
                                                    id="customer_id"
                                                    name="customer_id"
                                                    {{ isset($workorder) ? 'disabled' : '' }}>
                                                    <option value="">Select Customer Code</option>
                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ old('customer_id', $workorder->customer_id ?? '') == $c->id ? 'selected' : '' }}>
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
                                                <span class="text-red customer"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="project_id" class="form-label">Project Name <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single" id="project_id" name="project_id">
                                                    <option value="">Select Project</option>
                                                    @foreach($projects as $p)
                                                    @if(isset($workorder) && $p->customer_id == $workorder->customer_id)
                                                    <option value="{{ $p->id }}"
                                                        {{ old('project_id', $workorder->project_id ?? '') == $p->id ? 'selected' : '' }}>
                                                        {{ $p->project_name }}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                @error('project_id') <span class="text-red">{{ $message }}</span> @enderror
                                                <span class="text-red project_id"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="previous_part" class="form-label">Previous Part List</label>
                                                <select class="form-control form-select" id="previous_part" name="previous_part">
                                                    <option value="">No Previous Part</option>
                                                </select>

                                                <span class="text-red previous_part"></span>
                                            </div>
                                        </div>



                                        <div class="col-md-2">
                                            <div class="mb-2">
                                                <label for="part" class="form-label">Part <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="part" name="part"
                                                    placeholder="Enter New Part" value="{{ old('part', $workorder->part ?? '') }}">
                                                @error('part')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                                <span class="text-red part"></span>
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

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="dimeter" class="form-label">Diameter</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
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
                                                    class="form-control"
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
                                                    class="form-control"
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
                                                    class="form-control"
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
                                                <span class="text-red exp_time"></span>
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
                                                <span class="text-red quantity"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="material" class="form-label">Material type <span class="mandatory">*</span></label>
                                            <select name="material" id="material" class="form-control form-select">
                                                <option value="">Select Material</option>
                                                @foreach($materialtype as $mat)
                                                <option value="{{ $mat->material_type }}"
                                                    {{ old('material', $workorder->material ?? '') == $mat->material_type ? 'selected' : '' }}>
                                                    {{ $mat->material_type }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('material')
                                            <span class="text-red small">{{ $message }}</span>
                                            @enderror
                                            <span class="text-red material"></span>
                                        </div>


                                        <!-- <div class="col-md-9">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">
                                                    Part Description <span class="mandatory">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="part_description" name="part_description"
                                                    placeholder="Description"
                                                    value="{{ old('part_description', $workorder->part_description ?? '') }}">
                                                @error('part_description')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                                <span class="text-red part_description_error"></span>
                                            </div>
                                        </div> -->
                                        <div class="col-md-9">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description</label>
                                                <input type="text" class="form-control" id="part_description" name="part_description"
                                                    placeholder="Description"
                                                    value="{{ old('part_description', $workorder->part_description ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- Buttons (Above Form) -->
                                        <div class="text-end mt-3" id="topButtons">
                                            @if(isset($workorder))
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            @else
                                            <button type="button" class="btn btn-success" id="addFirstRowBtn">
                                                <i class="bi bi-plus-lg"></i> Add
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive mt-4" id="workOrderTableWrapper" style="display: none;">
                                            <table class="table table-bordered" id="workOrderTable">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Customer Code</th>
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

                                        <script>
                                            let rowCount = 0;

                                            function clearErrors() {
                                                document.querySelectorAll(".text-red").forEach(el => {
                                                    if (!el.classList.contains("error")) el.textContent = "";
                                                });
                                            }
                                            // document.addEventListener("DOMContentLoaded", function() {
                                            //     document.getElementById("customer_id").selectedIndex = 0; // always selects "Select Customer"
                                            // });

                                            document.addEventListener("DOMContentLoaded", function() {
                                                let customerDropdown = document.getElementById("customer_id");
                                                if (!customerDropdown.hasAttribute("disabled")) {
                                                    customerDropdown.selectedIndex = 0;
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
                                                // if (!description) {
                                                //     $(".part_description_error").text("The Part description field is required");
                                                //     hasError = true;
                                                // }

                                                return !hasError;
                                            }

                                            // 🔹 Remove error when user types
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
                                                    let row = this.closest('tr');

                                                    // Get all values from row
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

                                                    // Set customer first
                                                    $('#customer_id').val(customerVal).trigger('change');

                                                    // Wait until AJAX finishes loading projects
                                                    let interval = setInterval(function() {
                                                        if ($('#project_id option').length > 1) {
                                                            $('#project_id').val(projectId).trigger('change');
                                                            clearInterval(interval);
                                                        }
                                                    }, 100);

                                                    // Set other fields
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

                                                    // Remove row from table
                                                    row.remove();
                                                    updateSrNo();
                                                });


                                                document.querySelectorAll("input, textarea").forEach(el => {
                                                    if (el.type !== "hidden" && el.id !== "customer_id") el.value = "";
                                                });
                                                $('#customer_id').val('').trigger('change');
                                                $('#material').val('').trigger('change');
                                                document.getElementById("workOrderTableWrapper").style.display = "block";
                                                document.getElementById("submitBtn").style.display = "inline-block";
                                                return true;
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

                                            // call on load
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

                                                toggleFields(); // initial check
                                            });
                                        </script>


                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            $(document).ready(function() {

                                                // Load projects based on customer
                                                function loadProjects(customerId, selectedProjectId = null) {
                                                    if (customerId) {
                                                        $.ajax({
                                                            url: '/get-projects/' + customerId,
                                                            type: 'GET',
                                                            dataType: 'json',
                                                            success: function(data) {
                                                                let projectDropdown = $('#project_id');
                                                                projectDropdown.html('<option value="">Select Project</option>');

                                                                if (data.length > 0) {
                                                                    $.each(data, function(index, project) {
                                                                        let selected = '';
                                                                        if (selectedProjectId && selectedProjectId == project.id) {
                                                                            selected = 'selected';
                                                                        }
                                                                        projectDropdown.append(
                                                                            '<option value="' + project.id + '" data-quantity="' + project.quantity + '" ' + selected + '>' +
                                                                            project.project_name + '</option>'
                                                                        );
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    } else {
                                                        $('#project_id').html('<option value="">Select Project</option>');
                                                        $('#previous_part').html('<option value="">No Previous Part</option>');
                                                    }
                                                }

                                                // Load parts based on project
                                                function loadParts(projectId) {
                                                    if (projectId) {
                                                        $.ajax({
                                                            url: '/get-parts/' + projectId,
                                                            type: 'GET',
                                                            dataType: 'json',
                                                            success: function(data) {
                                                                $('#previous_part').empty();

                                                                if (data.length > 0) {
                                                                    $.each(data, function(index, part) {
                                                                        $('#previous_part').append('<option value="' + part + '">' + part + '</option>');
                                                                    });
                                                                } else {
                                                                    $('#previous_part').append('<option value="">No Previous Part</option>');
                                                                }
                                                            }
                                                        });
                                                    } else {
                                                        $('#previous_part').empty().append('<option value="">No Previous Part</option>');
                                                    }
                                                }

                                                $('#customer_id').on('change', function() {
                                                    let customerId = $(this).val();
                                                    loadProjects(customerId);
                                                });

                                                // 🔹 Fixed project change handler
                                                $('#project_id').off('change').on('change', function() {
                                                    let selectedOption = $(this).find(':selected');
                                                    let projectId = selectedOption.val();
                                                    let qty = selectedOption.data('quantity');
                                                    let selectedText = selectedOption.text();

                                                    loadParts(projectId);

                                                    // Auto-fill part description
                                                    if (selectedText && selectedText !== "Select Project") {
                                                        $('#part_description').val(selectedText).prop('readonly', true);
                                                    } else {
                                                        $('#part_description').val('').prop('readonly', false);
                                                    }

                                                    // Only set quantity if adding new row, not editing existing row
                                                    if ($('#workOrderTableWrapper').is(':hidden')) {
                                                        $('#quantity').val(qty || '');
                                                    }
                                                });

                                                $('#part_description').on('dblclick', function() {
                                                    $(this).prop('readonly', false);
                                                });

                                                @if(isset($workorder))
                                                loadProjects('{{ $workorder->customer_id }}', '{{ $workorder->project_id }}');
                                                loadParts('{{ $workorder->project_id }}');

                                                setTimeout(function() {
                                                    let qty = $("#project_id option:selected").data('quantity');
                                                    if (qty) {
                                                        $('#quantity').val(qty);
                                                    }
                                                }, 800);
                                                @endif
                                            });
                                        </script>


                                        @endsection