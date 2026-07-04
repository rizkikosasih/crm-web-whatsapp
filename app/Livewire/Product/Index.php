<?php

namespace App\Livewire\Product;

use App\Services\ProductService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
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

    public function save(ProductService $productService)
    {
        $this->validate();

        try {
            $productService->save(
                [
                    'name' => $this->name,
                    'sku' => $this->sku,
                    'description' => $this->description,
                    'price' => $this->price,
                    'stock' => $this->stock,
                ],
                $this->image,
                $this->productId,
            );

            $this->resetForm();
            $this->dispatch('showSuccess', message: 'Produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            logger()->error('Gagal menyimpan produk: ' . $e->getMessage());
            $this->dispatch('showError', message: 'Terjadi kesalahan saat menyimpan produk.');
        }
    }

    public function edit($id, ProductService $productService)
    {
        $product = $productService->find($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->sku = $product->sku;
        $this->image = $product->image;
        $this->isEdit = true;
        $this->dispatch('open-form-modal');
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
        $this->dispatch('close-form-modal');
    }

    public function render(ProductService $productService)
    {
        $items = $productService->getPaginated($this->perPage, $this->search);
        return view('livewire.product.index', compact('items'));
    }
}
