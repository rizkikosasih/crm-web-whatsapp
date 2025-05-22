<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Services\Api\Implements\RapiwhaApiService;
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

  protected RapiwhaApiService $rapiwha;

  public function __construct()
  {
    $this->rapiwha = new RapiwhaApiService();
  }

  public function save()
  {
    $messages = [];

    if ($this->image instanceof TemporaryUploadedFile) {
      $rules['image'] = 'image|max:2048';
      $messages['image.image'] = 'Format file yang diperbolehkan hanya gambar';
      $messages['image.max'] = 'Ukuran gambar maksimal 2MB';
    }

    $validated = $this->validate($rules, $messages);

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
        'description' => $this->description,
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
    $this->image = $product->image;
    $this->isEdit = true;
  }

  public function delete($id)
  {
    Product::destroy($id);
    session()->flash('success', 'Produk dihapus.');
  }

  public function resetForm()
  {
    $this->reset([
      'productId',
      'name',
      'description',
      'price',
      'stock',
      'image',
      'isEdit',
    ]);
  }

  public function sendWA($productId)
  {
    $product = Product::where(['id' => $productId])
      ->limit(1)
      ->get();

    $result = $this->rapiwha->sendMessage(
      '6285777838862',
      'Test Pesan Produk: ' . (isset($product->image) ? $product->image : '')
    );

    dd($result);
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
