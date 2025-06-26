<?php

namespace App\Services\Contracts;

use App\Models\Order;

interface InvoiceServiceInterface
{
  /**
   * Generate PDF invoice dari model Order.
   *
   * @param Order $order
   * @return string PDF binary content
   */
  public function generate(Order $order): string;
}
