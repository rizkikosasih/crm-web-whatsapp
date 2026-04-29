<?php

namespace App\Providers;

use App\Services\Api\ImagekitServiceInterface;
use App\Services\Api\Implements\ImagekitService;
use Illuminate\Support\ServiceProvider;
use App\Services\Api\SendMessageApiServiceInterface;
use App\Services\Api\Implements\RapiwhaApiService;
use App\Services\Contracts\InvoiceServiceInterface;
use App\Services\InvoiceService;

class ServiceBindingProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindInterfaces();
        $this->singletonInterfaces();
    }

    protected function singletonInterfaces(): void
    {
        $this->app->singleton(SendMessageApiServiceInterface::class, RapiwhaApiService::class);
        $this->app->singleton(ImagekitServiceInterface::class, ImagekitService::class);
        $this->app->singleton(InvoiceServiceInterface::class, InvoiceService::class);
    }

    protected function bindInterfaces(): void
    {
    }

    public function boot(): void
    {
        //
    }
}
