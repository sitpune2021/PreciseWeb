@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Material Orders</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('AddMaterialorder') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Material Order
                                </a>
                                <a href="{{ route('trashMaterialorder') }}" class="btn btn-warning btn-sm">
                                    <i class="ri-delete-bin-line align-middle"></i> View Trash
                                </a>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr class="table-white text-center">
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Sr.No</th>
                                            <th rowspan="2">Customer Name</th>

                                            <th rowspan="2">Date</th>
                                            <th rowspan="2">Work Order desc</th>

                                            <th rowspan="2">Material</th>
                                            <th rowspan="2">Qty</th>
                                            <th rowspan="2">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $order->work_order_no }}</td>
                                            <td class="text-center">{{ $order->customer->name ?? 'N/A' }}</td>

                                            <td class="text-center">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                                            <td class="text-start">{{ $order->work_order_desc }}</td>

                                            <td class="text-end">{{ $order->material }}</td>
                                            <td class="text-end">{{ $order->quantity }}</td>

                                            <td class="text-center">
                                                <a href="{{ route('editMaterialorder', base64_encode($order->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>

                                                <!-- View Button -->
                                                <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn"
                                                    data-no="{{ $order->work_order_no }}"
                                                    data-name="{{ $order->customer->name ?? 'N/A' }}"
                                                    data-date="{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}"
                                                    data-desc="{{ $order->work_order_desc }}"
                                                    data-fdia="{{ $order->f_diameter }}"
                                                    data-flen="{{ $order->f_length }}"
                                                    data-fwid="{{ $order->f_width }}"
                                                    data-fhei="{{ $order->f_height }}"
                                                    data-rdia="{{ $order->r_diameter }}"
                                                    data-rlen="{{ $order->r_length }}"
                                                    data-rwid="{{ $order->r_width }}"
                                                    data-rhei="{{ $order->r_height }}"
                                                    data-mat="{{ $order->material }}"
                                                    data-qty="{{ $order->quantity }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>


                                                <a href="{{ route('deleteMaterialorder', base64_encode($order->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="15" class="text-center text-muted">No records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

        </div>
    </div>
</div>

<!-- 🔹 VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header   text-white">
                <h5 class="modal-title">Material Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Customer Name</th>
                        <td id="v_name"></td>
                    </tr>
                    <tr>
                        <th>Sr.No</th>
                        <td id="v_no"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="v_date"></td>
                    </tr>
                    <tr>
                        <th>Work Order Desc</th>
                        <td id="v_desc"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center bg-light">Finish Size</th>
                    </tr>
                    <tr>
                        <th>DIA</th>
                        <td id="v_fdia"></td>
                    </tr>
                    <tr>
                        <th>Length</th>
                        <td id="v_flen"></td>
                    </tr>
                    <tr>
                        <th>Width</th>
                        <td id="v_fwid"></td>
                    </tr>
                    <tr>
                        <th>Height</th>
                        <td id="v_fhei"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center bg-light">Raw Size</th>
                    </tr>
                    <tr>
                        <th>DIA</th>
                        <td id="v_rdia"></td>
                    </tr>
                    <tr>
                        <th>Length</th>
                        <td id="v_rlen"></td>
                    </tr>
                    <tr>
                        <th>Width</th>
                        <td id="v_rwid"></td>
                    </tr>
                    <tr>
                        <th>Height</th>
                        <td id="v_rhei"></td>
                    </tr>
                    <tr>
                        <th>Material</th>
                        <td id="v_mat"></td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td id="v_qty"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 🔹 SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.viewBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('v_date').innerText = this.dataset.date;
                document.getElementById('v_no').innerText = this.dataset.no;
                document.getElementById('v_name').innerText = this.dataset.name;
                document.getElementById('v_desc').innerText = this.dataset.desc;
                document.getElementById('v_fdia').innerText = this.dataset.fdia;
                document.getElementById('v_flen').innerText = this.dataset.flen;
                document.getElementById('v_fwid').innerText = this.dataset.fwid;
                document.getElementById('v_fhei').innerText = this.dataset.fhei;
                document.getElementById('v_rdia').innerText = this.dataset.rdia;
                document.getElementById('v_rlen').innerText = this.dataset.rlen;
                document.getElementById('v_rwid').innerText = this.dataset.rwid;
                document.getElementById('v_rhei').innerText = this.dataset.rhei;
                document.getElementById('v_mat').innerText = this.dataset.mat;
                document.getElementById('v_qty').innerText = this.dataset.qty;

                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('viewModal'));
                modal.show();
            });
        });
    });
</script>

@endsection