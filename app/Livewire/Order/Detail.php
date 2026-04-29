<?php

namespace App\Livewire\Order;

use App\Models\MessageTemplate;
use App\Models\Order;
use App\Services\Api\ImageKitServiceInterface;
use App\Services\Api\SendMessageApiServiceInterface;
use App\Services\Contracts\InvoiceServiceInterface;
use Illuminate\Support\Facades\DB;
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

    public int $orderId;
    public int $selectedStatus;
    public $proof_of_payment;

    protected $linkPdf = null;
    protected $template = null;

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

    public function updateStatus(
        SendMessageApiServiceInterface $rapiwha,
        InvoiceServiceInterface $invoiceService,
        ImageKitServiceInterface $imagekitService
    ) {
        // Guard Clauses (Cek validasi awal)
        if ($this->selectedStatus == $this->order->status) {
            return;
        }

        $allowed = $this->availableStatusOptions();
        if (!in_array($this->selectedStatus, $allowed)) {
            return $this->dispatch('showError', message: 'Perubahan status tidak valid.');
        }

        // Validasi Khusus (Belum Bayar -> Sudah Bayar)
        if ($this->selectedStatus == 1) {
            try {
                $this->validateProofOfPayment();
            } catch (ValidationException $e) {
                return $this->dispatch('showError', message: $e->validator->errors()->first());
            }
        }

        try {
            DB::beginTransaction();

            // Eksekusi Logika Spesifik Berdasarkan Status
            $this->handleStatusLogic($imagekitService, $invoiceService);

            // Pengiriman Pesan WA (Hanya jika ada template)
            $this->sendNotification($rapiwha);

            // Finalisasi
            $this->order->update(['status' => $this->selectedStatus]);

            DB::commit();

            session()->flash('success', "Status ID Pesanan #$this->orderId berhasil diperbarui.");
            return $this->redirect(route('transaksi-order'), true);
        } catch (\Exception $e) {
            DB::rollBack();
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

    private function handleStatusLogic($imagekitService, $invoiceService)
    {
        switch ($this->selectedStatus) {
            case 1: // Ke Sudah Bayar
                if ($this->proof_of_payment instanceof TemporaryUploadedFile) {
                    $filename = createFilename(
                        $this->order->customer->name . '-' . $this->orderId,
                        $this->proof_of_payment->getClientOriginalExtension()
                    );
                    $this->order->proof_of_payment = $this->proof_of_payment->storeAs(
                        $this->directory,
                        $filename,
                        'public'
                    );
                }
                $this->templateId = 3;
                break;

            case 2: // Ke Pengiriman
                $this->templateId = 4;
                break;

            case 3: // Ke Selesai
                $this->templateId = 5;
                $pdfContent = $invoiceService->generate($this->order);
                $this->linkPdf = $imagekitService->uploadPdfContent(
                    $pdfContent,
                    invoiceFilename($this->order->id),
                    'invoices'
                );
                $this->order->link_pdf = $this->linkPdf;
                break;

            case 4: // Batal (Restore Stok)
                $this->order->orderItems->each(function ($item) {
                    $item->product()->increment('stock', $item->quantity);
                });
                $this->templateId = 6;
                break;
        }
    }

    private function sendNotification($rapiwha)
    {
        if (!isset($this->templateId)) {
            return;
        }

        $template = MessageTemplate::where(['id' => $this->templateId, 'type' => 'order'])->first();
        if (!$template) {
            return;
        }

        $message = parseTemplatePlaceholders($template->body, [
            'customer_name' => $this->order->customer->name,
            'order_number' => $this->order->id,
            'order_total' => rupiah($this->order->total_amount),
            'contact_number' => config('app.contact'),
            'store_name' => config('app.name'),
            'invoice_link' => $this->linkPdf,
        ]);

        $rapiwha->sendMessage($this->order->customer->phone, $message, $this->linkPdf ?? null);
    }

    public function render()
    {
        return view('livewire.order.detail');
    }
}
