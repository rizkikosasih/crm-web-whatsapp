<button
  type="{{ $type ?? 'button' }}"
  class="btn btn-primary {{ $customClass ?? '' }}"
  {{ $attributes }}
>
  {{ $slot }}
</button>
