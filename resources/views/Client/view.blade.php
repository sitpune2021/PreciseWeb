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
                                            <th>Sr.No</th>
                                            <th>Client Name</th>
                                            <th>Email Address</th>
                                            <th>Phone No.</th>
                                            <th>GST No.</th>
                                            <th>Address</th>
                                            <th>Client Logo</th>
                                            <th>Status</th>
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
                                            <form action="{{ route('updateClientStatus') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $c->id }}">
                                                <div class="form-check form-switch">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="statusNameSwitch{{ $c->id }}"
                                                        name="status"
                                                        value="1"
                                                        onchange="this.form.submit()"
                                                        {{ $c->status == '1' ? 'checked' : '' }}>
                                                </div>
                                            </form>
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
                                                <a href="{{route('deleteClient', base64_encode($c->id))}}"
                                                onclick="return confirm('Are you sure you want to delete this record?')">
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
            </div><!--end row-->

        </div>
    </div>
</div>
@endsection