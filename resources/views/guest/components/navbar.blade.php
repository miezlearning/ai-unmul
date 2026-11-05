<nav class="fixed top-4 left-0 right-0 z-50 flex justify-center px-3 sm:px-4 pointer-events-none">
    <div id="navbarWrapper" class="pointer-events-auto flex justify-center w-full">
        <div id="navbarCard" 
             tabindex="0"
             role="button"
             aria-expanded="true"
             aria-label="Navigasi AI UNMUL. Klik area navbar untuk mengecilkan atau melebarkan"
             class="bg-base-100/90 backdrop-blur-md shadow-md hover:shadow-lg border border-base-300/80 cursor-pointer select-none overflow-visible will-change-transform w-full max-w-4xl rounded-2xl px-3.5 sm:px-4 py-2 transition-shadow duration-300">
            
            <div class="grid grid-cols-[auto_1fr_auto] md:grid-cols-[1fr_auto_1fr] items-center w-full min-h-[40px] gap-2">
                
                <!-- Left: logo + title + optional subtitle -->
                <div class="flex items-center justify-start flex-shrink-0">
                    <a href="{{ route('guest.beranda.index') }}" class="nav-interactive flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo/LogoAI (3).png') }}" alt="Logo AI" class="h-8 w-8 object-contain transition-transform group-hover:scale-105 flex-shrink-0">
                        <div class="flex flex-col leading-none">
                            <span class="text-left text-sm sm:text-base font-bold tracking-tight text-base-content whitespace-nowrap">AI UNMUL</span>
                            <span id="navbarSubtitle" class="text-[10px] text-base-content/60 hidden sm:inline font-medium whitespace-nowrap mt-0.5">Association of Informatics</span>
                        </div>
                    </a>
                </div>

                <!-- Center: nav buttons (md+) - EXACT DEAD CENTER -->
                <div id="navbarCenterMenu" class="hidden md:flex items-center justify-center">
                    <ul class="flex items-center gap-1 text-sm font-medium">
                        <li>
                            <a href="{{ route('guest.beranda.index') }}" class="nav-interactive px-3.5 py-1.5 rounded-full transition-all duration-150 {{ request()->routeIs('guest.beranda.*') ? 'bg-primary text-primary-content font-semibold shadow-sm shadow-brand-red/20' : 'text-base-content/75 hover:text-base-content hover:bg-base-200/80' }}">
                                Beranda
                            </a>
                        </li>
                        
                        <!-- Profil Dropdown -->
                        <li class="relative dropdown" id="profilDropdown">
                            <button type="button" id="profilToggle" aria-haspopup="true" aria-expanded="false"
                                class="nav-interactive px-3.5 py-1.5 rounded-full transition-all duration-150 inline-flex items-center gap-1 focus:outline-none select-none {{ request()->routeIs('guest.profil.*') ? 'bg-primary text-primary-content font-semibold shadow-sm shadow-brand-red/20' : 'text-base-content/75 hover:text-base-content hover:bg-base-200/80' }}">
                                <span>Profil</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 opacity-60 transition-transform duration-200" id="profilArrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul id="profilMenu" class="dropdown-menu-content absolute top-full left-1/2 -translate-x-1/2 p-1.5 shadow-xl bg-base-100 rounded-xl w-48 border border-base-300 flex flex-col gap-0.5 mt-2 z-50 invisible opacity-0 translate-y-1 transition-all duration-200">
                                <li>
                                    <a href="{{ route('guest.profil.tentang.index') }}" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150 {{ request()->routeIs('guest.profil.tentang.*') ? 'bg-primary text-primary-content' : '' }}">
                                        Tentang AI
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('guest.profil.kabinet.index') }}" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150 {{ request()->routeIs('guest.profil.kabinet.*') ? 'bg-primary text-primary-content' : '' }}">
                                        Kabinet
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('guest.profil.dari-masa-ke-masa.index') }}" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150 {{ request()->routeIs('guest.profil.dari-masa-ke-masa.*') ? 'bg-primary text-primary-content' : '' }}">
                                        Dari Masa ke Masa
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Layanan Dropdown -->
                        <li class="relative dropdown" id="servicesDropdown">
                            <button type="button" id="servicesToggle" aria-haspopup="true" aria-expanded="false"
                                class="nav-interactive px-3.5 py-1.5 rounded-full transition-all duration-150 inline-flex items-center gap-1 focus:outline-none select-none text-base-content/75 hover:text-base-content hover:bg-base-200/80">
                                <span>Layanan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 opacity-60 transition-transform duration-200" id="servicesArrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul id="servicesMenu" class="dropdown-menu-content absolute top-full left-1/2 -translate-x-1/2 p-1.5 shadow-xl bg-base-100 rounded-xl w-48 border border-base-300 flex flex-col gap-0.5 mt-2 z-50 invisible opacity-0 translate-y-1 transition-all duration-200">
                                <li>
                                    <a href="#posko-pengaduan" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150">
                                        Posko Pengaduan
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150">
                                        Layanan Akademik
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-interactive block w-full px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-primary hover:text-primary-content transition-colors duration-150">
                                        Aspirasi Mahasiswa
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#program" class="nav-interactive px-3.5 py-1.5 rounded-full transition-all duration-150 text-base-content/75 hover:text-base-content hover:bg-base-200/80">
                                Program
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Right: social icons & mobile dropdown toggle -->
                <div id="navbarRightMenu" class="flex items-center justify-end gap-1 flex-shrink-0">
                    <a href="https://whatsapp.com/channel/0029VazMHQcLI8YhavJcMh34" target="_blank" rel="noopener noreferrer" class="nav-interactive w-8 h-8 rounded-full flex items-center justify-center hover:bg-base-200/80 text-base-content/70 hover:text-base-content transition-all tooltip tooltip-bottom" aria-label="WhatsApp" data-tip="WhatsApp">
                        <img src="{{ asset('images/socmed/wa.svg') }}" alt="WhatsApp" class="h-4.5 w-4.5 grayscale hover:grayscale-0 transition-all hover:scale-110" />
                    </a>
                    <a href="https://www.instagram.com/ai.unmul/" target="_blank" rel="noopener noreferrer" class="nav-interactive w-8 h-8 rounded-full flex items-center justify-center hover:bg-base-200/80 text-base-content/70 hover:text-base-content transition-all tooltip tooltip-bottom" aria-label="Instagram" data-tip="Instagram">
                        <img src="{{ asset('images/socmed/ig.svg') }}" alt="Instagram" class="h-4.5 w-4.5 grayscale hover:grayscale-0 transition-all hover:scale-110" />
                    </a>
                    <a href="https://tiktok.com/@ai.unmul" target="_blank" rel="noopener noreferrer" class="nav-interactive w-8 h-8 rounded-full flex items-center justify-center hover:bg-base-200/80 text-base-content/70 hover:text-base-content transition-all tooltip tooltip-bottom" aria-label="TikTok" data-tip="TikTok">
                        <img src="{{ asset('images/socmed/tiktok.svg') }}" alt="TikTok" class="h-4.5 w-4.5 grayscale hover:grayscale-0 transition-all hover:scale-110" />
                    </a>

                    <!-- Theme Toggle Button (Hitam / Putih) -->
                    <button type="button" 
                            id="themeToggleBtn"
                            onclick="toggleTheme()" 
                            class="nav-interactive w-8 h-8 rounded-full flex items-center justify-center hover:bg-base-200/80 text-base-content/75 hover:text-base-content transition-all tooltip tooltip-bottom" 
                            aria-label="Ganti tema Hitam / Putih" 
                            data-tip="Tema Hitam / Putih">
                        <!-- Sun icon: visible in dark mode -->
                        <svg class="theme-icon-sun h-4 w-4 hidden text-warning" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon: visible in light mode -->
                        <svg class="theme-icon-moon h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Mobile dropdown toggle -->
                    <div class="dropdown dropdown-end md:hidden">
                        <label tabindex="0" class="nav-interactive btn btn-ghost btn-sm btn-circle" aria-label="Open menu">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </label>
                        <ul tabindex="0" class="menu menu-sm dropdown-content mt-2 p-2 shadow-xl bg-base-100 rounded-box w-52 border border-base-300">
                            <li><a href="{{ route('guest.beranda.index') }}" class="nav-interactive">Beranda</a></li>
                            <li class="menu-title">Profil</li>
                            <li><a href="{{ route('guest.profil.tentang.index') }}" class="nav-interactive">Tentang AI</a></li>
                            <li><a href="{{ route('guest.profil.kabinet.index') }}" class="nav-interactive">Kabinet</a></li>
                            <li><a href="{{ route('guest.profil.dari-masa-ke-masa.index') }}" class="nav-interactive">Dari Masa ke Masa</a></li>
                            <li class="menu-title">Layanan</li>
                            <li><a href="#posko-pengaduan" class="nav-interactive">Posko Pengaduan</a></li>
                            <li class="border-t border-base-300 mt-1 pt-1">
                                <button type="button" onclick="toggleTheme()" class="nav-interactive flex items-center justify-between w-full">
                                    <span>Tema Hitam / Putih</span>
                                    <svg class="theme-icon-sun h-4 w-4 hidden text-warning" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <svg class="theme-icon-moon h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</nav>

