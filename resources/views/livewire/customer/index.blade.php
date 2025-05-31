@section('title', $title)

@section('page-script')
  @vite(['resources/js/form.js'])
@endsection

<section class="content">
  <div class="container-fluid" id="create-or-update-form">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Pelanggan</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-form.input
                name="name"
                id="name"
                label="Nama Customer"
                placeholder="Masukan nama customer"
                wire:model.defer="name"
              />

              <x-form.input
                name="phone"
                id="phone"
                label="No Handphone <small>(contoh: 6285123456789)</small>"
                placeholder="Masukan no handphone"
                customClass="number-only"
                wire:model.defer="phone"
              />

              <x-form.textarea
                name="notes"
                id="notes"
                label="Catatan"
                placeholder="Masukan Catatan"
                rows="3"
                wire:model.defer="notes"
              />

              <x-form.button-container customClass="justify-content-end">
                <x-button wire:click="resetForm" color="danger">
                  Batal
                </x-button>

                <x-button type="submit" color="primary">
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
                    placeholder="Cari Pelanggan..."
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
                      <td>{{ $item->phone }}</td>
                      <td class="actions">
                        <div class="btn-group">
                          <x-button wire:click="edit({{$item->id}})" color="primary" size="sm" class="tooltips" title="Ubah">
                            <i class="fas fa-pencil"></i>
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
            </div>

            {{ $items->links('partials.pagination.bootstrap4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
