<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // show forgot form
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // send reset link
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $link = url('/reset-password/' . $token . '?email=' . $request->email);

        // send mail
        Mail::raw("Click here to reset password: $link", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password');
        });

        return back()->with('success', 'Reset link sent to your email');
    }

    // show reset form
    public function resetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid or expired token');
        }

        // update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password reset successfully');
    }
}