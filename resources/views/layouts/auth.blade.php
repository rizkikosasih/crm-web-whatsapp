<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', env('APP_NAME', 'CRM'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('storage/favicon/favicon.ico') }}">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles

  <style>
    body {
      background-color: #000;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
      min-height: 100%;
    }
  </style>

  @yield('page-style')
</head>

<body class="hold-transition login-page">

  {{ $slot }}

  @livewireScripts

  @yield('page-script')
</body>
</html>
