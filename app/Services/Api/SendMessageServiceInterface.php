<?php

namespace App\Services\Api;

use Illuminate\Http\JsonResponse;

interface SendMessageApiServiceInterface
{
  public function sendMessage(string $number, string $text): JsonResponse;
}
