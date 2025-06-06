<?php

namespace App\Livewire\Order;

use App\Models\MessageTemplate;
use App\Models\Order;
use App\Services\Api\SendMessageApiServiceInterface;
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
  public $directory = 'images/proof_of_payments';

  #[Locked]
  public $statusList = ['Belum Bayar', 'Sudah Bayar', 'Pengiriman', 'Selesai', 'Batal'];

  #[Locked]
  public $colorStatus = ['danger', 'warning', 'primary', 'success', 'secondary'];

  public $orderId, $selectedStatus, $proof_of_payment;

  public Order $order;

  public function mount($id)
  {
    $this->order = Order::with(['customer', 'orderItems.product'])->findOrFail($id);
    $this->orderId = $id;
    $this->selectedStatus = $this->order->status;
    $this->proof_of_payment = $this->order->proof_of_payment;
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

  public function updateStatus(SendMessageApiServiceInterface $rapiwha)
  {
    if ($this->selectedStatus == $this->order->status) {
      return;
    }

    $allowed = $this->availableStatusOptions();

    if (!in_array($this->selectedStatus, $allowed)) {
      $this->dispatch('showError', message: 'Perubahan status tidak valid.');
      return;
    }

    switch ($this->selectedStatus) {
      case 1:
        $rules = [];
        $messages = [];

        try {
          $rules['proof_of_payment'] = 'required';
          $messages['proof_of_payment.required'] = 'Bukti bayar wajib diisi';
          if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
            $rules['proof_of_payment'] = 'image|max:2048';
            $messages['proof_of_payment.image'] =
              'Format file yang diperbolehkan hanya gambar';
            $messages['proof_of_payment.max'] = 'Ukuran gambar maksimal 2MB';
          }
          $this->validate($rules, $messages);

          $imagePath = null;
          if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
            $filename = createFilename(
              $this->order->customer->name . '-' . $this->orderId,
              $this->proof_of_payment->getClientOriginalExtension()
            );

            $imagePath = $this->proof_of_payment->storeAs(
              $this->directory,
              $filename,
              'public'
            );

            $this->order->proof_of_payment = $imagePath;

            $template = MessageTemplate::where(['id' => 3, 'type' => 'order'])->first();
          }
        } catch (ValidationException $e) {
          $this->dispatch('showError', message: $e->validator->errors()->first());
          return;
        }
        break;

      case 2:
        $template = MessageTemplate::where(['id' => 4, 'type' => 'order'])->first();
        break;

      case 3:
        $template = MessageTemplate::where(['id' => 5, 'type' => 'order'])->first();
        break;

      case 4:
        foreach ($this->order->orderItems as $item) {
          $product = $item->product;
          // Kembalikan stok
          $product->stock += $item->quantity;
          // Simpan perubahan
          $product->save();
        }

        $template = MessageTemplate::where(['id' => 6, 'type' => 'order'])->first();
        break;
    }

    try {
      if ($template) {
        $message = parseTemplatePlaceholders($template->body, [
          'customer_name' => $this->order->customer->name,
          'order_number' => $this->order->id,
          'order_total' => rupiah($this->order->total_amount),
          'store_name' => env('APP_NAME'),
          'contact_number' => env('APP_CONTACT_PERSON'),
        ]);

        $rapiwha->sendMessage($this->order->customer->phone, $message);
      }
    } catch (\Exception $e) {
      $this->dispatch('showError', message: 'Exception: ' . $e->getMessage());
      return;
    }

    $this->order->status = $this->selectedStatus;
    $this->order->save();
    session()->flash('success', "Status ID Pesanan #$this->orderId berhasil diperbarui.");
    return $this->redirect(route('transaksi-order'), true);
  }

  public function render()
  {
    return view('livewire.order.detail');
  }
}
