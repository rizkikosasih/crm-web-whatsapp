<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

if (!function_exists('storage')) {
  function storage(string $path): string
  {
    return asset('storage/' . ltrim($path, '/'));
  }
}

if (!function_exists('imageUri')) {
  function imageUri(string $path): string
  {
    $file = Storage::disk('public')->get($path);

    // Dapatkan MIME type
    $mime = Storage::disk('public')->mimeType($path);

    // Encode ke base64
    $base64 = base64_encode($file);

    // Gabungkan jadi data URI
    return "data:$mime;base64,$base64";
  }
}

if (!function_exists('setActive')) {
  function setActive(string $slug, ?string $output = 'active'): ?string
  {
    return preg_match('/' . $slug . '/i', Route::currentRouteName()) ? $output : '';
  }
}

if (!function_exists('parentClass')) {
  function parentClass(mixed $child): string
  {
    $condition = sizeof($child->children) > 0;
    $output = $condition ? 'parent ' : '';
    if ($output) {
      $output .= preg_match('/' . $child->slug . '/i', Route::currentRouteName())
        ? 'menu-is-opening menu-open '
        : '';
    }
    return $output;
  }
}

if (!function_exists('dateIndo')) {
  function dateIndo(?string $string, ?string $format = 'l, d F Y'): ?string
  {
    if (!$string) {
      return '';
    }
    Carbon::setLocale('id_ID');
    return Carbon::parse(substr($string, 0, 10))->translatedFormat($format);
  }
}

if (!function_exists('timeIndo')) {
  function timeIndo(?string $string, $endTime = ' WIB'): ?string
  {
    if (!$string) {
      return '';
    }
    Carbon::setLocale('id_ID');
    return Carbon::parse(substr($string, 11))->translatedFormat('h:i:s') . $endTime;
  }
}

if (!function_exists('rupiah')) {
  function rupiah(mixed $angka, $label = null): string
  {
    $output = !$label ? 'Rp ' : $label;
    return $output .= number_format(floatval($angka), 0, ',', '.');
  }
}

if (!function_exists('rp')) {
  function rp(mixed $angka): string
  {
    return number_format($angka, 0, ',', '.');
  }
}

if (!function_exists('parseTemplatePlaceholders')) {
  function parseTemplatePlaceholders(string $template, array $data): string
  {
    foreach ($data as $key => $value) {
      $template = str_replace("{{{$key}}}", $value, $template);
    }
    return $template;
  }
}

if (!function_exists('arrayKey')) {
  function arrayKey(array $array, ?string $key): ?string
  {
    if (!is_array($array)) {
      return '';
    }
    if (is_object($array)) {
      $array = (array) $array;
    }
    return isset($array[$key]) ? $array[$key] : '';
  }
}
