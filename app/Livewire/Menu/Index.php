<?php

namespace App\Livewire\Menu;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination;

  #[Locked]
  public $title = 'Daftar Menu';

  #[Locked]
  public $statusList = ['Non Aktif', 'Aktif'];

  #[Locked]
  public $colorStatus = ['danger', 'success'];

  #[Locked]
  public $tableHeader = [
    ['name' => 'Parent'],
    ['name' => 'Nama'],
    ['name' => 'Icon'],
    ['name' => 'Urutan'],
    ['name' => 'Route'],
    ['name' => 'Slug'],
    ['name' => 'Status'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  public $isEdit = false;

  public $search = '';
  public $perPage = 10;

  public $menuId, $parentId, $selectedParentName, $parentSearch, $icon, $slug;
  #[Validate('required', message: 'Nama Menu tidak boleh kosong')]
  public $name;
  #[Validate('required', message: 'Urutan tidak boleh kosong')]
  #[Validate('numeric', message: 'Urutan handphone wajib angka')]
  public $position;
  #[Validate('required', message: 'Route tidak boleh kosong')]
  public $route;

  public function selectParent($id, $name)
  {
    $this->parentId = $id;
    $this->selectedParentName = $name;
    $this->parentSearch = $name;
  }

  public function save()
  {
    $this->validate();

    Menu::updateOrCreate(
      ['id' => $this->menuId],
      [
        'name' => $this->name,
        'parentId' => $this->parentId,
        'route' => $this->route,
        'slug' => Str::slug($this->slug),
        'position' => $this->position,
        'icon' => $this->icon,
      ]
    );

    session()->flash('success', 'Data Menu Berhasil Disimpan.');
    $this->resetForm();
  }

  public function edit($id)
  {
    $menu = Menu::with(['parent'])->findOrFail($id);

    $this->menuId = $id;
    $this->parentId = $menu->parent_id;
    $this->selectedParentName = $menu->parent?->name;
    $this->parentSearch = $menu->parent?->name;
    $this->name = $menu->name;
    $this->icon = $menu->icon;
    $this->route = $menu->route;
    $this->slug = $menu->slug;
    $this->position = $menu->position;
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
    $this->dispatch('clearError');
  }

  public function confirmActive($id, $status)
  {
    $html =
      'Apakah Anda yakin ingin ' .
      (!$status ? 'mengaktifkan' : 'menonaktifkan') .
      ' menu ini?';

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

      $menu = Menu::findOrFail($id);
      $menu->is_active = !$status;
      $menu->save();

      $message = 'Menu berhasil ' . (!$status ? 'diaktifkan' : 'dinonaktifkan') . '.';

      DB::commit();
      $this->dispatch('showSuccess', message: $message);
    } catch (\Exception $e) {
      DB::rollBack();
      $this->dispatch('showError', message: $e->getMessage());
    }
  }

  public function confirmDelete($id)
  {
    $html = 'Apakah Anda yakin ingin menghapus menu ini?';

    $this->dispatch(
      'swal:confirm',
      method: 'setDelete',
      params: ['id' => $id],
      options: [
        'html' => $html,
      ]
    );
  }

  #[On('setDelete')]
  public function setDelete($id)
  {
    try {
      DB::beginTransaction();

      $menu = Menu::where('id', $id)->delete();

      $message = 'Menu berhasil dihapus.';

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
      'menuId',
      'parentId',
      'selectedParentName',
      'parentSearch',
      'name',
      'icon',
      'route',
      'slug',
      'position',
    ]);
    $this->dispatch('clearError');
  }

  public function render()
  {
    $items = Menu::with('parent')
      ->when($this->search, function ($query) {
        $query->whereAny(['name', 'slug', 'route'], 'like', '%' . $this->search . '%');
      })
      ->orderByRaw('COALESCE(parent_id, 0) ASC')
      ->orderBy('position')
      ->paginate($this->perPage);

    $menus = Menu::when($this->parentSearch, function ($query) {
      $query->whereAny(['name'], 'like', '%' . $this->parentSearch . '%');
    })
      ->limit(3)
      ->get();

    return view('livewire.menu.index', compact(['items', 'menus']));
  }
}
