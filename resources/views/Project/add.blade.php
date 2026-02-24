@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex align-items-center">

                            <!-- Back Button ONLY on Edit -->
                            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-success me-2">
                                ←
                            </a>

                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($project) ? 'Edit Project' : 'Add Project' }}
                            </h4>
                        </div>

                        <div class="card-body">

                            <form action="{{ isset($project) ? route('updateProject', base64_encode($project->id)) : route('storeProject') }}" method="POST">
                                @csrf
                                @if(isset($project))
                                @method('PUT')

                                <input type="hidden" name="customer_id" value="{{ $project->customer_id }}">
                                @endif

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="customer_id" class="form-label">Customer Code <span class="text-red">*</span></label>
                                        <select class="form-select js-example-basic-single" id="customer_id" name="customer_id"
                                            {{ isset($project) ? 'disabled' : '' }}>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $c)
                                            <option value="{{ $c->id }}" data-code="{{ $c->code }}"
                                                {{ old('customer_id', $project->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                {{ $c->code }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="code" class="form-label">Customer Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', $project->customer_code ?? '') }}" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="project_name" class="form-label">Project Name <span class="text-red">*</span></label>
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                            placeholder="Enter Project Name"
                                            value="{{ old('project_name', $project->project_name ?? '') }}">
                                        @error('project_name')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="quantity" class="form-label">Quantity <span class="text-red">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                            placeholder="Enter Quantity"
                                            value="{{ old('quantity', $project->quantity ?? '') }}">
                                        @error('quantity')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ old('date', isset($project->date) ? \Carbon\Carbon::parse($project->date)->format('Y-m-d') : '') }}">
                                        @error('date')
                                        <span class="text-red small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 text-end mt-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            {{ isset($project) ? 'Update' : 'Submit' }}
                                        </button>
                                        @if(isset($project))
                                        <a href="{{ route('ViewProject') }}" class="btn btn-secondary px-4">Cancel</a>
                                        @else
                                        <button type="reset" class="btn btn-info px-4">Reset</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                            <div class="card">

                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">View Projects</h5>

                                    <!-- <div class="d-flex align-items-center gap-2">
                                        @if(hasPermission('Projects', 'add'))
                                        <a href="{{ route('AddProject') }}" class="btn btn-success btn-sm">
                                            <i class="ri-add-line align-middle"></i> Add Project
                                        </a>
                                        @endif
                                    </div> -->
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="table-light">
                                                    <th style="width:40px;">Sr.<br>No</th>
                                                    <th style="width:85px;">Created <br>Date</th>
                                                    <th style="width:30px;">Project<br>No.</th>
                                                    <th style="width:30px;">Customer<br>Code</th>
                                                    <th>Project Name</th>
                                                    <th style="width:50px;">Qty</th>
                                                    @if(
                                                    hasPermission('Projects', 'edit') ||
                                                    hasPermission('Projects', 'delete') ||
                                                    hasPermission('Projects', 'view')
                                                    )
                                                    <th width="12%">Action</th>
                                                    @endif

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($projects as $project)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($project->date)->format('d-m-Y') }}</td>
                                                    <td>{{ $project->project_no }}</td>
                                                    <td>{{ $project->customer_code }}</td>
                                                    <td>{{ $project->project_name }}</td>
                                                    <td>{{ $project->quantity }}</td>

                                                    <td>
                                                        @if(hasPermission('Projects', 'edit'))
                                                        <a href="{{ route('editProject', base64_encode($project->id)) }}">
                                                            <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                                <i class="ri-pencil-fill align-bottom"></i>
                                                            </button>
                                                        </a>
                                                        @endif
                                                        <!-- @if(hasPermission('Projects', 'view'))
                                                        <button type="button"
                                                            class="btn btn-primary btn-icon waves-effect waves-light viewBtn"
                                                            data-id="{{ $project->id }}"
                                                            data-workorder="{{ $project->project_no }}"
                                                            data-name="{{ $project->project_name }}"
                                                            data-code="{{ $project->customer_code }}"
                                                            data-qty="{{ $project->quantity }}"
                                                            data-date="{{ \Carbon\Carbon::parse($project->date)->format('d-m-Y') }}">
                                                            <i class="ri-eye-fill align-bottom"></i>
                                                        </button>
                                                        @endif -->

                                                        @if(hasPermission('Projects', 'delete'))
                                                        <a href="{{ route('deleteProject', base64_encode($project->id)) }}"
                                                            onclick="return confirm('Are you sure you want to delete this record?')">
                                                            <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                                            </button>
                                                        </a>
                                                        @endif

                                                        <a href="{{ route('AddWorkOrder', base64_encode($project->id)) }}">
                                                            <button type="button" class="btn btn-info btn-icon waves-effect waves-light">
                                                                <i class="ri-add-circle-line me-1"></i></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Project Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Date</th>
                                            <td id="view_date"></td>
                                        </tr>

                                        <tr>
                                            <th>Project No.</th>
                                            <td id="view_workorder"></td>
                                        </tr>

                                        <tr>
                                            <th>Customer Code</th>
                                            <td id="view_code"></td>
                                        </tr>

                                        <tr>
                                            <th>Project Name</th>
                                            <td id="view_name"></td>
                                        </tr>

                                        <tr>
                                            <th>Quantity</th>
                                            <td id="view_qty"></td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".viewBtn").forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("view_workorder").textContent = this.dataset.workorder;
                document.getElementById("view_name").textContent = this.dataset.name;
                document.getElementById("view_code").textContent = this.dataset.code;
                document.getElementById("view_qty").textContent = this.dataset.qty;
                document.getElementById("view_date").textContent = this.dataset.date;

                let modal = new bootstrap.Modal(document.getElementById("viewProjectModal"));
                modal.show();
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const customerSelect = document.getElementById("customer_id");
        const codeInput = document.getElementById("code");
        const quantityInput = document.getElementById("quantity");
        const dateInput = document.getElementById("date");

        const isEditMode = {
            {
                isset($project) ? 'true' : 'false'
            }
        };

        customerSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption.dataset.code || "";
            codeInput.value = code;

            if (!isEditMode) {
                quantityInput.value = "";
                dateInput.value = "";
            }
        });
    });
</script>

@endsection