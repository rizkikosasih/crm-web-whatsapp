@props([
  'path' => 'images/no-image.svg',
  'imageTitle' => 'Perbesar',
  'width' => '30px',
  'height' => 'auto'
])

@php
  $path = $path ?: 'images/no-image.svg';
@endphp

<a
  href="{{ imageUri($path) }}"
  data-toggle="lightbox"
  class="tooltips"
  title="{{ $imageTitle }}"
>
  <img
    width="{{ $width }}"
    height="{{ $height }}"
    src="{{ imageUri($path) }}"
    {{ $attributes->class(['img-rounded']) }}
  />
</a>
