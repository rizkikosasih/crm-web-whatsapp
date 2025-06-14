@props([
  'parentClass' => null,
  'customClass' => null,
  'label' => null,
  'name' => null,
  'horizontal' => false,
  'options' => [],
  'optionHeader' => null,
  'selected' => null,
])

<div @class(['form-group', 'row' => $horizontal, $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold @if($horizontal) col-sm-3 @endif">{!! $label !!}</label>
  @endisset

  @if($horizontal)
    <div class="col-sm-9">
  @endif

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
        <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>{!! $value !!}</option>
      @endforeach
    </select>

    @error($name)
      <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
    @enderror

  @if($horizontal)
    </div>
  @endif

</div>
