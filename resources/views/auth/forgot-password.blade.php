@extends('layouts.auth')

@section('content')

<div class="auth-page-content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                <div class="card mt-4 card-bg-fill">
                    <div class="card-body p-4">

                        <div class="text-center mt-2 mb-4">
                            <h5 class="fw-bold text-dark">Forgot Password</h5>
                            <p class="text-muted small">Enter your email to reset password</p>
                        </div>

                        <!-- SUCCESS -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- ERROR -->
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="Enter your email"
                                       required>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary w-100">
                                    Send Reset Link
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted">
                                    Back to Login
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection