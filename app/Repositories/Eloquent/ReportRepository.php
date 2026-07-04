<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportRepository
{
    /**
     * Get orders matching filters for report.
     */
    public function getOrderReport(?string $dateStart, ?string $dateEnd, ?string $status)
    {
        return Order::with(['customer', 'orderItems.product'])
            ->when($status !== '' && $status !== null, fn($q) => $q->where('status', $status))
            ->when($dateStart, fn($q) => $q->whereDate('order_date', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->whereDate('order_date', '<=', $dateEnd))
            ->latest()
            ->get();
    }

    /**
     * Get products sales statistics.
     */
    public function getProductReport(?string $dateStart, ?string $dateEnd)
    {
        return OrderItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * price) as total_income'),
        )
            ->whereHas('order', function ($q) use ($dateStart, $dateEnd) {
                $q->where('status', '!=', 4)
                    ->when($dateStart, fn($q2) => $q2->whereDate('order_date', '>=', $dateStart))
                    ->when($dateEnd, fn($q2) => $q2->whereDate('order_date', '<=', $dateEnd));
            })
            ->with('product:id,name')
            ->groupBy('product_id')
            ->get();
    }
}
