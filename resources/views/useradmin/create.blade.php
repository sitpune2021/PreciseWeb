@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 flex-grow-1">
                        {{ isset($user) ? 'Edit User' : 'Add User' }}
                    </h4>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ isset($user) ? route('UpdateUserAdmin', $user->id) : route('StoreUser') }}" method="POST">
                        @csrf

                        @if(isset($user))
                        @method('PUT')
                        @endif
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="full_name" class="form-label">Full Name <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    placeholder="Enter full name"
                                    value="{{ old('full_name', $user->full_name ?? '') }}">
                                @error('full_name')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-3">
                                <label class="form-label">Email<span class="text-red">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email ?? '') }}"
                                    placeholder="Enter email">
                                @error('email')
                                <div class="text-red small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username (Auto from Email) -->
                            <div class="col-md-4">
                                <label class="form-label">Username<span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="user_name" name="user_name"
                                    value="{{ old('user_name', $user->user_name ?? '') }}"
                                    placeholder="Username auto">
                                @error('user_name')
                                <div class="text-red small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mobile -->
                            <div class="col-md-3">
                                <label for="mobile" class="form-label">Phone No <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                    placeholder="Enter phone number"
                                    value="{{ old('mobile', $user->mobile ?? '') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)">
                                @error('mobile')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label>Role<span class="text-red">*</span></label>
                                <select name="role" class="form-select">
                                    <option value="">Select Role</option>

                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ (int) old('role', $user->role ?? 0) === (int) $role->id ? 'selected' : '' }}
                                        {{ $role->id == 1 ? 'disabled' : '' }}>
                                        {{ $role->name }}
                                    </option>

                                    @endforeach
                                </select>

                                @error('role')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(!isset($user))
                            <div class="col-md-3">
                                <label for="password" class="form-label">Password <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        placeholder="Enter password">

                                    <span class="input-group-text" onclick="togglePassword('password', this)">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirm password">

                                    <span class="input-group-text" onclick="togglePassword('password_confirmation', this)">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success">Save User</button>
                            <a href="{{ route('ListUserAdmin') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
    function togglePassword(fieldId, iconSpan) {
        let field = document.getElementById(fieldId);

        if (field.type === "password") {
            field.type = "text";
            iconSpan.querySelector('i').classList.remove('fa-eye');
            iconSpan.querySelector('i').classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            iconSpan.querySelector('i').classList.remove('fa-eye-slash');
            iconSpan.querySelector('i').classList.add('fa-eye');
        }
    }

    document.getElementById('email').addEventListener('input', function() {
        let email = this.value;

        if (email.includes('@')) {
            document.getElementById('user_name').value = email;
        }
    });

    document.getElementById('password').addEventListener('input', function() {
        document.getElementById('password_confirmation').value = this.value;
    });
</script>

@endsection