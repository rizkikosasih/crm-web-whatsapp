<?php

namespace App\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination;

  #[Locked]
  public $title = 'Data Pengguna';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Nama'],
    ['name' => 'Email'],
    ['name' => 'No Handphone'],
    ['name' => 'Status'],
    ['name' => 'Role'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  public $isEdit = false;

  public $search = '';
  public $perPage = 10;
  public $filterRoles = [];
  public $filterRole;

  public $userId;
  #[Validate('required', message: 'Nama tidak boleh kosong')]
  public $name;
  #[Validate('required', message: 'Username tidak boleh kosong')]
  public $username;
  public $originalUsername;
  #[Validate('required', message: 'No handphone tidak boleh kosong')]
  #[Validate('numeric', message: 'No handphone wajib angka')]
  public $phone;
  public $originalPhone;
  #[Validate('required', message: 'Email tidak boleh kosong')]
  #[Validate('email', message: 'Format email tidak valid')]
  public $email;
  public $originalEmail;
  public $is_active;
  #[Validate('required', message: 'Role tidak boleh kosong')]
  public $role_id;
  public $roleSearch, $selectedRoleName;

  public function mount()
  {
    foreach (Role::all() as $role) {
      $this->filterRoles[$role->id] = $role->name;
    }
  }

  public function selectRole($id, $name)
  {
    $this->role_id = $id;
    $this->selectedRoleName = $name;
    $this->roleSearch = $name;
  }

  public function save()
  {
    $this->validate();

    $fields = [
      'phone' => $this->originalPhone,
      'email' => $this->originalEmail,
      'username' => $this->originalUsername,
    ];

    $rules = [];
    $messages = [];

    foreach ($fields as $field => $originalValue) {
      if ($this->{$field} !== $originalValue) {
        $rules[$field] = 'unique:users,' . $field;
        $messages["$field.unique"] = ucfirst($field) . ' sudah ada';
      }
    }

    if (!empty($rules)) {
      $this->validate($rules, $messages);
    }

    try {
      DB::beginTransaction();

      User::updateOrCreate(
        ['id' => $this->userId],
        [
          'name' => $this->name,
          'username' => $this->username,
          'phone' => $this->phone,
          'email' => $this->email,
          'role_id' => $this->role_id,
          'password' => Hash::make($this->username . '123'),
        ]
      );

      DB::commit();
      session()->flash('success', 'Data Pengguna Berhasil Disimpan.');
    } catch (\Exception $e) {
      DB::rollBack();
      session()->flash('error', 'Error: ' . $e->getMessage());
    }

    $this->resetForm();
  }

  public function edit($id)
  {
    $user = User::findOrFail($id);

    $this->userId = $id;
    $this->name = $user->name;
    $this->username = $user->username;
    $this->originalUsername = $user->username;
    $this->phone = $user->phone;
    $this->originalPhone = $user->phone;
    $this->email = $user->email;
    $this->originalEmail = $user->email;
    $this->role_id = $user->role_id;
    $this->roleSearch = $this->filterRoles[$user->role_id];
    $this->selectedRoleName = $this->filterRoles[$user->role_id];
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
  }

  public function resetForm()
  {
    $this->reset([
      'userId',
      'username',
      'originalUsername',
      'name',
      'phone',
      'originalPhone',
      'email',
      'originalEmail',
      'role_id',
      'roleSearch',
      'selectedRoleName',
      'selectedRoleName',
      'isEdit',
    ]);
    $this->dispatch('clearError');
  }

  public function render()
  {
    $items = User::with('role')
      ->when($this->search, function ($q) {
        $q->whereAny(['name', 'email', 'phone'], 'like', '%' . $this->search . '%');
      })
      ->when($this->filterRole, function ($q) {
        $q->where('id', '=', $this->filterRole);
      })
      ->latest()
      ->paginate($this->perPage);

    $roles = Role::when($this->roleSearch, function ($query) {
      $query->whereAny(['name'], 'like', '%' . $this->roleSearch . '%');
    })
      ->limit(3)
      ->get();

    return view('livewire.user.index', compact(['items', 'roles']));
  }
}
