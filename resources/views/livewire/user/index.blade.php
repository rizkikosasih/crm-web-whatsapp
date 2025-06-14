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
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Pengguna</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            @if (session()->has('error'))
              <x-alert.danger dismissible="true">{{ session('error') }}</x-alert.danger>
            @endif

            <form wire:submit.prevent="save">
              <x-form.input
                name="name"
                id="name"
                label="Nama Pengguna"
                placeholder="Masukan Nama Pengguna"
                wire:model.defer="name"
              />

              <x-form.input
                name="username"
                id="username"
                label="Username Pengguna"
                placeholder="Masukan Username Pengguna"
                wire:model.defer="username"
              />

              <x-form.input
                name="phone"
                id="phone"
                label="No Handphone <small>(contoh: 6285123456789)</small>"
                placeholder="Masukan no handphone"
                customClass="number-only"
                wire:model.defer="phone"
              />

              <x-form.input
                name="email"
                id="email"
                type="email"
                label="Email"
                placeholder="Masukan email"
                wire:model.defer="email"
              />

              <x-form.input-select
                name="role_id"
                id="role_id"
                label="Role Pengguna"
                placeholder="Pilih Role Pengguna"
                :options="$roles"
                optionHeader="Pilih Role Pengguna"
                wire:model.defer="role_id"
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
          <div class="card-header">
            <div class="card-title">Daftar Pengguna</div>

            <x-card.tools refresh="true" minus="true"/>
          </div>

          <div class="card-body text-justify">
            <div class="d-flex justify-content-center justify-content-sm-start align-items-start gap-sm-3">
              <div class="col-auto">
                <x-form.input-group-select
                  prependText="Length"
                  name="perPage"
                  wire:model.live.debounce.250ms="perPage"
                  :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
                />
              </div>

              <div class="col-auto">
                <x-form.input-group-select
                  prependText="Role"
                  name="filterRole"
                  wire:model.live.debounce.250ms="filterRole"
                  :options="$roles"
                  optionHeader="Semua"
                />
              </div>

              <div class="ml-auto">
                <div class="col-auto">
                  <x-form.input
                    name="search"
                    placeholder="Cari Pengguna..."
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
                      <td>{{ $item->email }}</td>
                      <td>{{ $item->phone }}</td>
                      <td class="text-center">
                        <x-button
                          wire:click="confirmActive({{ $item->id }}, {{ $item->is_active }})"
                          color="{{ $colorStatus[$item->is_active] }}"
                          size="sm"
                        >
                          {{ $statusList[$item->is_active] }}
                        </x-button>
                      </td>
                      <td>{{ $item->role->name }}</td>
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
