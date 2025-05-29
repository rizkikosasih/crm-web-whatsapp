<?php

namespace App\Services\Api;

interface GoogleDriveServiceInterface
{
  /**
   * Upload an image to Google Drive.
   *
   * @param string $filePath The path to the file to upload.
   * @param string $fileName The name of the file in Google Drive.
   * @param string $mimeType The MIME type of the file.
   * @return string The URL of the uploaded file.
   */
  public function upload(string $filePath, string $fileName, string $mimeType): string;

  /**
   * Get the public URL of a file in Google Drive.
   *
   * @param string $fileId The ID of the file to retrieve.
   * @return string|null The public URL of the file, or null if not found.
   */
  public function getFileId(string $fileId): ?string;

  /**
   * Delete a file from Google Drive.
   *
   * @param string $fileId The ID of the file to delete.
   * @return bool True if the file was deleted successfully, false otherwise.
   */
  public function delete(string $fileId): bool;
}
