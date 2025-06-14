@props([
  'name' => null,
  'id' => null,
  'placeholder' => null,
  'type' => 'text',
  'parentClass' => null,
  'appendText' => null,
  'prependText' => null,
])

<div @class(['form-group', $parentClass ?? ''])>
  <div class="input-group">
    @if($prependText)
      <div class="input-group-prepend">
        <div class="input-group-text">
          {!! $prependText !!}
        </div>
      </div>
    @endif

    <input
      type="{{ $type }}"
      id="{{ $id ?? $name }}"
      placeholder="{{ $placeholder }}"
      {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($name),
      ]) }}
    >

    @if($appendText)
      <div class="input-group-append">
        <div class="input-group-text">
          {!! $appendText !!}
        </div>
      </div>
    @endif
  </div>

  @error($name)
    <x-alert.text-danger class="mt-2">{{ $message }}</x-alert.text-danger>
  @enderror
</div>
