<?php

namespace App\Livewire\Report;

use App\Services\ReportService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Order extends Component
{
    public $dateStart;
    public $dateEnd;
    public $status = '';
    public $report = [];

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

    public function mount(ReportService $reportService)
    {
        $this->dateStart = now()->subDays(6)->toDateString();
        $this->dateEnd = now()->toDateString();

        $this->report = $reportService->getOrderReport(
            $this->dateStart,
            $this->dateEnd,
            $this->status,
        );
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateStart', 'dateEnd', 'status'])) {
            $reportService = app(ReportService::class);
            $this->report = $reportService->getOrderReport(
                $this->dateStart,
                $this->dateEnd,
                $this->status,
            );
        }
    }

    public function render()
    {
        $orders = $this->report;

        $totalQty = collect($orders)->flatMap->orderItems->sum('quantity');
        $totalPrice = collect($orders)->flatMap->orderItems->sum(
            fn($item) => $item->price * $item->quantity,
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
            $headers,
        );
    }
}
