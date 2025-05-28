<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
  #[Validate('required', message: 'username tidak boleh kosong')]
  public $username;

  #[Validate('required', message: 'password tidak boleh kosong')]
  public $password;

  #[Layout('layouts.auth')]
  public function render()
  {
    return view('livewire.auth.login');
  }

  public function doLogin()
  {
    $this->validate();

    $fieldType = filter_var($this->username, 274) ? 'email' : 'username';

    $credentials = [$fieldType => $this->username, 'password' => $this->password];

    if (Auth::attempt($credentials)) {
      $user = Auth::user();
      if (!$user->is_active) {
        Auth::logout();
        session()->flash('error', 'Akun Anda belum aktif.');
        return redirect(route('/'), true);
      }

      session()->regenerate();
      session()->flash('message', 'Login successful.');

      return $this->redirect(route('dashboard'), true);
    } else {
      session()->flash('error', 'Username / Password Tidak Ditemukan.');
    }
  }
}
