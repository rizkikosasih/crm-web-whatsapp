@props([
  'target' => 'search, perPage, gotoPage, nextPage', // default target
])

@php
  $attrs = $attributes
    ->class(['overlay d-none position-absolute top-0 start-0 w-100 h-100'])
    ->merge([
      'wire:loading.class' => 'd-flex',
      'wire:loading.class.remove' => 'd-none',
    ]);

  // Tambahkan wire:target default kalau belum ada
  if (!$attributes->has('wire:target')) {
    $attrs = $attrs->merge(['wire:target' => $target]);
  }
@endphp

<div class="position-relative">
  <div {{ $attrs }}>
    <i class="fas fa-sync-alt fa-spin fa-2x"></i>
    <div class="pt-2 fw-bold">Loading...</div>
  </div>

  {{ $slot }}
</div>
