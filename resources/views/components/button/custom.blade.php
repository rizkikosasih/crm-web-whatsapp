@props([
  'type' => 'button',
  'customClass' => null,
  'color' => 'default',
  'size' => null
])

<button
  type="{{ $type }}"
  {{ $attributes->class([
    'btn',
    "btn-$color" => filled($color),
    "btn-$size" => filled($size),
    $customClass
  ]) }}
>
  {{ $slot }}
</button>
