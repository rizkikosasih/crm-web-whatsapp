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
            <div class="card-title">{{ $isEdit ? 'Ubah' : 'Tambah' }} Whatsapp API</div>
            <x-card.tools refresh="true"/>
          </div>

          <div class="card-body text-justify">
            @if (session()->has('success'))
              <x-alert.success dismissible="true">{{ session('success') }}</x-alert.success>
            @endif

            <form wire:submit.prevent="save">
              <x-form.input
                id="apiKey"
                name="apiKey"
                label="API Key"
                placeholder="Masukkan API Key"
                class="form-control-sm"
                wire:model.defer="apiKey"
                horizontal="true"
              />

              <x-form.input
                id="apiUrl"
                name="apiUrl"
                label="API URL"
                placeholder="Masukkan API URL dengan https://"
                class="form-control-sm"
                wire:model.defer="apiUrl"
                horizontal="true"
              />

              <hr>

              <x-form.button-container class="justify-content-end">
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
</section>
