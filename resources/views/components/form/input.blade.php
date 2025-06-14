@props([
  'parentClass' => null,
  'label' => null,
  'name' => null,
  'type' => 'text',
  'horizontal' => false,
])

<div @class(['form-group', 'row' => $horizontal, $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold @if($horizontal) col-sm-3 @endif">{!! $label !!}</label>
  @endisset

  @if($horizontal)
    <div class="col-sm-9">
  @endif

    <input
      type="{{ $type ?? 'text' }}"
      id="{{ $name }}"
      {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($name),
      ]) }}
    />

    @error($name)
      <x-alert.text-danger class="mt-2">{{ $message }}</x-alert.text-danger>
    @enderror

  @if($horizontal)
    </div>
  @endif
</div>
