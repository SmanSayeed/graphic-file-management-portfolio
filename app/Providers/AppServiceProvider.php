<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Set the login route for authentication
        if (method_exists($this->app['config'], 'set')) {
            $this->app['config']->set('auth.passwords.users.email', 'auth.emails.password');
        }
    }
}
