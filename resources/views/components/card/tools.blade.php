@props([
  'refresh' => null,
  'minus' => null,
])

<div class="card-tools">
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
