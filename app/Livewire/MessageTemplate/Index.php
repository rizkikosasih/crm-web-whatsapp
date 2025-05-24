<?php

namespace App\Livewire\MessageTemplate;

use App\Models\MessageTemplate;
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
  public $directory = 'images/template_messages';

  #[Locked]
  public $title = 'Template Pesan';

  #[Locked]
  public $tableHeader = [
    ['name' => 'No'],
    ['name' => 'Judul'],
    ['name' => 'Isi Pesan'],
    ['name' => 'Tipe'],
    ['name' => 'Gambar'],
    ['name' => '<i class="fas fa-cogs"></i>', 'class' => 'actions'],
  ];

  #[Locked]
  public $types = [
    'campaign' => 'Campaign Broadcast',
    'product' => 'Produk',
    'order' => 'Pemesanan',
  ];

  public $templateId;
  #[Validate('required', message: 'Judul Template tidak boleh kosong')]
  public $titleTemplate;
  #[Validate('required', message: 'Isi Pesan tidak boleh kosong')]
  public $body;
  public $type;
  public $image;

  public $isEdit = false;

  public $search;
  public $perPage = 5;

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
        Str::slug($this->titleTemplate) .
        '-' .
        time() .
        '.' .
        $this->image->getClientOriginalExtension();

      $imagePath = $this->image->storeAs($this->directory, $filename, 'public');
    }

    MessageTemplate::updateOrCreate(
      ['id' => $this->templateId],
      [
        'title' => $this->titleTemplate,
        'body' => e(str_replace(["\r\n", "\r", "\n"], "\n", $this->body)),
        'type' => $this->type,
        'image' => $imagePath ?? MessageTemplate::find($this->templateId)?->image,
      ]
    );

    $this->resetForm();
    session()->flash('success', 'Template Pesan berhasil disimpan!');
  }

  public function edit($id)
  {
    $template = MessageTemplate::findOrFail($id);
    $this->templateId = $template->id;
    $this->titleTemplate = $template->title;
    $this->body = $template->body;
    $this->type = $template->type;
    $this->image = $template->image;
    $this->isEdit = true;
    $this->dispatch('scrollToTop');
  }

  public function resetForm()
  {
    $this->reset(['templateId', 'titleTemplate', 'type', 'body', 'image', 'isEdit']);
    $this->dispatch('clearError');
  }

  public function render()
  {
    $items = MessageTemplate::when($this->search, function ($query) {
      $query->whereAny(['title', 'body'], 'like', '%' . $this->search . '%');
    })
      ->latest()
      ->paginate($this->perPage);

    return view('livewire.message-template.index', compact('items'));
  }
}
