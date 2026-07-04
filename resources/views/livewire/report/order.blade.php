@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Laporan rekapitulasi data penjualan per rentang waktu dan filter status.</p>
    </div>
    <div class="flex items-center gap-3">
      <x-button
        wire:click="exportXls"
        color="success"
        size="sm"
        class="w-full sm:w-auto cursor-pointer font-semibold"
        wire:loading.attr="disabled">
        <i class="fas fa-file-excel mr-1.5 text-sm"></i> Export ke Excel
      </x-button>

      <x-button
        wire:click="$refresh"
        color="primary"
        size="sm"
        class="w-full sm:w-auto cursor-pointer"
        wire:loading.attr="disabled">
        <i class="fas fa-arrows-rotate mr-1.5 text-xs"></i> Refresh
      </x-button>
    </div>
  </div>

  <!-- Main Table Card -->
  <div class="w-full">
    <x-card title="Filter Laporan Penjualan">
      <!-- Filter Inputs Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div>
          <x-form.input-select
            name="status"
            label="Filter Status"
            optionHeader="Semua Status"
            wire:model.live="status"
            :options="
              [
      0 => 'Belum Bayar',
      1 => 'Sudah Bayar',
      2 => 'Pengiriman',
      3 => 'Selesai',
      4 => 'Batal',
    ]
            "
            parentClass="mb-0" />
        </div>
        <div>
          <x-form.input
            type="date"
            name="dateStart"
            label="Tanggal Mulai"
            wire:model.live="dateStart"
            parentClass="mb-0" />
        </div>
        <div>
          <x-form.input
            type="date"
            name="dateEnd"
            label="Tanggal Akhir"
            wire:model.live="dateEnd"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Livewire Loading Overlay -->
      <x-overlay target="status, dateStart, dateEnd, exportXls">
        <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-700/50">
            <x-table.header :columns="$tableHeader" />
            <tbody class="divide-y divide-slate-800 bg-transparent text-slate-300">
              @forelse ($orders as $index => $item)
                @php
                  $badgeColor = match ($item->status) {
                    0 => 'bg-red-500/10 text-red-400 border border-red-500/20',
                    1 => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                    2 => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                    3 => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                    4 => 'bg-slate-500/10 text-slate-400 border border-slate-700/50',
                    default => 'bg-slate-500/10 text-slate-400 border border-slate-700/50',
                  };
                @endphp
                <tr class="hover:bg-slate-800/20 transition duration-150">
                  <td
                    class="px-6 py-3.5 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                    #{{ $item->id }}
                  </td>
                  <td class="px-6 py-3.5 text-center text-sm text-slate-400 whitespace-nowrap">
                    {{ dateIndo($item->order_date) }}
                  </td>
                  <td class="px-6 py-3.5 text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->customer->name }}
                  </td>
                  <td class="px-6 py-3.5 text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full {{ $badgeColor }}">
                      {{ $statusList[$item->status] }}
                    </span>
                  </td>
                  <td
                    class="px-6 py-3.5 text-center text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->orderItems->sum('quantity') }}
                  </td>
                  <td class="px-6 py-3.5 text-right text-sm font-bold text-white whitespace-nowrap">
                    {{ rupiah($item->total_amount) }}
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
            @if ($totalQty)
              <tfoot class="bg-slate-800/20">
                <tr>
                  <td
                    colspan="{{ sizeof($tableHeader) - 2 }}"
                    class="px-6 py-4 text-right text-sm font-bold text-slate-400">
                    Total Penjualan :
                  </td>
                  <td
                    class="px-6 py-4 text-center text-sm font-extrabold text-white whitespace-nowrap">
                    {{ $totalQty }}
                  </td>
                  <td
                    class="px-6 py-4 text-right text-base font-extrabold text-indigo-400 whitespace-nowrap">
                    {{ rupiah($totalPrice) }}
                  </td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      </x-overlay>
    </x-card>
  </div>
</div>
