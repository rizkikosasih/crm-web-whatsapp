<?php

namespace App\Livewire\Campaign;

use App\Models\Campaign;
use App\Models\Customer;
use App\Services\Api\Implements\RapiwhaApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

  protected RapiwhaApiService $rapiwha;

  public function __construct()
  {
    $this->rapiwha = new RapiwhaApiService();
  }

  public function save()
  {
    $this->validate();

    if ($this->image instanceof TemporaryUploadedFile) {
      $rules['image'] = 'image|max:2048';
      $messages['image.image'] = 'Format file yang diperbolehkan hanya gambar';
      $messages['image.max'] = 'Ukuran gambar maksimal 2MB';
      $this->validate($rules, $messages);
    }

    $imagePath = null;
    if ($this->image instanceof TemporaryUploadedFile) {
      $filename =
        Str::slug($this->campaignTitle) .
        '-' .
        time() .
        '.' .
        $this->image->getClientOriginalExtension();

      $imagePath = $this->image->storeAs($this->directory, $filename, 'public');
    }

    Campaign::updateOrCreate(
      ['id' => $this->campaignId],
      [
        'title' => $this->campaignTitle,
        'message' => e($this->campaignMessage),
        'image' => $imagePath ?? Campaign::find($this->campaignId)?->image,
        'created_by' => Auth::id(),
      ]
    );

    $this->resetForm();
    session()->flash('success', 'Produk berhasil disimpan!');
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

  public function sendWA($id)
  {
    $campaign = Campaign::findOrFail($id);
    if ($campaign) {
      foreach (Customer::all() as $customer) {
        $message = parseTemplatePlaceholders($campaign->message, [
          'name' => $customer->name,
          'contact_number' => env('APP_CONTACT_PERSON'),
          'store_name' => env('APP_NAME'),
        ]);

        $this->rapiwha->sendMessage($customer->phone, $message, $campaign->image);
      }

      $this->dispatch('showSuccess', message: 'Campaign berhasil terkirim ke pelanggan');
    } else {
      $this->dispatch('showError', message: 'Campaign tidak ditemukan');
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
