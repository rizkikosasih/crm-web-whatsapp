<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;

class ProductRepository
{
    /**
     * Get paginated products with search filter.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return Product::when($search, function ($query) use ($search) {
            $query->whereAny(['name', 'description'], 'like', '%' . $search . '%');
        })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find a product by ID.
     */
    public function find(int $id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Create or update a product.
     */
    public function createOrUpdate(array $data, ?int $id = null)
    {
        return Product::updateOrCreate(
            ['id' => $id],
            [
                'name' => $data['name'],
                'sku' => $data['sku'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'image' => $data['image'] ?? null,
                'image_url' => $data['image_url'] ?? null,
            ],
        );
    }

    /**
     * Decrement product stock.
     */
    public function decrementStock(int $id, int $quantity)
    {
        $product = $this->find($id);
        $product->decrement('stock', $quantity);
        return $product;
    }

    /**
     * Increment product stock.
     */
    public function incrementStock(int $id, int $quantity)
    {
        $product = $this->find($id);
        $product->increment('stock', $quantity);
        return $product;
    }

    /**
     * Get top selling products.
     */
    public function getTopSales($startDate, $endDate)
    {
        return \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('orders.status', '!=', 4)
            ->select(
                'products.name as product_name',
                \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_quantity'),
            )
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->get()
            ->pluck('total_quantity', 'product_name')
            ->all();
    }
}
