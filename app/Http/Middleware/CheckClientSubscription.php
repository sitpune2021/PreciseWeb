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

        if (!$user) return redirect()->route('login');

        $client = Client::where('login_id', $user->id)->first();

        if ($client) {
            $today = Carbon::today();
            $expiry = Carbon::parse($client->plan_expiry);

            $remaining = $today->diffInDays($expiry, false);
            $remaining = round($remaining);

            $expAlert = "";

            if ($remaining == 5) {

                $value = session('click');
                if ($value = "") {
                    session(['click' => 1]);


                    $expAlert = "⚠️ Your plan will expire in 5 days!";
                }
            } elseif ($remaining == 3) {
                $expAlert = "⚠️ Your plan will expire in 3 days!";
            } elseif ($remaining < 0) {

                $client->update([
                    'status' => 0
                ]);
                session()->forget('click');

                Auth::logout();

                return redirect()->route('login')
                    ->with('error', '❌ Your plan has expired. Please renew your subscription.');
            }


            // Send alert message to session
            if ($expAlert) {
                $request->session()->flash('plan_alert', $expAlert);
            }
        }

        return $next($request);
    }
}
