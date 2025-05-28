<?php

namespace App\Livewire\Layouts;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
  #[On('refreshNavbar')]
  public function refreshComponent()
  {
    $this->dispatch('$refresh');
  }

  public function render()
  {
    $user = auth()->user();

    return view('partials.navbar', compact('user'));
  }
}
