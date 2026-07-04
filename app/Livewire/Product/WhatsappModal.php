<?php

namespace App\Livewire\Product;

use App\Services\CustomerService;
use App\Services\MessageTemplateService;
use App\Services\ProductService;
use App\Services\Api\SendMessageApiServiceInterface;
use Livewire\Attributes\Locked;
use Livewire\Component;

class WhatsappModal extends Component
{
    public $perPage = 5;
    public $items = [];
    public $search, $searchPhone;

    public $idProduct,
        $productName,
        $productSku,
        $productImage,
        $productImageUrl,
        $productDescription,
        $productStock,
        $productPrice;

    #[Locked]
    public $tableHeader = [
        ['name' => 'ID'],
        ['name' => 'Nama'],
        ['name' => 'No Handphone'],
        ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
    ];

    protected $listeners = ['showWhatsappModal'];

    public function showWhatsappModal(
        $id,
        ProductService $productService,
        CustomerService $customerService,
    ) {
        $product = $productService->find($id);

        $this->idProduct = $id;
        $this->productSku = $product->sku;
        $this->productName = $product->name;
        $this->productImage = $product->image;
        $this->productImageUrl = $product->image_url;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;
        $this->productDescription = html_entity_decode($product->description);

        $this->items = $customerService->getPaginated($this->perPage, $this->search)->toArray();

        $this->dispatch('bootstrap:show');
    }

    public function updated(CustomerService $customerService)
    {
        $this->items = $customerService->getPaginated($this->perPage, $this->search)->toArray();
    }

    public function closeModal()
    {
        $this->reset();
    }

    public function sendWA(
        $phone,
        SendMessageApiServiceInterface $rapiwha,
        MessageTemplateService $messageTemplateService,
    ) {
        $template = $messageTemplateService->findByType('product');

        $message = parseTemplatePlaceholders($template->body, [
            'name' => $this->productName,
            'sku' => $this->productSku,
            'price' => rupiah($this->productPrice),
            'stock' => $this->productStock,
            'description' => $this->productDescription,
        ]);

        $response = $rapiwha->sendMessage($phone, $message, $this->productImageUrl);

        if ($response->success) {
            $this->dispatch('showSuccess', message: 'Info Produk Berhasil Dikirim');
        } else {
            $this->dispatch('showError', message: $response->message);
        }
    }

    public function render()
    {
        return view('livewire.product.whatsapp-modal');
    }
}
