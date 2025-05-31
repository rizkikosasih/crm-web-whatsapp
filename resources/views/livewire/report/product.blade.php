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
                <x-button
                  wire:click="exportXls"
                  class="tooltips"
                  title="Export ke Excel"
                  color="primary"
                  wire:loading.attr="disabled"
                >
                  <i class="fas fa-file-excel"></i>
                </x-button>
              </div>

              <div class="col-auto px-0">
                <x-button
                  wire:click="$refresh" title="Refresh Halaman"
                  wire:loading.attr="disabled"
                  color="info"
                >
                  <i class="fas fa-refresh"></i>
                </x-button>
              </div>
            </div>

            <div class="table-responsive mt-3">
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($items as $index => $item)
                    <tr>
                      <td class="text-center">{{ $item->product_id }}</td>
                      <td>{{ $item->product->name }}</td>
                      <td class="text-center">{{ $item->total_quantity }}</td>
                      <td class="text-end">{{ rupiah($item->total_income) }}</td>
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
        </div>
      </div>
    </div>
  </div>
</section>


