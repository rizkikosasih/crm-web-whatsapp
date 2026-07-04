@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola data administrator, petugas gudang, dan staf kasir beserta penugasan role akses Spatie.</p>
    </div>
    <div>
      <x-button
        @click="$dispatch('open-form-modal')"
        color="primary"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-plus mr-1.5 text-xs"></i> Tambah Pengguna
      </x-button>
    </div>
  </div>

  <!-- Modal Form -->
  <div x-on:closed-form-modal.window="$wire.resetForm()">
    <x-modal id="form-modal" title="{{ $isEdit ? 'Ubah' : 'Tambah' }} Pengguna" maxWidth="2xl">
      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      @if (session()->has('error'))
        <div class="mb-5">
          <x-alert.danger dismissible="true">{{ session('error') }}</x-alert.danger>
        </div>
      @endif

      <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-form.input
            name="name"
            id="name"
            label="Nama Pengguna"
            placeholder="Masukkan Nama Pengguna"
            wire:model="name" />

          <x-form.input
            name="username"
            id="username"
            label="Username Pengguna"
            placeholder="Masukkan Username Pengguna"
            wire:model="username" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-form.input
            name="phone"
            id="phone"
            label="No Handphone (contoh: 6285123456789)"
            placeholder="Masukkan no handphone"
            wire:model="phone" />

          <x-form.input
            name="email"
            id="email"
            type="email"
            label="Email"
            placeholder="Masukkan email"
            wire:model="email" />
        </div>

        <x-form.input-select
          name="role_id"
          id="role_id"
          label="Role Pengguna"
          placeholder="Pilih Role Pengguna"
          :options="$roles"
          optionHeader="Pilih Role Pengguna"
          wire:model="role_id" />

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
    <x-card title="Daftar Pengguna">
      <x-slot:tools>
        <x-card.tools refresh="true" minus="true" />
      </x-slot:tools>

      <!-- Table Filters -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex flex-wrap sm:flex-nowrap gap-4 items-center w-full">
          <div class="w-24">
            <x-form.input-select
              name="perPage"
              wire:model.live="perPage"
              :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
              parentClass="mb-0" />
          </div>
          <div class="w-48">
            <x-form.input-select
              name="filterRole"
              wire:model.live="filterRole"
              :options="$roles"
              optionHeader="Semua Role"
              parentClass="mb-0" />
          </div>
        </div>
        <div class="w-full sm:w-64 shrink-0">
          <x-form.input
            name="search"
            placeholder="Cari Pengguna..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Loading Overlay -->
      <x-overlay target="search, perPage, filterRole, gotoPage, nextPage">
        <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-700/50">
            <x-table.header :columns="$tableHeader" />
            <tbody class="divide-y divide-slate-800 bg-transparent text-slate-300">
              @forelse ($items as $index => $item)
                @php
                  $userRole = $item->roles->first();
                @endphp
                <tr class="hover:bg-slate-800/20 transition duration-150">
                  <td
                    class="px-6 py-4 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                    {{ $index + $items->firstItem() }}
                  </td>
                  <td class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->name }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap">
                    {{ $item->email }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap">
                    {{ $item->phone }}
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <button
                      type="button"
                      wire:click="confirmActive({{ $item->id }}, {{ $item->is_active }})"
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full cursor-pointer transition duration-150 {{ $item->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/25' : 'bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/25' }}"
                      title="{{ $titleStatus[$item->is_active] }}">
                      {{ $statusList[$item->is_active] }}
                    </button>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                      {{ $userRole ? $userRole->name : '-' }}
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
