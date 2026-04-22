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

                             <!-- SUCCESS MESSAGE -->
                             @if(session('status'))
                             <div class="alert alert-success">
                                 {{ session('status') }}
                             </div>
                             @endif

                             <!-- ERROR MESSAGE -->
                             @if($errors->any())
                             <div class="alert alert-danger">
                                 {{ $errors->first() }}
                             </div>
                             @endif

                             <!-- LOGIN FORM -->
                             <form method="POST" action="{{ route('login') }}">
                                 @csrf

                                 <div class="mb-3">
                                     <label class="form-label">Email</label>
                                     <input type="email"
                                         name="email"
                                         class="form-control"
                                         value="{{ old('email') }}"
                                         placeholder="Enter email"
                                         required>
                                 </div>

                                 <div class="mb-3">
                                     <div class="float-end">
                                         <a class="text-muted" href="{{ route('password.request') }}">
                                             Forgot Password ?
                                         </a>
                                     </div>

                                     <label class="form-label">Password</label>

                                     <div class="position-relative">
                                         <input type="password"
                                             name="password"
                                             id="password"
                                             class="form-control"
                                             placeholder="Enter password"
                                             required>

                                         <span class="position-absolute end-0 top-50 translate-middle-y me-3"
                                             onclick="togglePassword()"
                                             style="cursor:pointer;">
                                             👁
                                         </span>
                                     </div>
                                 </div>

                                 <div class="form-check mb-3">
                                     <input type="checkbox" name="remember" class="form-check-input">
                                     <label class="form-check-label">Remember me</label>
                                 </div>

                                 <button class="btn btn-success w-100">
                                     Sign In
                                 </button>
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