@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Quotation</h5>

                            <!-- Buttons on right -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('Addquotation') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Quotation
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
                                            <th>Sr.No</th>
                                            <th>Customer<br>Code</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quotations as $index => $q)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $q->customer->code ?? '-' }}</td>
                                            <td>{{ $q->project_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($q->date)->format('d-m-Y') }}</td>
                                            <td>{{ $q->project_name }}</td>
                                             



                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">

                                                    <a href="{{ route('editquotation', base64_encode($q->id)) }}"
                                                        class="btn btn-success btn-icon">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </a>

                                                    <a href="{{ route('deletequotation', base64_encode($q->id)) }}"
                                                        onclick="return confirm('Are you sure you want to delete this record?')"
                                                        class="btn btn-danger btn-icon">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </a>

                                                    <a href="{{ route('printquotation', base64_encode($q->id)) }}"
                                                        target="_blank"
                                                        onclick="return confirm('Are you sure you want to print quotation?');"
                                                        class="btn btn-info btn-sm   px-3">
                                                        <i class="fas fa-print"></i>
                                                    </a>

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



        </div>
    </div>
</div>
@endsection