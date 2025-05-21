<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Services\Api\RapiwhaApiService;
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

  public $orderId, $selectedStatus, $proof_of_payment;

  public Order $order;

  protected RapiwhaApiService $rapiwha;

  public function __construct()
  {
    $this->rapiwha = new RapiwhaApiService();
  }

  public function mount($id)
  {
    $this->order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
    $this->orderId = $id;
    $this->selectedStatus = $this->order->status;
    $this->dispatch('test', $this->order);
  }

  public function availableStatusOptions()
  {
    return match ($this->order->status) {
      0 => [0, 1, 4],
      1 => [1, 2],
      2 => [2, 3],
      3 => [3],
      default => [],
    };
  }

  public function updateStatus()
  {
    if ($this->selectedStatus == $this->order->status) {
      return;
    }

    $allowed = $this->availableStatusOptions();

    if (!in_array($this->selectedStatus, $allowed)) {
      $this->dispatch('showError', ['message' => 'Perubahan status tidak valid.']);
      return;
    }

    if ($this->selectedStatus == 1) {
      $messages = [];

      try {
        if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
          $rules['proof_of_payment'] = 'required|image|max:2048';
          $messages['proof_of_payment.required'] = 'Bukti Bayar Tidak Boleh Kosong';
          $messages['proof_of_payment.image'] =
            'Format file yang diperbolehkan hanya gambar';
          $messages['proof_of_payment.max'] = 'Ukuran gambar maksimal 2MB';
        }

        $validated = $this->validate($rules, $messages);

        $imagePath = null;
        if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
          $filename =
            $this->orderId .
            '-' .
            time() .
            '.' .
            $this->proof_of_payment->getClientOriginalExtension();

          $imagePath = $this->proof_of_payment->storeAs(
            'images/proof_of_payments',
            $filename,
            'public'
          );

          $this->order->proof_of_payment = $imagePath;
        }
      } catch (ValidationException $e) {
        $this->dispatch('showError', ['message' => $e->validator->errors()->first()]);
        return;
      }
    }

    $this->order->status = $this->selectedStatus;
    $this->order->save();
    session()->flash('success', "Status ID Pesanan #$this->orderId berhasil diperbarui.");
    return $this->redirect(route('order'), true);
  }

  public function render()
  {
    return view('livewire.order.detail');
  }
}
