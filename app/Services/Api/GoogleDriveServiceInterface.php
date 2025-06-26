<?php

namespace App\Services\Api;

interface GoogleDriveServiceInterface
{
  /**
   * Upload a file to Google Drive.
   *
   * @param string $filePath
   * @param string $fileName
   * @param string|null $folderName
   * @param string|null $parentFolderId
   * @param bool $isPublic
   * @return string
   */
  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string;

  /**
   * Upload a pdf content to Google Drive.
   *
   * @param string $filePath
   * @param string $fileName
   * @param string|null $folderName
   * @param string|null $parentFolderId
   * @param bool $isPublic
   * @return string
   */
  public function uploadPdfContent(
    string $pdfContent,
    string $fileName,
    ?string $folderName = null,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string;

  /**
   * Delete a file from Google Drive by its file ID or URL.
   *
   * @param string $fileIdOrFileUrl
   * @param string|null $folderName
   * @return bool
   */
  public function delete(string $fileIdOrFileUrl): bool;
}
