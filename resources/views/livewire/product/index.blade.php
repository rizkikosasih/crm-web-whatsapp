@section('title', $title)

@section('page-style')
  @vite(['resources/js/form.js', 'resources/js/ekko-lightbox.js'])
@endsection

<section class="content">
  <div class="container-fluid" id="form-create-or-update">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title">{{ $isEdit ? 'Ubah Produk' : 'Tambah Produk' }}</h5>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-form.input-horizontal
                id="name"
                name="name"
                label="Nama Produk"
                placeholder="Masukan Nama Produk"
                customClass="form-control-sm"
                wire:model.defer="name"
              ></x-form.input-horizontal>

              <x-form.input-horizontal
                id="price"
                name="price"
                label="Harga"
                placeholder="Masukan Harga"
                customClass="form-control-sm number-only"
                wire:model.defer="price"
              ></x-form.input-horizontal>

              <x-form.input-horizontal
                id="stock"
                name="stock"
                label="Stock"
                placeholder="Masukan Stock"
                customClass="form-control-sm number-only"
                maxlength="3"
                wire:model.defer="stock"
              ></x-form.input-horizontal>

              <x-form.textarea-horizontal
                id="description"
                name="description"
                label="Deskripsi Produk"
                placeholder="Masukan Deskripsi Produk"
                wire:model.defer="description"
              ></x-form.textarea-horizontal>

              <x-form.image-horizontal
                id="image"
                name="image"
                label="Gambar"
                :preview="$image"
                path="{{ $image ?? null }}"
                wire:model.defer="image"
              ></x-form.image-horizontal>

              <hr>

              <x-form.button-container customClass="justify-content-end">
                @if ($isEdit)
                  <x-button.danger wire:click="resetForm">
                    Batal
                  </x-button.danger>
                @endif

                <x-button.primary type="submit">
                  Simpan
                </x-button.primary>
              </x-form.button-container>
          </form>
          </div>
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>

  <div class="container-fluid" id="list">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title">Daftar Produk</h5>
          </div>

          <div class="card-body text-justify">
            <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
              <div class="col-auto">
                <x-form.input-group-select
                  label="Length"
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
              <table class="table table-striped table-bordered">
                <x-table.header :columns="$tableHeader" />

                <tbody>
                  @forelse ($items as $index => $item)
                    <tr>
                      <td class="text-center">{{ $index + $items->firstItem() }}</td>
                      <td>{{ $item->name }}</td>
                      <td class="text-center">
                        <x-button.primary customClass="btn-sm circle">
                          {{ $item->stock }}
                        </x-button.primary>
                      </td>
                      <td class="text-end">{{ rupiah($item->price) }}</td>
                      <td class="text-center">
                        <a
                          href="{{ imageUri($item->image ?? 'images/no-image.svg') }}"
                          data-toggle="lightbox"
                          class="tooltips"
                          title="Perbesar"
                        >
                          <img
                            class="img-rounded"
                            style="width:30px;height:auto"
                            src="{{ imageUri($item->image ?? 'images/no-image.svg') }}"
                          />
                        </a>
                      </td>
                      <td class="actions">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <x-link.icon-primary wire:click="edit({{ $item->id }})" customClass="tooltips" title="Ubah">
                            <i class="fas fa-pencil"></i>
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
