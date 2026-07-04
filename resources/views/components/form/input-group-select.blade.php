@props ([
  'name' => null,
  'id' => null,
  'label' => null,
  'parentClass' => null,
  'options' => [],
  'optionHeader' => null,
  'prependText' => null,
  'appendText' => null,
])

<div @class (['mb-4', $parentClass])>
  <div
    class="flex rounded-xl shadow-sm border border-slate-700 bg-slate-900/50 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition duration-150 overflow-hidden">
    @if ($prependText)
      <span
        class="inline-flex items-center px-3 border-r border-slate-700 bg-slate-800 text-slate-400 text-sm shrink-0">
        {!! $prependText !!}
      </span>
    @endif

    <select
      name="{{ $name }}"
      id="{{ $id ?? $name }}"
      {{
        $attributes->class([
          'block w-full border-0 bg-transparent text-white focus:ring-0 focus:outline-none sm:text-sm py-2.5 px-3',
          'text-red-400' => $errors->has($name),
        ])
      }}>
      @if ($optionHeader)
        <option value="" class="bg-slate-900 text-slate-500">{{ $optionHeader }}</option>
      @endif

      @foreach ($options as $key => $value)
        <option value="{{ $key }}" class="bg-slate-900 text-white">{!! $value !!}</option>
      @endforeach
    </select>

    @if ($appendText)
      <span
        class="inline-flex items-center px-3 border-l border-slate-700 bg-slate-800 text-slate-400 text-sm shrink-0">
        {!! $appendText !!}
      </span>
    @endif
  </div>

  @error ($name)
    <x-alert.text-danger class="mt-1.5">{{ $message }}</x-alert.text-danger>
  @enderror
</div>
