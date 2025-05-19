@props([
  'activeClass' => '',
  'parentClass' => '',
  'icon' => '',
  'name' => '',
  'route' => '',
])

<li @class(['nav-item', $parentClass])>
  <a
    @class(['nav-link', $activeClass])
    href="{{ !$parentClass ? url($route) : 'javascript:void(0);' }}"
    {{ !$parentClass ? 'wire:navigate' : '' }}
  >
    <i class="nav-icon {{ $icon }}"></i>
    <p>
      {{ $name }}
      @if($parentClass)
        <i class="right fas fa-angle-left"></i>
      @endif
    </p>
  </a>
  {{ $slot }}
</li>
