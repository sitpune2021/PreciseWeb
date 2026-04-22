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

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    //  Check Status before login
    // protected function attemptLogin($request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return false;
    //     }

    //     if ($user->user_type == 2) {
    //         $client = Client::where('login_id', $user->id)->first();

    //         if ($client && $client->status == 0) {
    //             return false;
    //         }
    //     }

    //     return Auth::attempt($this->credentials($request), $request->filled('remember'));
    // }

    protected function attemptLogin($request)
    {
        $login = $request->input('login');

        // check email or mobile
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $user = User::where($field, $login)->first();

        if (!$user) {
            return false;
        }

        // status check same 
        if ($user->user_type == 2) {
            $client = Client::where('login_id', $user->id)->first();

            if ($client && $client->status == 0) {
                return false;
            }
        }

        return Auth::attempt([
            $field => $login,
            'password' => $request->password
        ], $request->filled('remember'));
    }

    //  Show proper erroryt
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
            'login' => 'Invalid login credentials.'
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

    protected function loggedOut($request)
    {
        return redirect('/login');
    }

    public function username()
    {
        return 'login';
    }
}
