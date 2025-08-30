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
                                             <th>Sr.No</th>
                                             <th>Work Order.No</th>
                                            <th>Project Name</th>
                                            <th>Customer Name</th>
                                            <th>Customer code</th>
                                            <th>Quantity</th>
                                            <!-- <th>Start Date</th>
                                            <th>End Date</th> -->
                                            <th>Description</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $project->customer_id }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->customer->name ?? '' }}</td> {{-- assuming relation --}}
                                            <td>{{ $project->customer?->code }}</td> 
                                            <td>{{ $project->qty }}</td>
                                            <!-- <td>{{ \Carbon\Carbon::parse($project->startdate)->format('d-m-Y') }}</td> -->
                                            <!-- <td>{{ \Carbon\Carbon::parse($project->enddate)->format('d-m-Y') }}</td> -->
                                            <td>{{ $project->description }}</td>
                                            <td>
                                                <a href="{{ route('editProject', base64_encode($project->id)) }}">
                                                    <button type="button"
                                                        class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                    <button type="button"
                                                            class="btn btn-primary btn-icon waves-effect waves-light viewBtn"
                                                            data-id="{{ $project->id }}"
                                                            data-workorder="{{ $project->customer_id }}"
                                                            data-name="{{ $project->name }}"
                                                            data-customer="{{ $project->customer->name ?? '' }}"
                                                            data-code="{{ $project->customer?->code }}"
                                                            data-qty="{{ $project->qty }}"
                                                            data-start="{{ \Carbon\Carbon::parse($project->startdate)->format('d-m-Y') }}"
                                                            data-end="{{ \Carbon\Carbon::parse($project->enddate)->format('d-m-Y') }}"
                                                            data-desc="{{ $project->description }}">
                                                            <i class="ri-eye-fill align-bottom"></i>
                                                        </button>


                                                <a href="{{ route('deleteProject', base64_encode($project->id)) }}"
                                                onclick="return confirm('Are you sure you want to delete this record?')">
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
            <!-- View Modal -->
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
                  <th>Work Order.No</th>
                  <td id="view_workorder"></td>
              </tr>
              <tr>
                  <th>Project Name</th>
                  <td id="view_name"></td>
              </tr>
              <tr>
                  <th>Customer Name</th>
                  <td id="view_customer"></td>
              </tr>
              <tr>
                  <th>Customer Code</th>
                  <td id="view_code"></td>
              </tr>
              <tr>
                  <th>Quantity</th>
                  <td id="view_qty"></td>
              </tr>
              <tr>
                  <th>Start Date</th>
                  <td id="view_start"></td>
              </tr>
              <tr>
                  <th>End Date</th>
                  <td id="view_end"></td>
              </tr>
              <tr>
                  <th>Description</th>
                  <td id="view_desc"></td>
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
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".viewBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.getElementById("view_workorder").textContent = this.dataset.workorder;
            document.getElementById("view_name").textContent = this.dataset.name;
            document.getElementById("view_customer").textContent = this.dataset.customer;
            document.getElementById("view_code").textContent = this.dataset.code;
            document.getElementById("view_qty").textContent = this.dataset.qty;
            document.getElementById("view_start").textContent = this.dataset.start;
            document.getElementById("view_end").textContent = this.dataset.end;
            document.getElementById("view_desc").textContent = this.dataset.desc;

            let modal = new bootstrap.Modal(document.getElementById("viewProjectModal"));
            modal.show();
        });
    });
});
</script>

@endsection