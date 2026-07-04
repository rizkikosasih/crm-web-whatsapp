<?php

namespace App\Livewire\Campaign;

use App\Services\CampaignService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Locked]
    public $title = 'Campaign Broadcast';

    #[Locked]
    public $tableHeader = [
        ['name' => 'No'],
        ['name' => 'Judul'],
        ['name' => 'Pesan'],
        ['name' => 'Gambar'],
        ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
    ];

    public $perPage = 5;
    public $search;

    public $campaignId;
    #[Validate('required', message: 'Judul tidak boleh kosong')]
    public $campaignTitle;
    #[Validate('required', message: 'Pesan tidak boleh kosong')]
    public $campaignMessage;
    public $image;

    public $isEdit = false;

    public function save(CampaignService $campaignService)
    {
        $this->validate();

        try {
            $campaignService->save(
                [
                    'title' => $this->campaignTitle,
                    'message' => $this->campaignMessage,
                    'created_by' => Auth::id(),
                ],
                $this->image,
                $this->campaignId,
            );

            $this->resetForm();
            $this->dispatch('showSuccess', message: 'Campaign berhasil disimpan.');
        } catch (\Throwable $e) {
            logger()->error('Gagal menyimpan campaign: ' . $e->getMessage());
            $this->dispatch('showError', message: 'Terjadi kesalahan saat menyimpan campaign.');
        }
    }

    public function edit($id, CampaignService $campaignService)
    {
        $campaign = $campaignService->find($id);
        $this->campaignId = $id;
        $this->campaignTitle = $campaign->title;
        $this->campaignMessage = $campaign->message;
        $this->image = $campaign->image;
        $this->isEdit = true;
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->reset(['campaignId', 'campaignTitle', 'campaignMessage', 'image', 'isEdit']);
        $this->dispatch('clearError');
        $this->dispatch('close-form-modal');
    }

    public function sendWA($id, CampaignService $campaignService)
    {
        try {
            $campaignService->broadcast($id);
            $this->dispatch('showSuccess', message: 'Campaign berhasil terkirim ke pelanggan');
        } catch (\Exception $e) {
            $this->dispatch('showError', message: $e->getMessage());
        }
    }

    public function render(CampaignService $campaignService)
    {
        $items = $campaignService->getPaginated($this->perPage, $this->search);
        return view('livewire.campaign.index', compact('items'));
    }
}
