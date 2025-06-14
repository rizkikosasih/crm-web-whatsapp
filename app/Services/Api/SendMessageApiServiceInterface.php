<?php

namespace App\Services\Api;

interface SendMessageApiServiceInterface
{
  public function sendMessage(
    string $number,
    string $text,
    bool $isObject = true
  ): array|object;
}
