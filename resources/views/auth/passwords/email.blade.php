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
                             <p class="text-muted small">Forget to continue to Precise Eng.</p>
                         </div>
                         <div class="p-2 mt-4">


                             <form method="POST" action="{{ route('forgot.password.update') }}">
                                 @csrf

      
                                 <div class="mb-3">
                                     <label>Email</label>
                                     <input type="email" name="email" class="form-control">
                                     @error('email') <small class="text-red">{{ $message }}</small> @enderror
                                 </div>

                                 <div class="mb-3">
                                     <label>New Password</label>
                                     <input type="password" name="password" class="form-control">
                                     @error('password') <small class="text-red">{{ $message }}</small> @enderror
                                 </div>

                                 <div class="mb-3">
                                     <label>Confirm Password</label>
                                     <input type="password" name="password_confirmation" class="form-control">
                                 </div>

                                 <button class="btn btn-success w-100">Update Password</button>
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