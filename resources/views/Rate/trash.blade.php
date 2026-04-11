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
                    <h5 class="mb-0">Trash Rate</h5>
                    <a href="{{ route('Addrate') }}" class="btn btn-primary btn-sm">← Back to Vmc rate</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">

                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Hour</th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Deleted At</th>
                                    <th width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedRates as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $m->name }}</td>
                                    <td class="text-center">{{ $m->hour }}</td>
                                    <td class="text-center">₹ {{ number_format($m->rate, 2) }}</td>
                                    <td class="text-center">
                                        @if($m->deleted_at)
                                        {{ $m->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restorerate', base64_encode($m->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $m->material_type }}', '{{ route('restorerate', base64_encode($m->id)) }}')">
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No trashed rate found.</td>
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
    let trashedRates = @json($trashedRates);
</script>

<script>
    function confirmRestore(name, url) {
        let exists = trashedRates.some(mt => mt.rates === name && mt.deleted_at === null && mt.is_active == 1);

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