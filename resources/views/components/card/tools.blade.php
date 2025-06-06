@props([
  'refresh' => null,
  'minus' => null,
  'url' => null,
  'urlIcon' => 'fas fa-backward-step',
  'urlTitle' => 'Kembali',
])

<div class="card-tools">
  @if ($url)
    <x-link.button href="{{ $url }}" color="tool" class="tooltips" title="{{ $urlTitle}}" wire:navigate>
      <i class="{{ $urlIcon }}"></i>
    </x-link.button>
  @endif

  @if ($refresh)
    <x-button color="tool" class="tooltips" title="Refresh" x-on:click="$dispatch('refresh-with-tooltips')">
      <i class="fas fa-refresh"></i>
    </x-button>
  @endif

  @if ($minus)
    <x-button color="tool" data-card-widget="collapse">
      <i class="fas fa-minus"></i>
    </x-button>
  @endif
</div>
