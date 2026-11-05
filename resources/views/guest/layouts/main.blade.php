<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="ai_unmul">

<head>
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <meta name="description" content="{{ $page_meta_description }}" />

  <script>
    (function() {
      const urlTheme = new URLSearchParams(window.location.search).get('theme');
      let isDark = false;
      if (urlTheme === 'dark') {
        isDark = true;
      } else if (urlTheme === 'light') {
        isDark = false;
      } else {
        const saved = localStorage.getItem('ai_theme');
        if (saved === 'ai_unmul_dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
          isDark = true;
        }
      }
      document.documentElement.setAttribute('data-theme', isDark ? 'ai_unmul_dark' : 'ai_unmul');
      document.documentElement.classList.toggle('dark', isDark);
    })();
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
  <title>
    {{ $page_title ? $page_title . ' |' : '' }} {{ config('app.name', 'Laravel') }}
  </title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  @vite('resources/css/app.css')
  <script src="{{ asset('js/gsap.min.js') }}"></script>
  <script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
  <script src="{{ asset('js/ScrollToPlugin.min.js') }}"></script>

  <style>
    /* ========================================================
       MODERN ULTRA-SLEEK CUSTOM SCROLLBAR
       ======================================================== */
    ::-webkit-scrollbar {
      width: 7px;
      height: 7px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background-color: rgba(100, 116, 139, 0.4);
      border-radius: 9999px;
      border: 1px solid transparent;
      background-clip: padding-box;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }
    ::-webkit-scrollbar-thumb:hover {
      background-color: rgba(100, 116, 139, 0.7);
    }
    ::-webkit-scrollbar-thumb:active {
      background-color: #8D021F !important;
    }
    ::-webkit-scrollbar-corner {
      background: transparent;
    }

    [data-theme="ai_unmul_dark"]::-webkit-scrollbar-thumb,
    .dark::-webkit-scrollbar-thumb,
    [data-theme="ai_unmul_dark"] ::-webkit-scrollbar-thumb,
    .dark ::-webkit-scrollbar-thumb {
      background-color: rgba(255, 255, 255, 0.22);
    }
    [data-theme="ai_unmul_dark"]::-webkit-scrollbar-thumb:hover,
    .dark::-webkit-scrollbar-thumb:hover,
    [data-theme="ai_unmul_dark"] ::-webkit-scrollbar-thumb:hover,
    .dark ::-webkit-scrollbar-thumb:hover {
      background-color: rgba(255, 255, 255, 0.48);
    }
    [data-theme="ai_unmul_dark"]::-webkit-scrollbar-thumb:active,
    .dark::-webkit-scrollbar-thumb:active,
    [data-theme="ai_unmul_dark"] ::-webkit-scrollbar-thumb:active,
    .dark ::-webkit-scrollbar-thumb:active {
      background-color: #8D021F !important;
    }

    html {
      scrollbar-width: thin;
      scrollbar-color: rgba(100, 116, 139, 0.4) transparent;
    }
    html[data-theme="ai_unmul_dark"],
    html.dark {
      scrollbar-color: rgba(255, 255, 255, 0.22) transparent;
    }
  </style>

  @yield('document.head')
</head>

<body class="min-h-screen flex flex-col bg-base-100 text-base-content antialiased">
  <!-- Fullscreen Kinetic Theme Wipe Curtain -->
  <div id="themeCurtain" 
       class="fixed inset-0 z-[9999] pointer-events-none hidden will-change-[clip-path]"
       aria-hidden="true">
  </div>

  @include('guest.components.navbar')

  @yield('document.body')

  @include('guest.components.footer')

  @yield('document.end')

  <script>
    function updateThemeIcons(isDark) {
      document.querySelectorAll('.theme-icon-sun').forEach(el => el.classList.toggle('hidden', !isDark));
      document.querySelectorAll('.theme-icon-moon').forEach(el => el.classList.toggle('hidden', isDark));
    }

    // Sync icon state on initial load
    (function() {
      const current = document.documentElement.getAttribute('data-theme') || 'ai_unmul';
      updateThemeIcons(current === 'ai_unmul_dark');
    })();

    let isThemeTransitioning = false;

    function toggleTheme() {
      if (isThemeTransitioning) return;
      const current = document.documentElement.getAttribute('data-theme') || 'ai_unmul';
      const isDark = current === 'ai_unmul_dark';
      const nextTheme = isDark ? 'ai_unmul' : 'ai_unmul_dark';
      const nextIsDark = !isDark;
      const curtain = document.getElementById('themeCurtain');

      if (!curtain || !window.gsap) {
        document.documentElement.setAttribute('data-theme', nextTheme);
        document.documentElement.classList.toggle('dark', nextIsDark);
        localStorage.setItem('ai_theme', nextTheme);
        updateThemeIcons(nextIsDark);
        return;
      }

      isThemeTransitioning = true;
      curtain.classList.remove('hidden');

      if (!isDark) {
        // ==========================================
        // SWITCH TO HITAM (DARK):
        // Slide diagonally from top-left corner to bottom-right
        // ==========================================
        curtain.style.backgroundColor = '#0a0a0c';

        gsap.timeline({
          onComplete: () => {
            curtain.classList.add('hidden');
            gsap.set(curtain, { clearProps: 'clipPath,backgroundColor' });
            isThemeTransitioning = false;
          }
        })
        .fromTo(curtain,
          { clipPath: 'polygon(0% 0%, 0% 0%, 0% 0%, 0% 0%)' },
          { clipPath: 'polygon(0% 0%, 150% 0%, 100% 150%, 0% 100%)', duration: 0.45, ease: 'power3.inOut' }
        )
        .add(() => {
          document.documentElement.setAttribute('data-theme', 'ai_unmul_dark');
          document.documentElement.classList.add('dark');
          localStorage.setItem('ai_theme', 'ai_unmul_dark');
          updateThemeIcons(true);
        })
        .to(curtain, {
          clipPath: 'polygon(150% 0%, 150% 0%, 100% 150%, 100% 150%)',
          duration: 0.45,
          ease: 'power3.inOut'
        });

      } else {
        // ==========================================
        // SWITCH TO PUTIH (LIGHT):
        // Reverse slide from bottom-right corner to top-left
        // ==========================================
        curtain.style.backgroundColor = '#f8fafc';

        gsap.timeline({
          onComplete: () => {
            curtain.classList.add('hidden');
            gsap.set(curtain, { clearProps: 'clipPath,backgroundColor' });
            isThemeTransitioning = false;
          }
        })
        .fromTo(curtain,
          { clipPath: 'polygon(100% 100%, 100% 100%, 100% 100%, 100% 100%)' },
          { clipPath: 'polygon(-50% 0%, 100% 0%, 100% 100%, 0% 100%)', duration: 0.45, ease: 'power3.inOut' }
        )
        .add(() => {
          document.documentElement.setAttribute('data-theme', 'ai_unmul');
          document.documentElement.classList.remove('dark');
          localStorage.setItem('ai_theme', 'ai_unmul');
          updateThemeIcons(false);
        })
        .to(curtain, {
          clipPath: 'polygon(-50% 0%, -50% 0%, 0% 100%, 0% 100%)',
          duration: 0.45,
          ease: 'power3.inOut'
        });
      }
    }
  </script>
</body>

</html>
