<span
  {{
    $attributes->merge([
      'class' => 'text-red-400 text-xs font-medium block mt-1.5 error',
    ])
  }}
  >{{ $slot }}</span
>
