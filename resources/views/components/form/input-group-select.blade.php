@props([
  'name' => null,
  'id' => null,
  'label' => null,
  'parentClass' => '',
  'customClass' => '',
  'options' => [],
  'optionHeader' => ''
])

<div @class(['form-group', $parentClass ?? ''])>
  <div class="input-group">
    @isset($label)
      <span class="input-group-prepend"><div class="input-group-text">{!! $label !!}</div></span>
    @endisset

    <select
      name="{{ $name }}"
      id="{{ $id ?? $name }}"
      @class([
        'form-control',
        $customClass ?? '',
        'is-invalid' => $errors->has($name),
      ])
      {{ $attributes }}
    >
      @if($optionHeader)
        <option value="">{{ $optionHeader }}</option>
      @endif
      @foreach ($options as $key => $value)
        <option value="{{ $key }}">{!! $value !!}</option>
      @endforeach
    </select>
  </div>

  @error($name)
    <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
  @enderror
</div>
