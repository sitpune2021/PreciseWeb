@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trash WorkOrders</h5>
                    <a href="{{ route('ViewWorkOrder') }}" class="btn btn-primary btn-sm">← Back to WorkOrders</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">WorkOrder No.</th>
                                    <th class="text-center">Customer Code</th>
                                    <th class="text-center">Part No.</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Part Description</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedWorkOrders as $w)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $w->work_order_no }}</td>
                                    <td class="text-center">{{ $w->customer?->code ?? '' }}</td>
                                    <td class="text-center">{{ $w->part }}</td>
                                    <td class="text-center">{{ $w->date }}</td>
                                    <td class="text-center">{{ $w->part_description }}</td>
                                    <td class="text-center">
                                        @if($w->deleted_at)
                                            {{ $w->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreWorkOrder', base64_encode($w->id)) }}"
                                           class="btn btn-success btn-sm"
                                           onclick="return confirmRestore('{{ $w->work_order_no }}', '{{ route('restoreWorkOrder', base64_encode($w->id)) }}')">
                                           Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No trashed work orders found.</td>
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

<script>
    let WorkOrders = @json($WorkOrders);

    function confirmRestore(name, url) {
        let exists = WorkOrders.some(w => w.work_order_no === name && w.deleted_at === null);

        let message;
        if (exists) {
            message = "WorkOrder '" + name + "' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?";
        } else {
            message = "Do you want to restore WorkOrder '" + name + "' ?";
        }

        if (confirm(message)) {
            window.location.href = url;
        }
        return false;
    }
</script>

@endsection
