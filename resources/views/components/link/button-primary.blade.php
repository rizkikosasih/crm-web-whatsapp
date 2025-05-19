<a
  href="{{ isset($url) ? url($url) : 'javascript:;' }}"
  class="btn btn-primary {{ $customClass ?? '' }}"
  @isset($url) wire:navigate @endisset
  {{ $attributes }}
>
  {{ $slot }}
</a>
