<div class="space-y-8">
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
        Dashboard Utama
      </h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ringkasan data pesanan dari <strong class="text-slate-700 dark:text-slate-300">{{ dateIndo(now()->subYear()) }}</strong> sampai <strong class="text-slate-700 dark:text-slate-300">{{ dateIndo(now()) }}</strong>.</p>
    </div>
  </div>

  <!-- Stats Grid (4 Boxes) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach ($orderByStatus as $item)
      @php
        $glowColor = match ($item['color']) {
          'danger' => 'group-hover:border-red-500/50 group-hover:shadow-red-500/5',
          'warning' => 'group-hover:border-amber-500/50 group-hover:shadow-amber-500/5',
          'primary' => 'group-hover:border-indigo-500/50 group-hover:shadow-indigo-500/5',
          'success' => 'group-hover:border-emerald-500/50 group-hover:shadow-emerald-500/5',
          default => 'group-hover:border-indigo-500/50 group-hover:shadow-indigo-500/5',
        };

        $badgeColor = match ($item['color']) {
          'danger' => 'bg-red-500/10 text-red-500 dark:text-red-400 border border-red-500/20',
          'warning' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20',
          'primary' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20',
          'success'
            => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
          default => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20',
        };
      @endphp
      <a
        href="{{ $item['url'] }}"
        wire:navigate
        class="group flex flex-col justify-between bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-800/60 shadow-sm hover:shadow-md rounded-2xl p-6 transition duration-200 cursor-pointer {{ $glowColor }}">
        <div class="flex items-start justify-between gap-4">
          <div>
            <span
              class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight"
              >{{ $item['count'] }}</span
            >
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-2">
              {{ $item['title'] }}
            </h3>
          </div>
          <div
            class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $badgeColor }}">
            <x-icon name="{{ $item['icon'] }}" class="w-5 h-5" />
          </div>
        </div>
        <div
          class="mt-6 flex items-center gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 group-hover:text-indigo-500 dark:group-hover:text-indigo-300 transition duration-150">
          <span>Info Selengkapnya</span>
          <x-icon
            name="arrow-right"
            class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition duration-150" />
        </div>
      </a>
    @endforeach
  </div>

  <!-- Charts Grid (2 columns) -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach ($charts as $chart)
      @if ($chart['show'])
        <x-card title="{{ $chart['title'] }}" class="h-full">
          <x-slot:tools>
            <x-card.tools url="{{ url('dashboard') }}" urlTitle="Refresh" urlIcon="refresh-cw" />
          </x-slot:tools>
          <div class="p-2 flex items-center justify-center min-h-[300px]">
            <canvas id="{{ $chart['id'] }}" wire:ignore class="max-h-[350px] w-full"></canvas>
          </div>
        </x-card>
      @endif
    @endforeach
  </div>

  <!-- Table Container (Histori Pesan Keluar) -->
  <div class="w-full">
    <x-card title="Histori Pesan Keluar">
      <x-slot:tools>
        <x-card.tools refresh="true" />
      </x-slot:tools>

      <!-- Table Filters -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="w-24 shrink-0">
          <x-form.input-select
            name="perPage"
            wire:model.live="perPage"
            :options="[5 => 5, 10 => 10, 20 => 20, 50 => 50]"
            parentClass="mb-0" />
        </div>
        <div class="w-full sm:w-64 shrink-0">
          <x-form.input
            name="search"
            placeholder="Cari Pesan..."
            wire:model.live.debounce.250ms="search"
            parentClass="mb-0" />
        </div>
      </div>

      <!-- Livewire Loading Overlay -->
      <x-overlay target="search, perPage, gotoPage, nextPage">
        <!-- Table Responsive -->
        <div
          class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50 dark:bg-slate-900/10">
          <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700/50">
            <x-table.header :columns="$messageHeader" />
            <tbody
              class="divide-y divide-slate-200 dark:divide-slate-800 bg-transparent text-slate-700 dark:text-slate-300">
              @forelse ($messages as $index => $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition duration-150">
                  <td
                    class="px-6 py-4 text-center text-sm font-medium text-slate-500 whitespace-nowrap">
                    {{ $index + $messages->firstItem() }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                    {{ $item->customer->name }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                    {{ $item->user->name }}
                  </td>
                  <td
                    class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate md:max-w-md whitespace-normal leading-relaxed">
                    {!! nl2br(e($item->message)) !!}
                  </td>
                  <td class="px-6 py-4 text-center whitespace-nowrap">
                    @if ($item->image)
                      <x-preview-image path="{{ $item->image }}" width="40px" />
                    @else
                      <span class="text-slate-400">-</span>
                    @endif
                  </td>
                  <td
                    class="px-6 py-4 text-center whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                    <div class="font-medium text-slate-700 dark:text-slate-300">
                      {{ dateIndo($item->sent_at) }}
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                      {{ timeIndo($item->sent_at) }}
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td
                    colspan="{{ sizeof($messageHeader) }}"
                    class="px-6 py-10 text-center text-sm font-medium text-slate-500 bg-slate-50 dark:bg-slate-800/10">
                    Data Kosong
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </x-overlay>

      <!-- Pagination Footer -->
      <div class="mt-6">{{ $messages->links() }}</div>
    </x-card>
  </div>
</div>

@section ('page-script')
  <script>
    window.renderCharts = function (chartConfigs) {
      window.renderedCharts = window.renderedCharts || {};

      // Set dynamic Chart.js colors based on theme
      const isDark = document.documentElement.classList.contains('dark');
      Chart.defaults.color = isDark ? '#94a3b8' : '#475569';
      Chart.defaults.borderColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(71, 85, 105, 0.1)';

      chartConfigs.forEach((chart) => {
        const canvas = document.getElementById(chart.id);
        if (chart.show && canvas) {
          const ctx = canvas.getContext('2d');

          if (window.renderedCharts[chart.id]) {
            window.renderedCharts[chart.id].destroy();
          }

          window.renderedCharts[chart.id] = new Chart(ctx, chart.config);
        }
      });
    };

    function renderChartsSafe() {
      if (typeof window.renderCharts === 'function' && typeof window.Chart === 'function') {
        window.renderCharts(@json ($charts));
      } else {
        setTimeout(renderChartsSafe, 100);
      }
    }

    document.addEventListener('livewire:navigated', renderChartsSafe);
    Livewire.on('refreshChart', renderChartsSafe);
    window.addEventListener('theme-changed', renderChartsSafe);

    // Initial run
    setTimeout(renderChartsSafe, 150);
  </script>
@endsection
