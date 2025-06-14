@props([
  'parentClass' => null,
  'customClass' => null,
  'label' => null,
  'name' => null
])

<div @class(['form-group', 'row' => $horizontal, $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold col-sm-3">{!! $label !!}</label>
  @endisset

  <div class="col-sm-9">
    <textarea
      @class([
        'form-control',
        'is-invalid' => $errors->has($name),
        $customClass,
      ])
      {{ $attributes }}
    ></textarea>

    @error($name)
      <x-alert.text-danger customClass="mt-2">{{ $message }}</x-alert.text-danger>
    @enderror

  </div>
</div>
