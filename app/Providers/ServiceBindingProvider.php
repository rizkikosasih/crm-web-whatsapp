<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Api\SendMessageApiServiceInterface;
use App\Services\Api\Implements\RapiwhaApiService;
use App\Services\Api\GoogleDriveServiceInterface;
use App\Services\Api\Implements\GoogleDriveService;

class ServiceBindingProvider extends ServiceProvider
{
  public function register(): void
  {
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

  public function boot(): void
  {
    //
  }
}
