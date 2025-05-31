<?php

namespace App\Livewire\Role;

use App\Models\Role;
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

  public function save()
  {
    $this->validate();

    Role::updateOrCreate(['id' => $this->currentId], ['name' => $this->name]);

    session()->flash('success', 'Role Pengguna Berhasil Disimpan.');
    $this->resetForm();
  }

  public function edit($id)
  {
    $role = Role::findOrFail($id);

    $this->currentId = $role->id;
    $this->name = $role->name;
  }

  public function resetForm()
  {
    $this->reset(['currentId', 'name', 'isEdit']);
    $this->dispatch('clearError');
  }

  public function render()
  {
    $items = Role::when($this->search, function ($q) {
      $q->where('name', 'like', '%' . $this->search . '%');
    })
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.role.index', compact('items'));
  }
}
