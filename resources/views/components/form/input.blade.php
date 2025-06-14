@props([
  'parentClass' => null,
  'customClass' => null,
  'label' => null,
  'name' => null,
  'type' => 'text',
  'horizontal' => false,
])

<div @class(['form-group', 'row' => $horizontal, $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold @if($horizontal) col-sm-3 @endif">{!! $label !!}</label>
  @endisset

  @if($horizontal)
    <div class="col-sm-9">
  @endif

    <input
      type="{{ $type ?? 'text' }}"
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

  @if($horizontal)
    </div>
  @endif
</div>
