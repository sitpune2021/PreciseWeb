@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">View Machine Records</h5>

                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('trashMachineRecord') }}" class="btn btn-warning btn-sm">
                                    View Trash
                                </a>
                                <!-- Add WorkOrder Button -->
                                <a href="{{ route('AddMachinerecord') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Machine Records
                                </a>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Part No</th>
                                            <th>code</th>
                                            <th>Work Order</th>
                                            <th>First Set</th>
                                            <th>Qty</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Invoice No</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($record as $rec)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rec->part_no }}</td>
                                            <td>{{ $rec->code }}</td>
                                            <td>{{ $rec->work_order }}</td>
                                            <td>{{ $rec->first_set }}</td>
                                            <td>{{ $rec->qty }}</td>
                                            <td>{{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('d-m-Y h:i A') : '' }}</td>
                                            <td>{{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('d-m-Y h:i A') : '' }}</td>
                                            <td>{{ $rec->invoice_no }}</td>
                                            <td>
                                                <a href="{{ route('EditMachinerecord', base64_encode($rec->id)) }}">
                                                    <button type="button" class="btn btn-success btn-sm">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn"
                                                    data-id="{{ $rec->id }}"
                                                    data-part="{{ $rec->part_no }}"
                                                    data-workorder="{{ $rec->work_order }}"
                                                    data-machine="{{ $rec->machine }}"
                                                    data-operator="{{ $rec->operator }}"
                                                    data-setting="{{ $rec->setting_no }}"
                                                    data-material="{{ $rec->material }}"
                                                    data-qty="{{ $rec->qty }}"
                                                    data-start="{{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('d-m-Y h:i A') : '' }}"
                                                    data-end="{{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('d-m-Y h:i A') : '' }}"
                                                    data-est_time="{{ $rec->est_time }}"
                                                    data-minute="{{ $rec->minute }}"
                                                    data-hrs="{{ $rec->hrs }}"
                                                    data-time_taken="{{ $rec->time_taken }}"
                                                    data-actual_hrs="{{ $rec->actual_hrs }}"
                                                    data-invoice_no="{{ $rec->invoice_no }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>


                                                <a href="{{ route('DeleteMachinerecord', base64_encode($rec->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-sm">
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
                                    <th>Minute</th>
                                    <td id="view_minute"></td>
                                </tr>
                                <tr>
                                    <th>Hrs</th>
                                    <td id="view_hrs"></td>
                                </tr>
                                <tr>
                                    <th>Time Taken</th>
                                    <td id="view_time_taken"></td>
                                </tr>

                                <th>Actual Hrs</th>
                                <td id="view_actual_hrs"></td>
                                </tr>
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
                            document.getElementById("view_workorder").textContent = this.dataset.workorder;
                            document.getElementById("view_machine").textContent = this.dataset.machine;
                            document.getElementById("view_operator").textContent = this.dataset.operator;
                            document.getElementById("view_setting").textContent = this.dataset.setting;
                            document.getElementById("view_material").textContent = this.dataset.material;
                            document.getElementById("view_qty").textContent = this.dataset.qty;
                            document.getElementById("view_start").textContent = this.dataset.start;
                            document.getElementById("view_end").textContent = this.dataset.end;
                            document.getElementById("view_est_time").textContent = this.dataset.est_time;
                            document.getElementById("view_minute").textContent = this.dataset.minute;
                            document.getElementById("view_hrs").textContent = this.dataset.hrs;
                            document.getElementById("view_time_taken").textContent = this.dataset.time_taken;
                            document.getElementById("view_actual_hrs").textContent = this.dataset.actual_hrs;
                            document.getElementById("view_invoice").textContent = this.dataset.invoice_no;

                            let modal = new bootstrap.Modal(document.getElementById("viewRecordModal"));
                            modal.show();
                        });
                    });
                });
            </script>


            @endsection