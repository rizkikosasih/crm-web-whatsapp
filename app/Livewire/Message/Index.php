<?php

namespace App\Livewire\Message;

use App\Models\Message;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
  #[Locked]
  public $title = 'Pesan Keluar';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Pelanggan'],
    ['name' => 'Dikirim'],
    ['name' => 'Isi Pesan'],
    ['name' => 'Gambar'],
    ['name' => 'Tanggal Kirim'],
  ];

  public $perPage = 5;
  public $search;

  public function render()
  {
    $items = Message::with('customer')
      ->with('user')
      ->when($this->search, function ($q) {
        $q->whereHas('customer', function ($q2) {
          $q2->where('name', 'like', '%' . $this->search . '%');
        })->orWhereHas('user', function ($q2) {
          $q2->where('name', 'like', '%' . $this->search . '%');
        });
      })
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.message.index', compact('items'));
  }
}
