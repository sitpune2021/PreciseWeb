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
                    <h5 class="mb-0">Trash Vendors</h5>
                    <a href="{{ route('ViewVendor') }}" class="btn btn-primary btn-sm">← Back to Vendors</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Vendor Name</th>
                                    <th class="text-center">Contact Person</th>
                                    <th class="text-center">Phone No</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedVendors as $v)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $v->vendor_name }}</td>
                                    <td class="text-center">{{ $v->contact_person }}</td>
                                    <td class="text-center">{{ $v->phone_no }}</td>
                                    <td class="text-center">
                                        @if($v->deleted_at)
                                            {{ $v->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreVendor', base64_encode($v->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $v->vendor_name }}', '{{ route('restoreVendor', base64_encode($v->id)) }}')">
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No trashed vendors found.</td>
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
    let Vendors = @json($vendors);

    function confirmRestore(name, url) {
        let exists = Vendors.some(v => v.vendor_name === name && v.deleted_at === null);

        let message;
        if (exists) {
            message = "'" + name + "' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?";
        } else {
            message = "'" + name + "' Do you want to restore?";
        }

        if (confirm(message)) {
            window.location.href = url;
        }
        return false;
    }
</script>

@endsection
