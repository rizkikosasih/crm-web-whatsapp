@section('title', $title)

@section('page-script')
  @vite(['resources/js/ekko-lightbox.js'])
@endsection

<section class="content">
  <div class="container-fluid" id="form-create-or-update">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Template</div>
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
                id="titleTemplate"
                name="titleTemplate"
                label="Judul Template"
                placeholder="Masukan Judul Template"
                customClass="form-control-sm"
                wire:model.defer="titleTemplate"
              />

              <x-form.input-select-horizontal
                id="type"
                name="type"
                label="Tipe Template"
                customClass="form-control-sm"
                wire:model.defer="type"
                :options="['campaign' => 'Campaign Broadcast', 'product' => 'Produk', 'order' => 'Pemesanan']"
                selected="{{ $this->type }}"
              />

              <x-form.textarea-horizontal
                id="body"
                name="body"
                label="Isi Pesan"
                placeholder="Masukan Isi Pesan"
                rows="6"
                wire:model.defer="body"
              />

              <hr>

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
            <div class="card-title">Daftar Template Pesan</div>
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
                    placeholder="Cari Template..."
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
                      <td>{{ $item->title }}</td>
                      <td>{!! nl2br(e($item->body)) !!}</td>
                      <td class="text-center">
                        {{ $types[$item->type] }}
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
