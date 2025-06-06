<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Services\Api\GoogleDriveServiceInterface;
use Google\Service\AdMob\App;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Index extends Component
{
  use WithPagination, WithFileUploads;

  #[Locked]
  public $title = 'Produk';

  #[Locked]
  public $directory = 'images/products';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Nama Produk'],
    ['name' => 'Stock'],
    ['name' => 'Harga'],
    ['name' => 'Gambar'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  public $productId;
  #[Validate('required', message: 'Nama Produk tidak boleh kosong')]
  public $name;
  #[Validate('required', message: 'SKU Produk tidak boleh kosong')]
  public $sku;
  public $description;
  #[Validate('required', message: 'Harga tidak boleh kosong')]
  #[Validate('numeric', message: 'Harga wajib angka')]
  public $price;
  #[Validate('numeric', message: 'Stock wajib angka')]
  public $stock;
  public $image;
  public $image_url;

  public $isEdit = false;

  public $search;
  public $perPage = 10;

  public function save(GoogleDriveServiceInterface $googleDriveService)
  {
    $this->validate();

    $rules = [];
    $messages = [];

    if ($this->image instanceof TemporaryUploadedFile) {
      $rules['image'] = 'image|max:2048';
      $messages['image.image'] = 'Format file yang diperbolehkan hanya gambar';
      $messages['image.max'] = 'Ukuran gambar maksimal 2MB';
      $this->validate($rules, $messages);
    }

    $imageLocalPath = null;
    $imageUrl = null;

    if ($this->image instanceof TemporaryUploadedFile) {
      $product = Product::find($this->productId);
      $filename = createFilename($this->name, $this->image->getClientOriginalExtension());
      /* Simpan ke lokal */
      $imageLocalPath = $this->image->storeAs($this->directory, $filename, 'public');
      $oldImage = $product?->image;
      if (isset($oldImage) && $imageLocalPath && $oldImage !== $imageLocalPath) {
        Storage::disk('public')->delete($oldImage);
      }

      /* Simpan ke Google Drive */
      $oldImageUrl = $product?->image_url;
      if (isset($oldImageUrl) && $imageLocalPath && $oldImageUrl !== $imageLocalPath) {
        $googleDriveService->delete($oldImageUrl);
      }
      $imageUrl = $googleDriveService->upload($imageLocalPath, $filename, 'products');
    }

    $product = Product::find($this->productId);
    Product::updateOrCreate(
      ['id' => $this->productId],
      [
        'name' => $this->name,
        'sku' => $this->sku,
        'description' => e($this->description),
        'price' => $this->price,
        'stock' => $this->stock,
        'image' => $imageLocalPath ?? $product?->image,
        'image_url' => $imageUrl ?? $product?->image_url,
      ]
    );

    $this->resetForm();
    session()->flash('success', 'Produk berhasil disimpan!');
  }

  public function edit($id)
  {
    $product = Product::findOrFail($id);
    $this->productId = $product->id;
    $this->name = $product->name;
    $this->description = $product->description;
    $this->price = $product->price;
    $this->stock = $product->stock;
    $this->sku = $product->sku;
    $this->image = $product->image;
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
    $this->dispatch('clearError');
  }

  public function resetForm()
  {
    $this->reset([
      'productId',
      'name',
      'description',
      'price',
      'stock',
      'sku',
      'image',
      'isEdit',
    ]);
    $this->dispatch('clearError');
  }

  public function showCustomer($id)
  {
    $this->dispatch('showWhatsappModal', id: $id);
  }

  public function updated($propertyName)
  {
    if (in_array($propertyName, ['search', 'perPage', 'page'])) {
      $this->dispatch('clearError');
    }
  }

  public function render()
  {
    $items = Product::when($this->search, function ($query) {
      $query->whereAny(['name', 'description'], 'like', '%' . $this->search . '%');
    })
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.product.index', compact('items'));
  }
}
