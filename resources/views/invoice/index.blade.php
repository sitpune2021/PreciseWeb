@extends('layouts.header') 
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h4 class="mb-0 fw-bold text-primary">🧾 Invoice List</h4>
                        <div class="d-flex align-items-center gap-2">
                            <form method="GET" action="{{ route('invoice.index') }}" class="d-flex gap-2">
                                <select name="customer_id" class="form-select form-select-sm border-primary">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $cust)
                                        <option value="{{ $cust->id }}" {{ (isset($customerId) && $customerId == $cust->id) ? 'selected' : '' }}>
                                            {{ $cust->name }} ({{ $cust->code ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('invoice.add') }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus me-1"></i> Add Invoice
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoices Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center mb-0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Grand Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices as $index => $invoice)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><span class="fw-semibold text-dark">{{ $invoice->invoice_no }}</span></td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</td>
                                                <td>{{ $invoice->customer->name ?? '-' }}</td>
                                                <td class="fw-semibold text-success">₹{{ number_format($invoice->grand_total, 2) }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('invoice.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-info rounded-pill px-3">
                                                            <i class="fas fa-print"></i> Print
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="fas fa-exclamation-circle me-1"></i> No Invoices Found
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
        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
</div> <!-- main-content -->

<script>
    document.querySelector('select[name="customer_id"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection
