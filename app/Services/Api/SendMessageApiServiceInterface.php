<?php

namespace App\Services\Api;

interface SendMessageApiServiceInterface
{
  public function sendMessage(
    string $number,
    string $text,
    ?string $imageUrl = null,
    bool $isObject = true
  ): array|object;
}
