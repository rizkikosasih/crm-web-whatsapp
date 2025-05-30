<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route Fallback */
Route::fallback(function () {
  return abort(404);
});

Route::middleware(['guest'])->group(function () {
  Route::get('/', App\Livewire\Auth\Login::class)->name('index');
  Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
});

Route::middleware(['auth', 'role'])->group(function () {
  Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
  })->name('logout');

  Route::get('dashboard', App\Livewire\Dashboard::class)->name('dashboard');

  Route::prefix('master')->group(function () {
    Route::get('customer', App\Livewire\Customer\Index::class)->name('master-customer');

    Route::get('product', App\Livewire\Product\Index::class)->name('master-product');

    Route::get('campaign', App\Livewire\Campaign\Index::class)->name('master-campaign');
  });

  Route::prefix('transaksi')->group(function () {
    Route::prefix('order')->group(function () {
      Route::get('/', App\Livewire\Order\Index::class)->name('transaksi-order');
      Route::get('create', App\Livewire\Order\Create::class)->name(
        'transaksi-order-create'
      );
      Route::get('detail/{id}', App\Livewire\Order\Detail::class)
        ->where('id', '[0-9]+')
        ->name('transaksi-order-detail');
    });
  });

  Route::prefix('report')->group(function () {
    Route::get('order', App\Livewire\Report\Order::class)->name('report-order');
    Route::get('product', App\Livewire\Report\Product::class)->name('report-product');
  });

  Route::prefix('setting')->group(function () {
    Route::get('template', App\Livewire\MessageTemplate\Index::class)->name(
      'setting-template'
    );

    Route::get('whatsapp-api', App\Livewire\WhatsappApiSetting\Index::class)->name(
      'setting-whatsapp-api'
    );

    Route::prefix('user')->group(function () {
      Route::get('/', App\Livewire\User\Index::class)->name('setting-user');
      Route::get('profile', App\Livewire\User\Profile::class)->name(
        'setting-user-profile'
      );
    });

    Route::get('menu', App\Livewire\Menu\Index::class)->name('setting-menu');
  });
});
