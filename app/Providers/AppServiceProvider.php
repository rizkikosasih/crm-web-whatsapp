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
    $this->bindInterfaces();
  }

  protected function bindInterfaces(): void
  {
    $this->app->bind(
      \App\Services\Api\SendMessageApiServiceInterface::class,
      \App\Services\Api\Implements\RapiwhaApiService::class
    );

    $this->app->bind(
      \App\Services\Api\ImageKitServiceInterface::class,
      \App\Services\Api\Implements\ImageKitService::class
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
