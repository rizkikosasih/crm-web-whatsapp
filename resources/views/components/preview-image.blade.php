@props ([
  'path' => 'images/no-image.svg',
  'imageTitle' => 'Perbesar Gambar',
  'width' => '30px',
  'height' => 'auto'
])

@php
  $path = $path ?: 'images/no-image.svg';
@endphp

<a
  href="{{ imageUri($path) }}"
  target="_blank"
  class="inline-block transition hover:scale-105 duration-150"
  title="{{ $imageTitle }}">
  <img
    width="{{ $width }}"
    height="{{ $height }}"
    src="{{ imageUri($path) }}"
    {{
      $attributes->class([
        'rounded-lg object-cover border border-slate-700/50 shadow-sm',
      ])
    }} />
</a>
