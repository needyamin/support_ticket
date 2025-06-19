<?php

namespace App\Providers;

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
        // Publish the ticket CSS file
        $this->publishes([
            resource_path('css/ticket.css') => public_path('css/ticket.css'),
        ], 'public');
    }
}
