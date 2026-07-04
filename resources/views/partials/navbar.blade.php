<header
  class="bg-slate-800 border-b border-slate-700/50 h-16 flex items-center justify-between px-6 shrink-0 relative z-30">
  <!-- Left Side: Toggle Sidebar Button -->
  <div class="flex items-center">
    <button
      @click="sidebarOpen = !sidebarOpen"
      class="text-slate-400 hover:text-white p-2 rounded-lg hover:bg-slate-700/50 focus:outline-none cursor-pointer transition duration-150">
      <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
      </svg>
    </button>
  </div>

  <!-- Right Side: User Profile Dropdown -->
  <div class="flex items-center gap-4">
    <div x-data="{ open: false }" class="relative">
      <button
        @click="open = !open"
        @click.away="open = false"
        class="flex items-center gap-3 text-slate-300 hover:text-white focus:outline-none cursor-pointer group py-1.5 px-3 rounded-xl hover:bg-slate-700/30 transition duration-150">
        <!-- Profile Avatar / Image -->
        @if ($user->avatar)
          <img
            src="{!! imageUri($user->avatar) !!}"
            class="w-8 h-8 rounded-lg object-cover ring-2 ring-indigo-500/20 group-hover:ring-indigo-500/50 transition duration-150" />
        @else
          <div
            class="w-8 h-8 rounded-lg bg-indigo-600/20 text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-bold text-xs">
            {{
              strtoupper(
                substr($user->name, 0, 2),
              )
            }}
          </div>
        @endif
        <span class="text-sm font-medium hidden sm:inline-block">{{ $user->name }}</span>
        <!-- Chevron -->
        <svg class="h-4 w-4 text-slate-500 group-hover:text-slate-300 transition duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Dropdown Panel -->
      <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700/80 rounded-xl shadow-xl py-1.5 z-50 text-slate-300"
        style="display: none">
        <a
          href="{{ url('setting/user/profile') }}"
          class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-slate-700/40 hover:text-white transition duration-150">
          <i class="fas fa-user text-slate-500 w-4 text-center"></i>
          <span>Profil Saya</span>
        </a>

        <div class="h-px bg-slate-700/50 my-1.5"></div>

        <a
          href="#"
          class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-red-500/10 hover:text-red-400 transition duration-150 font-medium"
          @click.prevent="document.getElementById('logout-form').submit()">
          <i class="fas fa-sign-out-alt text-red-400/70 w-4 text-center"></i>
          <span>Keluar</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
        </form>
      </div>
    </div>
  </div>
</header>
