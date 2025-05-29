<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Profile extends Component
{
  use WithFileUploads;

  #[Locked]
  public $title = 'Profil Anda';

  #[Locked]
  public $directory = 'images/avatars';

  public $userId;
  public $name;
  public $email;
  public $originalEmail;
  public $phone;
  public $originalPhone;
  public $avatar;
  public $originalAvatar;
  public $address;
  public $originalPassword;
  public $password;
  public $passwordNew;
  public $passwordConfirm;
  public $roleName;

  public function mount()
  {
    $user = User::with('role')
      ->where('id', '=', auth()->user()->id)
      ->first();

    $this->userId = $user->id;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->originalEmail = $user->email;
    $this->phone = $user->phone;
    $this->originalPhone = $user->phone;
    $this->address = $user->address;
    $this->avatar = $user->avatar;
    $this->originalAvatar = $user->avatar;
    $this->roleName = $user->role->name;
    $this->originalPassword = $user->password;
  }

  protected function refreshUser()
  {
    auth()->loginUsingId($this->userId);
  }

  public function updateProfile()
  {
    $fields = [
      'phone' => $this->originalPhone,
      'email' => $this->originalEmail,
    ];

    $rules = [];
    $messages = [];

    foreach ($fields as $field => $originalValue) {
      if ($this->{$field} !== $originalValue) {
        $rules[$field] = 'unique:users,' . $field;
        $messages["$field.unique"] = ucfirst($field) . ' sudah ada';
      }
    }

    if ($this->avatar instanceof TemporaryUploadedFile) {
      $rules['avatar'] = 'image|max:2048';
      $messages['avatar.image'] = 'Format file yang diperbolehkan hanya gambar';
      $messages['avatar.max'] = 'Ukuran gambar maksimal 2MB';
    }

    if ($this->avatar instanceof TemporaryUploadedFile) {
      $filename = createFilename(
        $this->name,
        $this->avatar->getClientOriginalExtension()
      );
      /* Simpan ke lokal */
      $avatarPath = $this->avatar->storeAs($this->directory, $filename, 'public');

      $oldAvatar = User::find($this->userId)?->avatar;
      if (isset($oldAvatar) && $avatarPath && $oldAvatar !== $avatarPath) {
        Storage::disk('public')->delete($oldAvatar);
      }
    }

    if (!empty($rules)) {
      $this->validate($rules, $messages);
    }

    try {
      DB::beginTransaction();

      $this->originalAvatar = $avatarPath ?? User::find($this->userId)?->avatar;

      User::where('id', $this->userId)->update([
        'name' => $this->name,
        'phone' => $this->phone,
        'email' => $this->email,
        'address' => $this->address,
        'avatar' => $this->originalAvatar,
      ]);

      DB::commit();

      $this->refreshUser();
      $this->dispatch('refreshNavbar');
      $this->dispatch('showSuccess', message: 'Profil Anda berhasil diperbarui.');
    } catch (\Exception $e) {
      DB::rollBack();
      $this->dispatch('showError', message: $e->getMessage());
    }
  }

  public function render()
  {
    return view('livewire.user.profile');
  }
}
