<?php

namespace App\Livewire\WhatsappApiSetting;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
    #[Locked]
    public string $title = 'Whatsapp API';

    public ?string $apiKey = null;
    public ?string $apiUrl = null;
    public ?string $instanceName = null;

    // WA Connector States
    public ?string $qrCodeBase64 = null;
    public string $connectionStatus = 'LOADING';
    public bool $isLoadingQr = false;

    public function mount()
    {
        $this->apiKey = config('services.evolution.key');
        $this->apiUrl = config('services.evolution.url');
        $this->instanceName = config('services.evolution.instance');
    }

    public function checkConnection()
    {
        if (!$this->apiUrl || !$this->apiKey || !$this->instanceName) {
            $this->connectionStatus = 'ERROR';
            $this->isLoadingQr = false;
            return;
        }

        $this->isLoadingQr = true;
        $this->qrCodeBase64 = null;
        $this->connectionStatus = 'LOADING';

        try {
            $baseUrlClean = rtrim($this->apiUrl, '/');
            $createUrl = "{$baseUrlClean}/instance/create";
            $connectUrl = "{$baseUrlClean}/instance/connect/{$this->instanceName}";
            $stateUrl = "{$baseUrlClean}/instance/connectionState/{$this->instanceName}";

            $headers = [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            // 1. Check if connected first
            $stateResponse = Http::withHeaders($headers)->timeout(5)->get($stateUrl);
            logger(
                'Evolution API Check Connection State status: ' .
                    $stateResponse->status() .
                    ' body: ' .
                    $stateResponse->body(),
            );

            if ($stateResponse->successful()) {
                $stateData = $stateResponse->json();
                $status =
                    $stateData['instance']['status'] ??
                    ($stateData['instance']['state'] ?? 'close');
                if ($status === 'CONNECTED' || $status === 'open') {
                    $this->connectionStatus = 'CONNECTED';
                    $this->isLoadingQr = false;
                    return;
                }
            }

            // 2. Request connection QR
            $connectResponse = Http::withHeaders($headers)->timeout(12)->get($connectUrl);
            logger(
                'Evolution API Get Connect initial status: ' .
                    $connectResponse->status() .
                    ' body: ' .
                    $connectResponse->body(),
            );

            if ($connectResponse->status() === 404 || !$connectResponse->successful()) {
                logger('Evolution API Instance not found. Creating one...');
                $createResponse = Http::withHeaders($headers)
                    ->timeout(12)
                    ->post($createUrl, [
                        'instanceName' => $this->instanceName,
                        'qrcode' => true,
                        'integration' => 'WHATSAPP-BAILEYS',
                    ]);
                logger(
                    'Evolution API Create Instance status: ' .
                        $createResponse->status() .
                        ' body: ' .
                        $createResponse->body(),
                );

                if ($createResponse->successful()) {
                    $connectResponse = Http::withHeaders($headers)->timeout(12)->get($connectUrl);
                    logger(
                        'Evolution API Get Connect retry status: ' .
                            $connectResponse->status() .
                            ' body: ' .
                            $connectResponse->body(),
                    );
                }
            }

            if ($connectResponse->successful()) {
                $connectData = $connectResponse->json();
                $status =
                    $connectData['instance']['status'] ??
                    ($connectData['instance']['state'] ?? ($connectData['status'] ?? 'connecting'));
                if ($status === 'open' || $status === 'CONNECTED') {
                    $this->connectionStatus = 'CONNECTED';
                } else {
                    $this->qrCodeBase64 =
                        $connectData['base64'] ?? ($connectData['qrcode']['base64'] ?? null);
                    $this->connectionStatus = 'DISCONNECTED';
                }
            } else {
                logger(
                    'Evolution API Connect final check failed with status: ' .
                        $connectResponse->status() .
                        ' body: ' .
                        $connectResponse->body(),
                );
                $this->connectionStatus = 'ERROR';
            }
        } catch (\Exception $e) {
            logger('Evolution API Connection Error: ' . $e->getMessage());
            $this->connectionStatus = 'ERROR';
        }

        $this->isLoadingQr = false;
    }

    public function disconnect()
    {
        $this->isLoadingQr = true;
        $this->connectionStatus = 'LOADING';

        try {
            $baseUrlClean = rtrim($this->apiUrl, '/');
            $logoutUrl = "{$baseUrlClean}/instance/logout/{$this->instanceName}";

            $headers = [
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ];

            $response = Http::withHeaders($headers)->timeout(12)->delete($logoutUrl);
            logger(
                'Evolution API Disconnect response status: ' .
                    $response->status() .
                    ' body: ' .
                    $response->body(),
            );

            // Re-check connection to get new QR
            $this->checkConnection();

            session()->flash('success', 'Berhasil memutuskan koneksi WhatsApp.');
        } catch (\Exception $e) {
            logger('Evolution API Logout Error: ' . $e->getMessage());
            $this->dispatch('showError', [
                'message' => 'Gagal memutuskan koneksi: ' . $e->getMessage(),
            ]);
            $this->connectionStatus = 'ERROR';
            $this->isLoadingQr = false;
        }
    }

    public function render()
    {
        return view('livewire.settings.whatsapp-api.index');
    }
}
