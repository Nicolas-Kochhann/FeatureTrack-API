<?php

namespace App\Providers;

use App\Authorization\ProjectGate;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        ProjectGate::register();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
