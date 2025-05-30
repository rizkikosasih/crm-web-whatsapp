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

                <x-form.input-horizontal
                  name="name"
                  id="name"
                  label="Nama Menu"
                  placeholder="Masukan nama menu"
                  wire:model.defer="name"
                />

                <x-form.input-horizontal
                  name="position"
                  id="position"
                  type="number"
                  label="Urutan Menu"
                  placeholder="Masukan urutan menu"
                  customClass="number-only"
                  wire:model.defer="position"
                />

                <x-form.input-horizontal
                  name="icon"
                  id="icon"
                  label="Icon <small>(<a href='https://fontawesome.com/v6/icons' target='_blank'>Font Awesome</a>)</small>"
                  placeholder="Masukan icon menu"
                  wire:model.defer="icon"
                />

                <x-form.input-horizontal
                  name="route"
                  id="route"
                  label="Route"
                  placeholder="Masukan route menu"
                  wire:model.defer="route"
                />

                <x-form.input-horizontal
                  name="slug"
                  id="slug"
                  label="Slug"
                  placeholder="Masukan slug menu"
                  wire:model.defer="slug"
                />
              </x-overlay>

              <x-form.button-container customClass="justify-content-end">
                <x-button.danger wire:click="resetForm">
                  Batal
                </x-button.danger>

                <x-button.primary type="submit">
                  Simpan
                </x-button.primary>
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
                          <x-button.default
                            wire:click="confirmActive({{ $item->id }}, {{ $item->is_active }})"
                            customClass="btn-sm btn-{{ $colorStatus[$item->is_active] }}"
                          >
                            {{ $statusList[$item->is_active] }}
                          </x-button.default>
                        </td>
                        <td class="actions">
                          <div class="d-flex justify-content-center align-items-center gap-2">
                            <x-link.icon-primary wire:click="edit({{$item->id}})" customClass="tooltips" title="Ubah">
                              <i class="fas fa-pencil"></i>
                            </x-link.icon-primary>

                            <x-link.icon-danger wire:click="confirmDelete({{$item->id}})" customClass="tooltips" title="Hapus">
                              <i class="fas fa-trash"></i>
                            </x-link.icon-danger>
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
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>
