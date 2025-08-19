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
                                <form action="{{ isset($workorder) ? route('updateWorkEntry', base64_encode($workorder->id)) : route('storeWorkEntry') }}" method="POST">

                                    @csrf
                                    @if(isset($workorder)) @method('PUT') @endif

                                    <div class="row">
                                        <!-- <div class="col-md-4" disabled>
                                            <div class="mb-3">
                                                <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="work_order_no" name="work_order_no" placeholder="Work Order No" value="{{ old('work_order_no', $workorder->work_order_no ?? '') }}">
                                                @error('work_order_no') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                                <select class="form-select" @disabled(isset($workorder)) id="customer_id" name="customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ old('customer_id', isset($workorder) ? $workorder->customer_id : '') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }} - ({{ $c->code }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('customer_id')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                                <span class="text-danger customer"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="part" class="form-label">Part <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="part" name="part" placeholder="Enter Part" value="{{ old('part', $workorder->part ?? '') }}">
                                                @error('part') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger part"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $workorder->date ?? '') }}">
                                                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger date"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="dimeter" class="form-label">dimeter <span class="mandatory">*</span></label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="dimeter" name="dimeter" placeholder="dimeter" value="{{ old('dimeter', $workorder->dimeter ?? '') }}">
                                                @error('dimeter') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger dimeter"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="length" class="form-label">Length <span class="mandatory">*</span></label>
                                                <input type="number" class="form-control" id="length" name="length" placeholder="Length" step="0.01" min="0" value="{{ old('length', $workorder->length ?? '') }}">
                                                @error('length') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger length"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="width" class="form-label">Width <span class="mandatory">*</span></label>
                                                <input type="number" step="0.01" min="0" class="form-control" id="width" name="width" placeholder="Width" value="{{ old('width', $workorder->width ?? '') }}">
                                                @error('width') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger width"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="height" step="0.01" min="0" class="form-label">Height <span class="mandatory">*</span></label>
                                                <input type="number" class="form-control" id="height" name="height" placeholder="Height" value="{{ old('height', $workorder->height ?? '') }}">
                                                @error('height') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger height"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="exp_time" class="form-label">Exp Time (HH:MM)</label>
                                                <input type="text" name="exp_time" id="exp_time"
                                                    value="{{ isset($workorder) ? \Carbon\Carbon::createFromFormat('H:i:s', $workorder->exp_time)->format('H:i') : old('exp_time') }}"
                                                    class="form-control" placeholder="HH:MM">

                                                @error('exp_time')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="text-danger part_description_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" value="{{ old('quantity', $workorder->quantity ?? '') }}">
                                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                                <span class="text-danger quantity"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">
                                                    Part Description <span class="mandatory">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="part_description" name="part_description"
                                                    placeholder="Description"
                                                    value="{{ old('part_description', $workorder->part_description ?? '') }}">

                                                @error('part_description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <span class="text-danger part_description_error"></span>
                                            </div>
                                        </div>



                                        <!-- Buttons (Above Form) -->
                                        <div class="text-end mt-3" id="topButtons">
                                            @if(isset($workorder))
                                            <!-- Edit Mode -> Show Update Button -->
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                            @else
                                            <!-- Add Mode -> Show Add Row Button -->
                                            <button type="button" class="btn btn-success" id="addFirstRowBtn">
                                                <i class="bi bi-plus-lg"></i> Add
                                            </button>
                                            @endif
                                        </div>


                                        <!-- edit table ------------------->
                                        <?php

                                        if (!empty($id)) { ?>
                                            <hr>
                                            <br>
                                            <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>SrNo.</th>
                                                        <!-- <th>Work Order No</th> -->
                                                        <!-- <th>Entry Code</th> -->
                                                        <th>Part</th>
                                                        <th>Date</th>
                                                        <th>Part Code</th>
                                                        <th>Quantity</th>
                                                        <th>Part Description</th>
                                                        <th width="12%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($workorders as $wo)

                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <!-- <td>{{ $wo->customer_id  }}</td> -->
                                                        <!-- <td>{{ $wo->customer?->code }}</td> -->
                                                        <td>{{ $wo->part }}</td>
                                                        <td>{{ $wo->date }}</td>
                                                        <td>{{ ($wo->customer?->code ?? '') . '_' . ($wo->customer_id ?? '') . '_' . ($wo->part ?? '') }}</td>


                                                        <td>{{ $wo->quantity }}</td>
                                                        <td>{{ $wo->part_description }}</td>
                                                        <td>
                                                            <a href="{{ route('editWorkOrder', base64_encode($wo->id)) }}">
                                                                <button type="button" class="btn btn-success btn-icon">
                                                                    <i class="ri-pencil-fill"></i>
                                                                </button>
                                                            </a>
                                                            <!-- #region --> <a href="{{route('deleteWorkOrder', base64_encode($wo->id)) }}">
                                                                <button type="button" class="btn btn-danger btn-icon">
                                                                    <i class="ri-delete-bin-fill"></i>
                                                                </button>
                                                            </a>
                                                            <!-- View Button to open Modal -->

                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        <?php } ?>

                                        <!-- --------edit table end--------- -->

                                        <!-- Table (Initially Hidden) -->
                                        <div class="table-responsive mt-4" id="workOrderTableWrapper" style="display: none;">
                                            <table class="table table-bordered" id="workOrderTable">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Customer</th>
                                                        <th>Part</th>
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

                                            <!-- Buttons Below Table -->
                                            <!-- Buttons Below Table -->
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                                    {{ isset($project) ? 'Update' : 'Submit' }}
                                                </button>
                                            </div>

                                        </div>
                                      <script>
                    let rowCount = 0;

                    function clearErrors() {
                        document.querySelectorAll(".error").forEach(el => el.textContent = "");
                    }

                    function addRow() {
                        let customer = document.querySelector("#customer_id option:checked").text;
                        let customerVal = document.querySelector("#customer_id").value;
                        let part = document.getElementById("part").value;
                        let date = document.getElementById("date").value;
                        let dimeter = document.getElementById("dimeter").value;
                        let length = document.getElementById("length").value;
                        let width = document.getElementById("width").value;
                        let height = document.getElementById("height").value;
                        let exp_time = document.getElementById("exp_time").value;
                        let quantity = document.getElementById("quantity").value;
                        let description = document.getElementById("part_description").value;

                        let hasError = false;
                        clearErrors();

                        if (!customerVal) {
                            $(".customer").text("Please fill required field");
                            hasError = true;
                        }
                        if (!part) {
                            $(".part").text("Please fill required field");
                            hasError = true;
                        }
                        if (!date) {
                            $(".date").text("Please fill required field");
                            hasError = true;
                        }
                        if (!dimeter) {
                            $(".dimeter").text("Please fill required field");
                            hasError = true;
                        }
                        if (!length) {
                            $(".length").text("Please fill required field");
                            hasError = true;
                        }
                        if (!width) {
                            $(".width").text("Please fill required field");
                            hasError = true;
                        }
                        if (!height) {
                            $(".height").text("Please fill required field");
                            hasError = true;
                        }
                        if (!exp_time) {
                            $(".exp_time").text("Please fill required field");
                            hasError = true;
                        }
                        if (!quantity) {
                            $(".quantity").text("Please fill required field");
                            hasError = true;
                        }
                        if (!description) {
                            $(".part_description_error").text("Please fill required field");
                            hasError = true;
                        }

                        if (hasError) return false;

                        rowCount++;
                        let tableBody = document.querySelector("#workOrderTable tbody");

                        let newRow = document.createElement("tr");
                        newRow.innerHTML = `
        
                      <td>${rowCount}</td>
                      <td><input type="hidden" name="rows[${rowCount}][customer_id]" value="${customerVal}">${customer}</td>
                      <td><input type="hidden" name="rows[${rowCount}][part]" value="${part}">${part}</td>
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

                        // Delete Row
                        newRow.querySelector(".deleteRow").addEventListener("click", function() {
                            newRow.remove();
                            updateSrNo();
                        });

                        // Edit Row
                        newRow.querySelector(".editRow").addEventListener("click", function() {
                            document.getElementById("customer_id").value = customerVal;
                            document.getElementById("part").value = part;
                            document.getElementById("date").value = date;
                            document.getElementById("dimeter").value = dimeter;
                            document.getElementById("length").value = length;
                            document.getElementById("width").value = width;
                            document.getElementById("height").value = height;
                            document.getElementById("exp_time").value = exp_time;
                            document.getElementById("quantity").value = quantity;
                            document.getElementById("part_description").value = description;

                            newRow.remove();
                            updateSrNo();
                        });

                        // Clear form
                        document.querySelectorAll("input, textarea").forEach(el => {
                            if (el.type !== "hidden" && el.id !== "customer_id") el.value = "";
                        });

                        document.getElementById("workOrderTableWrapper").style.display = "block";
                        document.getElementById("submitBtn").style.display = "inline-block";

                        return true;
                    }

                    // Update Sr No
                    function updateSrNo() {
                        document.querySelectorAll("#workOrderTable tbody tr").forEach((tr, index) => {
                            tr.querySelector("td:first-child").textContent = index + 1;
                        });
                        rowCount = document.querySelectorAll("#workOrderTable tbody tr").length;
                    }

                    // Add button
                document.getElementById("addFirstRowBtn")?.addEventListener("click", addRow);
                </script>

                @endsection