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
        $this->app->bind(
            \App\Services\Contracts\InvoiceServiceInterface::class,
            \App\Services\InvoiceService::class,
        );

        $this->app->bind(
            \App\Services\Api\SendMessageApiServiceInterface::class,
            \App\Services\Api\Implements\EvolutionApiService::class,
        );

        $this->app->bind(
            \App\Services\Api\ImagekitServiceInterface::class,
            \App\Services\Api\Implements\ImagekitService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
