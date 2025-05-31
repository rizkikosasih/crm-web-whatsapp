@props([
  'path' => 'images/no-image.svg',
  'imageTitle' => 'Perbesar',
  'width' => '30px',
  'height' => 'auto',
  'customClass' => null,
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
    @class([ 'img-rounded', $customClass ])
  />
</a>
