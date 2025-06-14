<?php

namespace App\Services\Api\Implements;

use App\Services\Api\GoogleDriveServiceInterface;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

class GoogleDriveService implements GoogleDriveServiceInterface
{
  protected string $serviceAccountPath;
  protected Client $http;

  public function __construct()
  {
    $this->serviceAccountPath = storage_path('app/public/google-service-account.json');
    $this->http = new Client();
  }

  protected function getAccessToken(): string
  {
    $account = json_decode(file_get_contents($this->serviceAccountPath), true);
    $now = time();
    $payload = [
      'iss' => $account['client_email'],
      'scope' => 'https://www.googleapis.com/auth/drive',
      'aud' => 'https://oauth2.googleapis.com/token',
      'iat' => $now,
      'exp' => $now + 3600,
    ];

    $jwt = JWT::encode($payload, $account['private_key'], 'RS256');

    $response = $this->http->post('https://oauth2.googleapis.com/token', [
      'form_params' => [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt,
      ],
    ]);

    $data = json_decode($response->getBody(), true);
    return $data['access_token'];
  }

  protected function getHeaders(): array
  {
    return [
      'Authorization' => 'Bearer ' . $this->getAccessToken(),
    ];
  }

  protected function resolveFolder(
    ?string $folderName,
    ?string $parentFolderId = null
  ): string {
    if (!$folderName) {
      return $parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID');
    }

    $existing = $this->findFolder($folderName, $parentFolderId);
    return $existing ?? $this->createFolder($folderName, $parentFolderId);
  }

  protected function findFolder(
    string $folderName,
    ?string $parentFolderId = null
  ): ?string {
    $query = "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and trashed=false";
    if ($parentFolderId) {
      $query .= " and '{$parentFolderId}' in parents";
    }

    $response = $this->http->get('https://www.googleapis.com/drive/v3/files', [
      'headers' => $this->getHeaders(),
      'query' => [
        'q' => $query,
        'fields' => 'files(id, name)',
        'spaces' => 'drive',
      ],
    ]);

    $data = json_decode($response->getBody(), true);
    return count($data['files']) > 0 ? $data['files'][0]['id'] : null;
  }

  protected function createFolder(
    string $folderName,
    ?string $parentFolderId = null
  ): string {
    $folderMetadata = [
      'name' => $folderName,
      'mimeType' => 'application/vnd.google-apps.folder',
    ];
    if ($parentFolderId) {
      $folderMetadata['parents'] = [$parentFolderId];
    }

    $response = $this->http->post('https://www.googleapis.com/drive/v3/files', [
      'headers' => array_merge($this->getHeaders(), [
        'Content-Type' => 'application/json',
      ]),
      'body' => json_encode($folderMetadata),
    ]);

    $data = json_decode($response->getBody(), true);
    return $data['id'];
  }

  public function upload(
    string $filePath,
    string $fileName,
    ?string $folderName = null,
    ?string $parentFolderId = null,
    bool $isPublic = true
  ): string {
    try {
      $localPath = storage_path('app/public/' . $filePath);
      $folderId = $this->resolveFolder(
        $folderName,
        $parentFolderId ?? env('GOOGLE_DRIVE_FOLDER_ID')
      );

      $fileMetadata = [
        'name' => $fileName,
        'parents' => [$folderId],
      ];

      $multipart = [
        [
          'name' => 'metadata',
          'contents' => json_encode($fileMetadata),
          'headers' => ['Content-Type' => 'application/json'],
        ],
        [
          'name' => 'file',
          'contents' => fopen($localPath, 'r'),
          'headers' => ['Content-Type' => mime_content_type($localPath)],
        ],
      ];

      $response = $this->http->post(
        'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart',
        [
          'headers' => $this->getHeaders(),
          'multipart' => $multipart,
        ]
      );

      $data = json_decode($response->getBody(), true);

      if ($isPublic) {
        $this->setPublicPermission($data['id']);
      }

      return 'https://drive.google.com/uc?export=view&id=' . $data['id'];
    } catch (GuzzleException | Exception $e) {
      throw new Exception('Google Drive upload failed: ' . $e->getMessage());
    }
  }

  protected function setPublicPermission(string $fileId): void
  {
    $this->http->post("https://www.googleapis.com/drive/v3/files/{$fileId}/permissions", [
      'headers' => array_merge($this->getHeaders(), [
        'Content-Type' => 'application/json',
      ]),
      'body' => json_encode([
        'role' => 'reader',
        'type' => 'anyone',
      ]),
    ]);
  }

  public function delete(string $fileIdOrFileUrl): bool
  {
    try {
      $fileId = $this->getFileIdFromUrl($fileIdOrFileUrl);
      if (!$fileId) {
        throw new Exception("Invalid Google Drive file ID or URL: {$fileIdOrFileUrl}");
      }

      $this->http->delete("https://www.googleapis.com/drive/v3/files/{$fileId}", [
        'headers' => $this->getHeaders(),
      ]);

      return true;
    } catch (GuzzleException | Exception $e) {
      throw new Exception('Google Drive delete failed: ' . $e->getMessage());
    }
  }

  protected function getFileIdFromUrl(string $url): ?string
  {
    $parts = parse_url($url);
    parse_str($parts['query'] ?? '', $queryParams);
    if (isset($queryParams['id'])) {
      return $queryParams['id'];
    }
    if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $url, $matches)) {
      return $matches[1];
    }
    return null;
  }
}
