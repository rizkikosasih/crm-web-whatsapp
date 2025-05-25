<?php

namespace App\Livewire\WhatsappApiSetting;

use App\Models\WhatsappApiSetting;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
  #[Locked]
  public $title = 'Whatsapp API';

  public $templateId;
  public $apiKey;
  public $apiUrl;
  public $isEdit = false;

  public function mount()
  {
    $setting = WhatsappApiSetting::first();

    if ($setting) {
      $this->templateId = $setting->id;
      $this->apiKey = $setting->key;
      $this->apiUrl = $setting->url;
      $this->isEdit = true;
    }
  }

  public function save()
  {
    $this->validate(
      [
        'apiKey' => 'required',
        'apiUrl' => 'required|url',
      ],
      [
        'apiKey.required' => 'API Key tidak boleh kosong',
        'apiUrl.required' => 'API URL tidak boleh kosong',
        'apiUrl.url' =>
          'API URL harus berupa URL yang valid (contoh: https://example.com)',
      ]
    );

    WhatsappApiSetting::updateOrCreate(
      ['id' => $this->templateId],
      [
        'key' => $this->apiKey,
        'url' => $this->apiUrl,
      ]
    );

    $this->isEdit = true;
    session()->flash('success', 'Whatsapp API berhasil disimpan!');
  }

  public function resetForm()
  {
    $this->reset(['templateId', 'apiKey', 'apiUrl', 'isEdit']);
  }

  public function render()
  {
    return view('livewire.settings.whatsapp-api.index');
  }
}
