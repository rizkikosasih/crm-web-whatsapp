@props([
  'url' => 'javascript:;',
  'customClass' => null,
  'color' => 'default',
  'size' => null,
])

<a
  href="{{ $url }}"
  @if($url != 'javascript:;') wire:navigate @endif
  {{ $attributes->class([
    'btn',
    "btn-$color" => filled($color),
    "btn-$size" => filled($size),
    $customClass
  ]) }}
>
  {{ $slot }}
</a>
