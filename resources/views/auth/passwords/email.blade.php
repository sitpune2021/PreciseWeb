 


@extends('layouts.auth')

@section('content')

<div class="auth-page-content">
    <div class="container">

  <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4 card-bg-fill">

                    <div class="card-body p-4">
                        <div class="text-center mt-2 mb-4">
                            <a href="login" class="d-inline-block mb-3">
                                <!-- <img src="{{ asset('assets/images/precise.png')}}" alt="Logo" height="50"> -->
                            </a>
                            <h5 class="fw-bold text-dark">Welcome Back</h5>
                            <p class="text-muted small">Sign in to continue to Precise Eng.</p>
                        </div>
                        <div class="p-2 mt-4">


                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username<span class="mandatory">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="mb-3">
                                    <div class="float-end">
                                        @if (Route::has('password.request'))
                                        <a class="text-muted" href="{{ route('password.request') }}">Forgot
                                            Password ?</a>
                                        @endif
                                    </div>
                                    <label class="form-label" for="password-input">Password<span class="mandatory">*</span></label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input id="password" type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-success w-100" type="submit">Sign In</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->


            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end auth page content -->


@endsection
