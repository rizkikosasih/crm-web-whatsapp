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
>
  <img
    width="{{ $width }}"
    height="{{ $height }}"
    src="{{ imageUri($path) }}"
    {{  $attributes->merge([
      'class' => 'img-rounded',
      $customClass
    ]) }}
  />
</a>
