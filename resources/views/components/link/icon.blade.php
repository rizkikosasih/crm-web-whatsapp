@props([
  'url' => 'javascript:;',
  'customClass' => null,
  'color' => 'secondary'
])

<a
  href="{{ $url }}"
  @if($url != 'javascript:;') wire:navigate @endif
  {{ $attributes->class([
    'icon',
    "text-$color" => filled($color),
    $customClass
  ]) }}
>
  {{ $slot }}
</a>
