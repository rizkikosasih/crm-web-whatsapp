<?php

namespace App\Services;

use App\Repositories\Eloquent\ReportRepository;

class ReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Get order report.
     */
    public function getOrderReport(?string $dateStart, ?string $dateEnd, ?string $status)
    {
        return $this->reportRepository->getOrderReport($dateStart, $dateEnd, $status);
    }

    /**
     * Get product report.
     */
    public function getProductReport(?string $dateStart, ?string $dateEnd)
    {
        return $this->reportRepository->getProductReport($dateStart, $dateEnd);
    }
}
