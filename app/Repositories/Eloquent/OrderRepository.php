<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;

class OrderRepository
{
    /**
     * Count orders by status.
     */
    public function countByStatus(int $status, $startDate, $endDate)
    {
        return Order::where('status', $status)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get daily totals.
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
