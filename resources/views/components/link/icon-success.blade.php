<a
  href="{{ isset($url) ? url($url) : 'javascript:;' }}"
  class="icon text-success {{ $customClass ?? '' }}"
  @isset($url) wire:navigate @endisset
  {{ $attributes }}
>
  {{ $slot }}
</a>
