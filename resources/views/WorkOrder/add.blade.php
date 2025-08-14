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
                                                <label for="exp_time" class="form-label">
                                                    Expected Time (Hr / Min) <span class="mandatory">*</span>
                                                </label>
                                                <input type="text" step="1" class="form-control" id="exp_time" name="exp_time"
                                                    value="{{ old('exp_time', isset($workorder->exp_time) ? \Carbon\Carbon::parse($workorder->exp_time)->format('H:i:s') : \Carbon\Carbon::now()->format('H:i:s')) }}">
                                                @error('exp_time')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                 <span class="text-danger exp_time"></span>
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
                                            <button type="button" class="btn btn-success" id="addFirstRowBtn">
                                                <i class="bi bi-plus-lg"></i> Add
                                            </button>
                                        </div>

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
                                            <div class="text-end mt-3">
                                              
                                               <button type="submit" class="btn btn-primary">
                                                    {{ isset($project) ? 'Update' : 'Submit' }}
                                                </button>
                                            </div>
                                        </div>

                                        

                                        <script>
                                            let rowCount = 0;

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

                                               
                                                if (!customerVal)
                                                {
                                                    $(".customer").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!part)
                                                {
                                                    $(".part").text("Please fill required fields");
                                                    return false;
                                                } if (!date)
                                                {
                                                    $(".date").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!dimeter)
                                                {
                                                    $(".dimeter").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!length)
                                                {
                                                    $(".length").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!width)
                                                {
                                                    $(".width").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!height)
                                                {
                                                    $(".height").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!exp_time)
                                                {
                                                    $(".exp_time").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!quantity)
                                                {
                                                    $(".quantity").text("Please fill required fields");
                                                    return false;
                                                }
                                                 if (!description ) {
                                                    
                                                   $(".part_description_error").text("Please fill required fields");
                                                    return false;
                                                }

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
                                                <td><input type="hidden" name="rows[${rowCount}][description]" value="${description}">${description}</td>
                                                <td><button type="button" class="btn btn-danger btn-sm deleteRow">🗑</button></td>
                                            `;

                                                tableBody.appendChild(newRow);

                                                // Delete Row
                                                newRow.querySelector(".deleteRow").addEventListener("click", function() {
                                                    newRow.remove();
                                                    if (tableBody.rows.length === 0) {
                                                        document.getElementById("workOrderTableWrapper").style.display = "none";
                                                        document.getElementById("addMoreBtn").style.display = "none";
                                                        document.getElementById("submitBtn").style.display = "none";
                                                        rowCount = 0;
                                                    }
                                                });

                                                // Clear form except customer dropdown
                                                document.querySelectorAll("input, textarea").forEach(el => {
                                                    if (el.type !== "hidden" && el.id !== "customer_id") el.value = "";
                                                });

                                                return true;
                                            }

                                            // First Add Button
                                            document.getElementById("addFirstRowBtn").addEventListener("click", function() {
                                                if (addRow()) {
                                                    document.getElementById("workOrderTableWrapper").style.display = "block";
                                                    document.getElementById("addMoreBtn").style.display = "inline-block";
                                                    document.getElementById("submitBtn").style.display = "inline-block";
                                                    document.getElementById("addFirstRowBtn").style.display = "none";
                                                }
                                            });

                                            // Add More Button
                                            document.getElementById("addMoreBtn").addEventListener("click", function() {
                                                addRow();
                                            });
                                        </script>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>



                @endsection