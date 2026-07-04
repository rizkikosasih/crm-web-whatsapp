@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
        {{ $title }} : <span class="text-indigo-650 dark:text-indigo-400">{{ $roleName }}</span>
      </h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Aktifkan atau matikan hak akses menu navigasi di bawah ini untuk pengguna dengan role <strong>{{ $roleName }}</strong>.</p>
    </div>
    <div>
      <x-link.button
        url="{{ url('setting/role') }}"
        color="danger"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Kembali
      </x-link.button>
    </div>
  </div>

  <!-- Main Card Table -->
  <div class="w-full">
    <x-card title="Pengaturan Hak Akses Menu">
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

      <!-- Table Content with Loader -->
      <x-overlay target="search, perPage, toggleMenuAccess">
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
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap font-medium">
                    {{ $item->parent?->name ?? '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->name }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-400 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-slate-300">
                      <i class="{{ $item->icon }} text-indigo-400 text-xs"></i>
                      {{ $item->icon }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm actions">
                    <button
                      type="button"
                      wire:click="toggleMenuAccess({{ $item->id }})"
                      class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-2 focus:ring-offset-slate-900 {{ $item->is_assigned ? 'bg-indigo-600' : 'bg-slate-700' }}"
                      title="Klik untuk mengubah akses">
                      <span
                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $item->is_assigned ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
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
