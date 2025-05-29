@section('title', $title)

@section('page-script')
  @vite(['resources/js/form.js', 'resources/js/show-password.js', 'resources/js/ekko-lightbox.js'])
@endsection

<section class="content">
  <div class="container-fluid">
    <div class="row">
      {{-- Left Content --}}
      <div class="col-md-4">
        {{-- Profile Image --}}
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <x-preview-image
                path="{{ $originalAvatar ?: 'images/avatar.png' }}"
                imageTitle="Perbesar"
                width="128px"
                height="128px"
                customClass="profile-user-img img-fluid img-circle"
              />
            </div>

            <h3 class="profile-username text-center">{{ $name }}</h3>

            <p class="text-muted text-center text-primary">{{ $roleName }}</p>

            {{-- Address --}}
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
            <p class="text-muted">{!! nl2br($address) ?: 'Alamat belum diatur' !!}</p>
            <hr>

            {{-- Email --}}
            <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
            <p class="text-muted">{{ $email }}</p>
            <hr>

            {{-- Email --}}
            <strong><i class="fas fa-phone mr-1"></i> No Handphone</strong>
            <p class="text-muted">{{ Str::replaceFirst('0', '+62', $phone) }}</p>
          </div>
          {{-- /.card-body --}}
        </div>
      </div>
      {{-- Right Content --}}
      <div class="col-md-8">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Pengaturan</a></li>
              <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password</a></li>
            </ul>
          </div>
          {{-- /.card-header --}}
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="settings">
                <form class="form-profile" wire:submit.prevent="updateProfile">
                  <x-form.input-horizontal
                    name="name"
                    label="Nama"
                    placeholder="Masukan Nama Anda"
                    wire:model.defer="name"
                  />

                  <x-form.input-horizontal
                    name="email"
                    label="Email"
                    placeholder="Masukan Email Anda"
                    wire:model.defer="email"
                  />

                  <x-form.input-horizontal
                    name="phone"
                    label="No Handphone"
                    placeholder="Masukan No Handphone Anda"
                    wire:model.defer="phone"
                    customClass="number-only"
                    maxlength="16"
                  />

                  <x-form.textarea-horizontal
                    name="address"
                    label="Alamat"
                    placeholder="Masukan Alamat Anda"
                    wire:model.defer="address"
                  />

                  <x-form.image-horizontal
                    name="avatar"
                    label="Foto Profil"
                    :preview="$avatar"
                    path="{{ $avatar ?: 'images/no-image.svg' }}"
                    wire:model.defer="avatar"
                  />

                  <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                      <x-button.danger type="submit" wire:loading.attr="disabled" wire:target="avatar, updateProfile">
                        Submit
                      </x-button.danger>
                    </div>
                  </div>
                </form>
              </div>
              {{-- /.settings --}}

              <div class="tab-pane" id="password">
                <div class="form-group row">
                  <div class="offset-sm-3 col-sm-9">
                    <div class="icheck-primary">
                      <input type="checkbox" id="show-pwd">
                      <label for="show-pwd">Lihat Password</label>
                    </div>
                  </div>
                </div>
              </div>
              {{-- /.password --}}
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        {{-- /.card --}}
      </div>
    </div>
  </div>
</section>
