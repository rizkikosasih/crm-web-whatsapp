<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination;

  public $dateStart;
  public $dateEnd;
  public $status = '';
  public $search = '';
  public $perPage = 10;

  #[Locked]
  public $title = 'Daftar Pesanan';

  #[Locked]
  public $statusList = ['Belum Bayar', 'Sudah Bayar', 'Pengiriman', 'Selesai', 'Batal'];

  #[Locked]
  public $colorStatus = ['danger', 'warning', 'primary', 'success', 'secondary'];

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Pelanggan'],
    ['name' => 'Status'],
    ['name' => 'Total Harga'],
    ['name' => 'Tanggal'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  public function render()
  {
    $items = Order::query()
      ->with('customer')
      ->when($this->search, function ($q) {
        $q->whereHas('customer', function ($q2) {
          $q2->where('name', 'like', '%' . $this->search . '%');
        });
      })
      ->when(is_numeric($this->status), fn($q) => $q->where('status', $this->status))
      ->when(
        $this->dateStart,
        fn($q) => $q->whereDate('order_date', '>=', Carbon::parse($this->dateStart))
      )
      ->when(
        $this->dateEnd,
        fn($q) => $q->whereDate('order_date', '<=', Carbon::parse($this->dateEnd))
      )
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.order.index', compact('items'));
  }
}
