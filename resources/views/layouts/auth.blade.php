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
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
  <!-- Inline background style to prevent white flash before CSS loads -->
  <style>
    html {
      background-color: #f1f5f9;
    }
    html.dark {
      background-color: #020617;
    }
  </style>

  <title>@yield ('title', env('APP_NAME', 'CRM WhatsApp'))</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="@yield('title', env('APP_NAME', 'CRM WhatsApp'))" />
  <meta
    property="og:description"
    content="Sistem CRM terintegrasi dengan automasi WhatsApp Gateway dan cloud media storage." />
  <meta property="og:image" content="{{ asset('images/og-image.png') }}" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('title', env('APP_NAME', 'CRM WhatsApp'))" />
  <meta
    name="twitter:description"
    content="Sistem CRM terintegrasi dengan automasi WhatsApp Gateway dan cloud media storage." />
  <meta name="twitter:image" content="{{ asset('images/og-image.png') }}" />

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
          return theme === 'dark';
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
  x-data
  class="h-full text-slate-800 dark:text-slate-200 antialiased selection:bg-indigo-500 selection:text-white bg-slate-100 dark:bg-slate-950 transition-colors duration-150">
  <!-- Theme Toggle (top-right corner) -->
  <div class="fixed top-4 right-4 z-50">
    <button
      @click="$store.theme.toggle()"
      class="p-2 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white shadow-sm backdrop-blur-sm transition duration-150 cursor-pointer"
      title="Ubah Tema">
      <span x-show="$store.theme.dark" x-cloak>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
        </svg>
      </span>
      <span x-show="!$store.theme.dark" x-cloak>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
        </svg>
      </span>
    </button>
  </div>

  <div
    class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-100 via-slate-50 to-indigo-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 transition-colors duration-150">
    {{ $slot }}
  </div>

  @livewireScripts
  @yield ('page-script')
</body>
</html>
