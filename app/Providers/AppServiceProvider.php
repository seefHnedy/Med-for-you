<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        Passport::tokensExpireIn(now()->addHours(2));

        Passport::tokensCan([
            'User' => 'User Access',
            'Admin' => 'Admin Access',
            'Pharmacy' => 'Pharmacy Access',
        ]);
    }
}
