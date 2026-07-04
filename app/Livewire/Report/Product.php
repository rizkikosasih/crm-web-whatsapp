<?php

namespace App\Livewire\Report;

use App\Services\ReportService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Product extends Component
{
    public $dateStart, $dateEnd;
    public $report = [];

    #[Locked]
    public $title = 'Laporan Penjualan Per Produk';
    #[Locked]
    public $tableHeader = [
        ['name' => '#'],
        ['name' => 'Nama Produk'],
        ['name' => 'Jumlah Terjual'],
        ['name' => 'Pendapatan'],
    ];

    public function mount(ReportService $reportService)
    {
        $this->dateStart = now()->subDays(6)->toDateString();
        $this->dateEnd = now()->toDateString();
        $this->report = $reportService->getProductReport($this->dateStart, $this->dateEnd);
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateStart', 'dateEnd'])) {
            $reportService = app(ReportService::class);
            $this->report = $reportService->getProductReport($this->dateStart, $this->dateEnd);
        }
    }

    public function render()
    {
        $items = $this->report;

        $totalQty = collect($items)->sum('total_quantity');
        $totalPrice = collect($items)->sum('total_income');

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
            $headers,
        );
    }
}
