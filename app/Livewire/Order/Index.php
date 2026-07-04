<?php

namespace App\Livewire\Order;

use App\Services\OrderService;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $dateStart;
    public $dateEnd;
    public $status;
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

    public function mount()
    {
        $this->status = request()->query('status', '');
    }

    public function render(OrderService $orderService)
    {
        $items = $orderService->getPaginated(
            $this->perPage,
            $this->search,
            $this->status !== '' ? (string) $this->status : null,
            $this->dateStart,
            $this->dateEnd,
        );

        return view('livewire.order.index', compact('items'));
    }
}
