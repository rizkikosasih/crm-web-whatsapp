<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
  <!-- Brand / Header -->
  <div class="text-center mb-8">
    <div
      class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 shadow-lg shadow-indigo-500/30 text-white mb-4">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.12 2.9 2.78 3.02 1.37.1 2.68.83 3.6 1.9a.75.75 0 0 0 1.25.08l2.25-3c.23-.3.69-.39 1.01-.2 1.5.88 3.25.9 4.76.06a3.02 3.02 0 0 0 1.6-2.6v-5.2c0-1.66-1.35-3-3-3h-12a3 3 0 0 0-3 3v5.2Z" />
      </svg>
    </div>
    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
      CRM WhatsApp Integration
    </h2>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan login untuk mengelola sistem Anda</p>
  </div>

  <!-- Card Container -->
  <div
    class="bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/50 backdrop-blur-md shadow-xl rounded-2xl overflow-hidden p-6 sm:p-8">
    @if (session()->has('error'))
      <div
        class="mb-5 bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-500/50 rounded-xl p-4 flex items-start gap-3">
        <svg class="h-5 w-5 text-red-500 dark:text-red-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
        </svg>
        <span
          class="text-sm font-medium text-red-700 dark:text-red-200 leading-snug"
          >{{ session('error') }}</span
        >
      </div>
    @endif

    <form wire:submit.prevent="doLogin" class="space-y-6">
      <!-- Username Input -->
      <div>
        <label
          for="username"
          class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2"
          >Username / Email</label
        >
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
          </div>
          <input
            type="text"
            id="username"
            wire:model="username"
            placeholder="Masukkan username atau email"
            class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-900 dark:text-white rounded-xl text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 transition duration-150 outline-none" />
        </div>
        @error ('username')
          <p class="mt-1.5 text-xs font-medium text-red-500 dark:text-red-400">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password Input -->
      <div x-data="{ show: false }">
        <label
          for="password"
          class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2"
          >Password</label
        >
        <div class="relative">
          <div
            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
          </div>
          <input
            :type="show ? 'text' : 'password'"
            id="password"
            wire:model="password"
            placeholder="••••••••"
            class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-900 dark:text-white rounded-xl text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 transition duration-150 outline-none" />
          <button
            type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition duration-150 cursor-pointer">
            <!-- Show Icon -->
            <svg
              x-show="!show"
              class="h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <!-- Hide Icon -->
            <svg
              x-show="show"
              class="h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              style="display: none">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.815 7.815 3 3m-3-3-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
          </button>
        </div>
        @error ('password')
          <p class="mt-1.5 text-xs font-medium text-red-500 dark:text-red-400">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit Button -->
      <div>
        <button
          type="submit"
          wire:loading.attr="disabled"
          class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
          <!-- Default state -->
          <span wire:loading.remove>Masuk</span>
          <!-- Loading state -->
          <span wire:loading.inline-flex class="inline-flex items-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white shrink-0" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          </span>
        </button>
      </div>
    </form>
  </div>
</div>
