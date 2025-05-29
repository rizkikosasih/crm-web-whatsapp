@section('title', $title)

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <div class="d-flex justify-content-center justify-content-sm-start align-items-start flex-wrap gap-sm-3">
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
                <x-button.primary wire:click="exportXls" customClass="tooltips" title="Export ke Excel">
                  <i class="fas fa-file-excel"></i>
                </x-button.primary>
              </div>

              <div class="col-auto px-0">
                <x-button.default wire:click="$refresh" customClass="tooltips btn-info" title="Refresh Halaman">
                  <i class="fas fa-refresh"></i>
                </x-button.default>
              </div>
            </div>

            <div class="table-responsive mt-3">
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($orders as $index => $item)
                    <tr>
                      <td class="text-center">{{ $item->id }}</td>
                      <td class="text-end">{{ dateIndo($item->order_date) }}</td>
                      <td>{{ $item->customer->name }}</td>
                      <td class="text-center">
                        <x-button.default customClass="btn-sm btn-{{ $colorStatus[$item->status] }}">
                          {{ $statusList[$item->status] }}
                        </x-button.default>
                      </td>
                      <td class="text-center">{{ $item->orderItems->sum('quantity') }}</td>
                      <td class="text-end">{{ rupiah($item->total_amount) }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="{{ sizeof($tableHeader) }}" class="text-center">Data Kosong</td>
                    </tr>
                  @endforelse
                </tbody>
                @if ($totalQty)
                  <tfoot>
                    <tr>
                      <th colspan="{{ sizeof($tableHeader) - 2 }}" class="text-end">Total</th>
                      <th class="text-center">{{ $totalQty }}</th>
                      <th class="text-end">{{ rupiah($totalPrice) }}</th>
                    </tr>
                  </tfoot>
                @endif
              </table>
            </div>
          </div>
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>

