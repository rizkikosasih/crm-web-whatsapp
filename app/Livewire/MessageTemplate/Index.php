<?php

namespace App\Livewire\MessageTemplate;

use App\Services\MessageTemplateService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Locked]
    public $title = 'Template Pesan';

    #[Locked]
    public $tableHeader = [
        ['name' => 'No'],
        ['name' => 'Judul'],
        ['name' => 'Isi Pesan'],
        ['name' => 'Tipe'],
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

    public $isEdit = false;

    public $search;
    public $perPage = 5;

    public function save(MessageTemplateService $messageTemplateService)
    {
        $this->validate();

        $messageTemplateService->save(
            [
                'title' => $this->titleTemplate,
                'body' => e(str_replace(["\r\n", "\r", "\n"], "\n", $this->body)),
                'type' => $this->type,
            ],
            $this->templateId,
        );

        $this->resetForm();
        session()->flash('success', 'Template Pesan berhasil disimpan!');
    }

    public function edit($id, MessageTemplateService $messageTemplateService)
    {
        $template = $messageTemplateService->find($id);
        $this->templateId = $template->id;
        $this->titleTemplate = $template->title;
        $this->body = $template->body;
        $this->type = $template->type;
        $this->isEdit = true;
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->reset(['templateId', 'titleTemplate', 'type', 'body', 'isEdit']);
        $this->dispatch('clearError');
        $this->dispatch('close-form-modal');
    }

    public function render(MessageTemplateService $messageTemplateService)
    {
        $items = $messageTemplateService->getPaginated($this->perPage, $this->search);

        return view('livewire.message-template.index', compact('items'));
    }
}
