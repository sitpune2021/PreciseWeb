 @extends('layouts.auth')

 @section('content')

 <div class="auth-page-content">
     <div class="container">

         <!-- end row -->

         <div class="row justify-content-center">
             <div class="col-md-8">
                 <div class="card">
                     <div class="card-header">{{ __('Reset Password') }}</div>

                     <div class="card-body">
                         <form method="POST" action="{{ route('password.update') }}">
                             @csrf

                             <input type="hidden" name="token" value="{{ $token }}">

                             <input type="email" name="email" value="{{ request()->email }}" required>

                             <input type="password" name="password" placeholder="New Password" required>

                             <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

                             <button type="submit">Reset Password</button>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
         <!-- end row -->
     </div>
     <!-- end container -->
 </div>
 <!-- end auth page content -->


 @endsection