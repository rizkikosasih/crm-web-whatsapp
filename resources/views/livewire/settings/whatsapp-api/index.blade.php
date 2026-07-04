@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Konfigurasi endpoint API URL, API Key, dan Nama Instance WhatsApp Evolution API Anda.</p>
  </div>

  <!-- Form Card -->
  <div id="form-create-or-update" class="w-full max-w-3xl">
    <x-card title="Pengaturan Integrasi WhatsApp">
      <x-slot:tools>
        <x-card.tools refresh="true" />
      </x-slot:tools>

      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      <form wire:submit.prevent="save" class="space-y-6">
        <x-form.input
          id="apiUrl"
          name="apiUrl"
          label="API URL"
          placeholder="Contoh: http://localhost:8080"
          wire:model="apiUrl"
          horizontal="true" />

        <x-form.input
          id="apiKey"
          name="apiKey"
          label="API Key (Global/Instance Token) <button type='button' @click.prevent='\$dispatch(&quot;open-api-key-guide&quot;)' class='text-xs font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 ml-1 inline-flex items-center gap-1 cursor-pointer focus:outline-none' title='Lihat Panduan'><i class='fas fa-circle-question text-sm'></i> Cara Mendapatkan</button>"
          placeholder="Masukkan API Key/Token"
          wire:model="apiKey"
          horizontal="true" />

        <x-form.input
          id="instanceName"
          name="instanceName"
          label="Instance Name (Nama Sesi)"
          placeholder="Contoh: crm-whatsapp"
          wire:model="instanceName"
          horizontal="true" />

        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

        <x-form.button-container class="justify-end gap-3">
          <x-button
            type="submit"
            color="primary"
            size="sm"
            class="cursor-pointer"
            loadingText="Menyimpan...">
            <x-icon name="save" class="w-4 h-4 mr-1.5 inline-block" /> Simpan Konfigurasi
          </x-button>
        </x-form.button-container>
      </form>
    </x-card>
  </div>

  <!-- API Key Guide Modal -->
  <x-modal.api-key-guide />
</div>
