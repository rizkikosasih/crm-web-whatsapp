@props ([
  'id' => 'modal',
  'title' => '',
  'maxWidth' => 'lg',
])

@php
  $maxWClass = match ($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    default => 'max-w-lg',
  };
@endphp

<div
  x-data="{ open: false }"
  x-on:open-{{ $id }}.window="open = true"
  x-on:close-{{ $id }}.window="open = false"
  x-show="open"
  x-cloak
  class="fixed inset-0 z-50 flex items-center justify-center p-4"
  role="dialog"
  aria-modal="true">
  {{-- Backdrop --}}
  <div
    class="absolute inset-0 bg-black/60 backdrop-blur-sm"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="open = false; $dispatch('close-{{ $id }}')"
    aria-hidden="true"></div>

  {{-- Panel --}}
  <div
    class="relative w-full {{ $maxWClass }} bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
    @click.stop>
    {{-- Header --}}
    @if ($title)
      <div
        class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700/50">
        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        <button
          type="button"
          @click="open = false; $dispatch('close-{{ $id }}')"
          class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition duration-150 cursor-pointer p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700/50">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    @endif

    {{-- Body --}}
    <div class="px-6 py-5 max-h-[80vh] overflow-y-auto">{{ $slot }}</div>
  </div>
</div>
