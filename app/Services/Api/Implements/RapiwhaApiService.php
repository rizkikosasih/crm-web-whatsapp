<?php

namespace App\Services\Api\Implements;

use App\Models\Customer;
use App\Models\Message;
use App\Services\Api\SendMessageApiServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RapiwhaApiService implements SendMessageApiServiceInterface
{
  protected string $baseUrl, $apiKey;

  public function __construct()
  {
    $this->apiKey = waApiKey();
    $this->baseUrl = waApiUrl();
  }

  public function sendMessage(
    string $number,
    string $text,
    bool $isObject = true
  ): array|object {
    try {
      DB::beginTransaction();

      $customer = Customer::where('phone', $number)->first();
      if (!$customer) {
        return $this->formatResponse(
          false,
          'Nomor Handphone tidak ditemukan dalam daftar pelanggan',
          $isObject
        );
      }

      /* For Testing
      Http::fake([
        'rapiwha.com/*' => Http::response(
          [
            'success' => true,
            'description' => 'Message queued',
            'result_code' => 0,
          ],
          200
        ),
      ]); */

      // Kirim pesan utama (hanya 1x)
      $response = Http::timeout(10)
        ->retry(3, 1000)
        ->get($this->baseUrl, [
          'apikey' => $this->apiKey,
          'number' => $number,
          'text' => $text,
        ]);

      if ($response->successful()) {
        $result = $response->json();

        if (isset($result['result_code']) && $result['result_code'] === 0) {
          // Simpan ke DB
          Message::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'message' => e($text),
            'image' => null,
          ]);
        }

        DB::commit();
        return $this->formatResponse(
          $result['success'],
          $result['description'],
          $isObject
        );
      }

      return $this->formatResponse(
        false,
        $response->status() . ': ' . $response->body(),
        $isObject
      );
    } catch (\Exception $e) {
      DB::rollBack();
      logger('HTTP Error: ' . $e->getMessage());
      return $this->formatResponse(false, $e->getMessage(), $isObject);
    }
  }

  protected function formatResponse(
    bool $success,
    string $message,
    bool $asObject = true,
    array $data = []
  ): array|object {
    $result = [
      'success' => $success,
      'message' => $message,
      'data' => $data,
    ];

    return $asObject ? (object) $result : $result;
  }
}
