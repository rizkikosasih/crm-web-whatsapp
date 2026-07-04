@props ([
  'title' => null,
  'tools' => null,
  'footer' => null,
  'parentClass' => null,
])

<div
  @class ([
    'card-container bg-slate-800/40 border border-slate-700/50 backdrop-blur-md rounded-2xl overflow-hidden shadow-xl flex flex-col',
    $parentClass
  ])>
  <!-- Card Header -->
  @if ($title || $tools)
    <div class="px-6 py-4 border-b border-slate-700/50 flex items-center justify-between gap-4">
      <h3 class="text-base font-bold text-white leading-snug">{!! $title !!}</h3>
      @if ($tools)
        <div class="shrink-0">{{ $tools }}</div>
      @endif
    </div>
  @endif

  <!-- Card Body -->
  <div class="card-body-collapse flex-1 px-6 py-4">{{ $slot }}</div>

  <!-- Card Footer -->
  @if ($footer)
    <div class="px-6 py-4 border-t border-slate-700/50 bg-slate-900/20 text-sm text-slate-400">
      {{ $footer }}
    </div>
  @endif
</div>
