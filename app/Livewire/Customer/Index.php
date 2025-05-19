<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination;

  #[Locked]
  public $title = 'Data Pelanggan';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Nama'],
    ['name' => 'No Handphone'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  public $search = '';
  public $perPage = 10;

  public function render()
  {
    $items = Customer::when($this->search, function ($query) {
      $query->whereAny(['name', 'phone'], 'like', '%' . $this->search . '%');
    })
      ->latest()
      ->paginate($this->perPage);
    return view('livewire.customer.index', compact('items'));
  }
}
