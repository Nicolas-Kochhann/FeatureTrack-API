<?php

namespace App\Providers;

use App\Authorization\FeatureGate;
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
        FeatureGate::register();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
