@props([
  'url' => 'javascript:;',
  'color' => 'secondary'
])

<a {{ $attributes->merge(['href' => $url, 'wire:navigate' => $url != 'javascript:;'])->class([
  'icon',
  "text-$color" => filled($color)
]) }}>
  {{ $slot }}
</a>
