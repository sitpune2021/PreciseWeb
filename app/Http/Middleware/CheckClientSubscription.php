<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Client;

class CheckClientSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $client = Client::where('login_id', $user->id)->first();
        if (!$client) {
            return $next($request);
        }

        $today = Carbon::today();
        $expiry = Carbon::parse($client->plan_expiry);

        $remaining = $today->diffInDays($expiry, false); // remaining days

        //  Account inactive check
        if ($client->status == 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Contact admin.');
        }

        // Trial expiry notifications (3 days left)
        if ($remaining == 3) {
            $request->session()->flash('plan_alert', "Your plan will expire in 3 days! Please renew.");
        }
        $request->session()->flash('plan_alert', "Remaining days: $remaining");

        //  Plan expired logic
        if ($remaining < 0) {
            if ($client->status == 1) {
                // admin already toggled ON, allow login
                // optional: auto extend plan to allow login
                // $client->plan_expiry = $today->copy()->addDays(7);
                // $client->save();
                return $next($request);
            } else {
                // plan expired & status off -> logout
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Your plan has expired. Please renew.');
            }
        }

        return $next($request);
    }
}


