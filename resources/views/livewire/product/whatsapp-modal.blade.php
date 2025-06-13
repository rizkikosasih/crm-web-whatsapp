<div
  class="modal fade"
  id="whatsappModal"
  tabindex="-1"
  aria-labelledby="whatsappModalLabel"
  wire:ignore.self
>
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kirim Info Produk {{ $productName }} Ke Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
          <div class="col-auto">
            <x-form.input-group-select
              prependText="Length"
              name="perPage"
              wire:model.live="perPage"
              :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            />
          </div>

          <div class="col-auto">
            <x-form.input
              name="search"
              placeholder="Cari Nama Pelanggan..."
              wire:model.live="search"
            />
          </div>

          <div class="col-auto">
            <x-form.input
              name="searchPhone"
              placeholder="Cari No Handphone..."
              wire:model.live="searchPhone"
            />
          </div>
        </div>

        @if ($idProduct)
          <div class="table-responsive">
            <x-overlay wire:target='sendWA'>
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($items['data'] as $item)
                    <tr>
                      <td class="text-center">{{ $item['id'] }}</td>
                      <td>{{ $item['name'] }}</td>
                      <td>{{ $item['phone'] }}</td>
                      <td class="actions">
                        <div class="btn-group">
                          <x-button
                            class="tooltips"
                            title="Kirim Info Produk"
                            wire:click="sendWA({{$item['phone']}})"
                            color="success"
                          >
                            <i class="fas fa-paper-plane"></i>
                          </x-button>
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
            </x-overlay>
          </div>
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close" wire:click="closeModal()">Batal</button>
      </div>
    </div>
  </div>
</div>
