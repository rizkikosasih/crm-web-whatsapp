<?php

namespace App\Livewire\Report;

use App\Models\Order as ModelsOrder;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Order extends Component
{
  public $dateStart;
  public $dateEnd;
  public $status = '';

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

    $this->report = $this->getReportProperty();
  }

  public function getReportProperty()
  {
    return ModelsOrder::with('customer', 'orderItems')
      ->when($this->status !== '', fn($q) => $q->where('status', $this->status))
      ->when(
        $this->dateStart,
        fn($q) => $q->whereDate('order_date', '>=', $this->dateStart)
      )
      ->when($this->dateEnd, fn($q) => $q->whereDate('order_date', '<=', $this->dateEnd))
      ->latest()
      ->get();
  }

  public function updated($propertyName)
  {
    if (in_array($propertyName, ['dateStart', 'dateEnd', 'status'])) {
      $this->report = $this->getReportProperty();
    }
  }

  public function render()
  {
    $orders = $this->report;

    $totalQty = $orders->flatMap->orderItems->sum('quantity');
    $totalPrice = $orders->flatMap->orderItems->sum(
      fn($item) => $item->price * $item->quantity
    );

    return view('livewire.report.order', compact(['orders', 'totalQty', 'totalPrice']));
  }

  public function exportXls()
  {
    $orders = $this->report;

    $filename = 'laporan_penjualan_' . now()->format('Ymd_His') . '.xls';

    $headers = [
      'Content-Type' => 'application/vnd.ms-excel',
      'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $statusList = $this->statusList;

    $html = view('report.order-xls', compact(['orders', 'statusList']))->render();

    return response()->stream(
      function () use ($html) {
        echo $html;
      },
      200,
      $headers
    );
  }
}
