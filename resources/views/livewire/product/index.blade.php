@section ('title', $title)

@section ('page-script')
  <x-scripts.modal-handler id="whatsappModal" />
@endsection

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Mengelola katalog produk, stok, dan harga, serta membagikan informasi produk langsung ke WhatsApp pelanggan.</p>
    </div>
    <div>
      <x-button
        @click="$dispatch('open-form-modal')"
        color="primary"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-plus mr-1.5 text-xs"></i> Tambah Produk
      </x-button>
    </div>
  </div>

  <!-- Modal Form -->
  <div x-on:closed-form-modal.window="$wire.resetForm()">
    <x-modal id="form-modal" title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Produk" maxWidth="2xl">
      <form wire:submit.prevent="save" class="space-y-6">
        <x-overlay wire:target="save, image">
          <div class="space-y-6">
            <x-form.input
              id="name"
              name="name"
              label="Nama Produk"
              placeholder="Masukkan Nama Produk"
              wire:model="name"
              horizontal="true" />

            <x-form.input
              id="sku"
              name="sku"
              label="SKU Produk"
              placeholder="Masukkan SKU Produk"
              wire:model="sku"
              horizontal="true" />

            <x-form.input
              id="price"
              name="price"
              label="Harga"
              placeholder="Masukkan Harga Produk"
              x-data
              x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
              wire:model="price"
              horizontal="true" />

            <x-form.input
              id="stock"
              name="stock"
              label="Stok"
              placeholder="Masukkan Jumlah Stok"
              x-data
              x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
              maxlength="5"
              wire:model="stock"
              horizontal="true" />

            <x-form.textarea
              id="description"
              name="description"
              label="Deskripsi Produk"
              placeholder="Masukkan Deskripsi Produk"
              wire:model="description"
              horizontal="true" />

            <x-form.image
              id="image"
              name="image"
              label="Gambar Produk"
              wire:model="image"
              horizontal="true">
              @php
                $imageUri = isLivewireTemporaryFile($image)
                  ? $image->temporaryUrl()
                  : imageUri($image ?: 'images/no-image.svg');
              @endphp

              <x-preview-image path="{{ $imageUri }}" width="100px" />
            </x-form.image>
          </div>
        </x-overlay>

        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

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
            class="cursor-pointer"
            loadingText="Menyimpan...">
            Simpan
          </x-button>
        </x-form.button-container>
      </form>
    </x-modal>
  </div>

  <!-- List Card -->
  <div id="list" class="w-full">
    <x-card title="Daftar Produk">
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
            placeholder="Cari Produk..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Loading Overlay -->
      <x-overlay target="search, perPage, gotoPage, nextPage">
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
                  <td class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap">
                    <div>{{ $item->name }}</div>
                    <div class="text-xs font-normal text-slate-500 mt-0.5">
                      SKU: {{ $item->sku }}
                    </div>
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full {{ $item->stock > 5 ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                      {{ $item->stock }}
                    </span>
                  </td>
                  <td
                    class="px-6 py-4 text-right text-sm font-semibold text-white whitespace-nowrap">
                    {{ rupiah($item->price) }}
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
                        wire:click="$dispatch('showWhatsappModal', { id: {{ $item->id }} })"
                        color="success"
                        size="sm"
                        class="rounded-r-xl rounded-l-none border-l border-emerald-700/50 cursor-pointer"
                        title="Kirim ke WhatsApp">
                        <i class="fab fa-whatsapp mr-1 text-sm"></i> Kirim
                      </x-button>
                    </div>
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

      <!-- Pagination -->
      <div class="mt-6">{{ $items->links() }}</div>
    </x-card>
  </div>

  <livewire:product.whatsapp-modal />
</div>
