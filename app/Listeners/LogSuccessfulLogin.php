<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;
use App\Models\User;

class LogSuccessfulLogin
{
  /**
   * Handle the event.
   *
   * @param Login $event
   * @return void
   */
  public function handle(Login $event)
  {
    /** @var User $user */
    $user = $event->user;

    $user->last_login_at = now();
    $user->last_login_ip = Request::ip();
    $user->save(); // Tidak akan muncul error lagi
  }
}
