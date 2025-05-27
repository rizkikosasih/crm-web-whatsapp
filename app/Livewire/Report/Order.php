<?php

namespace App\Livewire\Report;

use App\Models\Order as ModelsOrder;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Order extends Component
{
  use WithPagination;

  public $dateStart;
  public $dateEnd;
  public $status = '';
  public $search = '';
  public $perPage = 10;

  #[Locked]
  public $title = 'Laporan Pesanan';

  #[Locked]
  public $statusList = ['Belum Bayar', 'Sudah Bayar', 'Pengiriman', 'Selesai', 'Batal'];

  #[Locked]
  public $colorStatus = ['danger', 'warning', 'primary', 'success', 'secondary'];

  #[Locked]
  public $tableHeader = [
    ['name' => '#'],
    ['name' => 'Tanggal'],
    ['name' => 'Pelanggan'],
    ['name' => 'Status'],
    ['name' => 'Jumlah Item'],
    ['name' => 'Total'],
  ];

  public function mount()
  {
    $this->dateStart = now()->subDays(6)->toDateString();
    $this->dateEnd = now()->toDateString();
  }

  public function render()
  {
    $orders = ModelsOrder::with('customer', 'orderItems')
      ->when($this->status, fn($q) => $q->where('status', $this->status))
      ->when(
        $this->dateStart,
        fn($q) => $q->whereDate('order_date', '>=', $this->dateStart)
      )
      ->when($this->dateEnd, fn($q) => $q->whereDate('order_date', '<=', $this->dateEnd))
      ->latest()
      ->get();

    $totalQty = $orders->flatMap->orderItems->sum('quantity');
    $totalPrice = $orders->flatMap->orderItems->sum(
      fn($item) => $item->price * $item->quantity
    );

    return view('livewire.report.order', compact(['orders', 'totalQty', 'totalPrice']));
  }

  public function exportXls()
  {
    $orders = ModelsOrder::with(['customer', 'orderItems.product'])
      ->when($this->status, fn($q) => $q->where('status', $this->status))
      ->whereDate('order_date', '>=', $this->dateStart)
      ->whereDate('order_date', '<=', $this->dateEnd)
      ->get();

    $filename = 'laporan_order_' . now()->format('Ymd_His') . '.xls';

    $headers = [
      'Content-Type' => 'application/vnd.ms-excel',
      'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $statusList = $this->statusList;

    $html = view('report.order.xls', compact(['orders', 'statusList']))->render();

    return response()->stream(
      function () use ($html) {
        echo $html;
      },
      200,
      $headers
    );
  }
}
