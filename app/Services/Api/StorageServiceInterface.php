<?php

namespace App\Services\Api;

interface StorageServiceInterface
{
  public function uploadIfNotExists(mixed $file): ?string;
}
