@props([
  'refresh' => null,
  'minus' => null,
  'url' => null,
  'urlIcon' => 'fas fa-backward-step',
  'urlTitle' => 'Kembali',
])

<div class="card-tools">
  @if ($url)
    <a href="{{ $url }}" class="btn btn-tool tooltips" title="{{ $urlTitle }}" wire:navigate>
      <i class="{{ $urlIcon }}"></i>
    </a>
  @endif

  @if ($refresh)
    <button type="button" class="btn btn-tool tooltips" title="Refresh" wire:click="$refresh">
      <i class="fas fa-refresh"></i>
    </button>
  @endif

  @if ($minus)
    <button type="button" class="btn btn-tool" data-card-widget="collapse">
      <i class="fas fa-minus"></i>
    </button>
  @endif
</div>
