<?php

namespace App\Services;

use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Services\MessageTemplateService;
use App\Services\Api\SendMessageApiServiceInterface;
use App\Services\Api\ImagekitServiceInterface;
use App\Services\Contracts\InvoiceServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;
    protected $customerRepository;
    protected $messageTemplateService;
    protected $sendMessageApiService;
    protected $imagekitService;
    protected $invoiceService;

    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        CustomerRepository $customerRepository,
        MessageTemplateService $messageTemplateService,
        SendMessageApiServiceInterface $sendMessageApiService,
        ImagekitServiceInterface $imagekitService,
        InvoiceServiceInterface $invoiceService,
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        $this->messageTemplateService = $messageTemplateService;
        $this->sendMessageApiService = $sendMessageApiService;
        $this->imagekitService = $imagekitService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Get paginated orders.
     */
    public function getPaginated(
        int $perPage,
        ?string $search,
        ?string $status = null,
        ?string $dateStart = null,
        ?string $dateEnd = null,
    ) {
        return $this->orderRepository->getPaginated(
            $perPage,
            $search,
            $status,
            $dateStart,
            $dateEnd,
        );
    }

    /**
     * Find order with its items and product info.
     */
    public function findWithRelations(int $id)
    {
        return $this->orderRepository->findWithRelations($id);
    }

    /**
     * Create a new order.
     */
    public function createOrder(array $data)
    {
        $customerId = $data['customer_id'];
        $userId = $data['user_id'];
        $items = $data['items']; // array of ['product_id', 'price', 'quantity']

        // 1. Stock check
        foreach ($items as $item) {
            $product = $this->productRepository->find($item['product_id']);
            if ($item['quantity'] > $product->stock) {
                throw new \Exception(
                    "Stok produk '{$product->name}' tidak mencukupi. Tersedia: {$product->stock}.",
                );
            }
        }

        return DB::transaction(function () use ($customerId, $userId, $items) {
            // Calculate total amount
            $totalAmount = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);

            // 2. Create Order
            $order = $this->orderRepository->create([
                'customer_id' => $customerId,
                'user_id' => $userId,
                'status' => 0, // Belum Bayar
                'total_amount' => $totalAmount,
                'order_date' => now(),
            ]);

            // 3. Create Order Items and Decrement stock
            $this->orderRepository->createItems($order->id, $items);

            $productListText = '';
            foreach ($items as $item) {
                $this->productRepository->decrementStock($item['product_id'], $item['quantity']);
                $product = $this->productRepository->find($item['product_id']);
                $itemPrice = rupiah($item['price']);
                $productListText .= "{$product->name} ({$product->sku}) @{$item['quantity']} {$itemPrice}\n";
            }

            // 4. Dispatch WA rincian pesanan (using template ID 2)
            $customer = $this->customerRepository->find($customerId);
            $template = $this->messageTemplateService->find(2); // Template order dibuat
            if ($template) {
                $message = parseTemplatePlaceholders($template->body, [
                    'customer_name' => $customer->name,
                    'order_number' => $order->id,
                    'product_list' => $productListText,
                    'order_date' => dateIndo($order->order_date),
                    'order_total' => rupiah($order->total_amount),
                    'contact_number' => config('app.contact') ?? '-',
                    'store_name' => config('app.name') ?? '-',
                ]);
                $this->sendMessageApiService->sendMessage($customer->phone, $message);
            }

            return $order;
        });
    }

    /**
     * Get available status transition options based on current status.
     */
    public function getAvailableStatusTransitions(int $currentStatus): array
    {
        return match ($currentStatus) {
            0 => [0, 1, 4], // Belum Bayar -> Sudah Bayar atau Batal
            1 => [1, 2], // Sudah Bayar -> Pengiriman
            2 => [2, 3], // Pengiriman -> Selesai
            3 => [3], // Selesai (Final)
            default => [],
        };
    }

    /**
     * Update order status and trigger notifications.
     */
    public function updateOrderStatus(int $id, int $newStatus, $proofOfPaymentFile = null)
    {
        $order = $this->orderRepository->findWithRelations($id);

        if ($newStatus === $order->status) {
            return $order;
        }

        $allowed = $this->getAvailableStatusTransitions($order->status);
        if (!in_array($newStatus, $allowed)) {
            throw new \Exception('Perubahan status tidak valid.');
        }

        return DB::transaction(function () use ($order, $newStatus, $proofOfPaymentFile) {
            $proofOfPaymentPath = null;
            $linkPdf = null;
            $templateId = null;

            switch ($newStatus) {
                case 1: // Belum Bayar -> Sudah Bayar
                    if ($proofOfPaymentFile instanceof TemporaryUploadedFile) {
                        $filename = createFilename(
                            $order->customer->name . '-' . $order->id,
                            $proofOfPaymentFile->getClientOriginalExtension(),
                        );
                        $proofOfPaymentPath = $proofOfPaymentFile->storeAs(
                            'images/proof_of_payments',
                            $filename,
                            'public',
                        );
                    } elseif (is_string($proofOfPaymentFile)) {
                        $proofOfPaymentPath = $proofOfPaymentFile;
                    } else {
                        throw new \Exception('Bukti bayar wajib diisi untuk status Sudah Bayar.');
                    }
                    $templateId = 3;
                    break;

                case 2: // Sudah Bayar -> Pengiriman
                    $templateId = 4;
                    break;

                case 3: // Pengiriman -> Selesai
                    $templateId = 5;
                    // Generate PDF invoice
                    $pdfContent = $this->invoiceService->generate($order);
                    $linkPdf = $this->imagekitService->uploadPdfContent(
                        $pdfContent,
                        invoiceFilename($order->id),
                        'invoices',
                    );
                    break;

                case 4: // Batal (Restore Stock)
                    foreach ($order->orderItems as $item) {
                        $this->productRepository->incrementStock(
                            $item->product_id,
                            $item->quantity,
                        );
                    }
                    $templateId = 6;
                    break;
            }

            // Update order record
            $order = $this->orderRepository->updateStatus(
                $order->id,
                $newStatus,
                $proofOfPaymentPath,
                $linkPdf,
            );

            // Send notification message if template exists
            if ($templateId) {
                $template = $this->messageTemplateService->find($templateId);
                if ($template) {
                    $message = parseTemplatePlaceholders($template->body, [
                        'customer_name' => $order->customer->name,
                        'order_number' => $order->id,
                        'order_total' => rupiah($order->total_amount),
                        'contact_number' => config('app.contact') ?? '-',
                        'store_name' => config('app.name') ?? '-',
                        'invoice_link' => $order->link_pdf,
                    ]);
                    $this->sendMessageApiService->sendMessage(
                        $order->customer->phone,
                        $message,
                        $order->link_pdf,
                    );
                }
            }

            return $order;
        });
    }

    /**
     * Count orders by status and date range.
     */
    public function countByStatus(int $status, $startDate, $endDate)
    {
        return $this->orderRepository->countByStatus($status, $startDate, $endDate);
    }

    /**
     * Get daily totals by status and date range for charts.
     */
    public function getDailyTotalByStatus(int $status, $startDate, $endDate)
    {
        return $this->orderRepository->getDailyTotalByStatus($status, $startDate, $endDate);
    }
}
