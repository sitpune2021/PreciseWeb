@extends('layouts.header')
@section('content')
 
<div class="main-content">
 
    <div class="page-content">
        <div class="container-fluid">
 
        <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">View Customers</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <th>Code</th>
                                                    <th>Contact Person</th>
                                                    <th>Phone No.</th>
                                                    <th>Address</th>
                                                    <th width="12%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @foreach($customer as $c)
                                                    <tr>
                                                        <td>{{ $c->name }}</td>
                                                        <td>{{ $c->code }}</td>
                                                        <td>{{ $c->contact_person }}</td>
                                                        <td>{{ $c->phone_no }}</td>
                                                        <td>{{ $c->address }}</td>
                                                        <td>
                                                            <a href="{{route('editCustomer', base64_encode($c->id))}}">
                                                                <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                                </button>
                                                            </a>
                                                            <button type="button" class="btn btn-primary btn-icon waves-effect waves-light">
                                                                <i class="ri-eye-fill align-bottom"></i>
                                                            </button>
                                                       
                                                            <a href="">
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