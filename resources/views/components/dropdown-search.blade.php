@props ([
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

<div
  @class ([
    'mb-4 relative',
    'grid grid-cols-1 md:grid-cols-4 gap-4 items-center' => $horizontal,
    $parentClass ?? ''
  ])>
  @isset ($label)
    <label
      class="block text-sm font-semibold text-slate-300 @if($horizontal) md:col-span-1 @else mb-2 @endif"
      >{!! $label !!}</label
    >
  @endisset

  @if ($horizontal)
    <div class="md:col-span-3 relative">
  @endif

  <input
    type="text"
    @isset ($id) id="{{ $id }}" @endisset
    name="{{ $name }}"
    placeholder="{{ $placeholder }}"
    {{
      $attributes->class([
        'block w-full rounded-xl border border-slate-700 bg-slate-900/50 text-white placeholder-slate-500 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm py-2.5 px-3 focus:outline-none transition duration-150',
        'border-red-500 focus:border-red-500 focus:ring-red-500/20' => $errors->has($name),
      ])
    }}
    wire:model.live.debounce.250ms="{{ $searchModel }}"
    wire:focus="$set('{{ $selectedNameModel }}', '')"
    value="{{ $selectedName }}"
    autocomplete="off" />

  @if (!empty($searching) && empty($selectedName))
    <ul
      class="absolute left-0 mt-1 w-full rounded-xl border border-slate-700 bg-slate-800 shadow-2xl overflow-hidden z-40 divide-y divide-slate-700/50 max-h-60 overflow-y-auto">
      @forelse ($items as $item)
        <li
          class="px-4 py-2.5 text-sm text-slate-300 hover:bg-indigo-600 hover:text-white cursor-pointer transition duration-150"
          wire:click="{{ $onSelect }}({{ $item->id }}, '{{ addslashes($item->name) }}')">
          @if ($views)
            <div class="flex items-center gap-2">
              @foreach ($views as $view)
                <span>{!! $item->$view !!}</span>
              @endforeach
            </div>
          @else
            {{ $item->name }}
          @endif
        </li>
      @empty
        <li class="px-4 py-3 text-sm text-slate-500 bg-slate-800">Data tidak ditemukan</li>
      @endforelse
    </ul>
  @endif

  @if ($horizontal)
</div>
@endif

@error ($name)
  <x-alert.text-danger class="mt-1.5">{{ $message }}</x-alert.text-danger>
@enderror
</div>
