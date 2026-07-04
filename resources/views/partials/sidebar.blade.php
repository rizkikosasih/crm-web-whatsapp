<aside
  x-show="sidebarOpen"
  x-transition:enter="transition ease-out duration-300 transform"
  x-transition:enter-start="-translate-x-full"
  x-transition:enter-end="translate-x-0"
  x-transition:leave="transition ease-in duration-300 transform"
  x-transition:leave-start="translate-x-0"
  x-transition:leave-end="-translate-x-full"
  class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 text-slate-300 flex flex-col md:static md:translate-x-0 transition-transform duration-300"
  :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
  style="display: none"
  x-cloak
  @click.away="if (window.innerWidth < 768) sidebarOpen = false;">
  <!-- Brand Header -->
  <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-800">
    <div
      class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
      <i class="fas fa-comments text-sm"></i>
    </div>
    <span
      class="font-bold text-white tracking-wide text-lg"
      >{{ env('APP_NAME', 'CRM WhatsApp') }}</span
    >
  </div>

  <!-- Scrollable Menu -->
  <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7">
    @foreach ($menus as $menu)
      @php
        // Filter children based on permissions
        $visibleChildren = $menu->children->filter(function ($child) {
          return !$child->permission || auth()->user()->can($child->permission);
        });
      @endphp

      @if ($visibleChildren->count() > 0)
        <div class="space-y-2">
          <!-- Header Menu (Section title) -->
          @if ($menu->name !== '-')
            <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
              {{ $menu->name }}
            </h3>
          @endif

          <!-- Children Menu Items -->
          <ul class="space-y-1">
            @foreach ($visibleChildren as $child)
              @php
                $isActive =
                  request()->is(ltrim($child->route, '/')) || request()->is(ltrim($child->route, '/') . '/*');
              @endphp
              <li>
                <a
                  href="{{ url($child->route) }}"
                  class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 group {{ $isActive ? 'bg-indigo-600/10 text-indigo-400 border-l-4 border-indigo-600' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                  <span class="w-5 flex items-center justify-center shrink-0">
                    <i
                      class="{{ $child->icon }} text-base {{ $isActive ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                  </span>
                  <span>{{ $child->name }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endif
    @endforeach
  </div>

  <!-- User Info Box Footer -->
  <div class="p-4 border-t border-slate-800 bg-slate-950/40 flex items-center gap-3">
    <div
      class="w-10 h-10 rounded-xl bg-indigo-600/20 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0">
      {{
        strtoupper(
          substr(auth()->user()->name, 0, 2),
        )
      }}
    </div>
    <div class="min-w-0 flex-1">
      <p class="text-sm font-semibold text-white truncate leading-snug">{{ auth()->user()->name }}</p>
      <p class="text-xs text-slate-500 truncate leading-snug">{{
        auth()->user()->roles->first()?->name ??
          'User'
      }}</p>
    </div>
  </div>
</aside>
