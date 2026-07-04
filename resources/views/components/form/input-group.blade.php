@props ([
  'name' => null,
  'id' => null,
  'placeholder' => null,
  'type' => 'text',
  'parentClass' => null,
  'appendText' => null,
  'prependText' => null,
])

<div @class (['mb-4', $parentClass ?? ''])>
  <div
    class="flex rounded-xl shadow-sm border border-slate-700 bg-slate-900/50 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition duration-150 overflow-hidden">
    @if ($prependText)
      <span
        class="inline-flex items-center px-3 border-r border-slate-700 bg-slate-800 text-slate-400 text-sm shrink-0">
        {!! $prependText !!}
      </span>
    @endif

    <input
      type="{{ $type }}"
      id="{{ $id ?? $name }}"
      placeholder="{{ $placeholder }}"
      {{
        $attributes->class([
          'block w-full border-0 bg-transparent text-white placeholder-slate-500 focus:ring-0 focus:outline-none sm:text-sm py-2.5 px-3',
          'text-red-400' => $errors->has($name),
        ])
      }} />

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
