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
                    <h5 class="mb-0">Trash Machine</h5>
                    <a href="{{ route('AddMachine') }}" class="btn btn-primary btn-sm">← Back to Machine</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Machine Name</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedmachine as $o)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $o->machine_name }}</td>
                                    <td class="text-center">
                                        @if($o->deleted_at)
                                        {{ $o->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoremachine', base64_encode($o->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $o->machine_name }}', '{{ route('restoremachine', base64_encode($o->id)) }}')">
                                            Restore
                                        </a>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No trashed operators found.</td>
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
    let Machine = @json($Machine);
</script>

<script>
    function confirmRestore(name, url) {
        let exists = Machine.some(op => op.machine_name === name && op.deleted_at === null && op.is_active == 1);

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