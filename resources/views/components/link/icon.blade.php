@props ([
  'url' => '#',
  'color' => 'secondary'
])

@php
  $colorClasses = match ($color) {
    'primary' => 'text-indigo-400 hover:text-indigo-300',
    'success' => 'text-emerald-400 hover:text-emerald-300',
    'danger' => 'text-red-400 hover:text-red-300',
    'secondary', 'default' => 'text-slate-400 hover:text-slate-300',
    default => 'text-slate-400 hover:text-slate-300',
  };

  $isAnchor = $url !== '#';
@endphp

<a
  {{
    $attributes
      ->merge(['href' => $url, 'wire:navigate' => $isAnchor])
      ->class([
        'inline-flex items-center justify-center transition duration-150 cursor-pointer',
        $colorClasses,
      ])
  }}>
  {{ $slot }}
</a>
