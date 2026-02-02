<?php

namespace App\Providers;

use App\Models\Step;
use App\Observers\StepObserver;
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
        Step::observe(StepObserver::class);
    }
}
