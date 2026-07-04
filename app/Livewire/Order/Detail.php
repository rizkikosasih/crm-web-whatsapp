<?php

namespace App\Livewire\Order;

use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Detail extends Component
{
    use WithFileUploads;

    #[Locked]
    public $title = 'Order Detail';

    #[Locked]
    public $statusList = ['Belum Bayar', 'Sudah Bayar', 'Pengiriman', 'Selesai', 'Batal'];

    #[Locked]
    public $colorStatus = ['danger', 'warning', 'primary', 'success', 'secondary'];

    public int $orderId;
    public int $selectedStatus;
    public $proof_of_payment;

    public Order $order;

    public function mount($id, OrderService $orderService)
    {
        $this->order = $orderService->findWithRelations($id);
        $this->orderId = $id;
        $this->selectedStatus = $this->order->status;
        $this->proof_of_payment = $this->order->proof_of_payment;
    }

    public function availableStatusOptions(OrderService $orderService)
    {
        return $orderService->getAvailableStatusTransitions($this->order->status);
    }

    public function updateStatus(OrderService $orderService)
    {
        if ($this->selectedStatus == $this->order->status) {
            return;
        }

        $allowed = $orderService->getAvailableStatusTransitions($this->order->status);
        if (!in_array($this->selectedStatus, $allowed)) {
            return $this->dispatch('showError', message: 'Perubahan status tidak valid.');
        }

        // Specific Validation: Payment confirmation needs proof of payment
        if ($this->selectedStatus == 1) {
            try {
                $this->validateProofOfPayment();
            } catch (ValidationException $e) {
                return $this->dispatch('showError', message: $e->validator->errors()->first());
            }
        }

        try {
            $orderService->updateOrderStatus(
                $this->orderId,
                $this->selectedStatus,
                $this->proof_of_payment,
            );

            session()->flash('success', "Status ID Pesanan #$this->orderId berhasil diperbarui.");
            return $this->redirect(route('transaksi-order'), true);
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateProofOfPayment()
    {
        $rules = ['proof_of_payment' => 'required'];
        if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
            $rules['proof_of_payment'] = 'image|max:2048';
        }
        $this->validate($rules, ['proof_of_payment.required' => 'Bukti bayar wajib diisi']);
    }

    public function render()
    {
        return view('livewire.order.detail');
    }
}
