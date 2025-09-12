@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Material Orders</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr class="table-white text-center">
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">SR No</th>
                                            <th rowspan="2">Date</th>
                                            <th rowspan="2">Work Order</th>

                                            <!-- Finish Size -->
                                            <th colspan="4" class="text-center">Finish Size</th>

                                            <!-- Raw Size -->
                                            <th colspan="4" class="text-center">Raw Size</th>

                                            <th rowspan="2">Material</th>
                                            <th rowspan="2">Qty</th>
                                            <th rowspan="2" width="12%">Action</th>
                                        </tr>
                                        <tr class="table-white text-center">
                                            <!-- Finish Size Sub-columns -->
                                            <th>DIA</th>
                                            <th>Length</th>
                                            <th>Width</th>
                                            <th>Height</th>

                                            <!-- Raw Size Sub-columns -->
                                            <th>DIA</th>
                                            <th>Length</th>
                                            <th>Width</th>
                                            <th>Height</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $order->sr_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                                            <td>{{ $order->work_order_desc }}</td>

                                            <!-- Finish Size -->
                                            <td>{{ $order->f_diameter }}</td>
                                            <td>{{ $order->f_length }}</td>
                                            <td>{{ $order->f_width }}</td>
                                            <td>{{ $order->f_height }}</td>

                                            <!-- Raw Size -->
                                            <td>{{ $order->r_diameter }}</td>
                                            <td>{{ $order->r_length }}</td>
                                            <td>{{ $order->r_width }}</td>
                                            <td>{{ $order->r_height }}</td>

                                            <td>{{ $order->material }}</td>
                                            <td>{{ $order->quantity }}</td>

                                            <td class="text-center">
                                                <a href="{{ route('editMaterialorder', base64_encode($order->id)) }}" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                   <button type="button"
                                                    class="btn btn-primary btn-sm viewBtn">
                                                
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

@endsection
