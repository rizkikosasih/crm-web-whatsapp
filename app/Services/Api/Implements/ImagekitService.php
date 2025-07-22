<?php

namespace App\Services\Api\Implements;

use App\Services\Api\ImagekitServiceInterface;
use Exception;
use ImageKit\ImageKit;

class ImagekitService implements ImagekitServiceInterface
{
  protected ?ImageKit $client = null;

  protected function setClient(): ImageKit
  {
    return $this->client ??= new ImageKit(
      env('IMAGE_KIT_PUBLIC_KEY'),
      env('IMAGE_KIT_PRIVATE_KEY'),
      env('IMAGE_KIT_URL_ENDPOINT')
    );
  }

  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null
  ): string {
    $client = $this->setClient();

    $fileFullPath = storage_path('app/public/' . $filePath);

    if (!file_exists($fileFullPath)) {
      throw new Exception("File not found: {$fileFullPath}");
    }

    $uploadParams = [
      'file' => fopen($fileFullPath, 'r'),
      'fileName' => $fileName,
    ];

    if ($folderName) {
      $uploadParams['folder'] = '/' . trim($folderName, '/');
    }

    $result = $client->upload($uploadParams);

    if ($result->error) {
      throw new Exception('Upload error: ' . json_encode($result->error));
    }

    return $result->result->url;
  }

  public function uploadPdfContent(
    string $pdfContent,
    string $fileName,
    ?string $folderName = null
  ): string {
    $client = $this->setClient();

    $base64 = base64_encode($pdfContent);

    $uploadParams = [
      'file' => 'data:application/pdf;base64,' . $base64,
      'fileName' => $fileName,
    ];

    if ($folderName) {
      $uploadParams['folder'] = '/' . trim($folderName, '/');
    }

    $result = $client->upload($uploadParams);

    if ($result->error) {
      throw new Exception('Upload error: ' . json_encode($result->error));
    }

    return $result->result->url;
  }

  public function delete(string $fileIdOrUrl): bool
  {
    $client = $this->setClient();

    try {
      $fileId = $this->extractFileId($fileIdOrUrl);

      $result = $client->deleteFile($fileId);

      if ($result->error) {
        throw new \Exception('Delete error: ' . json_encode($result->error));
      }

      return true;
    } catch (\Throwable $e) {
      logger()->error('Gagal menghapus file ImageKit: ' . $e->getMessage());
      return false;
    }
  }

  protected function extractFileId(string $urlOrId): string
  {
    if (!str_starts_with($urlOrId, 'http')) {
      return $urlOrId; // Langsung fileId
    }

    $client = $this->setClient();

    $after = null;

    do {
      $response = $client->listFiles([
        'limit' => 100,
        'skip' => $after ?? 0,
      ]);

      foreach ($response->result as $file) {
        if ($this->normalizeUrl($file->url) === $this->normalizeUrl($urlOrId)) {
          return $file->fileId;
        }
      }

      $after = count($response->result) === 100 ? ($after ?? 0) + 100 : null;
    } while ($after !== null);

    throw new \Exception("FileId tidak ditemukan berdasarkan URL: {$urlOrId}");
  }

  protected function normalizeUrl(string $url): string
  {
    // Hilangkan trailing slash, tanda tanya dst agar pencocokan lebih pasti
    return rtrim(preg_replace('/\?.*/', '', $url), '/');
  }
}
