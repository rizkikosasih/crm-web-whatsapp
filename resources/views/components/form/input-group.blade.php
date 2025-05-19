@props([
  'name' => null,
  'id' => null,
  'label' => null,
  'placeholder' => null,
  'type' => 'text',
  'customClass' => '',
  'parentClass' => '',
  'prepend' => '',
])

<div @class(['form-group', $parentClass ?? ''])>
  <div class="input-group">
    @if($label && $prepend)
      <div class="input-group-prepend">
        <div class="input-group-text">
          {!! $label !!}
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

    @if($label && !$prepend)
      <div class="input-group-append">
        <div class="input-group-text">
          {!! $label !!}
        </div>
      </div>
    @endif
  </div>

  @error($name)
    <x-alert.text-danger>{{ $message }}</x-alert.text-danger>
  @enderror
</div>
