 @extends('layouts.header')
 @section('content')

 <div class="main-content">
     <div class="page-content">
         <div class="container-fluid">

             <div class="row mb-3">
                 <div class="col-lg-12">
                     <div class="d-flex justify-content-between align-items-center">
                         <h5 class="mb-0">
                             <i class="me-2"></i> Proforma Invoice List
                         </h5>

                     </div>
                 </div>
             </div>
             <div class="card-body">
                 <div class="card shadow-sm border-0">
                     <div class="card-body">

                         <div class="d-flex justify-content-between align-items-end">
                             <!-- Left Side : Filter Form -->
                             <form method="GET" action="{{ route('proforma.index') }}" class="d-flex gap-2 align-items-end">
                                 <div>
                                     <label class="form-label fw-semibold">Customer</label>
                                     <select name="customer_id" class="form-select form-select-sm js-example-basic-single border-primary">
                                         <option value="">All Customers List</option>
                                         @foreach($customers as $cust)
                                         <option value="{{ $cust->id }}" {{ (isset($customerId) && $customerId == $cust->id) ? 'selected' : '' }}>
                                             {{ $cust->name }} ({{ $cust->code ?? '' }})
                                         </option>
                                         @endforeach
                                     </select>
                                 </div>

                                 <button type="submit" class="btn btn-primary">
                                     Search
                                 </button>
                             </form>

                             <!-- Right Side : Add Button -->
                             <a href="{{ route('proforma.add') }}" class="btn btn-success shadow-sm">
                                 </i> Add Invoice
                             </a>

                         </div>

                     </div>
                 </div>

             </div>

             <!-- Table Section -->
             <div class="card shadow-sm border-0">
                 <div class="card-body">
                     <div class="table-responsive">
                         <table id="buttons-datatables" class="table table-bordered table-striped table-hover align-middle">
                             <thead class="table-primary text-center">
                                 <tr>
                                     <th>#</th>
                                     <th>Invoice No</th>
                                     <th>Date</th>
                                     <th>Customer</th>
                                     <th>Grand Total</th>
                                     <th width="120px">Actions</th>
                                 </tr>
                             </thead>

                             <tbody>
                                 @forelse($invoices as $index => $invoice)
                                 <tr>
                                     <td class="text-center">{{ $index + 1 }}</td>

                                     <td><span class="fw-semibold">{{ $invoice->invoice_no }}</span></td>

                                     <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>

                                     <td>{{ $invoice->customer->name ?? '-' }}</td>

                                     <td class="fw-semibold text-success">₹{{ number_format($invoice->grand_total, 2) }}</td>

                                     <td class="text-center">
                                         <a href="{{ route('proforma.print', $invoice->id) }}"
                                             
                                             class="btn btn-info btn-sm rounded-pill px-3">
                                             <i class="fas fa-print"></i>
                                         </a>
                                     </td>
                                 </tr>

                                 @empty
                                 <tr>
                                     <td colspan="6" class="text-center text-muted py-4">
                                         <i class="fas fa-exclamation-circle me-1"></i> No Proforma Invoices Found
                                     </td>
                                 </tr>
                                 @endforelse
                             </tbody>

                         </table>
                     </div>
                 </div>
             </div>

         </div>
     </div>
 </div>



 @endsection