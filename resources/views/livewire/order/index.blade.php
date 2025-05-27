@section('title', $title)

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <x-link.button-primary url="{{ url('transaksi/order/create') }}" customClass="btn-sm">
              <i class="fas fa-plus"></i> Buat Pesanan
            </x-link.button-primary>
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
                        <x-button.default customClass="btn-sm btn-{{ $colorStatus[$item->status] }}">
                          {{ $statusList[$item->status] }}
                        </x-button.default>
                      </td>
                      <td class="text-end">{{ rupiah($item->total_amount) }}</td>
                      <td class="text-end">{{ dateIndo($item->order_date) }}</td>
                      <td class="actions">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <x-link.icon-primary
                            url="{{ url('transaksi/order/detail/' . $item->id) }}"
                            customClass="tooltips"
                            title="{{ in_array($item->status, [3,4]) ? 'Lihat Detail' : 'Update Status' }}"
                          >
                            @if (in_array($item->status, [3,4]))
                              <i class="fas fa-eye"></i>
                            @else
                              <i class="fas fa-pencil"></i>
                            @endif
                          </x-link.icon-primary>
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
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>

