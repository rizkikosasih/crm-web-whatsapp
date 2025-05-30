<div class="position-relative">
  <div
    {{
      $attributes->merge([
        'class' => 'overlay d-none position-absolute top-0 start-0 w-100 h-100',
        'wire:loading.class' => 'd-flex',
        'wire:loading.class.remove' => 'd-none',
      ])
    }}
  >
    <i class="fas fa-sync-alt fa-spin fa-2x"></i>
    <div class="pt-2 fw-bold">Loading...</div>
  </div>
  {{ $slot }}
</div>
