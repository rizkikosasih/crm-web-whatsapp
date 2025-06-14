<?php

namespace App\Services\Api\Implements;

use App\Services\Api\GoogleDriveServiceInterface;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService implements GoogleDriveServiceInterface
{
  protected Drive $service;

  public function __construct()
  {
    $client = new GoogleClient();

    $serviceAccountPath = storage_path('app/public/google-service-account.json');
    if (!file_exists($serviceAccountPath)) {
      throw new Exception(
        "Google Drive Service Account file not found at: {$serviceAccountPath}"
      );
    }

    $client->setAuthConfig($serviceAccountPath);
    $client->addScope(Drive::DRIVE);
    $this->service = new Drive($client);
  }

  protected function resolveFolder(?string $folderName, ?string $parentFolderId): string
  {
    if (!$folderName) {
      return $parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID');
    }

    $existing = $this->findFolder($folderName, $parentFolderId);
    return $existing ?? $this->createFolder($folderName, $parentFolderId);
  }

  protected function findFolder(string $folderName, ?string $parentFolderId): ?string
  {
    $query = sprintf(
      "mimeType = 'application/vnd.google-apps.folder' and name = '%s' and trashed = false",
      addslashes($folderName)
    );
    if ($parentFolderId) {
      $query .= " and '{$parentFolderId}' in parents";
    }

    $results = $this->service->files->listFiles([
      'q' => $query,
      'fields' => 'files(id, name)',
      'pageSize' => 1,
    ]);

    $files = $results->getFiles();
    return count($files) > 0 ? $files[0]->getId() : null;
  }

  protected function createFolder(string $folderName, ?string $parentFolderId): string
  {
    $folderMetadata = new DriveFile([
      'name' => $folderName,
      'mimeType' => 'application/vnd.google-apps.folder',
      'parents' => $parentFolderId ? [$parentFolderId] : [],
    ]);

    $folder = $this->service->files->create($folderMetadata, [
      'fields' => 'id',
    ]);

    return $folder->getId();
  }

  protected function findFileIdByName(string $fileName, ?string $folderId = null): ?string
  {
    $query = sprintf("name = '%s' and trashed = false", addslashes($fileName));
    if ($folderId) {
      $query .= " and '{$folderId}' in parents";
    }

    $results = $this->service->files->listFiles([
      'q' => $query,
      'fields' => 'files(id, name)',
      'pageSize' => 1,
    ]);

    $files = $results->getFiles();
    return count($files) > 0 ? $files[0]->getId() : null;
  }

  protected function getFileIdFromUrl(string $url): ?string
  {
    // 1. From query parameter `id`
    $parts = parse_url($url);
    if (isset($parts['query'])) {
      parse_str($parts['query'], $queryParams);
      if (isset($queryParams['id'])) {
        return $queryParams['id'];
      }
    }

    // 2. From URL format /d/FILE_ID/
    if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $url, $matches)) {
      return $matches[1];
    }

    return null;
  }

  public function delete(string $fileIdOrFileUrl): bool
  {
    try {
      $fileId = $this->getFileIdFromUrl($fileIdOrFileUrl) ?? $fileIdOrFileUrl;
      $this->service->files->delete($fileId);
      return true;
    } catch (Exception) {
      return false;
    }
  }

  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string {
    try {
      $localPath = storage_path('app/public/' . ltrim($filePath, '/'));

      if (!file_exists($localPath)) {
        throw new Exception("File not found: {$localPath}");
      }

      $parentFolderId = $parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID');
      $targetFolderId = $this->resolveFolder($folderName, $parentFolderId);

      $fileMetadata = new DriveFile([
        'name' => $fileName,
        'parents' => [$targetFolderId],
      ]);

      $file = $this->service->files->create($fileMetadata, [
        'data' => file_get_contents($localPath),
        'mimeType' => mime_content_type($localPath) ?: 'application/octet-stream',
        'uploadType' => 'multipart',
        'fields' => 'id',
      ]);

      if ($isPublic) {
        $permission = new Permission([
          'type' => 'anyone',
          'role' => 'reader',
        ]);
        $this->service->permissions->create($file->getId(), $permission);
      }

      return 'https://drive.google.com/uc?export=view&id=' . $file->getId();
    } catch (Exception $e) {
      throw new Exception('Google Drive upload failed: ' . $e->getMessage(), 0, $e);
    }
  }
}
