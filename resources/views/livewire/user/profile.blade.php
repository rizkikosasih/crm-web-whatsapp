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
                class="profile-user-img img-fluid img-circle"
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
        </div>
      </div>
      {{-- Right Content --}}
      <div class="col-md-8">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a @class(["nav-link", 'active' => !$isPasswordVisible ]) href="#settings" data-toggle="tab">Pengaturan</a></li>
              <li class="nav-item"><a @class(["nav-link", 'active' => $isPasswordVisible ]) href="#password" data-toggle="tab">Password</a></li>
            </ul>
          </div>

          <div class="card-body">
            <div class="tab-content">
              <div @class(["tab-pane", 'active' => !$isPasswordVisible ]) id="settings">
                <form class="form-profile" wire:submit.prevent="updateProfile">
                  <x-form.input
                    name="name"
                    label="Nama"
                    placeholder="Masukan Nama Anda"
                    wire:model.defer="name"
                    horizontal="true"
                  />

                  <x-form.input
                    name="email"
                    label="Email"
                    placeholder="Masukan Email Anda"
                    wire:model.defer="email"
                    horizontal="true"
                  />

                  <x-form.input
                    name="phone"
                    label="No Handphone"
                    placeholder="Masukan No Handphone Anda"
                    wire:model.defer="phone"
                    class="number-only"
                    horizontal="true"
                  />

                  <x-form.textarea
                    name="address"
                    label="Alamat"
                    placeholder="Masukan Alamat Anda"
                    wire:model.defer="address"
                    horizontal="true"
                  />

                  <x-form.image
                    name="avatar"
                    label="Foto Profil"
                    :preview="$avatar"
                    path="{{ $avatar ?: 'images/no-image.svg' }}"
                    wire:model.defer="avatar"
                    horizontal="true"
                  >
                    @php
                      $imageUri = isLivewireTemporaryFile($avatar) ? $avatar->temporaryUrl() : imageUri($avatar ?: 'images/no-image.svg');
                    @endphp

                    <a href="{{ $imageUri }}" data-toggle="lightbox" class="tooltips" title="Perbesar">
                      <img
                        src="{{ $imageUri }}"
                        class="img-rounded"
                        width="100"
                        height="auto"
                      >
                    </a>
                  </x-form.image>

                  <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                      <x-button type="submit" wire:loading.attr="disabled" wire:target="avatar, updateProfile" color="danger">
                        Submit
                      </x-button>
                    </div>
                  </div>
                </form>
              </div>

              <div @class(["tab-pane", 'active' => $isPasswordVisible ]) id="password">
                <form wire:submit="updatePassword" class="form-change-password">
                  <x-form.input
                    name="currentPassword"
                    label="Password Saat Ini"
                    type="password"
                    placeholder="Masukan Password Saat Ini"
                    wire:model.defer="password"
                    horizontal="true"
                  />

                  <x-form.input
                    name="newPassword"
                    label="Password Baru"
                    type="password"
                    placeholder="Masukan Password Baru"
                    wire:model.defer="passwordNew"
                    horizontal="true"
                  />

                  <x-form.input
                    name="confirmPassword"
                    label="Konfirmasi Password Baru"
                    type="password"
                    placeholder="Masukan Konfirmasi Password Baru"
                    wire:model.defer="passwordConfirm"
                    horizontal="true"
                  />

                  <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                      <div class="icheck-primary">
                        <input type="checkbox" id="show-pwd">
                        <label for="show-pwd">Lihat Password</label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                      <x-button type="submit" color="danger">
                        Ubah Password
                      </x-button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
