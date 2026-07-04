@props ([
  'title' => null,
  'tools' => null,
  'footer' => null,
  'parentClass' => null,
])

<div
  @class ([
    'card-container bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 backdrop-blur-md rounded-2xl overflow-hidden shadow-sm dark:shadow-xl flex flex-col transition-all duration-150',
    $parentClass
  ])>
  <!-- Card Header -->
  @if ($title || $tools)
    <div
      class="px-6 py-4 border-b border-slate-200 dark:border-slate-700/50 flex items-center justify-between gap-4 transition-colors duration-150">
      <h3 class="text-base font-bold text-slate-900 dark:text-white leading-snug">
        {!! $title !!}
      </h3>
      @if ($tools)
        <div class="shrink-0">{{ $tools }}</div>
      @endif
    </div>
  @endif

  <!-- Card Body -->
  <div class="card-body-collapse flex-1 px-6 py-4">{{ $slot }}</div>

  <!-- Card Footer -->
  @if ($footer)
    <div
      class="px-6 py-4 border-t border-slate-200 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/20 text-sm text-slate-500 dark:text-slate-400 transition-colors duration-150">
      {{ $footer }}
    </div>
  @endif
</div>
