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
    $client->setAuthConfig(storage_path('app/public/google-service-account.json'));
    $client->addScope(Drive::DRIVE);
    $this->service = new Drive($client);
  }

  /**
   * Upload a file to Google Drive.
   *
   * @param string $filePath Local file path relative to 'storage/app/public'.
   * @param string $fileName Name for the file in Google Drive.
   * @param string|null $parentFolderId Optional folder ID.
   * @param bool $isPublic Whether the file should be publicly accessible.
   * @return string Public URL to access the uploaded file.
   * @throws Exception
   */
  public function upload(
    string $filePath,
    string $fileName,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string {
    try {
      $localPath = storage_path('app/public/' . $filePath);

      $fileMetadata = new DriveFile([
        'name' => $fileName,
        'parents' => [$parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID')],
      ]);

      $file = $this->service->files->create($fileMetadata, [
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
        $this->service->permissions->create($file->id, $permission);
      }

      return 'https://drive.google.com/uc?export=view&id=' . $file->id;
    } catch (Exception $e) {
      throw new Exception('Google Drive upload failed: ' . $e->getMessage());
    }
  }

  /**
   * Delete a file from Google Drive by its file ID.
   *
   * @param string $fileId Google Drive File ID.
   * @return bool True if deletion was successful.
   * @throws Exception
   */
  public function delete(string $fileId): bool
  {
    try {
      $this->service->files->delete($fileId);
      return true;
    } catch (\Google_Service_Exception $e) {
      return false;
    }
  }

  function getFileId(string $url): ?string
  {
    $parts = parse_url($url);

    if (!isset($parts['query'])) {
      return null;
    }

    parse_str($parts['query'], $queryParams);

    return $queryParams['id'] ?? null;
  }
}
