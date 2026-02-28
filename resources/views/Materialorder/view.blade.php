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
                            <h5 class="mb-0">View Material Orders</h5>
                            <div class="d-flex gap-2">

                                @if(hasPermission('MaterialOrder', 'add'))
                                <a href="{{ route('AddMaterialorder') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Material Order
                                </a>
                                @endif

                                <a href="{{ route('trashMaterialorder') }}" class="btn btn-warning btn-sm">
                                    <i class="ri-delete-bin-line align-middle"></i> View Trash
                                </a>
                            </div>
                        </div>

                        @if(hasPermission('MaterialOrder', 'view'))
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>#</th>
                                            <th>Sr.No</th>
                                            <th>Date</th>
                                            <th style="width: 40px;">Customer Code</th>
                                            <th>Work Order desc</th>
                                            <th>Material</th>
                                            <th>Qty</th>
                                            <th width="12%">Action</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ $order->sr_no ?? $order->materialReq->sr_no ?? '-' }}
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>

                                            <td class="text-center">{{ $order->customer->code ?? 'N/A' }}</td>

                                            <td class="text-start">{{ $order->work_order_desc }}</td>

                                            <td>
                                                {{ $order->material }}
                                            </td>
                                            <td class="text-end">{{ $order->quantity }}</td>

                                            <td class="text-center">

                                                @if(hasPermission('MaterialOrder', 'edit'))
                                                <a href="{{ route('editMaterialorder', base64_encode($order->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                @endif

                                                <!-- View Button -->
                                                <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn"
                                                    data-no="{{ $order->sr_no }}"
                                                    data-name="{{ $order->customer->code ?? 'N/A' }}"
                                                    data-date="{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}"
                                                    data-desc="{{ $order->work_order_desc }}"
                                                    data-fdia="{{ rtrim(rtrim($order->f_diameter, '0'), '.') }}"
                                                    data-flen="{{ rtrim(rtrim($order->f_length, '0'), '.') }}"
                                                    data-fwid="{{ rtrim(rtrim($order->f_width, '0'), '.') }}"
                                                    data-fhei="{{ rtrim(rtrim($order->f_height, '0'), '.') }}"
                                                    data-rdia="{{ rtrim(rtrim($order->r_diameter, '0'), '.') }}"
                                                    data-rlen="{{ rtrim(rtrim($order->r_length, '0'), '.') }}"
                                                    data-rwid="{{ rtrim(rtrim($order->r_width, '0'), '.') }}"
                                                    data-rhei="{{ rtrim(rtrim($order->r_height, '0'), '.') }}"
                                                    data-mat="{{ $order->material }}"

                                                    data-qty="{{ $order->quantity }}">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>


                                                @if(hasPermission('MaterialOrder', 'delete'))
                                                <a href="{{ route('deleteMaterialorder', base64_encode($order->id)) }}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="ri-delete-bin-fill"></i>
                                                </a>
                                                @endif
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
                        @endif
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
            <div class="modal-header text-white">
                <h5 class="modal-title">Material Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered mb-3">
                    <tr>
                        <th>Customer Code</th>
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
                        <th>Material</th>
                        <td id="v_mat"></td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td id="v_qty"></td>
                    </tr>
                </table>

                <!-- Finish & Raw Size side-by-side -->
                <div class="row text-center mb-3">

                    <div class="col-6">
                        <table class="table table-bordered">
                            <tr class="bg-light">
                                <th colspan="2">Raw Size</th>
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
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.viewBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Basic info
                document.getElementById('v_name').innerText = this.dataset.name;
                document.getElementById('v_no').innerText = this.dataset.no;
                document.getElementById('v_date').innerText = this.dataset.date;
                document.getElementById('v_desc').innerText = this.dataset.desc;
                document.getElementById('v_mat').innerText = this.dataset.mat;
                document.getElementById('v_qty').innerText = this.dataset.qty;


                // Raw Size
                document.getElementById('v_rdia').innerText = this.dataset.rdia;
                document.getElementById('v_rlen').innerText = this.dataset.rlen;
                document.getElementById('v_rwid').innerText = this.dataset.rwid;
                document.getElementById('v_rhei').innerText = this.dataset.rhei;

                new bootstrap.Modal(document.getElementById('viewModal')).show();
            });
        });
    });
</script>

@endsection