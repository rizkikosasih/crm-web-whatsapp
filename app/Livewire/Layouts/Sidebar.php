<?php

namespace App\Livewire\Layouts;
use App\Models\Menu;
use Livewire\Component;

class Sidebar extends Component
{
  public $menus;

  public function mount()
  {
    $user = auth()->user();
    $roleId = $user->role_id; // Misalkan role user disimpan di kolom `role_id`

    // Ambil menu berdasarkan role
    $this->menus = Menu::with([
      'children' => function ($query) use ($roleId) {
        $query
          ->active()
          ->notDelete()
          ->whereHas('roles', function ($q) use ($roleId) {
            $q->where('role_id', $roleId);
          })
          ->orderBy('position', 'asc');
      },
    ])
      ->active()
      ->notDelete()
      ->whereNull('parent_id')
      ->whereHas('roles', function ($query) use ($roleId) {
        $query->where('role_id', $roleId);
      })
      ->orderBy('position', 'asc')
      ->get();
  }

  public function render()
  {
    return view('partials.sidebar');
  }
}
