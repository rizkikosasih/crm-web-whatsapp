@props ([
  'refresh' => null,
  'minus' => null,
  'url' => null,
  'urlIcon' => 'fas fa-backward-step',
  'urlTitle' => 'Kembali',
])

<div class="flex items-center gap-1.5 shrink-0">
  @if ($url)
    <x-link.button
      url="{{ $url }}"
      color="secondary"
      size="xs"
      title="{{ $urlTitle }}"
      class="tooltip cursor-pointer"
      wire:navigate>
      <i class="{{ $urlIcon }} text-xs"></i>
    </x-link.button>
  @endif

  @if ($refresh)
    <x-button
      color="secondary"
      size="xs"
      title="Refresh"
      class="tooltip cursor-pointer"
      x-on:click="$wire.$refresh()">
      <i class="fas fa-arrows-rotate text-xs"></i>
    </x-button>
  @endif

  @if ($minus)
    <x-button
      color="secondary"
      size="xs"
      class="tooltip cursor-pointer"
      x-data
      x-on:click="
        $el
          .closest('.card-container')
          .querySelector('.card-body-collapse')
          .classList.toggle('hidden')
      ">
      <i class="fas fa-minus text-xs"></i>
    </x-button>
  @endif
</div>