<script>
    (function(){
        const card = document.getElementById('navbarCard');
        const centerMenu = document.getElementById('navbarCenterMenu');
        const rightMenu = document.getElementById('navbarRightMenu');
        const subtitle = document.getElementById('navbarSubtitle');

        if (!card) return;

        let isCollapsed = false;
        const COLLAPSED_WIDTH = 154; // Logo + AI UNMUL

        // Ensure GSAP is loaded, otherwise load fallback or wait
        function withGSAP(fn) {
            if (window.gsap) {
                fn();
            } else {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js';
                script.onload = fn;
                document.head.appendChild(script);
            }
        }

        withGSAP(function() {
            function collapseNavbar(animate = true) {
                if (isCollapsed) return;
                isCollapsed = true;
                try { sessionStorage.setItem('ai_navbar_collapsed', 'true'); } catch(e) {}
                card.setAttribute('aria-expanded', 'false');

                const currentWidth = card.getBoundingClientRect().width;
                gsap.killTweensOf([card, centerMenu, rightMenu, subtitle]);

                // Set overflow hidden during collapse so shrinking pill stays neat
                gsap.set(card, { overflow: 'hidden' });

                if (!animate) {
                    gsap.set([centerMenu, rightMenu, subtitle], { opacity: 0, display: 'none' });
                    gsap.set(card, { width: COLLAPSED_WIDTH, borderRadius: 9999, paddingLeft: 14, paddingRight: 14 });
                    return;
                }

                // 60FPS High-Performance GSAP Timeline
                const tl = gsap.timeline();

                // 1. Instantly begin fading out extra contents
                tl.to([centerMenu, rightMenu, subtitle], {
                    opacity: 0,
                    scale: 0.94,
                    duration: 0.16,
                    ease: "power2.inOut",
                    onComplete: () => {
                        gsap.set([centerMenu, rightMenu, subtitle], { display: 'none' });
                    }
                }, 0);

                // 2. Lock current width and morph to pill smoothly
                gsap.set(card, { width: currentWidth, maxWidth: 'none' });
                tl.to(card, {
                    width: COLLAPSED_WIDTH,
                    borderRadius: 9999,
                    paddingLeft: 14,
                    paddingRight: 14,
                    duration: 0.4,
                    ease: "power3.inOut"
                }, 0.04);
            }

            function expandNavbar(animate = true) {
                if (!isCollapsed) return;
                isCollapsed = false;
                try { sessionStorage.setItem('ai_navbar_collapsed', 'false'); } catch(e) {}
                card.setAttribute('aria-expanded', 'true');

                const targetWidth = Math.min(896, window.innerWidth - 24);
                gsap.killTweensOf([card, centerMenu, rightMenu, subtitle]);

                if (!animate) {
                    gsap.set([centerMenu, rightMenu, subtitle], { display: '', opacity: 1, scale: 1 });
                    gsap.set(card, { width: '100%', maxWidth: '56rem', borderRadius: 16, paddingLeft: 16, paddingRight: 16, overflow: 'visible' });
                    return;
                }

                // Make elements visible but transparent before expansion
                gsap.set([centerMenu, rightMenu, subtitle], { display: '', opacity: 0, scale: 0.94 });

                const tl = gsap.timeline({
                    onComplete: () => {
                        // Restore overflow visible so tooltips and dropdowns are never clipped!
                        gsap.set(card, { width: '100%', maxWidth: '56rem', overflow: 'visible' });
                    }
                });

                // 1. Expand card to full width & border-radius
                tl.to(card, {
                    width: targetWidth,
                    borderRadius: 16,
                    paddingLeft: 16,
                    paddingRight: 16,
                    duration: 0.42,
                    ease: "power3.out"
                }, 0);

                // 2. Staggered fluid fade-in of contents
                tl.to([subtitle, centerMenu, rightMenu], {
                    opacity: 1,
                    scale: 1,
                    duration: 0.28,
                    stagger: 0.04,
                    ease: "power2.out"
                }, 0.14);
            }

            function toggleNavbar() {
                isCollapsed ? expandNavbar() : collapseNavbar();
            }

            // Restore saved preference if any
            try {
                if (sessionStorage.getItem('ai_navbar_collapsed') === 'true') {
                    collapseNavbar(false);
                }
            } catch(e) {}

            // Click anywhere on navbar card
            card.addEventListener('click', function(e) {
                if (isCollapsed) {
                    e.preventDefault();
                    expandNavbar();
                    return;
                }

                // In expanded mode, ignore clicks on interactive links, buttons, inputs, dropdowns
                if (e.target.closest('.nav-interactive, a, button, input, select, textarea, .dropdown-content')) {
                    return;
                }

                // Clicked on the navbar body area
                collapseNavbar();
            });

            // Keyboard accessible
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (!e.target.closest('.nav-interactive, a, button, input')) {
                        e.preventDefault();
                        toggleNavbar();
                    }
                } else if (e.key === 'Escape' && !isCollapsed) {
                    collapseNavbar();
                }
            });

            // Responsive auto-adjust if window resizes while expanded
            window.addEventListener('resize', function() {
                if (!isCollapsed) {
                    gsap.set(card, { width: '100%', maxWidth: '56rem' });
                }
            });
        });

        // Dropdowns setup
        function setupDropdown(toggleId, menuId, arrowId) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(arrowId);
            if (!toggle || !menu) return;

            function open() {
                menu.classList.remove('invisible', 'opacity-0', 'translate-y-1');
                menu.classList.add('opacity-100', 'translate-y-0');
                toggle.setAttribute('aria-expanded', 'true');
                if (arrow) arrow.classList.add('rotate-180');
            }

            function close() {
                menu.classList.add('invisible', 'opacity-0', 'translate-y-1');
                menu.classList.remove('opacity-100', 'translate-y-0');
                toggle.setAttribute('aria-expanded', 'false');
                if (arrow) arrow.classList.remove('rotate-180');
            }

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = toggle.getAttribute('aria-expanded') === 'true';
                isOpen ? close() : open();
            });

            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !toggle.contains(e.target)) close();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') close();
            });
        }

        setupDropdown('profilToggle', 'profilMenu', 'profilArrow');
        setupDropdown('servicesToggle', 'servicesMenu', 'servicesArrow');
    })();
</script>
