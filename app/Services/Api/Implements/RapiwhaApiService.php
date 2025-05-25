<?php

namespace App\Services\Api\Implements;

use App\Models\Customer;
use App\Models\Message;
use App\Services\Api\SendMessageApiServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RapiwhaApiService implements SendMessageApiServiceInterface
{
  protected string $baseUrl, $apiKey;

  public function __construct()
  {
    $this->apiKey = config('services.rapiwha.key');
    $this->baseUrl = config('services.rapiwha.url');
  }

  public function sendMessage(
    string $number,
    string $text,
    ?string $image = ''
  ): JsonResponse {
    DB::beginTransaction();
    try {
      $customer = Customer::where('phone', $number)->first();
      if (!$customer) {
        return response()->json(
          [
            'success' => false,
            'message' => 'Nomor Handphone tidak ditemukan dalam daftar pelanggan',
          ],
          404
        );
      }

      /* For Testing */
      Http::fake([
        'rapiwha.com/*' => Http::response(
          [
            'success' => true,
            'description' => 'Message queued',
            'result_code' => 0,
          ],
          200
        ),
      ]);

      if ($image) {
        Http::timeout(10)->get($this->baseUrl, [
          'apikey' => $this->apiKey,
          'number' => $number,
          'text' => storage($image),
        ]);
      }

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
          if ($customer) {
            Message::create([
              'customer_id' => $customer->id,
              'user_id' => Auth::id(),
              'message' => e($text),
              'image' => $image ?: null,
            ]);
          }
        }

        DB::commit();

        return response()->json($result, $response->status());
      }

      return response()->json(
        [
          'success' => false,
          'message' => $response->status() . ': ' . $response->body(),
        ],
        500
      );
    } catch (\Exception $e) {
      DB::rollBack();
      logger('HTTP Error: ' . $e->getMessage());
      return response()->json(
        [
          'success' => false,
          'message' => $e->getMessage(),
        ],
        500
      );
    }
  }
}
