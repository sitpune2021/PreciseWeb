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
                    <h5 class="mb-0">Trash Machine Records</h5>
                    <a href="{{ route('ViewMachinerecord') }}" class="btn btn-primary btn-sm">← Back to Machine Records</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Work Order</th>
                                    <th class="text-center">Operator</th>
                                    <th class="text-center">Setting No</th>
                                    <th class="text-center">Material</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Start Time</th>
                                    <th class="text-center">End Time</th>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedMachines as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $m->part_no }}</td>
                                    <td>{{ $m->code }}</td>
                                    <td>{{ $m->work_order }}</td>
                                    <td>{{ $m->operator }}</td>
                                    <td>{{ $m->setting_no }}</td>
                                    <td>{{ $m->material }}</td>
                                    <td>{{ $m->qty }}</td>
                                    <td>{{ $m->start_time ? \Carbon\Carbon::parse($m->start_time)->format('d-m-Y h:i A') : '' }}</td>
                                    <td>{{ $m->end_time ? \Carbon\Carbon::parse($m->end_time)->format('d-m-Y h:i A') : '' }}</td>
                                    <td>{{ $m->invoice_no }}</td>

                                    <td class="text-center">
                                        @if($m->deleted_at)
                                        {{ $m->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreMachineRecord', base64_encode($m->id)) }}"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirmRestore('{{ $m->machine_name }}', '{{ route('restoreMachineRecord', base64_encode($m->id)) }}')">
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No trashed machine records found.</td>
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
    let Machines = @json($machines);
</script>
<script>
    function confirmRestore(name, url) {
        // Check if duplicate exists
        let exists = Machines.some(m => m.machine_name === name && m.deleted_at === null);

        let message;
        if (exists) {
            // Duplicate exists → show message about redirecting to edit page
            message = "Machine record '" + name + "' already exists.\nYou will be redirected to the Edit Page.\nDo you want to continue?";
        } else {
            // No duplicate → normal restore confirmation
            message = "already exists '" + name + "'?\nYou will be redirected to the Edit Page after restore.";
        }

        if (confirm(message)) {
            window.location.href = url;
        }
        return false;
    }
</script>



@endsection