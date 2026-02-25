<?php

namespace App\Providers;

use App\Authorization\FeatureGate;
use App\Authorization\InviteGate;
use App\Authorization\ProjectGate;
use App\Authorization\StepGate;
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
        StepGate::register();
        InviteGate::register();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
