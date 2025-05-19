@props([
  'customClass' => '',
  'dismissible' => false,
])

<div @class(['alert alert-success', 'alert-dismissible' => $dismissible, $customClass])>
  @if ($dismissible)
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  @endif
  {{ $slot }}
</div>
