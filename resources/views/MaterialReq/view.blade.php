@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Material Requirements</h5>

                            <!-- Buttons on right -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('AddMaterialReq') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Material Req
                                </a>
                                <a href="{{ route('trashMaterialReq') }}" class="btn btn-warning btn-sm">
                                    <i class="ri-delete-bin-line align-middle"></i> View Trash
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                             <th>Sr.No</th>
                                            <th>Customer Code</th>
                                            <th>Code</th>
                                            <th>Date</th>
                                           
                                            <th>Description</th>

                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materialReq as $req)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $req->work_order_no }}</td>
                                            <td>{{ $req->customer->code ?? 'N/A' }}</td>
                                            <td>{{ $req->code }}</td>
                                            <td>{{ $req->date }}</td>
                                            
                                            <td>{{ $req->description }}</td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2 align-items-center">

                                                    @if(hasPermission('MaterialReq', 'edit'))
                                                    <a href="{{ route('editMaterialReq', base64_encode($req->id)) }}"
                                                        class="btn btn-success btn-sm" title="Edit">
                                                        <i class="ri-pencil-fill"></i>
                                                    </a>
                                                    @endif
                                                    <button type="button" class="btn btn-primary btn-sm viewMaterialReqBtn"
                                                        data-customer="{{ $req->customer->code ?? 'N/A' }}"
                                                        data-code="{{ $req->code }}"
                                                        data-date="{{ $req->date }}"
                                                        data-workorder="{{ $req->work_order_no }}"
                                                        data-description="{{ $req->description }}"
                                                        data-dia="{{ $req->dia }}"
                                                        data-length="{{ $req->length }}"
                                                        data-width="{{ $req->width }}"
                                                        data-height="{{ $req->height }}"
                                                       data-material="{{ $req->material ?? 'N/A' }}"
                                                        data-qty="{{ $req->qty }}"
                                                        data-weight="{{ $req->weight }}"
                                                        data-cost="{{ $req->material_cost ?? 'N/A' }}"
                                                        data-lathe="{{ $req->lathe }}"
                                                        data-mg4="{{ $req->mg4 }}"
                                                        data-mg2="{{ $req->mg2 }}"
                                                        data-rg2="{{ $req->rg2 }}"
                                                        data-sg4="{{ $req->sg4 }}"
                                                        data-sg2="{{ $req->sg2 }}"
                                                        data-vmc_hrs="{{ $req->vmc_hrs }}"
                                                        data-vmc_cost="{{ $req->vmc_cost }}"
                                                        data-hrc="{{ $req->hrc }}"
                                                        data-edm_qty="{{ $req->edm_qty }}"
                                                        data-edm_rate="{{ $req->edm_rate }}"
                                                        data-cl="{{ $req->cl }}"

                                                        data-total_cost="{{ $req->total_cost }}">
                                                        <i class="ri-eye-fill"></i>
                                                    </button>
                                                    @if(hasPermission('MaterialReq', 'delete'))
                                                    <a href="{{ route('deleteMaterialReq', base64_encode($req->id)) }}"
                                                        onclick="return confirm('Are you sure you want to delete this record?')"
                                                        class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </a>
                                                    @endif
                                                </div>
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

            <!-- 🔹 Material Requirement View Modal -->
            <div class="modal fade" id="viewMaterialReqModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Material Requirement Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Customer Code</th>
                                    <td id="mr_customer"></td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td id="mr_code"></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td id="mr_date"></td>
                                </tr>
                                <tr>
                                    <th>Work Order No</th>
                                    <td id="mr_workorder"></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td id="mr_description"></td>
                                </tr>
                                <tr>
                                    <th>Dia</th>
                                    <td id="mr_dia"></td>
                                </tr>
                                <tr>
                                    <th>Length</th>
                                    <td id="mr_length"></td>
                                </tr>
                                <tr>
                                    <th>Width</th>
                                    <td id="mr_width"></td>
                                </tr>
                                <tr>
                                    <th>Height</th>
                                    <td id="mr_height"></td>
                                </tr>
                                <tr>
                                    <th>Material</th>
                                    <td id="mr_material"></td>
                                </tr>
                                <tr>
                                    <th>Quantity</th>
                                    <td id="mr_qty"></td>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <td id="mr_weight"></td>
                                </tr>
                                <tr>
                                    <th> Material cost</th>
                                    <td id="mr_cost"></td>
                                </tr>
                                <tr>
                                    <th>Lathe</th>
                                    <td id="mr_lathe"></td>
                                </tr>
                                <tr>
                                    <th>MG4</th>
                                    <td id="mr_mg4"></td>
                                </tr>
                                <tr>
                                    <th>MG2</th>
                                    <td id="mr_mg2"></td>
                                </tr>
                                <tr>
                                    <th>RG2</th>
                                    <td id="mr_rg2"></td>
                                </tr>
                                <tr>
                                    <th>SG4</th>
                                    <td id="mr_sg4"></td>
                                </tr>
                                <tr>
                                    <th>SG2</th>
                                    <td id="mr_sg2"></td>
                                </tr>
                                <tr>
                                    <th>VMC Hrs</th>
                                    <td id="mr_vmc_hrs"></td>
                                </tr>
                                <tr>
                                    <th>VMC Cost</th>
                                    <td id="mr_vmc_cost"></td>
                                </tr>
                                <tr>
                                    <th>HRC</th>
                                    <td id="mr_hrc"></td>
                                </tr>
                                <tr>
                                    <th>EDM Qty</th>
                                    <td id="mr_edm_qty"></td>
                                </tr>
                                <tr>
                                    <th>EDM Rate</th>
                                    <td id="mr_edm_rate"></td>
                                </tr>
                                <tr>
                                    <th>CL</th>
                                    <td id="mr_cl"></td>
                                </tr>
                                <tr>
                                    <th>Total Cost</th>
                                    <td id="mr_total_cost"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelectorAll(".viewMaterialReqBtn").forEach(btn => {
                        btn.addEventListener("click", function() {
                            document.getElementById("mr_customer").textContent = this.dataset.customer;
                            document.getElementById("mr_code").textContent = this.dataset.code;
                            document.getElementById("mr_date").textContent = this.dataset.date;
                            document.getElementById("mr_workorder").textContent = this.dataset.workorder;
                            document.getElementById("mr_description").textContent = this.dataset.description;
                            document.getElementById("mr_dia").textContent = this.dataset.dia;
                            document.getElementById("mr_length").textContent = this.dataset.length;
                            document.getElementById("mr_width").textContent = this.dataset.width;
                            document.getElementById("mr_height").textContent = this.dataset.height;
                            document.getElementById("mr_material").textContent = this.dataset.material;

                            document.getElementById("mr_qty").textContent = this.dataset.qty;
                            document.getElementById("mr_weight").textContent = this.dataset.weight;
                            document.getElementById("mr_cost").textContent = this.dataset.cost;

                            document.getElementById("mr_lathe").textContent = this.dataset.lathe;
                            document.getElementById("mr_mg4").textContent = this.dataset.mg4;
                            document.getElementById("mr_mg2").textContent = this.dataset.mg2;
                            document.getElementById("mr_rg2").textContent = this.dataset.rg2;
                            document.getElementById("mr_sg4").textContent = this.dataset.sg4;
                            document.getElementById("mr_sg2").textContent = this.dataset.sg2;
                            document.getElementById("mr_vmc_hrs").textContent = this.dataset.vmc_hrs;
                            document.getElementById("mr_vmc_cost").textContent = this.dataset.vmc_cost;
                            document.getElementById("mr_hrc").textContent = this.dataset.hrc;
                            document.getElementById("mr_edm_qty").textContent = this.dataset.edm_qty;
                            document.getElementById("mr_edm_rate").textContent = this.dataset.edm_rate;
                            document.getElementById("mr_cl").textContent = this.dataset.cl;
                            document.getElementById("mr_total_cost").textContent = this.dataset.total_cost;

                            let modal = new bootstrap.Modal(document.getElementById("viewMaterialReqModal"));
                            modal.show();
                        });
                    });
                });
            </script>

        </div>
    </div>
</div>
@endsection