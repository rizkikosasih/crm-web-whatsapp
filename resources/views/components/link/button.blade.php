@props([
  'url' => 'javascript:;',
  'color' => 'default',
  'size' => null,
])

<a {{ $attributes->merge(['href' => $url, 'wire:navigate' => $url != 'javascript:;'])->class([
  'btn',
  "btn-$color" => filled($color),
  "btn-$size" => filled($size)
]) }}>
  {{ $slot }}
</a>
