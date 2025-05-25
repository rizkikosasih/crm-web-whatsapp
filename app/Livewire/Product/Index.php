<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Str;
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

  public $isEdit = false;

  public $search;
  public $perPage = 10;

  public function save()
  {
    $this->validate();

    if ($this->image instanceof TemporaryUploadedFile) {
      $rules['image'] = 'image|max:2048';
      $messages['image.image'] = 'Format file yang diperbolehkan hanya gambar';
      $messages['image.max'] = 'Ukuran gambar maksimal 2MB';
      $this->validate($rules, $messages);
    }

    $imagePath = null;
    if ($this->image instanceof TemporaryUploadedFile) {
      $filename =
        Str::slug($this->name) .
        '-' .
        time() .
        '.' .
        $this->image->getClientOriginalExtension();

      $imagePath = $this->image->storeAs('images/products', $filename, 'public');
    }

    Product::updateOrCreate(
      ['id' => $this->productId],
      [
        'name' => $this->name,
        'sku' => $this->sku,
        'description' => e($this->description),
        'price' => $this->price,
        'stock' => $this->stock,
        'image' => $imagePath ?? Product::find($this->productId)?->image,
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
