@section ('title', $title)

<div class="space-y-8">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Perbarui detail profil akun Anda, unggah foto profil, atau ubah kata sandi.</p>
  </div>

  <div
    class="grid grid-cols-1 lg:grid-cols-12 gap-8"
    x-data="{ activeTab: '{{ $isPasswordVisible ? 'password' : 'settings' }}' }">
    <!-- Left Panel: Profile Summary Card -->
    <div class="lg:col-span-4 space-y-6">
      <x-card title="Ringkasan Profil">
        <div class="flex flex-col items-center text-center pb-6">
          <div
            class="w-32 h-32 rounded-full overflow-hidden border-2 border-indigo-500 shadow-lg relative flex items-center justify-center mb-4 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
            @if ($originalAvatar)
              <img
                src="{{ imageUri($originalAvatar) }}"
                class="w-full h-full object-cover"
                alt="Avatar User" />
            @else
              <span class="text-3xl font-extrabold tracking-wider">
                {{ strtoupper(substr($name, 0, 2)) }}
              </span>
            @endif
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
            {{ $name }}
          </h3>
          <span
            class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border border-indigo-500/20 mt-1.5">
            {{ $roleName }}
          </span>
        </div>

        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-4"></div>

        <div class="space-y-4 text-sm">
          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
              ><i class="fas fa-map-marker-alt mr-1"></i> Alamat</span
            >
            <span class="text-slate-700 dark:text-slate-300">{!!
              nl2br(e($address)) ?:
                'Alamat belum diatur'
            !!}</span>
          </div>

          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
              ><i class="fas fa-envelope mr-1"></i> Email</span
            >
            <span class="text-slate-700 dark:text-slate-300">{{ $email }}</span>
          </div>

          <div>
            <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
              ><i class="fas fa-phone mr-1"></i> No Handphone</span
            >
            <span class="text-slate-700 dark:text-slate-300">
              {{
                Str::replaceFirst(
                  '0',
                  '+62',
                  $phone,
                )
              }}
            </span>
          </div>
        </div>
      </x-card>
    </div>

    <!-- Right Panel: Tabs Settings / Password -->
    <div class="lg:col-span-8 space-y-6">
      <div class="border-b border-slate-200 dark:border-slate-700/80">
        <nav class="flex space-x-6" aria-label="Tabs">
          <button
            type="button"
            @click="activeTab = 'settings'"
            :class="activeTab === 'settings'
              ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
              : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600'"
            class="py-4 px-1 text-sm font-semibold border-b-2 tracking-wide cursor-pointer transition duration-150">
            Pengaturan Profil
          </button>
          <button
            type="button"
            @click="activeTab = 'password'"
            :class="activeTab === 'password'
              ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
              : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600'"
            class="py-4 px-1 text-sm font-semibold border-b-2 tracking-wide cursor-pointer transition duration-150">
            Ubah Kata Sandi
          </button>
        </nav>
      </div>

      <!-- Settings Panel -->
      <div x-show="activeTab === 'settings'" class="space-y-6" x-transition>
        <x-card title="Perbarui Informasi Profil">
          <form wire:submit.prevent="updateProfile" class="space-y-6">
            <x-form.input
              name="name"
              label="Nama Lengkap"
              placeholder="Masukkan Nama Anda"
              wire:model="name"
              horizontal="true" />

            <x-form.input
              name="email"
              label="Alamat Email"
              placeholder="Masukkan Email Anda"
              wire:model="email"
              horizontal="true" />

            <x-form.input
              name="phone"
              label="No Handphone"
              placeholder="Masukkan No Handphone Anda"
              wire:model="phone"
              horizontal="true" />

            <x-form.textarea
              name="address"
              label="Alamat Rumah"
              placeholder="Masukkan Alamat Anda"
              wire:model="address"
              horizontal="true" />

            <x-form.image
              name="avatar"
              label="Unggah Foto Profil"
              wire:model="avatar"
              horizontal="true">
              @php
                $imageUri = isLivewireTemporaryFile($avatar)
                  ? $avatar->temporaryUrl()
                  : ($avatar
                    ? imageUri($avatar)
                    : imageUri('images/no-image.svg'));
              @endphp
              <x-preview-image path="{{ $imageUri }}" width="100px" />
            </x-form.image>

            <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

            <div class="flex justify-end">
              <x-button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="avatar, updateProfile"
                color="primary"
                size="sm"
                class="cursor-pointer font-bold px-6 py-2.5 rounded-xl text-sm">
                <i class="fas fa-save mr-1.5 text-xs"></i> Simpan Profil
              </x-button>
            </div>
          </form>
        </x-card>
      </div>

      <!-- Password Panel -->
      <div x-show="activeTab === 'password'" class="space-y-6" x-transition x-cloak>
        <x-card title="Ganti Kata Sandi">
          <form
            wire:submit.prevent="updatePassword"
            class="space-y-6"
            x-data="{ showPasswords: false }">
            <x-form.input
              name="password"
              label="Password Saat Ini"
              ::type="showPasswords ? 'text' : 'password'"
              placeholder="Masukkan Password Saat Ini"
              wire:model="password"
              horizontal="true" />

            <x-form.input
              name="passwordNew"
              label="Password Baru"
              ::type="showPasswords ? 'text' : 'password'"
              placeholder="Masukkan Password Baru"
              wire:model="passwordNew"
              horizontal="true" />

            <x-form.input
              name="passwordConfirm"
              label="Konfirmasi Password Baru"
              ::type="showPasswords ? 'text' : 'password'"
              placeholder="Konfirmasi Password Baru"
              wire:model="passwordConfirm"
              horizontal="true" />

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
              <div class="md:col-span-3"></div>
              <div class="md:col-span-9 flex items-center gap-2">
                <input
                  type="checkbox"
                  id="show-pwd"
                  @click="showPasswords = !showPasswords"
                  class="rounded border-slate-300 dark:border-slate-700 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900 bg-white dark:bg-slate-900 w-4 h-4 cursor-pointer" />
                <label
                  for="show-pwd"
                  class="text-sm font-semibold text-slate-600 dark:text-slate-400 cursor-pointer select-none"
                  >Tampilkan Password</label
                >
              </div>
            </div>

            <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-6"></div>

            <div class="flex justify-end">
              <x-button
                type="submit"
                color="primary"
                size="sm"
                class="cursor-pointer font-bold px-6 py-2.5 rounded-xl text-sm">
                <i class="fas fa-key mr-1.5 text-xs animate-bounce"></i> Ubah Password
              </x-button>
            </div>
          </form>
        </x-card>
      </div>
    </div>
  </div>
</div>
