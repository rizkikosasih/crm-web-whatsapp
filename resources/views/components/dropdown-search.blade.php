@props([
  'label' => null,
  'id' => null,
  'name' => null,
  'parentClass' => null,
  'onSelect' => null,
  'placeholder' => 'Cari ...',
  'searchModel' => null,
  'selectedNameModel' => null,
  'selectedName' => null,
  'searching' => null,
  'items' => [],
  'views' => [],
  'horizontal' => false,
  'value' => null,
])

<div @class(['form-group position-relative', 'row' => $horizontal, $parentClass ?? ''])>
  <label @class(['fw-bold', 'col-sm-3' => $horizontal])>{!! $label ?? 'Cari' !!}</label>

  @if($horizontal)
    <div class="col-sm-9 position-relative">
  @endif

    <input
      type="text"
      @isset($id) id="{{ $id }}" @endisset
      name="{{ $name }}"
      placeholder="{{ $placeholder }}"
      {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
      wire:model.live.debounce.250ms="{{ $searchModel }}"
      wire:focus="$set('{{ $selectedNameModel }}', '')"
      value="{{ $selectedName }}"
      autocomplete="off"
    >

    @if (!empty($searching) && empty($selectedName))
      <ul class="list-group position-absolute w-100 shadow" style="z-index: 10;">
        @forelse($items as $item)
          <li
            class="list-group-item list-group-item-action"
            wire:click="{{ $onSelect }}({{ $item->id }}, '{{ addslashes($item->name) }}')"
          >
            @if($views)
              @foreach ($views as $view)
                {!! $item->$view !!}
              @endforeach
            @else
              {{ $item->name }}
            @endif
          </li>
        @empty
          <li class="list-group-item">Data tidak ditemukan</li>
        @endforelse
      </ul>
    @endif

  @if($horizontal)
    </div>
  @endif

  @error($name)
    <x-alert.text-danger class="mt-2">{{ $message }}</x-alert.text-danger>
  @enderror
</div>

