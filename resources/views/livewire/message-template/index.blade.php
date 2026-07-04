@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-400 mt-1">Kelola kerangka pesan notifikasi otomatis untuk order (dibuat, lunas, dikirim, batal) dan katalog sharing.</p>
  </div>

  <!-- Form Card -->
  <div id="form-create-or-update" class="w-full">
    <x-card title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Template">
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
          id="titleTemplate"
          name="titleTemplate"
          label="Judul Template"
          placeholder="Masukkan Judul Template"
          wire:model="titleTemplate"
          horizontal="true" />

        <x-form.input-select
          id="type"
          name="type"
          label="Tipe Template"
          wire:model="type"
          :options="
            [
      'campaign' => 'Campaign Broadcast',
      'product' => 'Produk',
      'order' => 'Pemesanan',
    ]
          "
          horizontal="true" />

        <x-form.textarea
          id="body"
          name="body"
          label="Isi Pesan"
          placeholder="Masukkan Isi Pesan (Gunakan placeholders seperti {name}, {order_number}, dll.)"
          rows="6"
          wire:model="body"
          horizontal="true" />

        <div class="h-px bg-slate-700/50 my-6"></div>

        <x-form.button-container class="justify-end gap-3">
          <x-button
            wire:click="resetForm"
            color="danger"
            size="sm"
            type="button"
            class="cursor-pointer">
            Batal
          </x-button>

          <x-button type="submit" color="primary" size="sm" class="cursor-pointer">
            Simpan
          </x-button>
        </x-form.button-container>
      </form>
    </x-card>
  </div>

  <!-- List Card -->
  <div id="list" class="w-full">
    <x-card title="Daftar Template Pesan">
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
            placeholder="Cari Template..."
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
                    {{ $item->title }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm text-slate-300 whitespace-normal leading-relaxed max-w-md">
                    {!! nl2br(e($item->body)) !!}
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                      {{ $types[$item->type] ?? $item->type }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                    <x-button
                      wire:click="edit({{ $item->id }})"
                      color="primary"
                      size="sm"
                      class="cursor-pointer"
                      title="Ubah">
                      <i class="fas fa-pencil mr-1 text-xs"></i> Ubah
                    </x-button>
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
