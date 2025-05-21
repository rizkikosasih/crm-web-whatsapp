<?php

namespace App\Services\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class RapiwhaApiService implements SendMessageApiServiceInterface
{
  protected string $baseUrl = 'https://panel.rapiwha.com/send_message.php';
  protected string $apiKey;

  public function __construct()
  {
    $this->apiKey = config('services.rapiwha.key');
  }

  public function sendMessage(string $number, string $text): JsonResponse
  {
    try {
      $response = Http::timeout(10)->get($this->baseUrl, [
        'apikey' => urlencode($this->apiKey),
        'number' => urlencode($number),
        'text' => urlencode($text),
      ]);

      if ($response->successful()) {
        return $response->json();
      }

      return response()->json(
        [
          'success' => false,
          'error' => $response->status() . ': ' . $response->body(),
        ],
        $response->status()
      );
    } catch (\Exception $e) {
      return response()->json(
        [
          'success' => false,
          'error' => 'Exception: ' . $e->getMessage(),
        ],
        500
      );
    }
  }
}
