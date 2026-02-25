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
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">View Machine Records</h5>

                            <div class="ms-auto d-flex gap-2">

                                @if(hasPermission('MachineRecord', 'add'))
                                <a href="{{ route('AddMachinerecord') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Machine Records
                                </a>
                                @endif

                                <a href="{{ route('trashMachineRecord') }}" class="btn btn-warning btn-sm">
                                    View Trash
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr.No</th>
                                            <th>Part No</th>
                                            <!-- <th>Customer Code</th> -->
                                            <!-- <th>Wo.No</th> -->
                                            <th>First Set</th>
                                            <th>Qty</th>
                                            <th>M/C</th>
                                            <th>Op</th>
                                            <th>Set</th>
                                            <th>Est.Time</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Total Hrs</th>
                                            <th>IDL Time</th>
                                            <!-- <th>Time Taken</th> -->
                                            <!-- <th>Adjustment</th> -->
                                            <!-- <th>Invoice No</th> -->
                                            @if(
                                            hasPermission('MachineRecord', 'edit') ||
                                            hasPermission('MachineRecord', 'delete')||
                                            hasPermission('MachineRecord', 'view')
                                            )
                                            <th width="9%">Action</th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($record as $rec)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rec->part_no }}</td>
                                            <!-- <td>{{ $rec->code}}</td> -->
                                            <!-- <td>{{ $rec->work_order }}</td> -->
                                            <td>{{ $rec->first_set }}</td>
                                            <td>{{ $rec->qty }}</td>
                                            <td>{{ $rec->machine }}</td>
                                            <td>{{ $rec->operator }}</td>
                                            <td>{{ $rec->setting_no }}</td>
                                            <td>{{ $rec->est_time }}</td>
                                            <td>{{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('d-m-Y h:i A') : '' }}</td>
                                            <td>{{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('d-m-Y h:i A') : '' }}</td>
                                            <!-- <td>{{ $rec->time_taken }}</td> -->
                                            <!-- <td>{{ $rec->adjustment }}</td> -->
                                            <td>{{ $rec->hrs }}</td>
                                            <td>{{ $rec->idl_time }}</td>
                                            <!-- <td>{{ $rec->invoice_no }}</td> -->
                                            <td>
                                                @if(hasPermission('MachineRecord', 'edit'))
                                                <a href="{{ route('EditMachinerecord', base64_encode($rec->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </a>
                                                @endif
                                                @if(hasPermission('MachineRecord', 'view'))
                                                <button type="button" class="btn btn-primary btn-sm viewBtn"
                                                    data-id="{{ $rec->id }}"
                                                    data-part="{{ $rec->part_no }}"
                                                    data-first_set="{{ $rec->first_set }}"
                                                    data-code="{{ $rec->customer?->code ?? '' }}"
                                                    data-workorder="{{ $rec->work_order }}"
                                                    data-machine="{{ $rec->machine }}"
                                                    data-operator="{{ $rec->operator }}"
                                                    data-setting="{{ $rec->setting_no }}"
                                                    data-material="{{ $rec->material }}"
                                                    data-qty="{{ $rec->qty }}"
                                                    data-est_time="{{ $rec->est_time }}"
                                                    data-start="{{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('d-m-Y h:i A') : ''}}"
                                                    data-end="{{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('d-m-Y h:i A') : ''}}"
                                                    data-time_taken="{{ $rec->time_taken }}"
                                                    data-hrs="{{ $rec->hrs }}"
                                                    data-adjustment="{{ $rec->adjustment }}"
                                                    data-invoice_no="{{ $rec->invoice_no }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>
                                                @endif

                                                @if(hasPermission('MachineRecord', 'delete'))
                                                <a href="{{ route('DeleteMachinerecord', base64_encode($rec->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ri-delete-bin-fill align-bottom"></i>
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

            <!-- View Modal -->
            <div class="modal fade" id="viewRecordModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Machine Record Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Part No</th>
                                    <td id="view_part"></td>
                                </tr>
                                <tr>
                                    <th>Part Description</th>
                                    <td id="view_first_set"></td>
                                </tr>
                                <tr>
                                    <th>Work Order</th>
                                    <td id="view_workorder"></td>
                                </tr>
                                <tr>
                                    <th>Machine</th>
                                    <td id="view_machine"></td>
                                </tr>
                                <tr>
                                    <th>Operator</th>
                                    <td id="view_operator"></td>
                                </tr>
                                <tr>
                                    <th>Setting No</th>
                                    <td id="view_setting"></td>
                                </tr>
                                <tr>
                                    <th>Material type</th>
                                    <td id="view_material"></td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td id="view_qty"></td>
                                </tr>
                                <tr>
                                    <th>Start Time</th>
                                    <td id="view_start"></td>
                                </tr>
                                <tr>
                                    <th>End Time</th>
                                    <td id="view_end"></td>
                                </tr>
                                <tr>
                                    <th>Estimated Time</th>
                                    <td id="view_est_time"></td>
                                </tr>
                                <tr>
                                    <th>Adjustment</th>
                                    <td id="view_adjustment"></td>
                                </tr>

                                <!-- <tr>
                                    <th>Minute</th>
                                    <td id="view_minute"></td>
                                </tr> -->
                                <tr>
                                    <th>Hrs</th>
                                    <td id="view_hrs"></td>
                                </tr>
                                <!-- <tr>
                                    <th>Time Taken</th>
                                    <td id="view_time_taken"></td>
                                </tr> -->
                                <!-- <tr>
                                    <th>Actual Hrs</th>
                                    <td id="view_actual_hrs"></td>
                                </tr> -->
                                <tr>
                                    <th>Invoice No</th>
                                    <td id="view_invoice"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll(".viewBtn").forEach(btn => {
                        btn.addEventListener("click", function() {
                            document.getElementById("view_part").textContent = this.dataset.part;
                            document.getElementById("view_first_set").textContent = this.dataset.first_set;
                            document.getElementById("view_workorder").textContent = this.dataset.workorder;
                            document.getElementById("view_machine").textContent = this.dataset.machine;
                            document.getElementById("view_operator").textContent = this.dataset.operator;
                            document.getElementById("view_setting").textContent = this.dataset.setting;
                            document.getElementById("view_material").textContent = this.dataset.material;
                            document.getElementById("view_qty").textContent = this.dataset.qty;
                            document.getElementById("view_start").textContent = this.dataset.start;
                            document.getElementById("view_end").textContent = this.dataset.end;
                            document.getElementById("view_est_time").textContent = this.dataset.est_time;
                            document.getElementById("view_adjustment").textContent = this.dataset.adjustment;
                            // document.getElementById("view_minute").textContent = this.dataset.minute;
                            document.getElementById("view_hrs").textContent = this.dataset.hrs;
                            // document.getElementById("view_time_taken").textContent = this.dataset.time_taken;
                            // document.getElementById("view_actual_hrs").textContent = this.dataset.actual_hrs;
                            document.getElementById("view_invoice").textContent = this.dataset.invoice_no;

                            let modal = new bootstrap.Modal(document.getElementById("viewRecordModal"));
                            modal.show();
                        });
                    });
                });
            </script>

        </div>
    </div>
</div>

@endsection