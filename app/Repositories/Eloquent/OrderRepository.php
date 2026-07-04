<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;

class OrderRepository
{
    /**
     * Get paginated orders with search and filter parameters.
     */
    public function getPaginated(
        int $perPage,
        ?string $search,
        ?string $status = null,
        ?string $dateStart = null,
        ?string $dateEnd = null,
    ) {
        return Order::query()
            ->with('customer')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('customer', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when(is_numeric($status), fn($q) => $q->where('status', $status))
            ->when(
                $dateStart,
                fn($q) => $q->whereDate('order_date', '>=', Carbon::parse($dateStart)),
            )
            ->when($dateEnd, fn($q) => $q->whereDate('order_date', '<=', Carbon::parse($dateEnd)))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find order by ID with customer, orderItems, and product relationships.
     */
    public function findWithRelations(int $id)
    {
        return Order::with(['customer', 'orderItems.product'])->findOrFail($id);
    }

    /**
     * Create an order record.
     */
    public function create(array $data)
    {
        return Order::create([
            'customer_id' => $data['customer_id'],
            'user_id' => $data['user_id'],
            'status' => $data['status'] ?? 0,
            'total_amount' => $data['total_amount'],
            'order_date' => $data['order_date'] ?? now(),
        ]);
    }

    /**
     * Create order item records.
     */
    public function createItems(int $orderId, array $items)
    {
        $createdItems = [];
        foreach ($items as $item) {
            $createdItems[] = OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }
        return $createdItems;
    }

    /**
     * Update order status and related fields (proof of payment, invoice link).
     */
    public function updateStatus(
        int $id,
        int $status,
        ?string $proofOfPayment = null,
        ?string $linkPdf = null,
    ) {
        $order = Order::findOrFail($id);

        $updateData = ['status' => $status];
        if ($proofOfPayment) {
            $updateData['proof_of_payment'] = $proofOfPayment;
        }
        if ($linkPdf) {
            $updateData['link_pdf'] = $linkPdf;
        }

        $order->update($updateData);
        return $order;
    }

    /**
     * Count orders by status and date range.
     */
    public function countByStatus(int $status, $startDate, $endDate)
    {
        return Order::where('status', $status)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get daily totals by status and date range for charts.
     */
    public function getDailyTotalByStatus(int $status, $startDate, $endDate)
    {
        return Order::selectRaw('DATE(order_date) as date, COUNT(*) as total')
            ->where('status', $status)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->all();
    }
}
