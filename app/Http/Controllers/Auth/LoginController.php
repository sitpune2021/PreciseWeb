<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    //  Check Status before login
    protected function attemptLogin($request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return false;
        }

        if ($user->user_type == 2) {
            $client = Client::where('login_id', $user->id)->first();

            if ($client && $client->status == 0) {
                return false;
            }
        }

        return Auth::attempt($this->credentials($request), $request->filled('remember'));
    }

    //  Show proper error
    protected function sendFailedLoginResponse($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && $user->user_type == 2) {
            $client = Client::where('login_id', $user->id)->first();

            if ($client && $client->status == 0) {
                return back()->withErrors([
                    'email' => 'Your account is deactivated.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ]);
    }

    // After login success
    protected function authenticated($request, $user)
    {
        $client = Client::where('login_id', $user->id)->first();

        if (!$client) {
            return redirect($this->redirectTo);
        }

        if ($client->plan_type == null) {
            $client->plan_type = 1;
            $client->trial_start = Carbon::now();
            $client->trial_end = Carbon::now()->addDays(7);
            $client->save();
        }

        return redirect($this->redirectTo);
    }
}
