<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Services\Contracts\InvoiceServiceInterface;

class InvoiceService implements InvoiceServiceInterface
{
  public function generate(Order $order): string
  {
    $itemCount = $order->orderItems->count() + 3;

    $baseHeight = 300; // header, info customer, footer
    $itemHeight = 30; // tinggi estimasi tiap baris item
    $maxHeight = 842; // max height set ke A4 (pt)

    $estimatedHeight = $baseHeight + $itemCount * $itemHeight;
    if ($estimatedHeight > $maxHeight) {
      $estimatedHeight = $maxHeight;
    }

    $pdf = PDF::loadView('invoices.order', ['order' => $order])->setPaper([
      0,
      0,
      595.28,
      $estimatedHeight,
    ]); // lebar A4, tinggi dinamis

    return $pdf->output();
  }
}
