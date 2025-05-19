<div
  @class([
    'd-flex',
    'align-items-center',
    'gap-3',
    $customClass ?? ''
  ])
>
  {{ $slot }}
</div>
