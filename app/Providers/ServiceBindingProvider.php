<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Api\SendMessageApiServiceInterface;
use App\Services\Api\Implements\RapiwhaApiService;
use App\Services\Api\GoogleDriveServiceInterface;
use App\Services\Api\Implements\GoogleDriveService;
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
    $this->app->singleton(
      SendMessageApiServiceInterface::class,
      RapiwhaApiService::class
    );
    $this->app->singleton(GoogleDriveServiceInterface::class, GoogleDriveService::class);
  }

  protected function bindInterfaces(): void
  {
    $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
  }

  public function boot(): void
  {
    //
  }
}
