@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-400 mt-1">Kelola transaksi pesanan pelanggan, validasi pembayaran, cetak invoice PDF, dan notifikasi status pengiriman.</p>
    </div>
    <div>
      <x-link.button
        url="{{ url('transaksi/order/create') }}"
        color="primary"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-plus mr-1.5 text-xs"></i> Buat Pesanan
      </x-link.button>
    </div>
  </div>

  <!-- Main Card -->
  <div class="w-full">
    <x-card title="Daftar Pesanan Masuk">
      <x-slot:tools>
        <x-card.tools refresh="true" />
      </x-slot:tools>

      @if (session()->has('success'))
        <div class="mb-5">
          <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
        </div>
      @endif

      <!-- Filter Controls Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div>
          <x-form.input-select
            name="perPage"
            label="Tampilkan"
            wire:model.live="perPage"
            :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            parentClass="mb-0" />
        </div>
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
        <div>
          <x-form.input
            name="search"
            label="Cari Pelanggan"
            placeholder="Nama..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Table Loader & Container -->
      <x-overlay target="search, perPage, status, dateStart, dateEnd, gotoPage, nextPage">
        <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-700/50">
            <x-table.header :columns="$tableHeader" />
            <tbody class="divide-y divide-slate-800 bg-transparent text-slate-300">
              @forelse ($items as $index => $item)
                @php
                  $customer = $item->customer;

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
                    class="px-6 py-4 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                    {{ $index + $items->firstItem() }}
                  </td>
                  <td class="px-6 py-4 text-sm font-semibold text-white whitespace-nowrap">
                    {{ $customer->name }}
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    <span
                      class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full {{ $badgeColor }}">
                      {{ $statusList[$item->status] }}
                    </span>
                  </td>
                  <td
                    class="px-6 py-4 text-right text-sm font-semibold text-white whitespace-nowrap">
                    {{ rupiah($item->total_amount) }}
                  </td>
                  <td class="px-6 py-4 text-center text-sm text-slate-400 whitespace-nowrap">
                    {{ dateIndo($item->order_date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium actions">
                    <div class="inline-flex rounded-lg shadow-sm">
                      <x-link.button
                        color="primary"
                        size="sm"
                        class="cursor-pointer {{ $item->status === 3 ? 'rounded-l-xl rounded-r-none border-r border-slate-700/50' : 'rounded-xl' }}"
                        url="{{ url('transaksi/order/detail/' . $item->id) }}"
                        title="{{ in_array($item->status, [3,4]) ? 'Lihat Detail' : 'Update Status' }}">
                        @if (in_array($item->status, [3, 4]))
                          <i class="fas fa-eye mr-1 text-xs"></i>
                          Detail
                        @else
                          <i class="fas fa-pencil mr-1 text-xs"></i>
                          Update
                        @endif
                      </x-link.button>

                      @if ($item->status === 3)
                        <x-link.button
                          color="success"
                          size="sm"
                          class="rounded-r-xl rounded-l-none cursor-pointer"
                          onclick="window.open(`{{ $item->link_pdf }}`, `_blank`)"
                          title="Cetak Invoice PDF">
                          <i class="fas fa-file-pdf mr-1 text-xs animate-pulse"></i> Invoice
                        </x-link.button>
                      @endif
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

      <!-- Pagination Footer -->
      <div class="mt-6">{{ $items->links() }}</div>
    </x-card>
  </div>
</div>
