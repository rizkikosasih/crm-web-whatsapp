@props([
  'parentClass' => null,
  'id' => null,
  'label' => null,
  'name' => null,
  'imageUri' => null,
  'horizontal' => null,
])

<div @class(['form-group', 'd-flex flex-wrap' => $horizontal, $parentClass])>
  @isset($label)
    <label for="{{ $name }}" @class(['fw-bold', 'col-sm-3' => $horizontal])>{!! $label !!}</label>
  @endisset

  @if($horizontal)
    <div class="col-sm-9">
  @endif
    <div class="custom-file">
      <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $attributes->class(['custom-file-input']) }}
      />
      <label class="custom-file-label" for="inputGroupFile" aria-describedby="inputGroupFileAddon">Pilih Gambar</label>
    </div>

    <div class="border text-center p-3">
      {{ $slot }}
    </div>

    @error($name)
      <x-alert.text-danger customClass="mt-2">{{ $message }}</x-alert.text-danger>
    @enderror
  @if($horizontal)
    </div>
  @endif
</div>

