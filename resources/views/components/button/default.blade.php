<button
  type="{{ $type ?? 'button' }}"
  class="btn {{ $customClass ?? '' }}"
  {{ $attributes }}
>
  {{ $slot }}
</button>
