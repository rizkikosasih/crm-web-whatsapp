<?php

namespace App\Services;

use App\Repositories\Eloquent\OrderRepository;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Count orders by status.
     */
    public function countByStatus(int $status, $startDate, $endDate)
    {
        return $this->orderRepository->countByStatus($status, $startDate, $endDate);
    }

    /**
     * Get daily totals.
     */
    public function getDailyTotalByStatus(int $status, $startDate, $endDate)
    {
        return $this->orderRepository->getDailyTotalByStatus($status, $startDate, $endDate);
    }
}
