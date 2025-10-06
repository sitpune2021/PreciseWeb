@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">


                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Work Order Entries</h5>

                            <div class="d-flex align-items-center gap-2">
                                <!-- Add WorkOrder Button -->
                                <a href="{{ route('AddWorkOrder') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add WorkOrder
                                </a>

                                 <!-- View Trash Button -->
                                <a href="{{ route('trashWorkOrder') }}" class="btn btn-warning btn-sm">
                                    View Trash
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.<br>No</th>
                                            <th>Work <br>Order No.</th>
                                            <th>Customer <br>Code</th>
                                            <th>Part<br>No.</th>
                                            <th>Date</th>
                                            <th>Part Code</th>

                                            <!-- <th>Project name</th> -->
                                            <!-- <th>Entry Code</th> -->
                                            <!-- <th>Quantity</th> -->
                                            <th>Part Description</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workorders as $wo)
                                            @php
                                                $project = \App\Models\Project::where('id', $wo->project_id)->first();
                                            @endphp


                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                           <td>{{ $project?->project_no ?? '' }}</td>

                                            <td>{{ $wo->customer?->code ?? '' }}</td>
                                            <td>{{ $wo->part }}</td>
                                            <td>{{ $wo->date }}</td>
                                            <td>
                                                {{ ($wo->customer?->code ?? '') . '_' . ($wo->project_no ?? '') . '_' . ($wo->part ?? '') . '_' . ($wo->quantity ?? '') }}
                                            </td>
                                            <!-- <td>{{ $wo->project->project_name ?? '' }}</td>                                           -->


                                            <!-- <td>{{ $wo->quantity }}</td> -->
                                            <td>{{ $wo->part_description }}</td>
                                            <td>
                                                <a href="{{ route('editWorkOrder', base64_encode($wo->id)) }}">
                                                    <button type="button" class="btn btn-success btn-icon">
                                                        <i class="ri-pencil-fill"></i>
                                                    </button>
                                                </a>

                                                <!-- View Button to open Modal -->
                                                <button type="button"
                                                    class="btn btn-primary btn-icon viewWorkOrder"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewWorkOrderModal"
                                                    data-wo='@json($wo)'>
                                                    <i class="ri-eye-fill"></i>
                                                </button>

                                                <a href="{{route('deleteWorkOrder', base64_encode($wo->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-icon">
                                                        <i class="ri-delete-bin-fill"></i>
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

        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('importWorkOrder') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import WorkOrder (Excel/CSV)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">Choose Excel/CSV File</label>
                        <input type="file" name="file" id="excelFile" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>

                    <a href="{{ route('exportWorkOrder') }}" class="btn btn-success">
                        <i class="ri-download-2-line"></i> Download Sample
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Work Order Modal -->
<div class="modal fade" id="viewWorkOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Work Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Work Order No.</th>
                            <td id="wo_work_order_no"></td>
                        </tr>
                        <tr>
                            <th>Customer Code</th>
                            <td id="wo_entry_code"></td>
                        </tr>
                        <tr>
                            <th>Part No.</th>
                            <td id="wo_part"></td>
                        </tr>
                        <tr>
                            <th>Material Type</th>
                            <td id="wo_mtype"></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td id="wo_date"></td>
                        </tr>
                        <tr>
                            <th>Part Code</th>
                            <td id="wo_part_code"></td>
                        </tr>

                        <tr>
                            <th>part_description</th>
                            <td id="wo_part_description"></td>
                        </tr>

                        <tr>
                            <th>Diameter</th>
                            <td id="wo_diameter"></td>
                        </tr>
                        <tr>
                            <th>Length</th>
                            <td id="wo_length"></td>
                        </tr>
                        <tr>
                            <th>Width</th>
                            <td id="wo_width"></td>
                        </tr>
                        <tr>
                            <th>Height</th>
                            <td id="wo_height"></td>
                        </tr>
                        <tr>
                            <th>Expected Time</th>
                            <td id="wo_exp_time"></td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td id="wo_quantity"></td>
                        </tr>


                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.viewWorkOrder').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let data = JSON.parse(this.getAttribute('data-wo'));

                document.getElementById('wo_work_order_no').textContent = data.project_id ?? '';
                document.getElementById('wo_entry_code').textContent = data.customer?.code ?? '';
                document.getElementById('wo_part').textContent = data.part ?? '';
                document.getElementById('wo_mtype').textContent = data.material ?? '';
                document.getElementById('wo_date').textContent = data.date ?? '';
                document.getElementById('wo_part_code').textContent =
                (data.customer?.code ?? '') + '_' + (data.customer_id ?? '') + '_' + (data.part ?? '') + '_' + (data.quantity ?? '');
                document.getElementById('wo_diameter').textContent = data.dimeter ?? '';;
                document.getElementById('wo_length').textContent = data.length ?? '';
                document.getElementById('wo_width').textContent = data.width ?? '';
                document.getElementById('wo_height').textContent = data.height ?? '';
                document.getElementById('wo_exp_time').textContent = data.exp_time ?? '';
                document.getElementById('wo_quantity').textContent = data.quantity ?? '';
                document.getElementById('wo_part_description').textContent = data.part_description ?? '';
            });
        });
    });
</script>

@endsection