@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Clients</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SrNo.</th>
                                            <th>Client Name</th>
                                            <th>Email Address</th>
                                            <th>Phone No.</th>
                                            <th>GST No.</th>
                                            <th>Address</th>
                                            <th>Client Logo</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ $c->email_id }}</td>
                                            <td>{{ $c->phone_no }}</td>
                                            <td>{{ $c->gst_no }}</td>
                                            <td>{{ $c->address }}</td>
                                            <td>
                                                @if($c->logo)
                                                <img src="{{ asset($c->logo) }}" alt="Client Logo" width="50" height="50">
                                                @else
                                                No Logo
                                                @endif
                                            </td>
                                            <td>
                                           
                                             <a href="{{route('editClient', base64_encode($c->id))}}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light">
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                    <i class="ri-delete-bin-fill align-bottom"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
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