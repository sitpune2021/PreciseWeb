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
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="work_order_no" name="work_order_no" placeholder="Work Order No" value="{{ old('work_order_no', $workorder->work_order_no ?? '') }}">
                                                @error('work_order_no') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                    



                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                                <select class="form-select" @disabled(isset($workorder)) id="customer_id" name="customer_id">
                                                    <option value="">Select Customer</option>
                                                  
                                                    @foreach($codes as $c)

                                                    <option value="{{ $c->id }}" {{ isset($workorder) && $c->id == $workorder->id ? 'selected' : '' }}>
                                                        {{ $c->name }} - ({{ $c->code }})
                                                    </option>

                                                    @endforeach

                                                   
                                                </select>

                                                @error('customer_id')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="part" class="form-label">Part <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="part" name="part" placeholder="Enter Part" value="{{ old('part', $workorder->part ?? '') }}">
                                                @error('part') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $workorder->date ?? '') }}">
                                                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                   

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="diameter" class="form-label">Diameter <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="diameter" name="diameter" placeholder="Diameter" value="{{ old('diameter', $workorder->diameter ?? '') }}">
                                                @error('diameter') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="length" class="form-label">Length <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="length" name="length" placeholder="Length" value="{{ old('length', $workorder->length ?? '') }}">
                                                @error('length') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="width" class="form-label">Width <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="width" name="width" placeholder="Width" value="{{ old('width', $workorder->width ?? '') }}">
                                                @error('width') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="height" class="form-label">Height <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="height" name="height" placeholder="Height" value="{{ old('height', $workorder->height ?? '') }}">
                                                @error('height') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="exp_time" class="form-label">Expected Time <span class="mandatory">*</span></label>
                                                <input type="time" class="form-control" id="exp_time" name="exp_time" value="{{ old('exp_time', $workorder->exp_time ?? '') }}">
                                                @error('exp_time') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" value="{{ old('quantity', $workorder->quantity ?? '') }}">
                                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="part_description" name="part_description" placeholder="Description" value="{{ old('part_description', $workorder->part_description ?? '') }}">
                                                @error('part_description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">{{ isset($workorder) ? 'Update' : 'Submit' }}</button>
                                                &nbsp;
                                                @if(isset($workorder))
                                                <a href="{{ route('ViewWorkOrder') }}" class="btn btn-info">Cancel</a>
                                                @else
                                                <button type="reset" class="btn btn-info">Reset</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div><!-- end row -->
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- end row -->

        </div>
    </div>
</div>

@endsection