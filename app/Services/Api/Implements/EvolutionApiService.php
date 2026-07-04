<?php

namespace App\Services\Api\Implements;

use App\Models\Customer;
use App\Models\Message;
use App\Services\Api\SendMessageApiServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EvolutionApiService implements SendMessageApiServiceInterface
{
    protected ?string $baseUrl;
    protected ?string $apiKey;
    protected ?string $instanceName;

    public function __construct()
    {
        $this->apiKey = waApiKey();
        $this->baseUrl = waApiUrl();
        $this->instanceName = waInstanceName() ?: 'crm-whatsapp';
    }

    /**
     * Send message using Evolution API.
     */
    public function sendMessage(
        string $number,
        string $text,
        ?string $imageUrl = null,
        bool $isObject = true,
    ): array|object {
        try {
            DB::beginTransaction();

            $customer = Customer::where('phone', $number)->first();
            if (!$customer) {
                return $this->formatResponse(
                    false,
                    'Nomor Handphone tidak ditemukan dalam daftar pelanggan',
                    $isObject,
                );
            }

            // Clean phone number from spaces, dashes, or plus sign
            $cleanNumber = preg_replace('/[^0-9]/', '', $number);

            $headers = [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            if ($imageUrl) {
                // If there's a file attached, use sendMedia
                $mediaType = str_ends_with(strtolower($imageUrl), '.pdf') ? 'document' : 'image';
                $endpoint = rtrim($this->baseUrl, '/') . "/message/sendMedia/{$this->instanceName}";

                $response = Http::withHeaders($headers)
                    ->timeout(15)
                    ->retry(3, 1000)
                    ->post($endpoint, [
                        'number' => $cleanNumber,
                        'media' => $imageUrl,
                        'mediatype' => $mediaType,
                        'caption' => $text,
                    ]);
            } else {
                // If it's text-only, use sendText
                $endpoint = rtrim($this->baseUrl, '/') . "/message/sendText/{$this->instanceName}";

                $response = Http::withHeaders($headers)
                    ->timeout(15)
                    ->retry(3, 1000)
                    ->post($endpoint, [
                        'number' => $cleanNumber,
                        'text' => $text,
                    ]);
            }

            if ($response->successful()) {
                $result = $response->json();

                // Save to db message history
                Message::create([
                    'customer_id' => $customer->id,
                    'user_id' => Auth::id(),
                    'message' => e($text),
                    'image' => $imageUrl,
                ]);

                DB::commit();
                return $this->formatResponse(true, 'Message sent successfully', $isObject, $result);
            }

            return $this->formatResponse(
                false,
                'Evolution API Error: ' . $response->status() . ' - ' . $response->body(),
                $isObject,
            );
        } catch (\Exception $e) {
            DB::rollBack();
            logger('Evolution API Exception: ' . $e->getMessage());
            return $this->formatResponse(false, $e->getMessage(), $isObject);
        }
    }

    /**
     * Format standardized response array/object.
     */
    protected function formatResponse(
        bool $success,
        string $message,
        bool $asObject = true,
        array $data = [],
    ): array|object {
        $result = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        return $asObject ? (object) $result : $result;
    }
}
