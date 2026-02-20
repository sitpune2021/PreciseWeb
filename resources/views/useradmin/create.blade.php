@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center">

                    <!-- Back Button ONLY on Edit -->
                    <a href="{{ route('ListUserAdmin') }}" class="btn btn-sm btn-outline-success me-2">
                        ← 
                    </a>

                    <h4 class="mb-0 flex-grow-1">
                        {{ $mode === 'edit' ? 'Edit User' : 'Add User' }}
                    </h4>
                </div>

                <div class="card-body">

                    <form method="POST"
                        action="{{ $mode === 'edit' ? route('UpdateUserAdmin', $user->id) : route('StoreUser') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @if($mode === 'edit')
                        @method('PUT')
                        @endif

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label>Name <span class="text-red">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name ?? '') }}">
                                @error('name')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Email <span class="text-red">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                <div class="text-red small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Username <span class="text-red">*</span></label>
                                <input type="text" name="username" id="username" class="form-control"
                                    value="{{ old('username', $user->username ?? '') }}">
                                @error('username')
                                <div class="text-red small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Mobile <span class="text-red">*</span></label>
                                <input type="text" name="mobile" class="form-control"
                                    maxlength="10" pattern="[0-9]{10}"
                                    value="{{ old('mobile', $user->mobile ?? '') }}">
                                @error('mobile')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Role <span class="text-red">*</span></label>
                                <select name="user_type" class="form-select">
                                    <option value="">Select</option>
                                    @foreach([1=>'Super Admin',2=>'Admin',3=>'Programmer',4=>'Supervisor',5=>'Finance'] as $k=>$v)
                                    <option value="{{ $k }}"
                                        {{ old('user_type',$user->user_type ?? '')==$k?'selected':'' }}>
                                        {{ $v }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_type')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Status <span class="text-red">*</span></label>

                                <select name="status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ old('status', $user->status ?? '') == 1 ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('status', $user->status ?? '') == 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>

                                @error('status')
                                <div class="text-red small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control">

                                @if($user->profile_photo)
                                <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                    height="70"
                                    class="mt-2 rounded">
                                @endif

                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button class="btn btn-success">Save</button>
                            <a href="{{ route('ListUserAdmin') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const emailInput = document.getElementById('email');
        const usernameInput = document.getElementById('username');

        if (emailInput && usernameInput) {

            emailInput.addEventListener('input', function() {

                let emailValue = this.value.trim();

                if (emailValue.includes('@')) {
                    usernameInput.value = emailValue;
                } else {
                    usernameInput.value = '';
                }

            });

        }

    });
</script>



@endsection