<?php

namespace App\Livewire\Role;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

class Access extends Component
{
  use WithPagination;

  #[Locked]
  public $title = 'Akses Menu';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Parent'],
    ['name' => 'Nama'],
    ['name' => 'Icon'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  #[Locked]
  public $roleId;
  public $roleName;

  public $search = '';
  public $perPage = 10;

  public function mount($id)
  {
    $role = Role::findOrFail($id);
    $this->roleId = $role->id;
    $this->roleName = $role->name;
  }

  public function toggleMenuAccess(int $menuId)
  {
    $role = Role::findOrFail($this->roleId);
    $hasAccess = $role->menus()->where('menus.id', $menuId)->exists();

    if ($hasAccess) {
      $role->menus()->detach($menuId);
      $this->dispatch('showSuccess', message: 'Akses menu berhasil dihapus');
    } else {
      $role->menus()->attach($menuId);
      $this->dispatch('showSuccess', message: 'Akses menu berhasil ditambahkan');
    }
  }

  public function render()
  {
    $items = Menu::with('parent')
      ->when($this->search, function ($q) {
        $q->where('name', 'like', '%' . $this->search . '%');
      })
      ->select([
        'menus.*',
        DB::raw(
          "EXISTS (SELECT 1 FROM menu_roles WHERE menu_roles.menu_id = menus.id AND menu_roles.role_id = $this->roleId ) as is_assigned"
        ),
      ])
      ->orderByRaw('COALESCE(parent_id, 0) ASC')
      ->orderBy('position')
      ->paginate($this->perPage);

    return view('livewire.role.access', compact('items'));
  }
}
