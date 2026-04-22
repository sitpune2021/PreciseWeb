@extends('layouts.auth')

@section('content')

<div class="auth-page-content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                <div class="card mt-4 card-bg-fill">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h5 class="fw-bold text-dark">Reset Password</h5>
                            <p class="text-muted small">Enter new password</p>
                        </div>

                        <!-- ERROR -->
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email"
                                    name="email"
                                    value="{{ $email }}"
                                    class="form-control"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required>
                            </div>

                            <button class="btn btn-success w-100">
                                Reset Password
                            </button>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}">Back to Login</a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection