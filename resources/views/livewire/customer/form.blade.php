@section('title', $title)

@section('page-script')
  @vite(['resources/js/form.js'])
@endsection

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 m-1 p-1">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h3>{{ $subtitle }}</h3>
              <x-link.button-primary url="{{ url('customer') }}" wire:navigate>
                <i class="fas fa-arrow-left"></i> Kembali
              </x-link.button-primary>
            </div>
          </div>

          <div class="card-body text-justify">
            <form wire:submit.prevent="save">
              <x-form.input
                name="name"
                id="name"
                label="Nama Customer"
                placeholder="Masukan nama customer"
                wire:model.defer="name"
                ></x-form.input>

              <x-form.input
                name="phone"
                id="phone"
                label="No Handphone <small>(contoh: 6285123456789)</small>"
                placeholder="Masukan no handphone"
                customClass="number-only"
                wire:model.defer="phone"
              ></x-form.input>

              <x-form.textarea
                name="notes"
                id="notes"
                label="Catatan"
                placeholder="Masukan Catatan"
                rows="3"
                wire:model.defer="notes"
              ></x-form.textarea>

              <div class="d-flex align-items-center gap-3">
                <x-link.button-danger url="{{ url('customer') }}" wire:navigate>Batal</x-link.button-danger>
                <x-button.primary type="submit">Simpan</x-button.primary>
              </div>
            </form>
          </div>
          <!-- /. card body -->
        </div>
      </div>
    </div>
    <!--/. row -->
  </div>
  <!--/. container-fluid -->
</section>
