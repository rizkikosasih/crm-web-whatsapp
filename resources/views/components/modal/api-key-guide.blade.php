<x-modal id="api-key-guide" title="Panduan Mendapatkan API Key" maxWidth="xl">
  <div x-data="{ tab: 'local' }" class="space-y-5">
    <!-- Tabs Nav -->
    <div class="flex border-b border-slate-200 dark:border-slate-700/50">
      <button
        type="button"
        @click="tab = 'local'"
        class="flex-1 pb-3 text-sm font-semibold border-b-2 text-center transition duration-150 cursor-pointer"
        :class="tab === 'local'
          ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
          : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
        Lokal (Docker)
      </button>
      <button
        type="button"
        @click="tab = 'server'"
        class="flex-1 pb-3 text-sm font-semibold border-b-2 text-center transition duration-150 cursor-pointer"
        :class="tab === 'server'
          ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
          : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
        Server / VPS
      </button>
    </div>

    <!-- Tab Contents -->
    <div class="space-y-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
      <!-- Local Tab -->
      <div x-show="tab === 'local'" class="space-y-4" x-cloak>
        <p>Jika Anda menjalankan Evolution API secara lokal menggunakan <strong>Docker Compose</strong>, API Key didefinisikan dalam file <code>.env</code> milik Evolution API.</p>

        <div
          class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-3">
          <span class="block text-xs font-bold uppercase tracking-wider text-slate-400"
            >Variabel Environment</span
          >
          <code class="text-indigo-600 dark:text-indigo-400 font-semibold break-all"
            >EVOLUTION_API_KEY=your_evolution_api_key</code
          >
        </div>

        <p>Anda bisa menjalankan perintah berikut di Terminal/PowerShell untuk melihat atau mengekstrak API Key dari container Docker:</p>

        <div
          x-data="{
            copied: false,
            code: 'docker compose exec evolution-api cat .env | findstr API_KEY',
          }"
          class="relative group bg-slate-950 text-slate-200 font-mono text-xs rounded-xl p-4">
          <pre class="overflow-x-auto whitespace-pre-wrap break-all pr-12">
docker compose exec evolution-api cat .env | findstr API_KEY</pre
          >
          <button
            type="button"
            @click="
              navigator.clipboard.writeText(code);
              copied = true;
              setTimeout(() => (copied = false), 2000);
            "
            class="absolute top-3 right-3 p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition cursor-pointer"
            title="Salin Perintah">
            <span x-show="!copied" x-cloak>
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
              </svg>
            </span>
            <span x-show="copied" class="text-emerald-400 font-semibold text-[10px]" x-cloak
              >Tersalin!</span
            >
          </button>
        </div>
      </div>

      <!-- Server Tab -->
      <div x-show="tab === 'server'" class="space-y-4" x-cloak>
        <p>Jika Evolution API diinstal pada <strong>Server VPS</strong> atau <strong>Cloud Hosting</strong>, API Key dikonfigurasi sebagai variabel otentikasi global dalam file konfigurasi server.</p>

        <div
          class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-3">
          <span class="block text-xs font-bold uppercase tracking-wider text-slate-400"
            >Nama Kunci Konfigurasi</span
          >
          <code class="text-indigo-600 dark:text-indigo-400 font-semibold break-all"
            >AUTHENTICATION_API_KEY=Kunci_Rahasia_Anda</code
          >
        </div>

        <p>Untuk mendapatkan kunci tersebut, silakan buka file <code>.env</code> pada direktori instalasi Evolution API di VPS Anda, atau tanyakan administrator sistem Anda.</p>
      </div>
    </div>

    <!-- Modal Footer -->
    <div class="flex justify-end pt-3 border-t border-slate-200 dark:border-slate-700/50">
      <x-button
        type="button"
        @click="
          open = false;
          $dispatch('closed-api-key-guide');
        "
        color="secondary"
        size="sm"
        class="cursor-pointer">
        Tutup Panduan
      </x-button>
    </div>
  </div>
</x-modal>
