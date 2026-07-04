<?php

namespace App\Livewire;

use App\Helpers\ChartHelper;
use App\Services\MessageService;
use App\Services\OrderService;
use App\Services\ProductService;
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

    public $orderByStatus = [];
    public $charts = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $orderService = app(OrderService::class);
        $productService = app(ProductService::class);

        $startDate = now()->subYear();
        $endDate = now();

        $this->orderByStatus = $this->getOrderByStatusProperty($orderService, $startDate, $endDate);
        $this->charts = $this->getChartsProperty(
            $orderService,
            $productService,
            $startDate,
            $endDate,
        );
    }

    public function getOrderByStatusProperty(
        OrderService $orderService,
        $startDate,
        $endDate,
    ): array {
        $statusConfig = [
            [
                'title' => 'Belum Dibayar',
                'icon' => 'alert-circle',
                'color' => 'danger',
                'colorRibbon' => 'success',
            ],
            [
                'title' => 'Sudah Dibayar',
                'icon' => 'receipt',
                'color' => 'warning',
                'colorRibbon' => 'primary',
            ],
            [
                'title' => 'Dalam Pengiriman',
                'icon' => 'truck',
                'color' => 'primary',
                'colorRibbon' => 'warning',
            ],
            [
                'title' => 'Selesai',
                'icon' => 'check-circle',
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
                'count' => $orderService->countByStatus($status, $startDate, $endDate),
            ];
        }

        return $orderByStatus;
    }

    public function getChartsProperty(
        OrderService $orderService,
        ProductService $productService,
        $startDate,
        $endDate,
    ): array {
        $statusLabels = [
            0 => 'Belum Bayar',
            1 => 'Sudah Bayar',
            2 => 'Pengiriman',
            3 => 'Selesai',
        ];

        $orderData = [];
        foreach ($statusLabels as $status => $label) {
            $dailyTotals = $orderService->getDailyTotalByStatus($status, $startDate, $endDate);
            foreach ($dailyTotals as $date => $total) {
                $orderData[$status][$date] = $total;
            }
        }

        $productSales = $productService->getTopSales($startDate, $endDate);

        return [
            [
                'title' => 'Grafik Pesanan 1 Bulan Terakhir',
                'id' => 'orderCharts',
                'config' => ChartHelper::prepareChartConfig(
                    'Grafik Pesanan',
                    'line',
                    $orderData,
                    $statusLabels,
                ),
                'show' => !empty($orderData),
            ],
            [
                'title' => 'Grafik Penjualan Produk 1 Tahun Terakhir',
                'id' => 'productCharts',
                'config' => ChartHelper::prepareChartConfig(
                    'Grafik Penjualan Produk',
                    'doughnut',
                    $productSales,
                ),
                'show' => !empty($productSales),
            ],
        ];
    }

    public function getMessagesProperty(MessageService $messageService)
    {
        return $messageService->getPaginated($this->perPage, $this->search);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'perPage'])) {
            $this->dispatch('refreshChart');
        }
    }

    public function render()
    {
        $messageService = app(MessageService::class);
        $messages = $this->getMessagesProperty($messageService);

        return view('livewire.dashboard.index', [
            'orderByStatus' => $this->orderByStatus,
            'charts' => $this->charts,
            'messages' => $messages,
        ]);
    }
}
