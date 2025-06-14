<?php

namespace App\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
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

  #[Locked]
  public $statusList = ['Non Aktif', 'Aktif'];

  #[Locked]
  public $colorStatus = ['danger', 'success'];

  #[Locked]
  public $titleStatus = ['Aktifkan', 'Non Aktifkan'];

  public $isEdit = false;

  public $search = '';
  public $perPage = 10;
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
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
  }

  public function confirmActive($id, $status)
  {
    $html =
      'Apakah Anda yakin ingin ' .
      (!$status ? 'mengaktifkan' : 'menonaktifkan') .
      ' pengguna ini?';

    $this->dispatch(
      'swal:confirm',
      method: 'setActive',
      params: ['id' => $id, 'status' => $status],
      options: [
        'html' => $html,
      ]
    );
  }

  #[On('setActive')]
  public function setActive($id, $status)
  {
    try {
      DB::beginTransaction();

      $user = User::findOrFail($id);
      $user->is_active = !$status;
      $user->save();

      $message = 'Pengguna berhasil ' . (!$status ? 'diaktifkan' : 'dinonaktifkan') . '.';

      DB::commit();
      $this->dispatch('showSuccess', message: $message);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->dispatch('showError', message: $e->getMessage());
    }
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
        $q->where('role_id', '=', $this->filterRole);
      })
      ->where('id', '!=', 1)
      ->where('id', '!=', auth()->id())
      ->latest()
      ->paginate($this->perPage);

    $roles = Role::whereRaw('LOWER(name) != ?', ['super admin'])
      ->pluck('name', 'id')
      ->toArray();

    return view('livewire.user.index', compact(['items', 'roles']));
  }
}
