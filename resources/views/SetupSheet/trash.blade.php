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
                    <h5 class="mb-0">Trash Setup Sheets</h5>
                    <a href="{{ route('ViewSetupSheet') }}" class="btn btn-primary btn-sm">← Back to Setup Sheets</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Part Code</th>
                                    <th class="text-center">Work Order<br> No</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Size X</th>
                                    <th class="text-center">Size Y</th>
                                    <th class="text-center">Size Z</th>
                                    <th class="text-center">Setting</th>
                                    <th class="text-center">Deleted At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trashedSheets as $sheet)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                   <td>
                                                @if($sheet->setup_image)
                                                <img src="{{ asset('setup_images/'.$sheet->setup_image) }}"
                                                    alt="Setup Image" style="max-width:60px; height:auto;" class="img-thumbnail">
                                                @else
                                                <span class="text-muted">No Image</span>
                                                @endif
                                            </td>

                                            <td>{{ $sheet->part_code }}</td>
                                            <td>{{ $sheet->work_order_no }}</td>
                                            <td>{{ $sheet->date }}</td>
                                            <td>{{ $sheet->size_in_x }}</td>
                                            <td>{{ $sheet->size_in_y }}</td>
                                            <td>{{ $sheet->size_in_z }}</td>
                                            <td>{{ $sheet->setting }}</td>
                                    <td class="text-center">
                                        @if($sheet->deleted_at)
                                        {{ $sheet->deleted_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('restoreSetupSheet', base64_encode($sheet->id)) }}"
                                            class="btn btn-success btn-sm"
                                           onclick="return confirmRestore('{{ $sheet->part_code }}', '{{ route('restoreSetupSheet', base64_encode($sheet->id)) }}')"
>
                                            Restore
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No trashed setup sheets found.</td>
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

{{-- JS --}}
<script>
    let Sheets = @json($sheets);

    function confirmRestore(name, url) {
       let exists = Sheets.some(sheet => sheet.part_code === name && sheet.deleted_at === null);


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