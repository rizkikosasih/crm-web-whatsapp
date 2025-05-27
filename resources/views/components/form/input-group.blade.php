@props([
  'name' => null,
  'id' => null,
  'placeholder' => null,
  'type' => 'text',
  'customClass' => null,
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
      @class([
        'form-control',
        $customClass ?? '',
        'is-invalid' => $errors->has($name),
      ])
      id="{{ $id ?? $name }}"
      placeholder="{{ $placeholder }}"
      {{ $attributes }}
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
    <x-alert.text-danger>{{ $message }}</x-alert.text-danger>
  @enderror
</div>
