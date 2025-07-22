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
  }

  protected function bindInterfaces(): void
  {
    $this->app->bind(SendMessageApiServiceInterface::class, RapiwhaApiService::class);
    $this->app->bind(ImagekitServiceInterface::class, ImagekitService::class);
    $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
  }

  public function boot(): void
  {
    //
  }
}
