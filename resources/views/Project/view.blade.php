@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
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

                            <div class="d-flex align-items-center gap-2">
                                @if(hasPermission('Projects', 'add'))
                                <a href="{{ route('AddProject') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Project
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr.<br>No</th>
                                            <th>Created <br>Date</th>
                                            <th>Project<br>No.</th>
                                            <th>Customer<br>Code</th>
                                            <th>Project Name</th>
                                            <th>Qty</th>                                   
                                            <th width="12%">Action</th>
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

                                                @if(hasPermission('Projects', 'view'))
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
                                                @endif

                                                @if(hasPermission('Projects', 'delete'))
                                                <a href="{{ route('deleteProject', base64_encode($project->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                                @endif
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

@endsection