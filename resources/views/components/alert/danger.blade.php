@props ([
  'dismissible' => false,
])

<div
  x-data="{ show: true }"
  x-show="show"
  {{
    $attributes->class([
      'bg-red-950/40 border border-red-500/50 rounded-xl p-4 flex items-center justify-between gap-3 text-red-200 text-sm font-medium',
    ])
  }}>
  <div class="flex items-center gap-3">
    <svg class="h-5 w-5 text-red-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
    </svg>
    <div>{{ $slot }}</div>
  </div>

  @if ($dismissible)
    <button
      @click="show = false"
      type="button"
      class="text-red-400 hover:text-red-200 transition duration-150 cursor-pointer">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  @endif
</div>
