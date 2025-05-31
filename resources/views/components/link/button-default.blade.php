@props([
  'url' => 'javascript:;',
  'customClass' => null,
])

<a
  href="{{ $url }}"
  @if($url != 'javascript:;') wire:navigate @endif
  @class(['btn', $customClass])
  {{ $attributes }}
>
  {{ $slot }}
</a>
