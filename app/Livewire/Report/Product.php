<?php

namespace App\Livewire\Report;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Product extends Component
{
  public $dateStart, $dateEnd;

  #[Locked]
  public $title = 'Laporan Penjualan Per Produk';
  #[Locked]
  public $tableHeader = [
    ['name' => '#'],
    ['name' => 'Nama Produk'],
    ['name' => 'Jumlah Terjual'],
    ['name' => 'Pendapatan'],
  ];

  public function mount()
  {
    $this->dateStart = now()->subDays(6)->toDateString();
    $this->dateEnd = now()->toDateString();
  }

  public function updated($propertyName)
  {
    if (in_array($propertyName, ['dateStart', 'dateEnd'])) {
      $this->report = $this->getReportProperty();
    }
  }

  public function getReportProperty()
  {
    return OrderItem::select(
      'product_id',
      DB::raw('SUM(quantity) as total_quantity'),
      DB::raw('SUM(quantity * price) as total_income')
    )
      ->whereHas('order', function ($q) {
        $q->where('status', '!=', 4)
          ->whereDate('order_date', '>=', $this->dateStart)
          ->whereDate('order_date', '<=', $this->dateEnd);
      })
      ->with('product:id,name')
      ->groupBy('product_id')
      ->get();
  }

  public function render()
  {
    $items = $this->report;

    $totalQty = $items->sum('total_quantity');
    $totalPrice = $items->sum('total_income');

    return view('livewire.report.product', compact(['items', 'totalQty', 'totalPrice']));
  }

  public function exportXls()
  {
    $items = $this->report;

    $filename = 'laporan_penjualan_per_produk_' . now()->format('Ymd_His') . '.xls';

    $headers = [
      'Content-Type' => 'application/vnd.ms-excel',
      'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $html = view('report.order-product-xls', compact(['items']))->render();

    return response()->stream(
      function () use ($html) {
        echo $html;
      },
      200,
      $headers
    );
  }
}
