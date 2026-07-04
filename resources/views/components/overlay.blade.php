@props ([
  'target' => 'search, perPage, gotoPage, nextPage' // default target
])

@php
  $attrs = $attributes
    ->class([
      'overlay hidden absolute inset-0 bg-slate-950/60 backdrop-blur-sm z-30 flex-col items-center justify-center rounded-2xl',
    ])
    ->merge([
      'wire:loading.class' => '!flex',
      'wire:loading.class.remove' => 'hidden',
    ]);

  if (!$attributes->has('wire:target')) {
    $attrs = $attrs->merge(['wire:target' => $target]);
  }
@endphp

<div class="relative">
  <!-- Loading Overlay -->
  <div {{ $attrs }}>
    <div
      class="w-10 h-10 rounded-full border-4 border-indigo-500/20 border-t-indigo-500 animate-spin mb-3"></div>
    <div class="text-xs font-semibold text-slate-300">Memuat Data...</div>
  </div>

  {{ $slot }}
</div>
