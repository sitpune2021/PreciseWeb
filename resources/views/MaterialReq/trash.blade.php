@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Success/Error Message --}}
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trash Material Requirements</h5>
                    <a href="{{ route('ViewMaterialReq') }}" class="btn btn-primary btn-sm">← Back to Material Requirements</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Work Order No</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedMaterialReq as $req)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $req->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $req->code }}</td>
                                    <td>{{ $req->date }}</td>
                                    <td>{{ $req->work_order_no }}</td>
                                    <td>{{ $req->description }}</td>
                                    <td class="text-center">
                                        @if($req->deleted_at)
                                        {{ $req->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreMaterialReq', base64_encode($req->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $req->code }}', '{{ route('restoreMaterialReq', base64_encode($req->id)) }}')">
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No trashed material requirements found.</td>
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
    let MaterialReq = @json($materialReq);

    function confirmRestore(code, url) {
        let exists = MaterialReq.some(m => m.code === code && m.deleted_at === null);

        let message;
        if (exists) {
            message = "'" + code + "' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?";
        } else {
            message = "Do you want to restore '" + code + "'?";
        }

        if (confirm(message)) {
            window.location.href = url;
        }
        return false;
    }
</script>

@endsection