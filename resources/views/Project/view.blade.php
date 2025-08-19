@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">View Projects</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                             <th>Work Order No</th>
                                            <th>Project Name</th>
                                            <th>Customer Name</th>
                                            <th>Customer code</th>
                                            <th>Quantity</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Description</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->customer_id }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->customer->name ?? '' }}</td> {{-- assuming relation --}}
                                            <td>{{ $project->customer?->code }}</td> 
                                            <td>{{ $project->qty }}</td>
                                            <td>{{ \Carbon\Carbon::parse($project->startdate)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($project->enddate)->format('d-m-Y') }}</td>
                                            <td>{{ $project->description }}</td>
                                            <td>
                                                <a href="{{ route('editProject', base64_encode($project->id)) }}">
                                                    <button type="button"
                                                        class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <button type="button"
                                                    class="btn btn-primary btn-icon waves-effect waves-light">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>

                                                <a href="{{ route('deleteProject', base64_encode($project->id)) }}">
                                                    <button type="button"
                                                        class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
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
            <!--end row-->
        </div>
    </div>
</div>

@endsection