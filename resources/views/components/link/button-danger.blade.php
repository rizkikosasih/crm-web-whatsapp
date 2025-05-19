<a
  href="{{ isset($url) ? url($url) : 'javascript:;' }}"
  class="btn btn-danger {{ $customClass ?? '' }}"
  @isset($url) wire:navigate @endisset
  {{ $attributes }}
>
  {{ $slot }}
</a>
