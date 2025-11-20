@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($machine) ? 'Edit Machine' : 'Add Machine' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($machine) ? route('updateMachine', base64_encode($machine->id)) : route('storeMachine') }}" method="POST">
                        @csrf
                        @if(isset($machine))
                        @method('PUT')
                        @endif

                        <div class="row align-items-end">
                            <div class="col-md-4 col-sm-6 mb-3 position-relative">
                                <label for="machine_name" class="form-label">
                                    Machine Name <span class="mandatory"> *</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="machine_name"
                                    name="machine_name"
                                    value="{{ old('machine_name', isset($machine) ? $machine->machine_name : '') }}"
                                    placeholder="Enter Machine Name"
                                    style="background-image: none !important;"
                                    onkeypress="return /[a-zA-Z\s]/.test(event.key)">

                                @error('machine_name')
                                <small class="text-red position-absolute" style="bottom:-18px; left:2px; font-size:12px; margin-left:10px;">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($machine) ? 'Update ' : 'Add ' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Form End -->

            <!-- List Start -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Machine List</h5>
                    <a href="{{ route('trashmachine') }}" class="btn btn-warning btn-sm">
                        View Trash
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered align-middle text-center" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sr.No</th>
                                    <th style="width: 50%;">Machine Name</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($machines as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $m->machine_name }}</td>
                                    <td>
                                        <form action="{{ route('machine.updateStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $m->id }}">
                                            <div class="form-check form-switch">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $m->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $m->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>

                                    <td>
                                        <a href="{{ route('editMachine', base64_encode($m->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                        <a href="{{route('deleteMachine', base64_encode($m->id))}}"
                                            onclick="return confirm('Are you sure you want to delete this record?')">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No machines found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- List End -->

        </div>
    </div>
</div>

@endsection