@extends('layouts.header')
@section('content')
 
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
 
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trash Material Types</h5>
                    <a href="{{ route('AddMaterialType') }}" class="btn btn-primary btn-sm">← Back to Material Type</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Material Type</th>
                                    <th>Material Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materialtypes as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $m->material_type }}</td>
                                    <td>₹ {{ number_format($m->material_rate, 2) }}</td>
                                    <td>
                                        <a href="{{ route('restoreMaterialType', base64_encode($m->id)) }}"
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Restore this Material Type?')">
                                           Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No Material Types in Trash.</td>
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
 
@endsection