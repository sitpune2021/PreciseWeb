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
                    <h5 class="mb-0">Trash Material Types</h5>
                    <a href="{{ route('AddMaterialType') }}" class="btn btn-primary btn-sm">← Back to Material Type</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Material Type</th>
                                    <th class="text-center">Material Gravity</th>
                                    <th class="text-center">Material Rate</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedmaterialtypes as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $m->material_type }}</td>
                                    <td class="text-center">{{ $m->material_gravity }}</td>
                                    <td class="text-center">₹ {{ number_format($m->material_rate, 2) }}</td>
                                    <td class="text-center">
                                        @if($m->deleted_at)
                                        {{ $m->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreMaterialType', base64_encode($m->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $m->material_type }}', '{{ route('restoreMaterialType', base64_encode($m->id)) }}')">
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No trashed material type found.</td>
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
    let materialtypes = @json($materialtypes);
</script>

<script>
    function confirmRestore(name, url) {
        let exists = materialtypes.some(mt => mt.material_type === name && mt.deleted_at === null && mt.is_active == 1);

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