@extends('layouts.header')
@section('content')
 
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
 
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
 
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trashed Financial Years</h5>
                    <a href="{{ route('AddFinancialYear') }}" class="btn btn-primary btn-sm">← Back to Financial Years</a>
                </div>
 
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Year</th>
                                    <th>Status</th>
                                    <th>Deleted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedFinancialYears as $fy)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $fy->year }}</td>
                                        <td>{{ $fy->status == 1 ? 'Active' : 'Inactive' }}</td>
                                        <td>{{ $fy->deleted_at ? $fy->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') : '—' }}</td>
                                        <td>
                                            <a href="{{ route('restoreFinancial', base64_encode($fy->id)) }}"
                                               class="btn btn-success btn-sm"
                                               onclick="return confirmRestore('{{ $fy->year }}', '{{ route('restoreFinancial', base64_encode($fy->id)) }}', {{ $fy->id }})">
                                                Restore
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No trashed financial years found.</td>
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
 
{{-- JS for confirm + duplicate check --}}
<script>
    let FinancialYears = @json($financialYears); // Active main list
 
    function confirmRestore(name, url, id){
        // Duplicate check in main list
        let exists = FinancialYears.some(fy => fy.year === name && fy.id != id);
 
        let message;
        if(exists){
            message = `'${name}' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?`;
        } else {
            message = `Do you want to restore '${name}'?`;
        }
 
        if(confirm(message)){
            window.location.href = url;
        }
        return false;
    }
</script>
 
@endsection