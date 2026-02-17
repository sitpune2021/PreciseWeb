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
                                @if(hasPermission('Quotation', 'add'))
                                <a href="{{ route('Addquotation') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Quotation
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr.No</th>
                                            <th>Quotation No</th>
                                            <th>Customer<br>Code</th>
                                            <th>Project Name</th>
                                            <th>Date</th>
                                            <th>Description</th>

                                            @if(
                                            hasPermission('Quotation', 'edit') ||
                                            hasPermission('Quotation', 'delete')
                                            )
                                            <th width="12%">Action</th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quotations as $index => $q)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $q->quotation_no }}</td>
                                            <td>{{ $q->customer->code ?? '-' }}</td>
                                            <td>{{ $q->project_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($q->date)->format('d-m-Y') }}</td>
                                            <td>{{ $q->project_name }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">

                                                    <!-- @if(hasPermission('Quotation', 'edit'))
                                                    <a href="{{ route('editquotation', base64_encode($q->id)) }}"
                                                        class="btn btn-success btn-icon">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </a>
                                                    @endif -->

                                                    @if(hasPermission('Quotation', 'delete'))
                                                    <a href="{{ route('deletequotation', base64_encode($q->id)) }}"
                                                        onclick="return confirm('Are you sure you want to delete this record?')"
                                                        class="btn btn-danger btn-icon">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </a>
                                                    @endif

                                                    @if(hasPermission('Quotation', 'view'))
                                                    <a href="{{ route('printquotation', base64_encode($q->id)) }}"
                                                        onclick="return confirm('Are you sure you want to print quotation?');"
                                                        class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center"
                                                        style="width:38px; height:38px;">

                                                        <i class="fas fa-print fa-lg"></i>

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
        </div>
    </div>
</div>
@endsection