<a
  href="{{ isset($url) ? url($url) : 'javascript:;' }}"
  class="icon text-danger {{ $customClass ?? '' }}"
  @isset($url) wire:navigate @endisset
  {{ $attributes }}
>
  {{ $slot }}
</a>
