<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
  <meta charset="UTF-8" />
  <title>@yield ('title', env('APP_NAME', 'CRM WhatsApp'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="shortcut icon" href="{{ asset('storage/favicon/favicon.ico') }}" />

  <!-- Google Fonts - Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  @vite (['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
  @yield ('page-style')
</head>
<body class="h-full text-slate-200 antialiased selection:bg-indigo-500 selection:text-white">
  <div
    class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 bg-radial-gradient from-slate-800 to-slate-950">
    {{ $slot }}
  </div>

  @livewireScripts
  @yield ('page-script')
</body>
</html>
