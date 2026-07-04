@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-400 mt-1">Kelola menu sidebar navigasi, urutan posisi, route URL, dan penugasan Spatie permission pengaman menu.</p>
  </div>

  <!-- Form Card -->
  <div id="create-or-update-form" class="w-full">
    <x-card title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Menu">
      <x-slot:tools>
        <x-card.tools minus="true" />
      </x-slot:tools>

      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      <form wire:submit.prevent="save" class="space-y-6">
        <x-overlay target="save">
          <div class="space-y-6">
            <x-dropdown-search
              label="Parent Menu (opsional)"
              name="parentId"
              placeholder="Cari menu parent..."
              horizontal="true"
              :items="$menus"
              :searching="$parentSearch"
              :selectedName="$selectedParentName"
              searchModel="parentSearch"
              selectedNameModel="selectedParentName"
              onSelect="selectParent" />

            <x-form.input
              name="name"
              id="name"
              label="Nama Menu"
              placeholder="Masukkan nama menu"
              wire:model="name"
              horizontal="true" />

            <x-form.input
              name="position"
              id="position"
              type="number"
              label="Urutan Posisi (Sort)"
              placeholder="Masukkan angka urutan menu"
              wire:model="position"
              horizontal="true" />

            <x-form.input
              name="icon"
              id="icon"
              label="Icon FontAwesome"
              placeholder="Contoh: fas fa-house"
              wire:model="icon"
              horizontal="true" />

            <x-form.input
              name="route"
              id="route"
              label="Route / URL Path"
              placeholder="Masukkan route menu"
              wire:model="route"
              horizontal="true" />

            <x-form.input
              name="slug"
              id="slug"
              label="Slug Identifikasi"
              placeholder="Masukkan slug menu"
              wire:model="slug"
              horizontal="true" />

            <x-form.input
              name="permission"
              id="permission"
              label="Spatie Permission"
              placeholder="Contoh: order-index"
              wire:model="permission"
              horizontal="true" />
          </div>
        </x-overlay>

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
    <x-card title="Daftar Menu Navigasi">
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
            placeholder="Cari menu..."
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
                  <td class="px-6 py-4 text-sm font-semibold text-slate-400 whitespace-nowrap">
                    {{ $item->parent?->name ?? '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->name }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-300 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-slate-300">
                      <i class="{{ $item->icon }} text-indigo-400 text-xs"></i>
                      {{ $item->icon }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-center text-sm font-bold text-white whitespace-nowrap">
                    {{ $item->position }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap font-mono">
                    {{ $item->route }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap font-mono">
                    {{ $item->slug }}
                  </td>
                  <td class="px-6 py-4 text-sm text-center whitespace-nowrap font-semibold">
                    @if ($item->permission)
                      <span
                        class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        {{ $item->permission }}
                      </span>
                    @else
                      <span class="text-slate-500 text-xs">-</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <button
                      type="button"
                      wire:click="confirmActive({{ $item->id }}, {{ $item->is_active }})"
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full cursor-pointer transition duration-150 {{ $item->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/25' : 'bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/25' }}">
                      {{ $statusList[$item->is_active] }}
                    </button>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                    <div class="inline-flex rounded-lg shadow-sm gap-2">
                      <x-button
                        wire:click="edit({{ $item->id }})"
                        color="primary"
                        size="sm"
                        class="cursor-pointer"
                        title="Ubah">
                        <i class="fas fa-pencil mr-1 text-xs"></i> Ubah
                      </x-button>

                      <x-button
                        wire:click="confirmDelete({{ $item->id }})"
                        color="danger"
                        size="sm"
                        class="cursor-pointer"
                        title="Hapus">
                        <i class="fas fa-trash-can mr-1 text-xs"></i> Hapus
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
