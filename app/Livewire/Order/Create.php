<?php

namespace App\Livewire\Order;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Services\Api\Implements\RapiwhaApiService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Create extends Component
{
  #[Locked]
  public $title = 'Form Pemesanan';

  public $customer_id;
  public $customerSearch, $selectedCustomerName;

  public $product_id;
  public $quantity = 1;
  public $productSearch, $selectedProductName;

  /* format: [['product_id' => 1, 'name' => '...', 'price' => 1000, 'quantity' => 2]] */
  public $orderItems = [];

  protected RapiwhaApiService $rapiwha;

  public function __construct()
  {
    $this->rapiwha = new RapiwhaApiService();
  }

  public function selectCustomer($id, $name)
  {
    $this->customer_id = $id;
    $this->selectedCustomerName = $name;
    $this->customerSearch = $name;
  }

  public function selectProduct($id, $name)
  {
    $this->product_id = $id;
    $this->selectedProductName = $name;
    $this->productSearch = $name;
  }

  public function addProduct()
  {
    $product = Product::find($this->product_id);
    if (!$product) {
      return;
    }

    $existingIndex = collect($this->orderItems)->search(
      fn($item) => $item['product_id'] == $product->id
    );

    $currentQuantity =
      $existingIndex !== false ? $this->orderItems[$existingIndex]['quantity'] : 0;

    $requestedTotal = $currentQuantity + $this->quantity;

    // Cek stok
    if ($requestedTotal > $product->stock) {
      $this->dispatch('showError', [
        'message' => "Stok produk '{$product->name}' tidak mencukupi. Sisa stok: {$product->stock}",
      ]);
      return;
    }

    // Tambah atau update item
    if ($existingIndex !== false) {
      $this->orderItems[$existingIndex]['quantity'] = $requestedTotal;
    } else {
      $this->orderItems[] = [
        'product_id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $this->quantity,
      ];
    }

    $this->reset(['product_id', 'selectedProductName', 'productSearch', 'quantity']);
  }

  public function removeItem($index)
  {
    unset($this->orderItems[$index]);
    $this->orderItems = array_values($this->orderItems);
  }

  public function save()
  {
    if (empty($this->customer_id) || empty($this->orderItems)) {
      $this->dispatch('showError', [
        'message' => 'Customer dan produk harus dipilih.',
      ]);
      return;
    }

    foreach ($this->orderItems as $item) {
      $product = Product::find($item['product_id']);

      if (!$product) {
        $this->dispatch('showError', [
          'message' => "Produk dengan ID {$item['product_id']} tidak ditemukan.",
        ]);
        return;
      }

      if ($item['quantity'] > $product->stock) {
        $this->dispatch('showError', [
          'message' => "Stok produk '{$product->name}' tidak mencukupi. Tersedia: {$product->stock}, diminta: {$item['quantity']}.",
        ]);
        return;
      }
    }

    // Simpan order
    DB::beginTransaction();

    try {
      $order = Order::create([
        'customer_id' => $this->customer_id,
        'status' => 0,
        'total_amount' => collect($this->orderItems)->sum(
          fn($item) => $item['price'] * $item['quantity']
        ),
        'order_date' => now(),
      ]);

      foreach ($this->orderItems as $item) {
        $order->orderItems()->create([
          'product_id' => $item['product_id'],
          'price' => $item['price'],
          'quantity' => $item['quantity'],
        ]);

        // Kurangi stok produk
        Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
      }

      DB::commit();

      session()->flash('success', 'Pesanan berhasil dibuat.');
      return $this->redirect(route('order'), true);
    } catch (\Exception $e) {
      DB::rollBack();

      $this->dispatch('showError', [
        'message' => "Terjadi kesalahan saat menyimpan order: {$e->getMessage()}",
      ]);
    }
  }

  public function render()
  {
    $customers = Customer::when($this->customerSearch, function ($query) {
      $query->whereAny(['name', 'phone'], 'like', '%' . $this->customerSearch . '%');
    })
      ->limit(3)
      ->get();

    $products = Product::when($this->productSearch, function ($query) {
      $query->whereAny(['name', 'description'], 'like', '%' . $this->productSearch . '%');
    })
      ->limit(3)
      ->get();

    return view('livewire.order.create', [
      'customers' => $customers,
      'products' => $products,
    ]);
  }
}
