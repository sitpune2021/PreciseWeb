@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($project) ? 'Edit Project' : 'Add Project' }}
                            </h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ isset($project) ? route('updateProject', base64_encode($project->id)) : route('storeProject') }}" method="POST">
                                    @csrf
                                    @if(isset($project))
                                    @method('PUT')
                                    @endif

                                    <div class="row">
                                        <!-- Project Name -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Project Name <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Project Name" value="{{ old('name', $project->name ?? '') }}">
                                                @error('name') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                                <select class="form-select" @disabled(isset($project)) id="customer_id" name="customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ isset($project) && $c->id == $project->customer_id ? 'selected' : '' }}>
                                                        {{ $c->name }} - ({{ $c->code }})
                                                    </option>
                                                    @endforeach
                                                </select>

                                                @error('customer_id')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror


                                            </div>
                                        </div>

                                        <!-- Work Order No -->
                                        <!-- <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="work_order_no" class="form-label">Work Order No. <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="work_order_no" name="work_order_no" placeholder="Work Order Number" value="{{ old('work_order_no', $project->work_order_no ?? '') }}">
                                                @error('work_order_no') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div> -->



                                        <!-- Quantity -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="qty" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                <input type="number" step="any" class="form-control" id="qty" name="qty" placeholder="Quantity" value="{{ old('qty', $project->qty ?? '') }}">
                                                @error('qty') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>



                                        <!-- Start Date -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="StartDate" class="form-label">Start Date</label>
                                                <input type="date" class="form-control datepicker" id="StartDate" name="StartDate"
                                                    value="{{ old('StartDate', isset($project->startdate) ? \Carbon\Carbon::parse($project->startdate)->format('Y-m-d') : '') }}">
                                                @error('StartDate') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="EndDate" class="form-label">End Date</label>
                                                <input type="date" class="form-control datepicker" id="EndDate" name="EndDate" value="{{ old('enddate', isset($project->enddate) ? \Carbon\Carbon::parse($project->enddate)->format('Y-m-d') : '') }}">
                                                @error('EndDate') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Project Description -->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description <span class="mandatory">*</span></label>
                                                <input class="form-control" id="part_description" name="part_description" rows="5" placeholder="Description">{{ old('part_description', $workorder->part_description ?? '') }}
                                                @error('part_description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                      

                                        <!-- Submit Button -->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ isset($project) ? 'Update' : 'Submit' }}
                                                </button>
                                                &nbsp;
                                                @if(isset($project))
                                                <a href="{{ route('ViewProject') }}" class="btn btn-info">Cancel</a>
                                                @else
                                                <button type="reset" class="btn btn-info">Reset</button>
                                                @endif
                                            </div>
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
</div>

@endsection