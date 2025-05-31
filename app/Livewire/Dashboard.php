<?php

namespace App\Livewire;

use App\Helpers\ChartHelper;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
  use WithPagination;

  #[Locked]
  public $title = 'Dashboard';

  #[Locked]
  public $messageHeader = [
    ['name' => 'No'],
    ['name' => 'Pelanggan'],
    ['name' => 'Dikirim'],
    ['name' => 'Isi Pesan'],
    ['name' => 'Gambar'],
    ['name' => 'Tanggal Kirim'],
  ];

  public $perPage = 5;
  public $search;

  public function mount()
  {
    $this->orderByStatus = $this->getOrderByStatusProperty();
    $this->charts = $this->getChartsProperty();
    $this->messages = $this->getMessagesProperty();
  }

  public function getOrderByStatusProperty(): array
  {
    /* Small Box */
    $statusConfig = [
      [
        'title' => 'Belum Dibayar',
        'icon' => 'fas fa-circle-exclamation',
        'color' => 'danger',
        'colorRibbon' => 'success',
      ],
      [
        'title' => 'Sudah Dibayar',
        'icon' => 'fas fa-receipt',
        'color' => 'warning',
        'colorRibbon' => 'primary',
      ],
      [
        'title' => 'Dalam Pengiriman',
        'icon' => 'fas fa-truck-fast',
        'color' => 'primary',
        'colorRibbon' => 'warning',
      ],
      [
        'title' => 'Selesai',
        'icon' => 'fas fa-circle-check',
        'color' => 'success',
        'colorRibbon' => 'danger',
      ],
    ];

    $orderByStatus = [];

    foreach ($statusConfig as $status => $config) {
      $orderByStatus[] = [
        'url' => url("/transaksi/order?status={$status}"),
        'icon' => $config['icon'],
        'title' => $config['title'],
        'color' => $config['color'],
        'colorRibbon' => $config['colorRibbon'],
        'count' => Order::where('status', $status)
          ->whereBetween('order_date', [now()->subMonth(), now()])
          ->count(),
      ];
    }

    return $orderByStatus;
  }

  public function getChartsProperty(): array
  {
    $statusLabels = [
      0 => 'Belum Bayar',
      1 => 'Sudah Bayar',
      2 => 'Pengiriman',
      3 => 'Selesai',
    ];

    $orderData = [];
    foreach ($statusLabels as $status => $label) {
      $result = Order::selectRaw('DATE(order_date) as date, COUNT(*) as total')
        ->where('status', $status)
        ->whereBetween('order_date', [now()->subDays(29), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

      foreach ($result as $item) {
        $orderData[$status][$item->date] = $item->total;
      }
    }

    $productSales = DB::table('order_items')
      ->join('orders', 'order_items.order_id', '=', 'orders.id')
      ->join('products', 'order_items.product_id', '=', 'products.id')
      ->whereBetween('orders.order_date', [now()->subDays(29), now()])
      ->where('orders.status', '!=', 4)
      ->select(
        'products.name as product_name',
        DB::raw('SUM(order_items.quantity) as total_quantity')
      )
      ->groupBy('products.name')
      ->orderByDesc('total_quantity')
      ->get()
      ->pluck('total_quantity', 'product_name')
      ->all();

    return [
      [
        'title' => 'Grafik Pesanan',
        'id' => 'orderCharts',
        'config' => ChartHelper::prepareChartConfig(
          'Grafik Pesanan',
          'line',
          $orderData,
          $statusLabels
        ),
        'show' => $orderData ? true : false,
      ],
      [
        'title' => 'Grafik Penjualan Produk',
        'id' => 'productCharts',
        'config' => ChartHelper::prepareChartConfig(
          'Grafik Penjualan Produk',
          'doughnut',
          $productSales
        ),
        'show' => $orderData ? true : false,
      ],
    ];
  }

  public function getMessagesProperty()
  {
    return Message::with('customer')
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
  }

  public function updated($propertyName)
  {
    if (in_array($propertyName, ['search', 'perPage'])) {
      $this->messages = $this->getMessagesProperty();
    } else {
      // $this->items = $this->getItemsProperty();
    }
  }

  public function render()
  {
    $messages = $this->messages;
    $orderByStatus = $this->orderByStatus;
    $charts = $this->charts;

    return view(
      'livewire.dashboard.index',
      compact(['orderByStatus', 'charts', 'messages'])
    );
  }
}
