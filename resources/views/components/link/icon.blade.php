@props([
  'url' => 'javascript:;',
  'customClass' => null,
  'color' => 'secondary'
])

<a {{ $attributes->merge(['href' => $url, 'wire:navigate' => $url != 'javascript:;'])->class([
  'icon',
  "text-$color" => filled($color),
  $customClass
]) }}>
  {{ $slot }}
</a>
