<?php

namespace App\Services\Api;

interface ImagekitServiceInterface
{
  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null
  ): string;

  public function uploadPdfContent(
    string $pdfContent,
    string $fileName,
    ?string $folderName = null
  ): string;

  public function delete(string $fileIdOrUrl): bool;
}
