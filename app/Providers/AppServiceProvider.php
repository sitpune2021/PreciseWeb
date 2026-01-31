<?php

namespace App\Providers;

use App\Models\AdminSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        view()->composer('*', function ($view) {

            if (Auth::check()) {

                $client = Client::with('plan')
                    ->where('login_id', Auth::id())
                    ->first();
            } else {
                $client = null;
            }

            $view->with('clientData', $client);

            View::composer('*', function ($view) {
                $view->with(
                    'adminSetting',
                    Auth::check()
                        ? AdminSetting::where('admin_id', Auth::id())->first()
                        : null
                );
            });
        });


        //
    }
}
