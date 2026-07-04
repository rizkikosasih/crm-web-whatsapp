<?php

namespace App\Livewire\Order;

use App\Services\CustomerService;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
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

    public function addProduct(ProductService $productService)
    {
        $product = $productService->find($this->product_id);
        if (!$product) {
            return;
        }

        $existingIndex = collect($this->orderItems)->search(
            fn($item) => $item['product_id'] == $product->id,
        );

        $currentQuantity =
            $existingIndex !== false ? $this->orderItems[$existingIndex]['quantity'] : 0;

        $requestedTotal = $currentQuantity + $this->quantity;

        // Check stock
        if ($requestedTotal > $product->stock) {
            $this->dispatch(
                'showError',
                message: "Stok produk '{$product->name}' tidak mencukupi. Sisa stok: {$product->stock}",
            );
            return;
        }

        // Add or update item in cart
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

    public function save(OrderService $orderService)
    {
        if (empty($this->customer_id) || empty($this->orderItems)) {
            $this->dispatch('showError', message: 'Customer dan produk harus dipilih.');
            return;
        }

        try {
            $orderService->createOrder([
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id(),
                'items' => $this->orderItems,
            ]);

            session()->flash('success', 'Pesanan berhasil dibuat.');
            return $this->redirect(route('transaksi-order'), true);
        } catch (\Exception $e) {
            $this->dispatch('showError', message: 'Gagal menyimpan order: ' . $e->getMessage());
        }
    }

    public function render(CustomerService $customerService, ProductService $productService)
    {
        $customers = [];
        if ($this->customerSearch) {
            $customers = $customerService->getPaginated(3, $this->customerSearch)->items();
        }

        $products = [];
        if ($this->productSearch) {
            $products = $productService->getPaginated(3, $this->productSearch)->items();
        }

        return view('livewire.order.create', [
            'customers' => $customers,
            'products' => $products,
        ]);
    }
}
