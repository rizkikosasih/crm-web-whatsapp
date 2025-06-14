<?php

namespace App\Livewire\Product;

use App\Models\Customer;
use App\Models\MessageTemplate;
use App\Models\Product;
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

  public function showWhatsappModal($id)
  {
    $product = Product::find($id);

    $this->idProduct = $id;
    $this->productSku = $product->sku;
    $this->productName = $product->name;
    $this->productImage = $product->image;
    $this->productImageUrl = $product->image_url;
    $this->productPrice = $product->price;
    $this->productStock = $product->stock;
    $this->productDescription = html_entity_decode($product->description);

    $this->items = Customer::when($this->search, function ($query) {
      $query->where('name', 'like', '%' . $this->search . '%');
    })
      ->when($this->searchPhone, function ($query) {
        $query->where('phone', 'like', '%' . $this->searchPhone . '%');
      })
      ->latest()
      ->paginate($this->perPage)
      ->toArray();

    $this->dispatch('bootstrap:show');
  }

  public function updated()
  {
    $this->items = Customer::when($this->search, function ($query) {
      $query->where('name', 'like', '%' . $this->search . '%');
    })
      ->when($this->searchPhone, function ($query) {
        $query->where('phone', 'like', '%' . $this->searchPhone . '%');
      })
      ->latest()
      ->paginate($this->perPage)
      ->toArray();
  }

  public function closeModal()
  {
    $this->reset();
  }

  public function sendWA($phone, SendMessageApiServiceInterface $rapiwha)
  {
    $template = MessageTemplate::where(['id' => 1, 'type' => 'product'])->first();

    $message = parseTemplatePlaceholders($template->body, [
      'name' => $this->productName,
      'sku' => $this->productSku,
      'price' => rupiah($this->productPrice),
      'stock' => $this->productStock,
      'description' => $this->productDescription,
    ]);

    // Tambahkan link gambar jika tersedia
    $image = $this->productImage ?? null;
    $imageUrl = $this->productImageUrl ?? null;
    if (!empty($imageUrl)) {
      $message .= "\n\nKlik untuk melihat gambar:\n" . $imageUrl;
    }

    $response = $rapiwha->sendMessage($phone, $message);

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
