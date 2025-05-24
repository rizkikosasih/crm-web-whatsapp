<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::post('/logout', function () {
  Auth::logout();
  session()->invalidate();
  session()->regenerateToken();
  return redirect('/');
})
  ->middleware('auth')
  ->name('logout');

Route::middleware(['auth', 'role'])->group(function () {
  Route::get('dashboard', App\Livewire\Dashboard::class)->name('dashboard');

  Route::prefix('customer')->group(function () {
    Route::get('/', App\Livewire\Customer\Index::class)->name('customer');
  });

  Route::prefix('product')->group(function () {
    Route::get('/', App\Livewire\Product\Index::class)->name('product');
  });

  Route::prefix('order')->group(function () {
    Route::get('/', App\Livewire\Order\Index::class)->name('order');
    Route::get('create', App\Livewire\Order\Create::class)->name('order-create');
    Route::get('detail/{id}', App\Livewire\Order\Detail::class)
      ->where('id', '[0-9]+')
      ->name('order-detail');
  });

  Route::prefix('message')->group(function () {
    Route::get('template', App\Livewire\MessageTemplate\Index::class)->name(
      'message-template'
    );
    Route::get('out', App\Livewire\Message\Index::class)->name('message-out');
  });

  Route::prefix('report')->group(function () {});
  Route::prefix('settings')->group(function () {});
});
