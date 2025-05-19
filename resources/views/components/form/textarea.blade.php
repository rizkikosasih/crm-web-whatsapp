<div @class(['form-group', $parentClass ?? ''])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold">{!! $label !!}</label>
  @endisset

  <textarea
    @class([
      'form-control',
      $customClass ?? '',
      'is-invalid' => $errors->has($name),
    ])
    {{ $attributes }}
  ></textarea>

  @error('alamat')
    <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
  @enderror
</div>
