<?php

namespace App\Livewire\WhatsappApiSetting;

use App\Services\WhatsappSettingsService;
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
