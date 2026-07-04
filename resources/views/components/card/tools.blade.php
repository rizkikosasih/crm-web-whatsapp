@props ([
  'refresh' => null,
  'minus' => null,
  'url' => null,
  'urlIcon' => 'arrow-left',
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
      <x-icon
        name="{{ str_starts_with($urlIcon, 'fa') ? 'arrow-left' : $urlIcon }}"
        class="w-3.5 h-3.5" />
    </x-link.button>
  @endif

  @if ($refresh)
    <x-button
      color="secondary"
      size="xs"
      title="Refresh"
      class="tooltip cursor-pointer"
      x-on:click="$wire.$refresh()">
      <x-icon name="refresh-cw" class="w-3.5 h-3.5" />
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
      <x-icon name="minus" class="w-3.5 h-3.5" />
    </x-button>
  @endif
</div>
