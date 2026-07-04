@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Buat data pesanan baru untuk pelanggan dan kurangi stok produk secara langsung.</p>
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

  <!-- Form Selection Card -->
  <div class="w-full">
    <x-card title="Pilih Pelanggan & Produk">
      <div class="space-y-6">
        <!-- Customer Selection -->
        <div
          class="p-4 bg-slate-100/80 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700/50 rounded-xl space-y-4">
          <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            1. Data Pelanggan
          </h3>
          <x-dropdown-search
            label="Pelanggan"
            name="customer_id"
            placeholder="Cari pelanggan..."
            horizontal="true"
            :items="$customers"
            :views="['name', 'phone']"
            :searching="$customerSearch"
            :selectedName="$selectedCustomerName"
            searchModel="customerSearch"
            selectedNameModel="selectedCustomerName"
            onSelect="selectCustomer" />
        </div>

        <!-- Product Selection -->
        <div
          class="p-4 bg-slate-100/80 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700/50 rounded-xl space-y-4">
          <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            2. Pilih Barang Belanjaan
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
            <div class="md:col-span-6">
              <x-dropdown-search
                label="Produk"
                name="product_id"
                placeholder="Cari produk..."
                :items="$products"
                :searching="$productSearch"
                :selectedName="$selectedProductName"
                searchModel="productSearch"
                selectedNameModel="selectedProductName"
                onSelect="selectProduct" />
            </div>
            <div class="md:col-span-3">
              <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2"
                >Jumlah (Qty)</label
              >
              <input
                type="number"
                wire:model="quantity"
                class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-150 text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                min="1" />
            </div>
            <div class="md:col-span-3">
              <x-button
                type="button"
                color="primary"
                size="sm"
                class="w-full py-2.5 rounded-xl cursor-pointer"
                wire:click="addProduct">
                <i class="fas fa-cart-plus mr-1.5 text-xs"></i> Tambah Item
              </x-button>
            </div>
          </div>
        </div>

        <!-- Cart Items List -->
        @if ($orderItems)
          <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

          <div class="space-y-6">
            <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">
              Rincian Daftar Belanja
            </h3>

            <div
              class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-900/10">
              <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700/50">
                <thead class="bg-slate-50 dark:bg-slate-800/40 text-slate-600 dark:text-slate-400">
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
                    <th
                      scope="col"
                      class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody
                  class="divide-y divide-slate-100 dark:divide-slate-800 bg-transparent text-slate-700 dark:text-slate-300">
                  @foreach ($orderItems as $index => $item)
                    <tr
                      class="hover:bg-slate-50 dark:hover:bg-slate-800/10 transition duration-150">
                      <td
                        class="px-6 py-3.5 text-sm font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                        {{ $item['name'] }}
                      </td>
                      <td
                        class="px-6 py-3.5 text-right text-sm whitespace-nowrap text-slate-500 dark:text-slate-400">
                        {{ rupiah($item['price']) }}
                      </td>
                      <td
                        class="px-6 py-3.5 text-center text-sm font-semibold whitespace-nowrap text-slate-900 dark:text-white">
                        {{ $item['quantity'] }}
                      </td>
                      <td
                        class="px-6 py-3.5 text-right text-sm font-bold text-slate-900 dark:text-white whitespace-nowrap">
                        {{
                          rupiah(
                            $item['price'] * $item['quantity'],
                          )
                        }}
                      </td>
                      <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm actions">
                        <x-button
                          color="danger"
                          size="xs"
                          class="cursor-pointer"
                          wire:click="removeItem({{ $index }})"
                          title="Hapus Item">
                          <i class="fas fa-trash-can text-xs"></i>
                        </x-button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="bg-slate-50 dark:bg-slate-800/20">
                    <td
                      colspan="3"
                      class="px-6 py-4 text-right text-sm font-bold text-slate-500 dark:text-slate-400">
                      Total Transaksi :
                    </td>
                    <td
                      class="px-6 py-4 text-right text-base font-extrabold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                      {{
                        rupiah(
                          collect($orderItems)->sum(fn($i) => $i['price'] * $i['quantity']),
                        )
                      }}
                    </td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <div class="flex justify-end pt-4">
              <x-button
                type="button"
                wire:click="save"
                color="success"
                size="sm"
                class="w-full sm:w-auto px-8 py-2.5 rounded-xl cursor-pointer font-bold text-sm tracking-wide">
                <i class="fas fa-check mr-2 text-xs"></i> Simpan Order & Kirim WA
              </x-button>
            </div>
          </div>
        @endif
      </div>
    </x-card>
  </div>
</div>
