@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-400 mt-1">Kelola data pelanggan untuk pengiriman notifikasi WhatsApp dan histori transaksi.</p>
  </div>

  <!-- Form Card -->
  <div id="create-or-update-form" class="w-full">
    <x-card title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Pelanggan">
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
          name="name"
          id="name"
          label="Nama Customer"
          placeholder="Masukkan nama customer"
          wire:model="name" />

        <x-form.input
          name="phone"
          id="phone"
          label="No Handphone <span class='text-slate-500 font-normal text-xs'>(contoh: 6285123456789)</span>"
          placeholder="Masukkan no handphone"
          x-data
          x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
          wire:model="phone" />

        <x-form.textarea
          name="notes"
          id="notes"
          label="Catatan"
          placeholder="Masukkan Catatan khusus pelanggan"
          rows="3"
          wire:model="notes" />

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
    <x-card title="Daftar Pelanggan Terdaftar">
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
            placeholder="Cari Pelanggan..."
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
                  {{ $item->name }}
                </td>
                <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap">
                  {{ $item->phone }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                  <x-button
                    wire:click="edit({{$item->id}})"
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
</div>
