@props (['name', 'class' => 'w-5 h-5'])

@php
  $path = base_path("node_modules/lucide-static/icons/{$name}.svg");
  $svg = '';
  if (file_exists($path)) {
    $svg = file_get_contents($path);
    // Remove class attribute if already present in raw SVG
    $svg = preg_replace('/class="[^"]*"/', '', $svg);
    // Inject the custom CSS class and any forwarded component attributes to style the SVG via Tailwind CSS/Alpine
    $svg = str_replace('<svg ', "<svg class=\"{$class}\" " . $attributes->toHtml() . ' ', $svg);
  }
@endphp

{!! $svg !!}
