@props([
  'customClass' => null
])

<div {{ $attributes->class(['d-flex', 'align-items-center', 'gap-3', $customClass]) }} {{ $attribute }}>
  {{ $slot }}
</div>
