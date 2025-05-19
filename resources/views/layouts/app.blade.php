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

  @include('partials.head')
</head>
<body class="hold-transition sidebar-mini layout-fixed" style="overflow-x: visible;">
  <div class="wrapper">
    @include('partials.navbar')

    @livewire('layouts.sidebar')

    <div class="content-wrapper">
      @include('partials.content-header')

      <div class="content">
        {{ $slot }}
      </div>
    </div>
  </div>

  @include('partials.footer')
</body>
</html>
