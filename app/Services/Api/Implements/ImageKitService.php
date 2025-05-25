<?php

namespace App\Services\Api\Implements;

use App\Services\Api\StorageServiceInterface;
use ImageKit\ImageKit;
use Illuminate\Support\Str;

class ImageKitService implements StorageServiceInterface
{
  protected $imageKit;

  public function __construct()
  {
    $this->imageKit = new ImageKit(
      env('IMAGEKIT_PUBLIC_KEY'),
      env('IMAGEKIT_PRIVATE_KEY'),
      env('IMAGEKIT_URL_ENDPOINT')
    );
  }

  public function uploadIfNotExists(mixed $file): ?string
  {
    return null;
    // $originalName = $file->getClientOriginalName();

    // $filename =
    //   Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) .
    //   '.' .
    //   strtolower($file->getClientOriginalExtension());

    // // Cek apakah file dengan nama sama sudah ada
    // $existing = $this->imageKit->listFiles([
    //   'path' => "$filename",
    //   'includeFolder' => false,
    // ]);

    // if (!empty($existing->result)) {
    //   // File sudah ada, ambil URL-nya
    //   return $existing->result[0]->url;
    // }

    // // Upload file baru
    // $upload = $this->imageKit->upload([
    //   'file' => fopen($file->getRealPath(), 'r'),
    //   'fileName' => $filename,
    //   'folder' => $folder,
    // ]);

    // return $upload->result->url ?? null;
  }
}
