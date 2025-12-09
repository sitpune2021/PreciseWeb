@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">View Users</h5>
                            <a href="{{ route('AddUserAdmin') }}" class="btn btn-success btn-sm">
                                <i class="ri-add-line align-middle"></i> Add User
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</ th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td> <!-- This is the auto-increment number -->

                                            <td>{{ $user->full_name }}</td>
                                            <td>{{ $user->user_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->roles->name }}</td>

                                            <td>
                                                <form action="{{ route('updateStatus') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="statusSwitch{{ $user->id }}"
                                                            name="status"
                                                            onchange="this.form.submit()"
                                                            {{ $user->status == 'Active' ? 'checked' : '' }}>
                                                    </div>
                                                </form>
                                            </td>

                                            <td class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('EditUserAdmin', base64_encode($user->id)) }}" class="btn btn-success btn-sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
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
    </div>
</div>

@endsection