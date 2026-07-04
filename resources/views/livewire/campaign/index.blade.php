@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-400 mt-1">Buat kampanye promosi dan kirim siaran pesan WhatsApp massal secara terjadwal kepada semua pelanggan terdaftar.</p>
  </div>

  <!-- Form Card -->
  <div id="form-create-or-update" class="w-full">
    <x-card title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Campaign">
      <x-slot:tools>
        <x-card.tools minus="true" />
      </x-slot:tools>

      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      <form wire:submit.prevent="save" class="space-y-6">
        <x-form.input
          id="campaignTitle"
          name="campaignTitle"
          label="Judul Campaign"
          placeholder="Masukkan Judul Campaign"
          wire:model="campaignTitle"
          horizontal="true" />

        <x-form.textarea
          id="campaignMessage"
          name="campaignMessage"
          label="Pesan Campaign"
          rows="6"
          placeholder="Masukkan Pesan Campaign"
          wire:model="campaignMessage"
          horizontal="true" />

        <x-form.image
          id="image"
          name="image"
          label="Gambar Pendukung"
          wire:model="image"
          horizontal="true">
          @php
            $imageUri = isLivewireTemporaryFile($image)
              ? $image->temporaryUrl()
              : imageUri($image ?: 'images/no-image.svg');
          @endphp

          <x-preview-image path="{{ $imageUri }}" width="100px" />
        </x-form.image>

        <div class="h-px bg-slate-700/50 my-6"></div>

        <x-form.button-container class="justify-end gap-3">
          <x-button
            wire:click="resetForm"
            wire:loading.attr="disabled"
            wire:target="image, save"
            color="danger"
            size="sm"
            type="button"
            class="cursor-pointer">
            Batal
          </x-button>

          <x-button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="image, save"
            color="primary"
            size="sm"
            class="cursor-pointer">
            Simpan
          </x-button>
        </x-form.button-container>
      </form>
    </x-card>
  </div>

  <!-- List Card -->
  <div id="list" class="w-full">
    <x-card title="Daftar Campaign Broadcast">
      <x-slot:tools>
        <x-card.tools refresh="true" />
      </x-slot:tools>

      <!-- Table Filters -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="w-24 shrink-0">
          <x-form.input-select
            name="perPage"
            wire:model.live="perPage"
            :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            parentClass="mb-0" />
        </div>
        <div class="w-full sm:w-64 shrink-0">
          <x-form.input
            name="search"
            placeholder="Cari Campaign..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Loading Overlay -->
      <x-overlay target="sendWA, search, perPage, gotoPage, nextPage">
        <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-700/50">
            <x-table.header :columns="$tableHeader" />
            <tbody class="divide-y divide-slate-800 bg-transparent text-slate-300">
              @forelse ($items as $index => $item)
                <tr class="hover:bg-slate-800/20 transition duration-150">
                  <td
                    class="px-6 py-4 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                    {{ $index + $items->firstItem() }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap max-w-[150px] truncate">
                    {{ $item->title }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm text-slate-300 whitespace-normal leading-relaxed max-w-xs md:max-w-md">
                    {!! nl2br(e($item->message)) !!}
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <x-preview-image path="{{ $item->image }}" width="45px" />
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                    <div class="inline-flex rounded-lg shadow-sm">
                      <x-button
                        wire:click="edit({{ $item->id }})"
                        color="primary"
                        size="sm"
                        class="rounded-l-xl rounded-r-none cursor-pointer"
                        title="Ubah">
                        <i class="fas fa-pencil mr-1 text-xs"></i> Ubah
                      </x-button>

                      <x-button
                        wire:click="sendWA({{ $item->id }})"
                        color="success"
                        size="sm"
                        class="rounded-r-xl rounded-l-none border-l border-emerald-700/50 cursor-pointer"
                        title="Broadcast Whatsapp">
                        <i class="fab fa-whatsapp mr-1 text-sm animate-pulse"></i> Broadcast
                      </x-button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td
                    colspan="{{ sizeof($tableHeader) }}"
                    class="px-6 py-10 text-center text-sm font-medium text-slate-500 bg-slate-800/10">
                    Data Kosong
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </x-overlay>

      <!-- Pagination -->
      <div class="mt-6">{{ $items->links() }}</div>
    </x-card>
  </div>
</div>
