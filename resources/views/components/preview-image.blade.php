@props([
  'path' => 'images/no-image.svg',
  'imageTitle' => 'Perbesar',
  'width' => '30px',
  'height' => 'auto',
])

<a
  href="{{ imageUri($path ?: 'images/no-image.svg') }}"
  data-toggle="lightbox"
  class="tooltips"
  title="{{ $imageTitle }}"
>
  <img
    class="img-rounded"
    width="{{ $width }}"
    height="{{ $height }}"
    src="{{ imageUri($path ?: 'images/no-image.svg') }}"
  />
</a>
