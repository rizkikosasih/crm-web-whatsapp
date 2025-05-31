@props([
  'path' => 'images/no-image.svg',
  'imageTitle' => 'Perbesar',
  'width' => '30px',
  'height' => 'auto',
  'customClass' => null,
])

<a
  href="{{ imageUri($path) }}"
  data-toggle="lightbox"
  class="tooltips"
  title="{{ $imageTitle }}"
  {{ $attributes }}
>
  <img
    width="{{ $width }}"
    height="{{ $height }}"
    src="{{ imageUri($path) }}"
    {{  $attributes->class([
      'class' => 'img-rounded',
      $customClass
    ]) }}
  />
</a>
