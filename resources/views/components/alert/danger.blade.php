@props([
  'dismissible' => false,
])

<div {{ $attributes->class([
  'alert alert-danger',
  'alert-dismissible' => $dismissible,
]) }}>
  @if ($dismissible)
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  @endif

  {{ $slot }}
</div>
