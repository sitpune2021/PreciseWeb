@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!--  Operator Add Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Operator</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('storeOperator') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="operator_name" class="col-sm-2 col-form-label">Operator Name<span class="mandatory">*</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="operator_name" name="operator_name" value="{{ old('operator_name') }}" placeholder="Enter Operator Name" required>
                                        @error('operator_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary">Add Operator</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!--  Operator List Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Operators List</h5>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
</div> <!-- main-content -->

@endsection
