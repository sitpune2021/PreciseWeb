<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
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

    /**
     * After login success event
     */
    protected function authenticated($request, $user)
    {
        // Client record
        $client = Client::where('login_id', $user->id)->first();

        // If no client found → skip
        if (!$client) {
            return redirect($this->redirectTo);
        }

        // If plan_type is null → assign default plan
        if ($client->plan_type == null) {

            $client->plan_type = 1; // Default Plan ID
            $client->trial_start = Carbon::now();
            $client->trial_end = Carbon::now()->addDays(7);
            $client->save();
        }

        return redirect($this->redirectTo);
    }
}
