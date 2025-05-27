@section('title', $title)

@section('page-script')
  @vite(['resources/js/ekko-lightbox.js'])

  <x-scripts.modal-handler id="whatsappModal" />
@endsection

<section class="content">
  <div class="container-fluid" id="form-create-or-update">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Campaign</div>

            <x-card.tools minus="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-form.input-horizontal
                id="campaignTitle"
                name="campaignTitle"
                label="Judul Campaign"
                placeholder="Masukan Judul Campaign"
                customClass="form-control-sm"
                wire:model.defer="campaignTitle"
              />

              <x-form.textarea-horizontal
                id="campaignMessage"
                name="campaignMessage"
                label="Pesan Campaign"
                rows="6"
                placeholder="Masukan Pesan Campaign"
                wire:model.defer="campaignMessage"
              />

              <x-form.image-horizontal
                id="image"
                name="image"
                label="Gambar"
                :preview="$image"
                path="{{ $image ?? null }}"
                wire:model.defer="image"
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
            <div class="card-title">Daftar Campaign Broadcast</div>

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
                    placeholder="Cari Campaign..."
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
                      <td>{!! nl2br(e($item->message)) !!}</td>
                      <td class="text-center">
                        <x-preview-image path="{{ $item->image }}"/>
                      </td>
                      <td class="actions">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <x-link.icon-primary wire:click="edit({{ $item->id }})" customClass="tooltips" title="Ubah">
                            <i class="fas fa-pencil"></i>
                          </x-link.icon-primary>

                          <x-link.icon-success wire:click="sendWA({{ $item->id }})" customClass="tooltips" title="Kirim Whatsapp">
                            <i class="fab fa-whatsapp"></i>
                          </x-link.icon-success>
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
