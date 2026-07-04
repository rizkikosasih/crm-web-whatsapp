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
          label="API Key (Global/Instance Token)"
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
          @if ($isEdit)
            <x-button
              type="button"
              color="success"
              size="sm"
              class="cursor-pointer font-bold"
              wire:click="getQrCode"
              wire:target="getQrCode"
              loadingText="Menghubungkan...">
              <x-icon name="qr-code" class="w-4 h-4 mr-1.5 inline-block" /> Hubungkan WhatsApp (Scan
              QR)
            </x-button>
          @endif
          <x-button
            type="submit"
            color="primary"
            size="sm"
            class="cursor-pointer"
            wire:target="save"
            loadingText="Menyimpan...">
            <x-icon name="save" class="w-4 h-4 mr-1.5 inline-block" /> Simpan Konfigurasi
          </x-button>
        </x-form.button-container>
      </form>
    </x-card>
  </div>

  <!-- WhatsApp Connector Modal -->
  <x-modal id="wa-connector" title="Hubungkan WhatsApp (Scan QR)" maxWidth="md">
    <div class="space-y-5">
      <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed text-center">Pindai QR Code di bawah menggunakan fitur <strong>Perangkat Tertaut</strong> pada aplikasi WhatsApp di ponsel Anda.</p>

      <div
        class="flex items-center justify-center min-h-[220px] bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-4 border border-slate-200 dark:border-slate-800/80">
        @if ($isLoadingQr)
          <div class="flex flex-col items-center gap-3">
            <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 animate-pulse"
              >Menghubungkan ke Evolution API...</span
            >
          </div>
        @elseif ($connectionStatus === 'CONNECTED')
          <div class="flex flex-col items-center gap-3 text-emerald-600 dark:text-emerald-400 py-6">
            <svg class="w-16 h-16 shrink-0 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-lg font-bold text-slate-900 dark:text-white"
              >WhatsApp Terhubung!</span
            >
            <span class="text-xs text-slate-500 dark:text-slate-400 text-center"
              >Sesi sinkronisasi aktif. Sistem siap mengirimkan notifikasi.</span
            >
          </div>
        @elseif ($qrCodeBase64)
          <div class="flex flex-col items-center gap-4">
            <img
              src="{{ $qrCodeBase64 }}"
              class="w-[180px] h-[180px] rounded-xl shadow-md border-4 border-white dark:border-slate-800"
              alt="QR Code WhatsApp" />
            <span
              class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-3 py-1 rounded-full animate-pulse border border-indigo-200 dark:border-indigo-800/40">
              MENUNGGU PEMINDAIAN QR CODE
            </span>
          </div>
        @else
          <div
            class="flex flex-col items-center gap-3 text-red-500 dark:text-red-400 text-center py-6">
            <x-icon name="alert-triangle" class="w-12 h-12 shrink-0" />
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"
              >Gagal terhubung ke Evolution API.</span
            >
            <span class="text-xs text-slate-500 dark:text-slate-400"
              >Pastikan status container docker/VPS aktif & konfigurasi URL/Key valid.</span
            >
            <x-button
              type="button"
              size="xs"
              color="default"
              wire:click="getQrCode"
              class="mt-2 font-bold">
              Coba Lagi
            </x-button>
          </div>
        @endif
      </div>

      <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700/50 gap-3">
        @if ($connectionStatus !== 'CONNECTED' && !$isLoadingQr)
          <x-button
            type="button"
            color="default"
            size="xs"
            wire:click="getQrCode"
            class="font-bold">
            <i class="fas fa-rotate mr-1.5 text-xs"></i> Refresh QR
          </x-button>
        @endif
        <x-button
          type="button"
          color="primary"
          size="xs"
          @click="
            open = false;
            $dispatch('closed-wa-connector');
          "
          class="font-bold">
          Tutup
        </x-button>
      </div>
    </div>
  </x-modal>
</div>
