<?php

namespace App\Livewire\WhatsappApiSetting;

use App\Services\WhatsappSettingsService;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
    #[Locked]
    public $title = 'Whatsapp API';

    public $templateId;
    public $apiKey;
    public $apiUrl;
    public $instanceName;
    public $isEdit = false;

    // WA Connector States
    public $qrCodeBase64 = null;
    public $connectionStatus = 'DISCONNECTED';
    public $isLoadingQr = false;

    public function mount(WhatsappSettingsService $whatsappSettingsService)
    {
        $setting = $whatsappSettingsService->getFirst();

        if ($setting) {
            $this->templateId = $setting->id;
            $this->apiKey = $setting->key;
            $this->apiUrl = $setting->url;
            $this->instanceName = $setting->instance_name;
            $this->isEdit = true;
        }
    }

    public function getQrCode()
    {
        if (!$this->apiUrl || !$this->apiKey || !$this->instanceName) {
            $this->dispatch('showError', [
                'message' =>
                    'Silakan isi & simpan konfigurasi API URL, API Key, dan Instance Name terlebih dahulu.',
            ]);
            return;
        }

        $this->isLoadingQr = true;
        $this->qrCodeBase64 = null;
        $this->dispatch('open-wa-connector');

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
                // If 404, the instance name might not exist on the server yet. Let's create it.
                logger('Evolution API Instance not found. Creating one...');
                $createResponse = Http::withHeaders($headers)
                    ->timeout(12)
                    ->post($createUrl, [
                        'instanceName' => $this->instanceName,
                        'qrcode' => true,
                    ]);
                logger(
                    'Evolution API Create Instance status: ' .
                        $createResponse->status() .
                        ' body: ' .
                        $createResponse->body(),
                );

                if ($createResponse->successful()) {
                    // Try to connect again to get the newly generated QR
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
                    ($connectData['instance']['state'] ?? 'connecting');
                if ($status === 'open' || $status === 'CONNECTED') {
                    $this->connectionStatus = 'CONNECTED';
                } else {
                    $this->qrCodeBase64 = $connectData['qrcode']['base64'] ?? null;
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

    public function save(WhatsappSettingsService $whatsappSettingsService)
    {
        $this->validate(
            [
                'apiKey' => 'required',
                'apiUrl' => 'required|url',
                'instanceName' => 'required',
            ],
            [
                'apiKey.required' => 'API Key tidak boleh kosong',
                'apiUrl.required' => 'API URL tidak boleh kosong',
                'apiUrl.url' => 'API URL harus berupa URL yang valid (contoh: https://example.com)',
                'instanceName.required' => 'Instance Name tidak boleh kosong',
            ],
        );

        $whatsappSettingsService->save(
            [
                'key' => $this->apiKey,
                'url' => $this->apiUrl,
                'instance_name' => $this->instanceName,
            ],
            $this->templateId,
        );

        $this->isEdit = true;
        session()->flash('success', 'Whatsapp API berhasil disimpan!');
    }

    public function resetForm()
    {
        $this->reset(['templateId', 'apiKey', 'apiUrl', 'instanceName', 'isEdit']);
    }

    public function render()
    {
        return view('livewire.settings.whatsapp-api.index');
    }
}
