@section('title', $title)

@section('page-script')
  @vite(['resources/js/form.js', 'resources/js/ekko-lightbox.js'])

  <x-scripts.modal-handler id="whatsappModal" />
@endsection

<section class="content">
  <div class="container-fluid" id="form-create-or-update">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Produk</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-overlay wire:target='save'>
                <x-form.input-horizontal
                  id="name"
                  name="name"
                  label="Nama Produk"
                  placeholder="Masukan Nama Produk"
                  customClass="form-control-sm"
                  wire:model.defer="name"
                />

                <x-form.input-horizontal
                  id="sku"
                  name="sku"
                  label="SKU Produk"
                  placeholder="Masukan SKU Produk"
                  customClass="form-control-sm"
                  wire:model.defer="sku"
                />

                <x-form.input-horizontal
                  id="price"
                  name="price"
                  label="Harga"
                  placeholder="Masukan Harga"
                  customClass="form-control-sm number-only"
                  wire:model.defer="price"
                />

                <x-form.input-horizontal
                  id="stock"
                  name="stock"
                  label="Stock"
                  placeholder="Masukan Stock"
                  customClass="form-control-sm number-only"
                  maxlength="3"
                  wire:model.defer="stock"
                />

                <x-form.textarea-horizontal
                  id="description"
                  name="description"
                  label="Deskripsi Produk"
                  placeholder="Masukan Deskripsi Produk"
                  wire:model.defer="description"
                />

                <x-form.image-horizontal
                  id="image"
                  name="image"
                  label="Gambar"
                  :preview="$image"
                  path="{{ $image ?? null }}"
                  wire:model.defer="image"
                />
              </x-overlay>

              <hr>

              <x-form.button-container customClass="justify-content-end">
                <x-button wire:click="resetForm" wire:loading.attr="disabled" wire:target="image, save" color="danger">
                  Batal
                </x-button>

                <x-button type="submit" wire:loading.attr="disabled" wire:target="image, save" color="primary">
                  Simpan
                </x-button>
              </x-form.button-container>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid" id="list">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">Daftar Produk</div>

            <x-card.tools refresh="true"/>
          </div>

          <div class="card-body text-justify">
            <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
              <div class="col-auto">
                <x-form.input-group-select
                  prependText="Length"
                  name="perPage"
                  wire:model.live="perPage"
                  :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
                />
              </div>

              <div class="ml-auto">
                <div class="col-auto">
                  <x-form.input
                    name="search"
                    placeholder="Cari Produk..."
                    wire:model.live.debounce.250ms="search"
                  />
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <x-overlay wire:target='search, perPage, gotoPage'>
                <table class="table table-striped table-bordered">
                  <x-table.header :columns="$tableHeader" />

                  <tbody>
                    @forelse ($items as $index => $item)
                      <tr>
                        <td class="text-center">{{ $index + $items->firstItem() }}</td>
                        <td>{{ $item->name }} ( {{ $item->sku }} )</td>
                        <td class="text-center">
                          <x-button color="primary" size="sm" class="circle">
                            {{ $item->stock }}
                          </x-button>
                        </td>
                        <td class="text-end">{{ rupiah($item->price) }}</td>
                        <td class="text-center">
                          <x-preview-image path="{{ $item->image }}" />
                        </td>
                        <td class="actions">
                          <div class="btn-group">
                            <x-button
                              wire:click="edit({{ $item->id }})"
                              class="tooltips"
                              title="Ubah"
                              color="primary"
                              size="sm"
                            >
                              <i class="fas fa-pencil"></i>
                            </x-button>

                            <x-button
                              wire:click="$dispatch('showWhatsappModal', { id: {{ $item->id }} })"
                              class="tooltips"
                              title="Kirim Sebagai Pesan"
                              color="success"
                              size="sm"
                            >
                              <i class="fab fa-whatsapp"></i>
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

            {{ $items->links('partials.pagination.bootstrap4') }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <livewire:product.whatsapp-modal />
</section>
