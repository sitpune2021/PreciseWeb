@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trashed Material Orders</h5>
                    <a href="{{ route('ViewMaterialorder') }}" class="btn btn-primary btn-sm">← Back to Material Orders</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Material</th>
                                    <th>Qty</th>
                                    <th>Deleted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedOrders as $order)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $order->date }}</td>
                                        <td>{{ $order->work_order_desc }}</td>
                                        <td>{{ $order->material }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>
                                            {{ optional($order->deleted_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') ?? '—' }}
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm"
                                                onclick="confirmRestore('{{ $order->work_order_desc }}', '{{ route('restoreMaterialorder', base64_encode($order->id)) }}')">
                                                Restore
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No trashed material orders found.
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

<script>
    let activeOrders = @json($activeOrders);

    function confirmRestore(desc, url) {
        let exists = activeOrders.some(o => o.work_order_desc === desc && o.deleted_at === null);
        let message = exists
            ? `'${desc}' already exists.\nRedirecting to Edit Page. Continue?`
            : `Do you want to restore '${desc}'?`;

        if (confirm(message)) {
            window.location.href = url;
        }
    }
</script>

@endsection
