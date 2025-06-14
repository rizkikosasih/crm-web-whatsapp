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
  protected ?Drive $service = null;

  protected function getService(): Drive
  {
    if (!$this->service) {
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
    return $this->service;
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
    $query =
      "mimeType = 'application/vnd.google-apps.folder' and name = '" .
      addslashes($folderName) .
      "' and trashed = false";
    if ($parentFolderId) {
      $query .= " and '$parentFolderId' in parents";
    }

    $service = $this->getService();

    $results = $service->files->listFiles([
      'q' => $query,
      'fields' => 'files(id, name)',
    ]);

    $files = $results->getFiles();
    return count($files) > 0 ? $files[0]->getId() : null;
  }

  protected function createFolder(string $folderName, ?string $parentFolderId): string
  {
    $folderMetadata = new DriveFile([
      'name' => $folderName,
      'mimeType' => 'application/vnd.google-apps.folder',
      'parents' => [$parentFolderId],
    ]);

    $service = $this->getService();

    $folder = $service->files->create($folderMetadata, [
      'fields' => 'id',
    ]);

    return $folder->id;
  }

  protected function findFileIdByName(string $fileName, ?string $folderId = null): ?string
  {
    $query = "name = '" . addslashes($fileName) . "' and trashed = false";
    if ($folderId) {
      $query .= " and '$folderId' in parents";
    }

    $service = $this->getService();

    $results = $service->files->listFiles([
      'q' => $query,
      'fields' => 'files(id, name)',
    ]);

    $files = $results->getFiles();
    return count($files) > 0 ? $files[0]->getId() : null;
  }

  protected function getFileIdFromUrl(string $url): ?string
  {
    // Ambil dari query parameter
    $parts = parse_url($url);
    parse_str($parts['query'] ?? '', $queryParams);
    if (isset($queryParams['id'])) {
      return $queryParams['id'];
    }

    // Ambil dari URL format .../d/{id}/
    if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $url, $matches)) {
      return $matches[1];
    }

    return null;
  }

  /**
   * Delete a file from Google Drive by its file ID.
   *
   * @param string $fileId Google Drive File ID.
   * @return bool True if deletion was successful.
   * @throws Exception
   */
  public function delete(string $fileIdOrFileUrl): bool
  {
    try {
      $fileId = $this->getFileIdFromUrl($fileIdOrFileUrl);
      $service = $this->getService();

      $service->files->delete($fileId);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Upload a file to Google Drive.
   *
   * @param string $filePath Local file path relative to 'storage/app/public'.
   * @param string $fileName Name for the file in Google Drive.
   * @param string|null $folderName Optional folder name in Google Drive.
   * @param string|null $parentFolderId Optional folder ID.
   * @param bool $isPublic Whether the file should be publicly accessible.
   * @return string Public URL to access the uploaded file.
   * @throws Exception
   */
  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string {
    try {
      $localPath = storage_path('app/public/' . $filePath);

      $parentFolderId = $parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID');
      $targetFolderId = $this->resolveFolder($folderName, $parentFolderId);

      $fileMetadata = new DriveFile([
        'name' => $fileName,
        'parents' => [$targetFolderId],
      ]);

      $service = $this->getService();

      $file = $service->files->create($fileMetadata, [
        'data' => file_get_contents($localPath),
        'mimeType' => mime_content_type($localPath),
        'uploadType' => 'multipart',
        'fields' => 'id',
      ]);

      if ($isPublic) {
        $permission = new Permission([
          'type' => 'anyone',
          'role' => 'reader',
        ]);

        $service = $this->getService();
        $service->permissions->create($file->id, $permission);
      }

      return 'https://drive.google.com/uc?export=view&id=' . $file->id;
    } catch (Exception $e) {
      throw new Exception('Google Drive upload failed: ' . $e->getMessage());
    }
  }
}
