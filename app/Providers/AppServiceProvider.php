<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
            //  // Share the companies data with all views
            //  View::composer('*', function ($view) {
            //     $user = Auth::user(); // Ambil user yang sedang login
            //     $companies = $user ? $user->companies : collect(); // Ambil companies jika user login, jika tidak, kembalikan collection kosong
            //     $view->with('companies', $companies);
            // });
    }
}
