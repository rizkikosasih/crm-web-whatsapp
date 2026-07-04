@props ([
  'parentClass' => null,
  'label' => null,
  'name' => null,
  'type' => 'text',
  'horizontal' => false,
])

<div
  @class ([
    'mb-4',
    'grid grid-cols-1 md:grid-cols-4 gap-4 items-center' => $horizontal,
    $parentClass
  ])>
  @isset ($label)
    <label
      for="{{ $name }}"
      class="block text-sm font-semibold text-slate-300 @if($horizontal) md:col-span-1 @else mb-2 @endif"
      >{!! $label !!}</label
    >
  @endisset

  @if ($horizontal)
    <div class="md:col-span-3">
  @endif

  <input
    type="{{ $type ?? 'text' }}"
    id="{{ $name }}"
    {{
      $attributes->class([
        'block w-full rounded-xl border border-slate-700 bg-slate-900/50 text-white placeholder-slate-500 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 sm:text-sm py-2.5 px-3 focus:outline-none transition duration-150',
        'border-red-500 focus:border-red-500 focus:ring-red-500/20' => $errors->has($name),
      ])
    }} />

  @error ($name)
    <x-alert.text-danger class="mt-1.5">{{ $message }}</x-alert.text-danger>
  @enderror

  @if ($horizontal)
</div>
@endif
</div>
