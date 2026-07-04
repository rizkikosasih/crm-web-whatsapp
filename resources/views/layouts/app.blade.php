<!DOCTYPE html>
<html
  lang="id"
  class="h-full"
  x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
  :class="{ dark: darkMode }">
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

  <!-- Anti-FOUC Script -->
  <script>
    if (
      localStorage.getItem('theme') === 'dark' ||
      (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>

  @vite (['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    [x-cloak] {
      display: none !important;
    }
  </style>
  @yield ('page-style')
</head>
<body
  x-data="{ sidebarOpen: window.innerWidth >= 768 }"
  class="h-full text-slate-800 dark:text-slate-200 antialiased selection:bg-indigo-500 selection:text-white bg-slate-50 dark:bg-slate-900 transition-colors duration-150">
  <div class="flex h-full overflow-hidden">
    <!-- Sidebar Navigation -->
    @livewire ('layouts.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <!-- Navbar -->
      @livewire ('layouts.navbar')

      <!-- Content Slot -->
      <main
        class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-slate-900 p-6 sm:p-8 transition-colors duration-150">
        {{ $slot }}
      </main>
    </div>
  </div>

  @livewireScripts

  <!-- Global Event Listeners -->
  <script type="module">
    document.addEventListener('livewire:init', () => {
      Livewire.on('showError', (event) => {
        const message = Array.isArray(event) ? event[0].message : event.message;
        Toast.fire({ icon: 'error', title: message });
      });

      Livewire.on('showSuccess', (event) => {
        const message = Array.isArray(event) ? event[0].message : event.message;
        Toast.fire({ icon: 'success', title: message });
      });

      Livewire.on('scrollToTop', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    });
  </script>

  @yield ('page-script')
</body>
</html>
