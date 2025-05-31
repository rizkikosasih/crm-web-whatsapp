@section('title', $title)

<section class="content">
  <div class="container-fluid" id="create-or-update-form">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Role</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-overlay wire:target="save">
                <x-form.input
                  name="name"
                  id="name"
                  label="Nama Role"
                  placeholder="Masukan nama role pengguna"
                  wire:model.defer="name"
                />
              </x-overlay>

              <x-form.button-container customClass="justify-content-end">
                <x-button.custom wire:click="resetForm" color="danger">
                  Batal
                </x-button.custom>

                <x-button.custom type="submit" color="primary">
                  Simpan
                </x-button.custom>
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
                    placeholder="Cari Role..."
                    wire:model.live.debounce.250ms="search"
                  />
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <x-overlay wire:target="search, perPage">
                <table class="table table-striped table-bordered">
                  <x-table.header :columns="$tableHeader" />

                  <tbody>
                    @forelse ($items as $index => $item)
                      <tr>
                        <td class="text-center">{{ $index + $items->firstItem() }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="actions">
                          <div class="btn-group">
                            <x-link.button
                              url="{{ url('setting/role/' . $item->id) }}"
                              size="sm"
                              color="warning"
                              class="tooltips"
                              title="Akses Menu"
                            >
                              <i class="fas fa-key"></i>
                            </x-link.button>

                            <x-button.custom
                              wire:click="edit({{$item->id}})"
                              class="btn-sm tooltips"
                              title="Ubah"
                              color="primary"
                              size="sm"
                            >
                              <i class="fas fa-pencil"></i>
                            </x-button.custom>
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
</section>
