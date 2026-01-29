@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @if(session('success'))
            <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1055;">
                <div id="successAlert"
                    class="alert alert-success alert-dismissible fade show py-2 px-3 shadow-sm text-center"
                    style="max-width:500px;">
                    {{ session('success') }}
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">View Users</h5>
                    <a href="{{ route('AddUserAdmin') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line"></i> Add User
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Role</th>
                                    <th>Photo</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile }}</td>

                                    <!-- ROLE -->
                                    <td>
                                        @php
                                        $roles = [1=>'Super Admin',2=>'Admin',3=>'Programmer',4=>'Supervisor',5=>'Finance'];
                                        @endphp
                                        {{ $roles[$user->user_type] ?? 'N/A' }}
                                    </td>

                                    <!-- IMAGE -->
                                    <td class="text-center">
                                        @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                            height="40">
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        <form action="{{ route('updateStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $user->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>

                                    <!-- ACTION -->
                                    <td class="text-center">
                                        <a href="{{ route('EditUserAdmin', base64_encode($user->id)) }}"
                                            class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection