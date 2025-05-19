<div @class(['form-group', 'row', $parentClass ?? ''])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold col-sm-3">{!! $label !!}</label>
  @endisset

  <div class="col-sm-9">
    <textarea
      @class([
        'form-control',
        $customClass ?? '',
        'is-invalid' => $errors->has($name),
      ])
      {{ $attributes }}
    ></textarea>
  </div>

  @error('alamat')
    <x-alert.danger customClass="mt-2">{{ $message }}</x-alert.danger>
  @enderror
</div>
