@props([
  'refresh' => null,
  'minus' => null,
  'url' => null,
])

<div class="card-tools">
  @if ($url)
    <a href="{{ $url }}" class="btn btn-tool tooltips" title="Kembali" title wire:navigate>
      <i class="fas fa-backward-step"></i>
    </a>
  @endif

  @if ($refresh)
    <button type="button" class="btn btn-tool" wire:click="$refresh">
      <i class="fas fa-refresh"></i>
    </button>
  @endif

  @if ($minus)
    <button type="button" class="btn btn-tool" data-card-widget="collapse">
      <i class="fas fa-minus"></i>
    </button>
  @endif
</div>
