@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Work Order Entries</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SrNo.</th>
                                            <th>Work Order No</th>
                                            <th>Entry Code</th>
                                            <th>Part</th>
                                            <th>Date</th>
                                            <th>Part Code</th>
                                            <th>Part Description</th>
                                            <th>Diameter</th>
                                            <th>Length</th>
                                            <th>Width</th>
                                            <th>Height</th>
                                            <th>Expected Time</th>
                                            <th>Quantity</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workorders as $wo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $wo->work_order_no }}</td>
                                            <td>{{ $wo->customer?->code }}</td>
                                            <td>{{ $wo->part }}</td>
                                            <td>{{ $wo->date }}</td>
                                            <td>{{ $wo->customer_id . ($wo->customer?->code ?? '') . $wo->part }}</td>
                                            <td>{{ $wo->part_description }}</td>
                                            <td>{{ $wo->diameter }}</td>
                                            <td>{{ $wo->length }}</td>
                                            <td>{{ $wo->width }}</td>
                                            <td>{{ $wo->height }}</td>
                                            <td>{{ $wo->exp_time }}</td>
                                            <td>{{ $wo->quantity }}</td>
                                            <td>
                                                <a href="{{ route('editWorkOrder', base64_encode($wo->id)) }}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>
                                                <a href="{{route('deleteWorkOrder', base64_encode($wo->id)) }}">
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

        </div>
    </div>
</div>

@endsection