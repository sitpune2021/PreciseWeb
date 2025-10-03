@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Success/Error Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trash HSN Code</h5>
                    <a href="{{ route('addHsn') }}" class="btn btn-primary btn-sm">← Back to HSN</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>HSN Code</th>
                                    <th>SGST %</th>
                                    <th>CGST %</th>
                                    <th>IGST %</th>
                                    <th>Invoice Description</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedhsn as $o)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $o->hsn_code }}</td>
                                        <td>{{ $o->sgst }}%</td>
                                        <td>{{ $o->cgst }}%</td>
                                        <td>{{ $o->igst }}%</td>
                                        <td>{{ $o->invoice_desc }}</td>
                                        <td class="text-center">
                                            @if($o->deleted_at)
                                                {{ $o->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('restorehsn', base64_encode($o->id)) }}"
                                               class="btn btn-success btn-sm"
                                               onclick="return confirmRestore('{{ $o->hsn_code }}', '{{ route('restorehsn', base64_encode($o->id)) }}')">
                                                Restore
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No trashed HSN found.</td>
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

{{-- Custom JS --}}
<script>
    let HsnList = @json($hsncodes ?? []);

    function confirmRestore(hsn_code, url) {
        let exists = HsnList.some(op =>
            op.hsn_code === hsn_code && op.deleted_at === null && op.is_active == 1
        );

        let message;
        if (exists) {
            message = "HSN Code '" + hsn_code + "' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?";
        } else {
            message = "Do you want to restore HSN Code '" + hsn_code + "' ?";
        }

        if (confirm(message)) {
            window.location.href = url;
        }
        return false;
    }
</script>


@endsection
