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
                            <a href="{{ route('ViewProject') }}" class="btn btn-sm btn-outline-success me-2">
                                ← Back
                            </a>
   
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($project) ? 'Edit Project' : 'Add Project' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
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
            </div>
        </div>
    </div>
</div>

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