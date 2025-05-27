@props([
  'parentClass' => null,
  'customClass' => null,
  'label' => null,
  'name' => null,
  'type' => 'text',
])

<div @class(['form-group', 'row', $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold col-sm-3">{!! $label !!}</label>
  @endisset

  <div class="col-sm-9">
    <input
      type="{{ $type }}"
      id="{{ $name }}"
      @class([
        'form-control',
        $customClass ?? '',
        'is-invalid' => $errors->has($name),
      ])
      {{ $attributes }}
    />

    @error($name)
      <x-alert.text-danger customClass="mt-2">{{ $message }}</x-alert.text-danger>
    @enderror
  </div>
</div>
