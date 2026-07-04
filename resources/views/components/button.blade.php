@props ([
  'type' => 'button',
  'color' => 'default',
  'size' => 'md',
])

@php
  $colorClasses = match ($color) {
    'primary'
      => 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
    'success'
      => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600',
    'danger'
      => 'bg-red-600 hover:bg-red-500 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600',
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
@endphp

<button
  type="{{ $type }}"
  {{
    $attributes->class([
      'inline-flex items-center justify-center font-semibold transition duration-150 focus:outline-none cursor-pointer',
      $colorClasses,
      $sizeClasses,
    ])
  }}>
  {{ $slot }}
</button>
