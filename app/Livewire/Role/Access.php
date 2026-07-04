<?php

namespace App\Livewire\Role;

use App\Models\Menu;
use App\Services\RoleService;
use Spatie\Permission\Models\Permission;
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

    public function mount($id, RoleService $roleService)
    {
        $role = $roleService->find($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
    }

    public function toggleMenuAccess(int $menuId, RoleService $roleService)
    {
        $role = $roleService->find($this->roleId);
        $menu = Menu::findOrFail($menuId);

        if (!$menu->permission) {
            return $this->dispatch(
                'showError',
                message: 'Menu ini tidak dikaitkan dengan permission apapun.',
            );
        }

        // Ensure Spatie permission exists
        Permission::findOrCreate($menu->permission, 'web');

        if ($role->hasPermissionTo($menu->permission)) {
            $role->revokePermissionTo($menu->permission);
            $this->dispatch('showSuccess', message: 'Akses menu berhasil dihapus');
        } else {
            $role->givePermissionTo($menu->permission);
            $this->dispatch('showSuccess', message: 'Akses menu berhasil ditambahkan');
        }
    }

    public function render(RoleService $roleService)
    {
        $role = $roleService->find($this->roleId);

        $items = Menu::with('parent')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderByRaw('COALESCE(parent_id, 0) ASC')
            ->orderBy('position')
            ->paginate($this->perPage);

        // Hydrate is_assigned dynamically from Spatie permissions association
        $items->getCollection()->transform(function ($menu) use ($role) {
            $menu->is_assigned = $menu->permission
                ? $role->hasPermissionTo($menu->permission)
                : false;
            return $menu;
        });

        return view('livewire.role.access', compact('items'));
    }
}
