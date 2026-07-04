@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Konfigurasi group hak akses (roles) seperti Administrator, Warehouse, Staff Kasir, dll.</p>
    </div>
    <div>
      <x-button
        @click="$dispatch('open-form-modal')"
        color="primary"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-plus mr-1.5 text-xs"></i> Tambah Role
      </x-button>
    </div>
  </div>

  <!-- Modal Form -->
  <div x-on:closed-form-modal.window="$wire.resetForm()">
    <x-modal id="form-modal" title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Role" maxWidth="lg">
      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      <form wire:submit.prevent="save" class="space-y-6">
        <x-overlay target="save">
          <x-form.input
            name="name"
            id="name"
            label="Nama Role"
            placeholder="Masukkan Nama Role Pengguna (contoh: Kasir)"
            wire:model="name" />
        </x-overlay>

        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

        <x-form.button-container class="justify-end gap-3">
          <x-button
            wire:click="resetForm"
            color="danger"
            size="sm"
            type="button"
            class="cursor-pointer">
            Batal
          </x-button>

          <x-button
            type="submit"
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
    <x-card title="Daftar Role Terdaftar">
      <!-- Table Filters -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="w-24">
          <x-form.input-select
            name="perPage"
            wire:model.live="perPage"
            :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            parentClass="mb-0" />
        </div>
        <div class="w-full sm:w-64 shrink-0">
          <x-form.input
            name="search"
            placeholder="Cari Role..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Loading Overlay -->
      <x-overlay target="search, perPage">
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
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                    <div class="inline-flex rounded-lg shadow-sm gap-2">
                      <x-link.button
                        url="{{ url('setting/role/' . $item->id) }}"
                        size="sm"
                        color="warning"
                        class="cursor-pointer"
                        title="Atur Hak Akses / Permissions">
                        <i class="fas fa-key mr-1 text-xs"></i> Hak Akses
                      </x-link.button>

                      <x-button
                        wire:click="edit({{ $item->id }})"
                        color="primary"
                        size="sm"
                        class="cursor-pointer"
                        title="Ubah Nama Role">
                        <i class="fas fa-pencil mr-1 text-xs"></i> Ubah
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
</div>
