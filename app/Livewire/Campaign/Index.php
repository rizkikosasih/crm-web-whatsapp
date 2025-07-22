<?php

namespace App\Livewire\Campaign;

use App\Models\Campaign;
use App\Models\Customer;
use App\Services\Api\ImageKitServiceInterface;
use App\Services\Api\SendMessageApiServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
  use WithPagination, WithFileUploads;

  #[Locked]
  public $title = 'Campaign Broadcast';

  #[Locked]
  public $directory = 'images/campaigns';

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

  public function save(ImageKitServiceInterface $imagekitService)
  {
    try {
      $this->validate();

      if ($this->image instanceof TemporaryUploadedFile) {
        $rules['image'] = 'image|max:2048';
        $messages['image.image'] = 'Format file yang diperbolehkan hanya gambar';
        $messages['image.max'] = 'Ukuran gambar maksimal 2MB';
        $this->validate($rules, $messages);
      }

      DB::transaction(function () use ($imagekitService) {
        $imageLocalPath = null;
        $imageUrl = null;

        if ($this->image instanceof TemporaryUploadedFile) {
          $campaign = Campaign::find($this->campaignId);
          $filename = createFilename(
            $this->campaignTitle,
            $this->image->getClientOriginalExtension()
          );

          // Simpan ke lokal
          $imageLocalPath = $this->image->storeAs($this->directory, $filename, 'public');

          // Hapus gambar lama dari lokal jika ada
          $oldImage = $campaign?->image;
          if (isset($oldImage) && $imageLocalPath && $oldImage !== $imageLocalPath) {
            Storage::disk('public')->delete($oldImage);
            $imagekitService->delete($campaign?->image_url);
          }

          // Upload ke ImageKit
          $imageUrl = $imagekitService->upload($imageLocalPath, $filename, 'campaigns');
        }

        // Simpan atau update campaign
        $campaign = Campaign::find($this->campaignId);
        Campaign::updateOrCreate(
          ['id' => $this->campaignId],
          [
            'title' => $this->campaignTitle,
            'message' => e($this->campaignMessage),
            'image' => $imageLocalPath ?? $campaign?->image,
            'image_url' => $imageUrl ?? $campaign?->image_url,
            'created_by' => Auth::id(),
          ]
        );
      });

      $this->resetForm();
      $this->dispatch('showSuccess', message: 'Campaign berhasil disimpan.');
    } catch (ValidationException $e) {
      $this->dispatch('showError', message: $e->validator->errors()->first());
    } catch (\Throwable $e) {
      logger()->error('Gagal menyimpan campaign: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString(),
      ]);

      $this->dispatch('showError', message: 'Terjadi kesalahan saat menyimpan campaign.');
    }
  }

  public function edit($id)
  {
    $campaign = Campaign::findOrFail($id);
    $this->campaignId = $id;
    $this->campaignTitle = $campaign->title;
    $this->campaignMessage = $campaign->message;
    $this->image = $campaign->image;
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
  }

  public function resetForm()
  {
    $this->reset(['campaignId', 'campaignTitle', 'campaignMessage', 'image', 'isEdit']);
    $this->dispatch('clearError');
  }

  public function sendWA($id, SendMessageApiServiceInterface $rapiwha)
  {
    try {
      $campaign = Campaign::findOrFail($id);
      if ($campaign) {
        foreach (Customer::all() as $customer) {
          $message = parseTemplatePlaceholders($campaign->message, [
            'name' => $customer->name,
            'contact_number' => config('app.contact'),
            'store_name' => config('app.name'),
            'image_url' => $campaign->image_url ?? '',
          ]);

          $response = $rapiwha->sendMessage($customer->phone, $message);
          if ($response->success) {
            $this->dispatch(
              'showSuccess',
              message: 'Campaign Broadcast Berhasil Dikirim'
            );
          } else {
            $this->dispatch('showError', message: $response->message);
          }
        }

        $this->dispatch(
          'showSuccess',
          message: 'Campaign berhasil terkirim ke pelanggan'
        );
      } else {
        $this->dispatch('showError', message: 'Campaign tidak ditemukan');
      }
    } catch (\Exception $e) {
      $this->dispatch('showError', message: $e->getMessage());
    }
  }

  public function render()
  {
    $items = Campaign::with('creator')
      ->when($this->search, function ($q) {
        $q->whereAny(['title', 'message'], 'like', '%' . $this->search . '%');
      })
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.campaign.index', compact('items'));
  }
}
