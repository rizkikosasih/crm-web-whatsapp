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
  protected string $baseUrl = 'https://panel.rapiwha.com/send_message.php';
  protected string $apiKey;

  public function __construct()
  {
    $this->apiKey = config('services.rapiwha.key');
    $this->baseUrl = url('fake-response');
  }

  public function sendMessage(
    string $number,
    string $text,
    string|null $image = null
  ): JsonResponse {
    DB::beginTransaction();
    try {
      if (isset($image)) {
        Http::timeout(10)->get($this->baseUrl, [
          'apikey' => urlencode($this->apiKey),
          'number' => urlencode($number),
          'text' => urlencode($image),
        ]);
      }

      $response = Http::timeout(10)->get($this->baseUrl, [
        'apikey' => urlencode($this->apiKey),
        'number' => urlencode($number),
        'text' => urlencode($text),
      ]);

      if ($response->successful()) {
        $result = $response->json();

        if ($result->result_code === 0) {
          $customer = Customer::where(['phone' => $number]);
          Message::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'message' => e($text),
            'image' => $image ?? null,
          ]);
        }
        return $result;
      }

      return response()->json(
        [
          'success' => false,
          'error' => $response->status() . ': ' . $response->body(),
        ],
        $response->status()
      );
    } catch (\Exception $e) {
      DB::rollBack();
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
