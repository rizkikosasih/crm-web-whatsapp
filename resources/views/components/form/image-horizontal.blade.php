@props([
  'parentClass' => null,
  'customClass' => '',
  'id' => null,
  'label' => null,
  'name' => null,
  'path' => 'images/no-image.svg',
  'preview' => null,
  'horizontal' => null
])

<div @class(['form-group', 'd-flex flex-wrap', $parentClass])>
  @isset($label)
    <label for="{{ $name }}" class="fw-bold col-sm-3">{!! $label !!}</label>
  @endisset

  <div class="col-sm-9">
    <div class="custom-file">
      <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        @class(['custom-file-input'])
        {{ $attributes }}
      />
      <label class="custom-file-label" for="inputGroupFile" aria-describedby="inputGroupFileAddon">Choose image</label>
    </div>

    @if ($preview instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
      <div class="border text-center p-3">
        <a href="{{ $preview->temporaryUrl() }}" data-toggle="lightbox" class="tooltips" title="Perbesar">
          <img
            src="{{ $preview->temporaryUrl() }}"
            class="img-rounded"
            width="100"
            height="auto"
          />
        </a>
      </div>
    @elseif($path)
      <div class="border text-center p-3">
        <a href="{{ imageUri($path) }}" data-toggle="lightbox" class="tooltips" title="Perbesar">
          <img
            src="{{ imageUri($path) }}"
            class="img-rounded"
            width="100"
            height="auto"
          />
        </a>
      </div>
    @endif

    @error($name)
      <x-alert.text-danger customClass="mt-2">{{ $message }}</x-alert.text-danger>
    @enderror
  </div>
</div>
