@section('title', $title)

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <x-link.button url="{{ url('transaksi/order/create') }}" color="primary" size="sm">
              <i class="fas fa-plus"></i> Buat Pesanan
            </x-link.button>

            <x-card.tools refresh="true" />
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <div class="d-flex justify-content-center justify-content-sm-start align-items-start flex-wrap gap-sm-3">
              <div class="col-auto px-0">
                <x-form.input-group-select
                  prependText="Length"
                  name="perPage"
                  parentClass="mb-0"
                  wire:model.live="perPage"
                  :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
                />
              </div>

              <div class="col-auto px-0">
                <x-form.input-group-select
                  prependText="Status"
                  name="status"
                  parentClass="mb-0"
                  optionHeader="Semua Status"
                  wire:model.live="status"
                  :options="[0 => 'Belum Bayar', 1 => 'Sudah Bayar', 2 => 'Pengiriman', 3 => 'Selesai', 4 => 'Batal']"
                />
              </div>

              <div class="col-auto px-0">
                <x-form.input-group
                  prependText="Mulai"
                  type="date"
                  name="dateStart"
                  parentClass="mb-0"
                  wire:model.live="dateStart"
                />
              </div>

              <div class="col-auto px-0">
                <x-form.input-group
                  prepend="true"
                  prependText="Akhir"
                  type="date"
                  name="dateEnd"
                  parentClass="mb-0"
                  wire:model.live="dateEnd"
                />
              </div>

              <div class="col-auto px-0">
                <x-form.input
                  name="search"
                  placeholder="Cari Pelanggan..."
                  wire:model.live.debounce.250ms="search"
                />
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($items as $index => $item)
                    @php
                      $customer = $item->customer;
                    @endphp
                    <tr>
                      <td class="text-center">{{ $index + $items->firstItem() }}</td>
                      <td>{{ $customer->name }}</td>
                      <td class="text-center">
                        <x-button color="{{ $colorStatus[$item->status] }}" size="sm">
                          {{ $statusList[$item->status] }}
                        </x-button>
                      </td>
                      <td class="text-end">{{ rupiah($item->total_amount) }}</td>
                      <td class="text-end">{{ dateIndo($item->order_date) }}</td>
                      <td class="actions">
                        <div class="btn-group">
                          <x-link.button
                            color="primary"
                            size="sm"
                            class="tooltips"
                            url="{{ url('transaksi/order/detail/' . $item->id) }}"
                            title="{{ in_array($item->status, [3,4]) ? 'Lihat Detail' : 'Update Status' }}"
                          >
                            @if (in_array($item->status, [3,4]))
                              <i class="fas fa-eye"></i>
                            @else
                              <i class="fas fa-pencil"></i>
                            @endif
                          </x-link.button>

                          @if ($item->status === 3)
                            <x-link.button
                              color="success"
                              size="sm"
                              class="tooltips"
                              onclick="window.open(`{{ $item->link_pdf }}`, `_blank`)"
                              title="cetak"
                            >
                              <i class="fas fa-file"></i>
                            </x-link.button>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="{{ sizeof($tableHeader) }}" class="text-center">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            {{ $items->links('partials.pagination.bootstrap4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

