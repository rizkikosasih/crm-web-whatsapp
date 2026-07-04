<!DOCTYPE html>
<html
  lang="id"
  class="h-full {{ (isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark') ? 'dark' : '' }}"
  :class="{ dark: $store.theme.dark }">
<head>
  <meta charset="UTF-8" />
  <!-- Anti-FOUC Script -->
  <script>
    const theme =
      document.cookie
        .split('; ')
        .find((row) => row.startsWith('theme='))
        ?.split('=')[1] || localStorage.getItem('theme');
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
  <!-- Inline background style to prevent white flash before CSS loads -->
  <style>
    html {
      background-color: #f8fafc;
    }
    html.dark {
      background-color: #0f172a;
    }
  </style>

  <title>@yield ('title', env('APP_NAME', 'CRM WhatsApp'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />

  <!-- Google Fonts - Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Font Awesome 6 Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- Alpine.js Global Theme Store -->
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('theme', {
        dark: (() => {
          const theme =
            document.cookie
              .split('; ')
              .find((row) => row.startsWith('theme='))
              ?.split('=')[1] || localStorage.getItem('theme');
          return (
            theme === 'dark' ||
            (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
          );
        })(),
        toggle() {
          this.dark = !this.dark;
          localStorage.setItem('theme', this.dark ? 'dark' : 'light');
          document.cookie =
            'theme=' + (this.dark ? 'dark' : 'light') + '; path=/; max-age=31536000; SameSite=Lax';
          if (this.dark) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
          window.dispatchEvent(new CustomEvent('theme-changed'));
        },
      });
    });
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
