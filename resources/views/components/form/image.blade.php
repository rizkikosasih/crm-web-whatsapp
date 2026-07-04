@props ([
  'parentClass' => null,
  'id' => null,
  'label' => null,
  'name' => null,
  'imageUri' => null,
  'horizontal' => null,
])

<div
  @class ([
    'mb-4',
    'grid grid-cols-1 md:grid-cols-4 gap-4 items-start' => $horizontal,
    $parentClass
  ])>
  @isset ($label)
    <label
      for="{{ $name }}"
      class="block text-sm font-semibold text-slate-700 dark:text-slate-300 @if($horizontal) md:col-span-1 md:mt-2.5 @else mb-2 @endif"
      >{!! $label !!}</label
    >
  @endisset

  @if ($horizontal)
    <div class="md:col-span-3 space-y-3">
  @endif

  <div class="flex items-center justify-center w-full">
    <label
      class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 dark:border-slate-700 border-dashed rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-900/40 hover:bg-slate-100 dark:hover:bg-slate-900/60 hover:border-slate-400 dark:hover:border-slate-600 transition duration-150">
      <div class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg class="w-8 h-8 mb-3 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 20 16" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
        </svg>
        <p class="mb-2 text-xs text-slate-600 dark:text-slate-400 font-semibold">Pilih Berkas / Seret Gambar</p>
        <p class="text-[10px] text-slate-500 dark:text-slate-500">PNG, JPG, JPEG (Max. 2MB)</p>
      </div>
      <input type="file" name="{{ $name }}" id="{{ $id }}" {{ $attributes->class(['hidden']) }} />
    </label>
  </div>

  <!-- Preview Slot -->
  <div
    class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 p-3 flex justify-center items-center min-h-[4rem]">
    {{ $slot }}
  </div>

  @error ($name)
    <x-alert.text-danger class="mt-1.5">{{ $message }}</x-alert.text-danger>
  @enderror

  @if ($horizontal)
</div>
@endif
</div>
