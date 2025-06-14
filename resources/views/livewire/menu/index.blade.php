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
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Menu</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-overlay wire:target="save">
                <x-dropdown-search
                  label="Parent Menu <small>(opsional)</small>"
                  name="parentId"
                  placeholder="Cari menu..."
                  horizontal="true"
                  :items="$menus"
                  :searching="$parentSearch"
                  :selectedName="$selectedParentName"
                  searchModel="parentSearch"
                  selectedNameModel="selectedParentName"
                  onSelect="selectParent"
                />

                <x-form.input
                  name="name"
                  id="name"
                  label="Nama Menu"
                  placeholder="Masukan nama menu"
                  wire:model.defer="name"
                  horizontal="true"
                />

                <x-form.input
                  name="position"
                  id="position"
                  type="number"
                  label="Urutan Menu"
                  placeholder="Masukan urutan menu"
                  customClass="number-only"
                  wire:model.defer="position"
                  horizontal="true"
                />

                <x-form.input
                  name="icon"
                  id="icon"
                  label="Icon <small>(<a href='https://fontawesome.com/v6/icons' target='_blank'>Font Awesome</a>)</small>"
                  placeholder="Masukan icon menu"
                  wire:model.defer="icon"
                  horizontal="true"
                />

                <x-form.input
                  name="route"
                  id="route"
                  label="Route"
                  placeholder="Masukan route menu"
                  wire:model.defer="route"
                  horizontal="true"
                />

                <x-form.input
                  name="slug"
                  id="slug"
                  label="Slug"
                  placeholder="Masukan slug menu"
                  wire:model.defer="slug"
                  horizontal="true"
                />
              </x-overlay>

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
                    placeholder="Cari menu..."
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
                        <td>{{ $item->parent?->name ?? '-' }}</td>
                        <td>{{ $item->name }}</td>
                        <td><i class="{{ $item->icon }}"></i> {{ $item->icon }}</td>
                        <td class="text-center">{{ $item->position }}</td>
                        <td>{{ $item->route }}</td>
                        <td>{{ $item->slug }}</td>
                        <td class="text-center">
                          <x-button
                            wire:click="confirmActive({{ $item->id }}, {{ $item->is_active }})"
                            color="{{ $colorStatus[$item->is_active] }}"
                            size="sm"
                          >
                            {{ $statusList[$item->is_active] }}
                          </x-button>
                        </td>
                        <td class="actions">
                          <div class="btn-group">
                            <x-button
                              wire:click="edit({{$item->id}})"
                              class="tooltips"
                              title="Ubah"
                              color="primary"
                              size="sm"
                            >
                              <i class="fas fa-pencil"></i>
                            </x-button>

                            <x-button
                              wire:click="confirmDelete({{$item->id}})"
                              class="tooltips"
                              title="Hapus"
                              color="danger"
                              size="sm"
                            >
                              <i class="fas fa-trash"></i>
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
</section>
