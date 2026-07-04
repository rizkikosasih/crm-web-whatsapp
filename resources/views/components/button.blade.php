@props ([
  'type' => 'button',
  'color' => 'default',
  'size' => 'md',
  'loadingText' => null,
])

@php
  $colorClasses = match ($color) {
    'primary'
      => 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
    'success'
      => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600',
    'danger'
      => 'bg-red-600 hover:bg-red-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600',
    'warning'
      => 'bg-amber-500 hover:bg-amber-400 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500',
    'secondary',
    'default'
      => 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700',
    default => 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700',
  };

  $sizeClasses = match ($size) {
    'xs' => 'px-2 py-1 text-xs rounded-lg',
    'sm' => 'px-2.5 py-1.5 text-xs rounded-xl',
    'md' => 'px-3.5 py-2.5 text-sm rounded-xl',
    'lg' => 'px-4 py-3 text-base rounded-2xl',
    default => 'px-3.5 py-2.5 text-sm rounded-xl',
  };

  $spinnerSize = match ($size) {
    'xs', 'sm' => 'h-3 w-3',
    'lg' => 'h-5 w-5',
    default => 'h-4 w-4',
  };

  $wireTarget = $attributes->get('wire:click', $attributes->get('wire:target', ''));
@endphp

<button
  type="{{ $type }}"
  {{
    $attributes->class([
      'inline-flex items-center justify-center gap-2 font-semibold transition duration-150 focus:outline-none cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed',
      $colorClasses,
      $sizeClasses,
    ])
  }}>
  @if ($loadingText)
    {{-- When loadingText prop is provided: show/hide slot vs spinner+text --}}
    <span
      wire:loading.remove
      wire:target="{{ $wireTarget }}"
      class="inline-flex items-center gap-1.5">
      {{ $slot }}
    </span>
    <span
      wire:loading.inline-flex
      wire:target="{{ $wireTarget }}"
      class="inline-flex items-center gap-1.5">
      <svg class="animate-spin shrink-0 {{ $spinnerSize }}" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      {{ $loadingText }}
    </span>
  @else
    {{-- Default: just render the slot content as-is --}}
    {{ $slot }}
  @endif
</button>
