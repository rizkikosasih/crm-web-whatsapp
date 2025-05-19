<button
  type="{{ $type ?? 'button' }}"
  class="btn btn-danger {{ $customClass ?? '' }}"
  {{ $attributes }}
>
  {{ $slot }}
</button>
