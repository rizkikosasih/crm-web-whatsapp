<div
  id="whatsappModal"
  x-data="{ show: false }"
  x-show="show"
  @open-modal.window="if ($event.target.id === $el.id) show = true;"
  @close-modal.window="if ($event.target.id === $el.id) show = false;"
  x-transition:enter="transition ease-out duration-200"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
  style="display: none"
  wire:ignore.self>
  <!-- Modal Content Dialog -->
  <div
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="scale-95 translate-y-4"
    x-transition:enter-end="scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="scale-100 translate-y-0"
    x-transition:leave-end="scale-95 translate-y-4"
    class="bg-slate-800 border border-slate-700/80 rounded-2xl w-full max-w-3xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center justify-between">
      <h3 class="text-base font-bold text-white leading-snug">
        Kirim Info Produk
        <span class="text-indigo-400 font-extrabold">"{{ $productName }}"</span> Ke Pelanggan
      </h3>
      <button
        type="button"
        class="text-slate-400 hover:text-white transition duration-150 cursor-pointer"
        @click="show = false"
        wire:click="closeModal()">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Modal Body -->
    <div class="p-6 overflow-y-auto space-y-6 flex-1">
      <!-- Search Filters -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <x-form.input-select
            name="perPage"
            wire:model.live="perPage"
            :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            parentClass="mb-0" />
        </div>
        <div>
          <x-form.input
            name="search"
            placeholder="Cari Nama..."
            wire:model.live="search"
            parentClass="mb-0" />
        </div>
        <div>
          <x-form.input
            name="searchPhone"
            placeholder="Cari No HP..."
            wire:model.live="searchPhone"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Customers Table -->
      @if ($idProduct)
        <x-overlay target="sendWA">
          <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
            <table class="min-w-full divide-y divide-slate-700/50">
              <x-table.header :columns="$tableHeader" />
              <tbody class="divide-y divide-slate-800 bg-transparent text-slate-300">
                @forelse ($items['data'] ?? [] as $item)
                  <tr class="hover:bg-slate-800/20 transition duration-150">
                    <td
                      class="px-6 py-3.5 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                      {{ $item['id'] }}
                    </td>
                    <td class="px-6 py-3.5 text-sm font-semibold text-white whitespace-nowrap">
                      {{ $item['name'] }}
                    </td>
                    <td class="px-6 py-3.5 text-sm text-slate-400 whitespace-nowrap">
                      {{ $item['phone'] }}
                    </td>
                    <td
                      class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-medium actions">
                      <x-button
                        title="Kirim Info Produk"
                        wire:click="sendWA('{{ $item['phone'] }}')"
                        color="success"
                        size="xs"
                        class="cursor-pointer">
                        <i class="fas fa-paper-plane mr-1 text-[10px]"></i> Kirim Info
                      </x-button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td
                      colspan="{{ sizeof($tableHeader) }}"
                      class="px-6 py-10 text-center text-sm font-medium text-slate-500 bg-white dark:bg-slate-800/10">
                      Data Kosong
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </x-overlay>
      @endif
    </div>

    <!-- Modal Footer -->
    <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-900/20 flex justify-end gap-3">
      <x-button
        type="button"
        color="secondary"
        size="sm"
        class="cursor-pointer"
        @click="show = false"
        wire:click="closeModal()">
        Tutup
      </x-button>
    </div>
  </div>
</div>
