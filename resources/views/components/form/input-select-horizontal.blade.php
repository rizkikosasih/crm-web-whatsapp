@props([
  'name' => null,
  'id' => null,
  'label' => null,
  'parentClass' => '',
  'customClass' => '',
  'options' => [],
  'optionHeader' => [],
  'selected' => null,
])

<div @class(['form-group row', $parentClass ?? ''])>
  @isset($label)
    <label class="fw-bold col-sm-3">{!! $label !!}</label>
  @endisset

  <div class="col-sm-9">
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
        <option value="{{ $key }}" {{ $selected == $value ? 'selected' : ''}}>{!! $value !!}</option>
      @endforeach
    </select>

    @error($name)
      <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
    @enderror
  </div>
</div>

