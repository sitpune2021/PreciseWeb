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
                                            <th>Customer Name</th>
                                            <th>Project Code</th>
                                            <th>Work Order No.</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Quantity</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                      <tr>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->code ?? '' }}</td>
                                                <td>{{ $project->work_order_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($project->StartDate)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($project->EndDate)->format('d-m-Y') }}</td>
                                                <td>{{ $project->qty }}</td>
                                                <td>
                                                    <a href="{{ route('editProject', base64_encode($project->id)) }}">
                                                        <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                            <i class="ri-pencil-fill align-bottom"></i>
                                                        </button>
                                                    </a>
 
                                                    <button type="button" class="btn btn-primary btn-icon waves-effect waves-light">
                                                        <i class="ri-eye-fill align-bottom"></i>
                                                    </button>
 
                                                    <a href="#">
                                                        <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
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