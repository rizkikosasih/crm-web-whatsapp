@section ('title', $title)

<div class="space-y-8" wire:init="checkConnection">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
      WhatsApp Connector
    </h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pantau status koneksi real-time dan hubungkan nomor WhatsApp Anda dengan Evolution API.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Status & Connector Card -->
    <div class="lg:col-span-2 space-y-6">
      <x-card title="Status Koneksi WhatsApp">
        <x-slot:tools>
          <x-card.tools />
        </x-slot:tools>

        @if (session()->has('success'))
          <div class="mb-5">
            <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
          </div>
        @endif

        <div
          class="min-h-[280px] flex flex-col items-center justify-center p-6 border border-dashed border-slate-200 dark:border-slate-700/80 rounded-2xl bg-slate-50/50 dark:bg-slate-900/10 backdrop-blur-sm transition-all duration-300">
          @if ($connectionStatus === 'LOADING')
            <!-- Loading Skeleton State -->
            <div class="flex flex-col items-center gap-4 text-center animate-pulse">
              <div
                class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-950/40 flex items-center justify-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </div>
              <div>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300"
                  >Mengecek Sesi Koneksi...</span
                >
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Menghubungkan ke Evolution API di background</p>
              </div>
            </div>

          @elseif ($connectionStatus === 'CONNECTED')
            <!-- Connected State -->
            <div class="flex flex-col items-center gap-5 text-center">
              <div
                class="w-20 h-20 rounded-full bg-emerald-100/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 flex items-center justify-center shadow-lg shadow-emerald-500/15">
                <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <span class="text-lg font-bold text-slate-900 dark:text-white block"
                  >WhatsApp Terhubung!</span
                >
                <span
                  class="inline-flex items-center gap-1.5 px-3 py-1 mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 rounded-full border border-emerald-200 dark:border-emerald-800/40">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Sesi Aktif
                </span>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mt-3 leading-relaxed">WhatsApp Gateway berhasil dipasangkan. Sistem Anda siap mengirim notifikasi pesanan secara otomatis.</p>
              </div>

              <div class="pt-4">
                <x-button
                  type="button"
                  color="danger"
                  size="sm"
                  class="font-bold shadow-sm"
                  wire:click="disconnect"
                  wire:confirm="Apakah Anda yakin ingin memutuskan koneksi WhatsApp?"
                  wire:loading.attr="disabled">
                  <span
                    wire:loading.remove
                    wire:target="disconnect"
                    class="inline-flex items-center gap-1.5">
                    <x-icon name="log-out" class="w-4 h-4" /> Putuskan Koneksi
                  </span>
                  <span
                    wire:loading.inline-flex
                    wire:target="disconnect"
                    class="inline-flex items-center gap-1.5">
                    <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memutus Koneksi...
                  </span>
                </x-button>
              </div>
            </div>

          @elseif ($connectionStatus === 'DISCONNECTED' && $qrCodeBase64)
            <!-- Disconnected & QR Available State -->
            <div class="flex flex-col md:flex-row items-center gap-8 p-4">
              <!-- QR Code Display -->
              <div class="flex flex-col items-center gap-3 shrink-0">
                <div
                  class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-md">
                  <img
                    src="{{ $qrCodeBase64 }}"
                    class="w-[180px] h-[180px]"
                    alt="QR Code WhatsApp" />
                </div>
                <span
                  class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-3 py-1 rounded-full animate-pulse border border-indigo-200/50 dark:border-indigo-800/40">
                  MENUNGGU SCANNING QR
                </span>
              </div>

              <!-- Scanning Instructions -->
              <div class="space-y-4 text-left flex-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block"
                  >Petunjuk Pemasangan</span
                >
                <ol
                  class="space-y-2.5 text-sm text-slate-600 dark:text-slate-400 list-decimal list-inside leading-relaxed">
                  <li>Buka aplikasi <strong>WhatsApp</strong> di HP Anda.</li>
                  <li>Buka <strong>Menu / Setelan</strong> (ikon titik tiga atau roda gigi).</li>
                  <li>Pilih <strong>Perangkat Tertaut (Linked Devices)</strong>.</li>
                  <li>Pilih <strong>Tautkan Perangkat (Link a Device)</strong>.</li>
                  <li>Arahkan kamera HP ke <strong>QR Code</strong> di samping.</li>
                </ol>

                <div class="pt-2 flex gap-3">
                  <x-button
                    type="button"
                    color="primary"
                    size="xs"
                    class="font-bold"
                    wire:click="checkConnection"
                    wire:loading.attr="disabled">
                    <span
                      wire:loading.remove
                      wire:target="checkConnection"
                      class="inline-flex items-center gap-1.5">
                      <x-icon name="refresh-cw" class="w-3.5 h-3.5" /> Refresh QR
                    </span>
                    <span
                      wire:loading.inline-flex
                      wire:target="checkConnection"
                      class="inline-flex items-center gap-1.5">
                      <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Memuat QR...
                    </span>
                  </x-button>
                </div>
              </div>
            </div>

          @else
            <!-- Error / Offline State -->
            <div class="flex flex-col items-center gap-3 text-center py-6">
              <div
                class="w-16 h-16 rounded-full bg-rose-100 dark:bg-rose-950/30 flex items-center justify-center text-rose-500 dark:text-rose-400 border border-rose-200 dark:border-rose-800/40 shadow-lg shadow-rose-500/10">
                <x-icon name="alert-triangle" class="w-8 h-8" />
              </div>
              <div>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 block"
                  >Gagal terhubung ke Evolution API</span
                >
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs">Pastikan container Docker/VPS aktif & konfigurasi credentials di `.env` valid.</p>
              </div>
              <x-button
                type="button"
                color="default"
                size="xs"
                wire:click="checkConnection"
                class="mt-2 font-bold border border-slate-300 dark:border-slate-700">
                <x-icon name="rotate-cw" class="w-3.5 h-3.5 mr-1.5" /> Coba Lagi
              </x-button>
            </div>
          @endif
        </div>
      </x-card>
    </div>

    <!-- Active Configurations Panel -->
    <div class="lg:col-span-1">
      <x-card title="Informasi Gateway">
        <div class="space-y-5">
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Semua parameter koneksi dimuat secara otomatis dari berkas konfigurasi internal (`.env`).</p>

          <div class="h-px bg-slate-200 dark:bg-slate-700/50"></div>

          <div class="space-y-4">
            <div>
              <span
                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block"
                >Endpoint API URL</span
              >
              <span
                class="text-sm font-semibold text-slate-700 dark:text-slate-300 break-all select-all font-mono mt-0.5 block">
                {{ $apiUrl }}
              </span>
            </div>

            <div>
              <span
                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block"
                >Nama Sesi (Instance)</span
              >
              <span
                class="text-sm font-semibold text-slate-700 dark:text-slate-300 select-all font-mono mt-0.5 block">
                {{ $instanceName }}
              </span>
            </div>

            <div>
              <span
                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block"
                >API Token</span
              >
              <span
                class="text-xs font-semibold text-slate-400 dark:text-slate-600 select-all font-mono mt-0.5 block">
                ••••••••••••••••••••
              </span>
            </div>
          </div>
        </div>
      </x-card>
    </div>
  </div>
</div>
