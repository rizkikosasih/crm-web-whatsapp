@props([
  'name' => null,
  'id' => null,
  'label' => null,
  'parentClass' => null,
  'customClass' => null,
  'options' => [],
  'optionHeader' => null,
  'prependText' => null,
  'appendText' => null,
])

<div @class(['form-group', $parentClass])>
  <div class="input-group">
    @if($prependText)
      <span class="input-group-prepend"><div class="input-group-text">{!! $prependText !!}</div></span>
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
        <option value="{{ $key }}">{!! $value !!}</option>
      @endforeach
    </select>

    @if($appendText)
      <span class="input-group-append"><div class="input-group-text">{!! $appendText !!}</div></span>
    @endif
  </div>

  @error($name)
    <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
  @enderror
</div>
