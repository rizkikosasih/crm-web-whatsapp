@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-400 mt-1">Detail rincian belanjaan dan alur status transaksi untuk pesanan pelanggan.</p>
    </div>
    <div>
      <x-link.button
        url="{{ url('transaksi/order') }}"
        color="danger"
        size="sm"
        class="w-full sm:w-auto cursor-pointer">
        <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Kembali
      </x-link.button>
    </div>
  </div>

  <!-- Detail Information Card -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Info & Status Panel -->
    <div class="lg:col-span-1 space-y-6">
      <x-card title="Rincian Pesanan">
        <div class="space-y-5 text-sm">
          <!-- ID Order -->
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider"
              >ID Pemesanan</span
            >
            <span class="text-base font-bold text-white mt-1 block">#{{ $order->id }}</span>
          </div>

          <!-- Customer Name -->
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider"
              >Nama Pelanggan</span
            >
            <span
              class="text-sm font-semibold text-white mt-1 block"
              >{{ $order->customer->name }}</span
            >
            <span class="text-xs text-slate-400 mt-0.5 block"
              >Telp: {{ $order->customer->phone }}</span
            >
          </div>

          <!-- Staff / Creator -->
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider"
              >Pemasok Pesanan</span
            >
            <span class="text-sm text-slate-300 mt-1 block">{{ $order->user->name }}</span>
          </div>

          <!-- Order Date -->
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider"
              >Tanggal Transaksi</span
            >
            <span
              class="text-sm text-slate-300 mt-1 block"
              >{{ dateIndo($order->order_date) }}</span
            >
          </div>

          <!-- Status Badge -->
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
              >Status Transaksi</span
            >
            @php
              $badgeColor = match ($order->status) {
                0 => 'bg-red-500/10 text-red-400 border border-red-500/20',
                1 => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                2 => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                3 => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                4 => 'bg-slate-500/10 text-slate-400 border border-slate-700/50',
                default => 'bg-slate-500/10 text-slate-400 border border-slate-700/50',
              };
            @endphp
            <span
              class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full {{ $badgeColor }}">
              {{ $statusList[$order->status] }}
            </span>
          </div>

          <!-- Proof of payment preview -->
          @if ($order->status > 0 && $order->status < 4)
            <div>
              <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                >Bukti Pembayaran</span
              >
              <div class="w-20 h-20 rounded-lg overflow-hidden border border-slate-700">
                <a
                  href="{{ imageUri($order->proof_of_payment ?? 'images/no-image.svg') }}"
                  target="_blank"
                  title="Klik untuk memperbesar">
                  <img
                    src="{{ imageUri($order->proof_of_payment ?? 'images/no-image.svg') }}"
                    class="w-full h-full object-cover" />
                </a>
              </div>
            </div>
          @endif
        </div>
      </x-card>

      <!-- Status Updater Panel -->
      @if (!in_array($order->status, [3, 4]))
        <x-card title="Ubah Status Transaksi">
          <form wire:submit.prevent="updateStatus" class="space-y-5">
            <x-overlay target="proof_of_payment, updateStatus">
              <div class="space-y-4">
                <div>
                  <label
                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                    >Pilih Status Baru</label
                  >
                  <select
                    wire:model.live="selectedStatus"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150 text-sm cursor-pointer">
                    @foreach ($this->availableStatusOptions() as $status)
                      <option value="{{ $status }}">{{ $statusList[$status] }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Proof of Payment Upload Input -->
                @if ($selectedStatus == 1 && !$order->proof_of_payment)
                  <div class="pt-2">
                    <x-form.image
                      id="proof_of_payment"
                      name="proof_of_payment"
                      label="Unggah Bukti Transfer"
                      wire:model="proof_of_payment">
                      @php
                        $imageUri = isLivewireTemporaryFile($proof_of_payment)
                          ? $proof_of_payment->temporaryUrl()
                          : imageUri($proof_of_payment ?: 'images/no-image.svg');
                      @endphp
                      <x-preview-image path="{{ $imageUri }}" width="100px" />
                    </x-form.image>
                  </div>
                @endif
              </div>
            </x-overlay>

            <div class="pt-2">
              <x-button
                type="submit"
                color="primary"
                size="sm"
                class="w-full cursor-pointer py-2.5 rounded-xl text-sm font-semibold"
                wire:loading.attr="disabled"
                wire:target="proof_of_payment, updateStatus">
                <i class="fas fa-check-circle mr-1.5 text-xs"></i> Simpan Status Baru
              </x-button>
            </div>
          </form>
        </x-card>
      @endif
    </div>

    <!-- Order Items Panel -->
    <div class="lg:col-span-2 space-y-6">
      <x-card title="Daftar Barang Belanja">
        <div class="overflow-x-auto rounded-xl border border-slate-700/80 bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-700/50">
            <thead class="bg-slate-800/40 text-slate-400">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                  Nama Produk
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                  Harga
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                  Qty
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">
                  Subtotal
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-850 bg-transparent text-slate-300">
              @foreach ($order->orderItems as $item)
                <tr class="hover:bg-slate-800/10 transition duration-150">
                  <td class="px-6 py-3.5 text-sm font-semibold text-white whitespace-nowrap">
                    <div>{{ $item->product->name }}</div>
                    <div class="text-xs font-normal text-slate-500 mt-0.5">
                      SKU: {{ $item->product->sku }}
                    </div>
                  </td>
                  <td class="px-6 py-3.5 text-right text-sm text-slate-400 whitespace-nowrap">
                    {{ rupiah($item->price) }}
                  </td>
                  <td
                    class="px-6 py-3.5 text-center text-sm font-semibold text-white whitespace-nowrap">
                    {{ $item->quantity }}
                  </td>
                  <td class="px-6 py-3.5 text-right text-sm font-bold text-white whitespace-nowrap">
                    {{
                      rupiah(
                        $item->price * $item->quantity,
                      )
                    }}
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="bg-slate-800/20">
                <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-slate-400">
                  Total Keseluruhan :
                </td>
                <td
                  class="px-6 py-4 text-right text-base font-extrabold text-indigo-400 whitespace-nowrap">
                  {{ rupiah($order->total_amount) }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Download Invoice PDF link if status Selesai (3) -->
        @if ($order->status === 3 && $order->link_pdf)
          <div class="mt-6 flex justify-end">
            <x-link.button
              color="success"
              size="sm"
              class="cursor-pointer font-bold tracking-wide"
              onclick="window.open(`{{ $order->link_pdf }}`, `_blank`)">
              <i class="fas fa-file-pdf mr-2 text-xs"></i> Cetak Invoice Resmi (PDF)
            </x-link.button>
          </div>
        @endif
      </x-card>
    </div>
  </div>
</div>
