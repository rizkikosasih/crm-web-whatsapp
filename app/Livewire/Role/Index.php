<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Locked]
    public $title = 'Daftar Role';

    #[Locked]
    public $tableHeader = [
        ['name' => 'No'],
        ['name' => 'Nama'],
        ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
    ];

    public $isEdit = false;

    public $search = '';
    public $perPage = 10;

    public $currentId;
    #[Validate('required', message: 'Nama tidak boleh kosong')]
    public $name;

    public function save(RoleService $roleService)
    {
        $this->validate();

        $roleService->save(['name' => $this->name], $this->currentId);

        session()->flash('success', 'Role Pengguna Berhasil Disimpan.');
        $this->resetForm();
    }

    public function edit($id, RoleService $roleService)
    {
        $role = $roleService->find($id);

        $this->currentId = $role->id;
        $this->name = $role->name;
        $this->isEdit = true;
    }

    public function resetForm()
    {
        $this->reset(['currentId', 'name', 'isEdit']);
        $this->dispatch('clearError');
    }

    public function render(RoleService $roleService)
    {
        $items = $roleService->getPaginated($this->perPage, $this->search);

        return view('livewire.role.index', compact('items'));
    }
}
