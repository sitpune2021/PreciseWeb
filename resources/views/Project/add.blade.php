@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card shadow-sm">
                        <div class="card-header  d-flex align-items-center">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($project) ? 'Edit Project' : 'Add Project' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ isset($project) ? route('updateProject', base64_encode($project->id)) : route('storeProject') }}" method="POST">
                                @csrf
                                @if(isset($project))
                                    @method('PUT')
                                @endif

                                <div class="row g-3">
                                    <!-- Customer Dropdown -->
                                    <div class="col-md-4">
                                        <label for="customer_id" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <select class="form-select  " id="customer_id" name="customer_id">
                                            <option value="">Select Customer</option>
                                            @foreach($codes as $c)
                                                <option value="{{ $c->id }}" data-code="{{ $c->code }}"
                                                    {{ old('customer_id', $project->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->name }} - ({{ $c->code }})
                                                </option>
                                                @error('customer_id')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Customer Code -->
                                    <div class="col-md-4">
                                        <label for="code" class="form-label">Customer Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code', $customer->code ?? '') }}" readonly>
                                    </div>

                                    <!-- Project Name -->
                                    <div class="col-md-4">
                                        <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="project_name" name="project_name"
                                            placeholder="Enter Project Name"
                                            value="{{ old('project_name', $project->project_name ?? '') }}">
                                            @error('project_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Project Code -->
                                    <div class="col-md-4">
                                        <label for="project_code" class="form-label">Project Code</label>
                                        <input type="text" class="form-control" id="project_code" name="project_code"
                                            placeholder="Enter Project Code"
                                            value="{{ old('project_code', $project->project_code ?? '') }}">
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-4">
                                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                            placeholder="Enter Quantity"
                                            value="{{ old('quantity', $project->quantity ?? '') }}">
                                        @error('quantity')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Date -->
                                    <div class="col-md-4">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="{{ old('date', isset($project->date) ? \Carbon\Carbon::parse($project->date)->format('Y-m-d') : '') }}">
                                        @error('date')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit Buttons -->
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
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer_id');
        const codeInput = document.getElementById('code');
        const projectName = document.getElementById('project_name');
        const projectCode = document.getElementById('project_code');

        // Auto-fill Customer Code
        customerSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption.getAttribute('data-code') || '';
            codeInput.value = code;
        });

        // Trigger on page load if customer already selected (edit mode)
        if (customerSelect.value) {
            customerSelect.dispatchEvent(new Event('change'));
        }

        // Auto-generate Project Code from Project Name (acronym)
        projectName.addEventListener('input', function() {
            const name = this.value.trim();
            if (name) {
                // Take the first letter of each word
                const words = name.split(/\s+/);
                const code = words.map(w => w[0].toUpperCase()).join('');
                projectCode.value = code;
            } else {
                projectCode.value = '';
            }
        });

        // Trigger on page load if project name exists (edit mode)
        if (projectName.value) {
            projectName.dispatchEvent(new Event('input'));
        }
    });
</script>


@endsection