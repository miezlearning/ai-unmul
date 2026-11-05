@extends('guest.layouts.main')

@section('document.head')
<style>
    /* ===== Scroll reveal ===== */
    .reveal {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .reveal-delay-1 { transition-delay: 0.08s; }
    .reveal-delay-2 { transition-delay: 0.16s; }
    .reveal-delay-3 { transition-delay: 0.24s; }
    .reveal-delay-4 { transition-delay: 0.32s; }

    @media (prefers-reduced-motion: reduce) {
        .reveal { opacity: 1; transform: none; transition: none; }
    }

    /* ===== Hero decorative glows ===== */
    .hero-glow {
        position: absolute;
        border-radius: 9999px;
        filter: blur(72px);
        opacity: 0.35;
        pointer-events: none;
        z-index: 1;
    }

    /* ===== Subtle Film Grain / Noise Overlay ===== */
    .hero-noise {
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    }

    /* ===== Slow float for hero badge / logo ===== */
    @keyframes floaty {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-floaty { animation: floaty 6s ease-in-out infinite; }

    /* ===== Bounce-down scroll cue ===== */
    @keyframes bounce-down {
        0%, 100% { transform: translateY(0); opacity: 0.6; }
        50% { transform: translateY(8px); opacity: 1; }
    }
    .animate-bounce-down { animation: bounce-down 1.8s ease-in-out infinite; }

    /* ===== Gradient text ===== */
    .text-gradient-brand {
        background: linear-gradient(120deg, #8D021F 0%, #8D021F 45%, #009B4C 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* ===== Tilt card hover ===== */
    .lift {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.35s ease;
    }
    .lift:hover {
        transform: translateY(-6px);
    }

    /* ===== Feature icon chip glow on hover ===== */
    .icon-chip {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
    }
    .group:hover .icon-chip {
        transform: scale(1.08) rotate(-4deg);
    }

    /* ===== Labeled Kinetic Block Wipe & Slide Stroke Reveal ===== */
    .kinetic-wrap {
        position: relative;
        display: inline-block;
        vertical-align: baseline;
        overflow: hidden;
        max-width: 100%;
    }
    .kinetic-wrap.block {
        display: block;
    }
    .kinetic-text {
        display: inline-block;
        position: relative;
        z-index: 1;
        opacity: 0;
        will-change: opacity;
    }
    .kinetic-wrap.block .kinetic-text {
        display: block;
    }
    .kinetic-curtain {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: none;
        pointer-events: none;
        will-change: transform;
    }
    .kinetic-curtain::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        width: 3.5px;
        background: #ffffff;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.85), 0 0 4px currentColor;
        border-radius: 1px;
    }
    @media (prefers-reduced-motion: reduce) {
        .kinetic-curtain { display: none !important; }
        .kinetic-text { opacity: 1 !important; }
    }

    /* ===== Liquid Glass Portal & 3D Perspective ===== */
    .liquid-portal-perspective {
        perspective: 1200px;
        transform-style: preserve-3d;
    }
    #liquidCard {
        transform-style: preserve-3d;
        backface-visibility: hidden;
        will-change: transform;
    }
    #liquidSheen {
        pointer-events: none;
        will-change: opacity, background;
    }
</style>
@endsection

@section('document.body')

{{-- ===================== HERO (LANDO NORRIS SIGNATURE THEME) ===================== --}}
<section id="heroSection" class="relative overflow-hidden bg-base-100 pt-24 pb-16 sm:pt-28 sm:pb-20 lg:pt-32 lg:pb-24 min-h-[90vh] flex items-center">
    
    {{-- Topographic Contour Lines (Lando Norris Signature Graphic) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none select-none z-0 opacity-30 dark:opacity-20">
        <svg id="heroContourSvg" class="w-full h-full text-base-content will-change-transform" viewBox="0 0 1440 900" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <path d="M-100,180 C300,80 500,420 900,220 C1300,20 1500,320 1700,180" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 6" opacity="0.35"/>
            <path d="M-100,300 C250,160 600,580 1000,380 C1400,180 1600,480 1700,320" stroke="currentColor" stroke-width="2" opacity="0.5"/>
            <path d="M-100,440 C200,280 700,720 1100,520 C1500,320 1700,620 1700,460" stroke="currentColor" stroke-width="1.5" opacity="0.3"/>
            <path d="M-100,570 C150,400 800,880 1200,680 C1600,480 1800,780 1700,630" stroke="currentColor" stroke-width="2" opacity="0.45"/>
            <path d="M-100,690 C300,580 900,1020 1300,820 C1700,620 1900,920 1700,770" stroke="currentColor" stroke-width="1.5" stroke-dasharray="8 6" opacity="0.3"/>
            {{-- Topographic Organic Loops --}}
            <path d="M 320 450 C 420 380, 580 400, 650 480 C 720 560, 680 680, 580 720 C 480 750, 360 680, 330 600 Z" stroke="currentColor" stroke-width="1.5" opacity="0.25"/>
            <path d="M 880 150 C 980 90, 1120 120, 1200 210 C 1280 300, 1220 420, 1120 450 C 1020 480, 900 380, 860 280 Z" stroke="currentColor" stroke-width="1.5" opacity="0.25"/>
        </svg>
    </div>

    {{-- Ambient glow accents (Restrained, brand colors only) --}}
    <div class="hero-glow hero-glow-1 bg-primary" style="width:28rem;height:28rem;top:-8rem;left:-8rem;opacity:0.2;"></div>
    <div class="hero-glow hero-glow-2 bg-accent" style="width:22rem;height:22rem;bottom:-6rem;right:8%;opacity:0.18;"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

            {{-- ================= LEFT COLUMN: CLEAN EDITORIAL TYPOGRAPHY ================= --}}
            <div id="heroLeftContent" class="lg:col-span-7 flex flex-col items-start text-left z-10">

                {{-- Clean Monumental Headline with Labeled Kinetic Block Wipe & Slide Stroke --}}
                <h1 id="heroTitle" class="select-none text-left tracking-tight w-full">
                    <span class="block text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-base-content leading-tight">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-base-content/85 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">Association of</span>
                        </span>
                    </span>
                    <span class="block text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-none my-2">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                            <span class="kinetic-text text-primary uppercase">Informatics</span>
                        </span>
                    </span>
                    <span class="block text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-snug">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-accent text-accent rounded-sm"></span>
                            <span class="kinetic-text text-accent">Universitas Mulawarman</span>
                        </span>
                    </span>
                </h1>

                {{-- Subheadline (Genuine, human, anti-slop) --}}
                <p id="heroSubtitle" class="mt-5 sm:mt-6 text-base sm:text-lg text-base-content/80 max-w-xl leading-relaxed flex flex-col items-start gap-1">
                    <span class="kinetic-wrap">
                        <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                        <span class="kinetic-text">Wadah berproses, berkolaborasi, dan berkarya mahasiswa Informatika</span>
                    </span>
                    <span class="kinetic-wrap">
                        <span class="kinetic-curtain bg-accent text-accent rounded-sm"></span>
                        <span class="kinetic-text">Universitas Mulawarman untuk mengembangkan keilmuan dan kontribusi nyata.</span>
                    </span>
                </p>

                {{-- Clean CTAs --}}
                <div id="heroCta" class="mt-7 sm:mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('guest.profil.tentang.index') }}"
                       class="btn btn-primary rounded-full px-7 py-3 text-sm font-semibold shadow-md shadow-brand-red/20 hover:shadow-lg hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">Tentang AI UNMUL</span>
                        </span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#program"
                       class="btn btn-outline rounded-full px-7 py-3 text-sm font-medium hover:-translate-y-0.5 transition-all inline-flex items-center gap-2">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-base-content/80 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">Program Kerja</span>
                        </span>
                        <i class="fa-solid fa-arrow-down text-xs"></i>
                    </a>
                </div>

                {{-- Honest Metadata Strip (No fake pills or HUD slop) --}}
                <div id="heroMetaStrip" class="mt-8 sm:mt-10 pt-6 border-t border-base-300/80 w-full flex flex-wrap items-center gap-6 sm:gap-8 text-xs text-base-content/65">
                    <div>
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                            <span class="kinetic-text"><span class="font-bold text-sm text-base-content">300+</span> Anggota Aktif</span>
                        </span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-base-content/30 hidden sm:block"></div>
                    <div>
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-accent text-accent rounded-sm"></span>
                            <span class="kinetic-text"><span class="font-bold text-sm text-base-content">8</span> Divisi &amp; Biro</span>
                        </span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-base-content/30 hidden sm:block"></div>
                    <div>
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-base-content/80 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">Fakultas Teknik • Berdiri Sejak 2009</span>
                        </span>
                    </div>
                </div>

            </div>

            {{-- ================= RIGHT COLUMN: EXPANSIVE LIQUID GLASS CENTERPIECE ================= --}}
            <div id="heroRightVisual" class="lg:col-span-5 flex justify-center lg:justify-end z-10">
                <div id="liquidPortalContainer" 
                     class="liquid-portal-perspective relative w-full max-w-[420px] sm:max-w-[460px] aspect-[4/5] rounded-[2rem] select-none group cursor-pointer">
                    
                    {{-- Ambient color aura --}}
                    <div class="absolute -inset-4 bg-gradient-to-tr from-primary/30 via-accent/20 to-secondary/20 rounded-[2.5rem] blur-2xl opacity-60 group-hover:opacity-90 transition-opacity duration-700 pointer-events-none -z-10"></div>

                    {{-- 3D Tilting Card Container --}}
                    <div id="liquidCard" 
                         class="relative w-full h-full rounded-[2rem] overflow-hidden shadow-2xl border border-base-300 dark:border-white/10 bg-base-200 transform-gpu transition-all duration-300">
                        
                        {{-- Interactive Liquid Cursor Mask Reveal SVG --}}
                        <svg id="liquidMaskSvg" viewBox="0 0 500 625" class="w-full h-full object-cover block select-none pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                {{-- Gooey / Metaball Filter (Alpha Threshold Blending) --}}
                                <filter id="gooeyMetaballs" x="-50%" y="-50%" width="200%" height="200%">
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="blur" />
                                    <feColorMatrix in="blur" mode="matrix" 
                                                   values="1 0 0 0 0  
                                                           0 1 0 0 0  
                                                           0 0 1 0 0  
                                                           0 0 0 28 -12" result="goo" />
                                    <feComposite in="SourceGraphic" in2="goo" operator="atop" />
                                </filter>

                                {{-- Liquid Cursor Mask --}}
                                <mask id="liquidCursorMask">
                                    {{-- Base: Black hides entire top layer --}}
                                    <rect width="100%" height="100%" fill="black" />
                                    
                                    {{-- Trailing metaball group: White reveals top layer --}}
                                    <g id="maskBlobGroup" filter="url(#gooeyMetaballs)" fill="white">
                                        <circle class="liquid-blob blob-0" cx="250" cy="312.5" r="0" />
                                        <circle class="liquid-blob blob-1" cx="250" cy="312.5" r="0" />
                                        <circle class="liquid-blob blob-2" cx="250" cy="312.5" r="0" />
                                        <circle class="liquid-blob blob-3" cx="250" cy="312.5" r="0" />
                                        <circle class="liquid-blob blob-4" cx="250" cy="312.5" r="0" />
                                    </g>
                                </mask>
                            </defs>

                            {{-- LAYER 1: Base Image (AI UNMUL Member Jacket Portrait) --}}
                            <image id="baseImage" 
                                   href="{{ asset('images/hero/hero-jacket.jpg') }}" 
                                   width="500" height="625" 
                                   preserveAspectRatio="xMidYMid slice" />

                            {{-- LAYER 2: Top Reveal Layer (INFORMATIKA UNIVERSITAS MULAWARMAN MECHA BANNER) --}}
                            <g mask="url(#liquidCursorMask)">
                                <image id="revealBannerImg" 
                                       href="{{ asset('images/hero/hero-banner.jpg') }}" 
                                       width="500" height="625" 
                                       preserveAspectRatio="xMidYMid slice" />
                            </g>
                        </svg>

                        {{-- Specular Light Sheen (Reacts to cursor coordinates) --}}
                        <div id="liquidSheen" 
                             class="absolute inset-0 z-30 pointer-events-none opacity-0 transition-opacity duration-300 mix-blend-overlay"
                             style="background: radial-gradient(circle 280px at 50% 50%, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.12) 40%, transparent 70%);">
                        </div>

                        {{-- Inner Bevel Glass Highlight --}}
                        <div class="absolute inset-0 z-30 pointer-events-none rounded-[2rem] border border-white/20 shadow-inner"></div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Subtle Bottom Feathering / Gradient Blend into Next Section --}}
    <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-b from-transparent to-base-100 pointer-events-none z-10"></div>

    {{-- Scroll cue with tactile bounce --}}
    <a id="heroScrollCue" href="#stats" class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 z-20 text-base-content/40 hover:text-primary transition-all duration-300 flex flex-col items-center gap-1 group" aria-label="Scroll ke bawah">
        <span class="kinetic-wrap">
            <span class="kinetic-curtain bg-base-content/70 dark:bg-white text-white rounded-sm"></span>
            <span class="kinetic-text text-[9px] tracking-widest uppercase font-semibold text-base-content/40 group-hover:text-primary transition-colors">Scroll</span>
        </span>
        <i class="fa-solid fa-chevron-down text-xs animate-bounce-down"></i>
    </a>
</section>


{{-- ===================== STATS STRIP ===================== --}}
<section id="stats" class="border-y border-base-300 bg-base-100">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $stats = [
                    ['icon' => 'fa-users', 'value' => '300+', 'label' => 'Anggota Aktif'],
                    ['icon' => 'fa-layer-group', 'value' => '8', 'label' => 'Divisi / Bidang'],
                    ['icon' => 'fa-calendar-check', 'value' => '20+', 'label' => 'Event / Tahun'],
                    ['icon' => 'fa-graduation-cap', 'value' => '2009', 'label' => 'Berdiri Sejak'],
                ];
            @endphp
            @foreach ($stats as $index => $stat)
                <div class="reveal reveal-delay-{{ $index + 1 }} flex flex-col items-center text-center">
                    <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-red/10 text-brand-red text-lg">
                        <i class="fa-solid {{ $stat['icon'] }}"></i>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain {{ $index % 2 === 0 ? 'bg-primary text-primary' : 'bg-accent text-accent' }} rounded-sm"></span>
                            <span class="kinetic-text">{{ $stat['value'] }}</span>
                        </span>
                    </div>
                    <div class="text-sm text-base-content/60 mt-1">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-base-content/85 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">{{ $stat['label'] }}</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ===================== WHY / FEATURES ===================== --}}
<section id="mengapa" class="py-20 sm:py-28">
    <div class="container mx-auto px-4">
        <div class="reveal max-w-2xl mx-auto text-center mb-14">
            <span class="inline-block text-sm font-semibold tracking-wide uppercase text-brand-red">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                    <span class="kinetic-text">Mengapa AI UNMUL</span>
                </span>
            </span>
            <h2 class="mt-3 text-3xl sm:text-4xl font-bold tracking-tight">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-base-content/85 dark:bg-white text-white rounded-sm"></span>
                    <span class="kinetic-text">Lebih dari sekadar organisasi</span>
                </span>
            </h2>
            <p class="mt-4 text-base-content/60">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-accent text-accent rounded-sm"></span>
                    <span class="kinetic-text">Kami menghadirkan ruang untuk berkembang secara akademik, profesional, dan sosial.</span>
                </span>
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = [
                    ['icon' => 'fa-code', 'chipBg' => 'bg-brand-red/10', 'chipText' => 'text-brand-red',
                     'title' => 'Pengembangan Kompetensi',
                     'desc'  => 'Belajar bersama lewat workshop, sharing session, dan proyek kolaboratif di bidang teknologi.'],
                    ['icon' => 'fa-handshake-angle', 'chipBg' => 'bg-brand-green/10', 'chipText' => 'text-brand-green',
                     'title' => 'Kolaborasi & Jejaring',
                     'desc'  => 'Terhubung dengan sesama mahasiswa, alumni, serta industri untuk memperluas peluangmu.'],
                    ['icon' => 'fa-trophy', 'chipBg' => 'bg-brand-yellow/30', 'chipText' => 'text-brand-yellow',
                     'title' => 'Prestasi & Kompetisi',
                     'desc'  => 'Wadah pembinaan tim untuk berlomba di berbagai kompetisi tingkat nasional.'],
                    ['icon' => 'fa-people-group', 'chipBg' => 'bg-brand-red/10', 'chipText' => 'text-brand-red',
                     'title' => 'Kekeluargaan',
                     'desc'  => 'Suasana kekeluargaan yang erat — tempat bertumbuh bersama tanpa merasa sendiri.'],
                    ['icon' => 'fa-bullhorn', 'chipBg' => 'bg-brand-green/10', 'chipText' => 'text-brand-green',
                     'title' => 'Advokasi Mahasiswa',
                     'desc'  => 'Menyalurkan aspirasi dan kepentingan mahasiswa Informatika ke pihak kampus.'],
                    ['icon' => 'fa-seedling', 'chipBg' => 'bg-brand-yellow/30', 'chipText' => 'text-brand-yellow',
                     'title' => 'Kepemimpinan',
                     'desc'  => 'Asah kemampuan memimpin, mengorganisir, dan bertanggung jawab melalui kabinet kerja.'],
                ];
            @endphp
            @foreach ($features as $f)
                <div class="reveal group lift rounded-2xl border border-base-300 bg-base-100 p-6 hover:border-brand-red/40 hover:shadow-xl hover:shadow-brand-red/5">
                    <div class="icon-chip inline-flex h-12 w-12 items-center justify-center rounded-xl {{ $f['chipBg'] }} {{ $f['chipText'] }} text-lg mb-4">
                        <i class="fa-solid {{ $f['icon'] }}"></i>
                    </div>
                    <h3 class="text-lg font-semibold">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                            <span class="kinetic-text">{{ $f['title'] }}</span>
                        </span>
                    </h3>
                    <p class="mt-2 text-sm text-base-content/60 leading-relaxed">
                        <span class="kinetic-wrap block">
                            <span class="kinetic-curtain bg-base-content/80 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">{{ $f['desc'] }}</span>
                        </span>
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ===================== PROGRAM / WHAT WE DO ===================== --}}
<section id="program" class="py-20 sm:py-28 bg-base-200 relative overflow-hidden">
    <div class="hero-glow bg-primary" style="width:20rem;height:20rem;top:-4rem;right:-4rem;opacity:0.18;"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="reveal max-w-2xl mx-auto text-center mb-14">
            <span class="inline-block text-sm font-semibold tracking-wide uppercase text-brand-green">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-accent text-accent rounded-sm"></span>
                    <span class="kinetic-text">Program Kerja</span>
                </span>
            </span>
            <h2 class="mt-3 text-3xl sm:text-4xl font-bold tracking-tight">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-base-content/85 dark:bg-white text-white rounded-sm"></span>
                    <span class="kinetic-text">Apa yang kami lakukan</span>
                </span>
            </h2>
            <p class="mt-4 text-base-content/60">
                <span class="kinetic-wrap">
                    <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                    <span class="kinetic-text">Ragam kegiatan rutin yang membawa manfaat untuk anggota dan civitas.</span>
                </span>
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $programs = [
                    ['icon' => 'fa-microphone-lines', 'title' => 'Tech Talk', 'desc' => 'Diskusi & seminar seputar teknologi terkini bersama praktisi.'],
                    ['icon' => 'fa-chalkboard-user', 'title' => 'Kelas & Workshop', 'desc' => 'Pendalaman materi & skill praktis untuk mahasiswa Informatika.'],
                    ['icon' => 'fa-flag', 'title' => 'Kompetisi', 'desc' => 'Pembinaan tim untuk lomba programming dan bidang terkait.'],
                    ['icon' => 'fa-hand-holding-heart', 'title' => 'Pengabdian', 'desc' => 'Berbagi ilmu dan kontribusi nyata kepada masyarakat.'],
                ];
            @endphp
            @foreach ($programs as $i => $p)
                <div class="reveal reveal-delay-{{ $i + 1 }} lift rounded-2xl bg-base-100 border border-base-300 p-6 hover:shadow-xl hover:shadow-base-300/40">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-base-200 text-brand-red text-lg">
                            <i class="fa-solid {{ $p['icon'] }}"></i>
                        </div>
                        <span class="text-3xl font-extrabold text-base-300">
                            <span class="kinetic-wrap">
                                <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                                <span class="kinetic-text">0{{ $i + 1 }}</span>
                            </span>
                        </span>
                    </div>
                    <h3 class="font-semibold">
                        <span class="kinetic-wrap">
                            <span class="kinetic-curtain bg-primary text-primary rounded-sm"></span>
                            <span class="kinetic-text">{{ $p['title'] }}</span>
                        </span>
                    </h3>
                    <p class="mt-2 text-sm text-base-content/60 leading-relaxed">
                        <span class="kinetic-wrap block">
                            <span class="kinetic-curtain bg-base-content/80 dark:bg-white text-white rounded-sm"></span>
                            <span class="kinetic-text">{{ $p['desc'] }}</span>
                        </span>
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ===================== KINETIC VALORANT REVEAL & PARALLAX SCRIPT ===================== --}}
<script>
    (function () {
        // Register GSAP plugins safely
        if (window.gsap) {
            if (window.ScrollTrigger) gsap.registerPlugin(ScrollTrigger);
            if (window.ScrollToPlugin) gsap.registerPlugin(ScrollToPlugin);
        }

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ========================================================
        // 1. SCROLL-TO-SHOW LOGIC FOR ALL CONTENT SECTIONS
        // (Ensures elements already near/in view appear immediately)
        // ========================================================
        function checkScrollReveals() {
            var reveals = document.querySelectorAll('.reveal');
            if (!reveals.length) return;
            var triggerBottom = window.innerHeight * 0.92;
            reveals.forEach(function (el) {
                var rect = el.getBoundingClientRect();
                if (rect.top <= triggerBottom) {
                    el.classList.add('is-visible');
                }
            });
        }

        // Run immediately on load so near-fold content (like #stats) is never blank!
        checkScrollReveals();
        window.addEventListener('scroll', checkScrollReveals, { passive: true });
        window.addEventListener('resize', checkScrollReveals, { passive: true });

        // ========================================================
        // 2. KINETIC SLIDE STROKE & BLOCK REVEAL (ALL TEXTS EXCEPT NAVBAR)
        // (Curtain with leading 3.5px glowing stroke sweeps across: -102% -> 0 -> 102%)
        // ========================================================
        if (window.gsap && !prefersReducedMotion) {
            // Function to animate a group of kinetic wraps sequentially
            function animateKineticBatch(wraps, baseDelay = 0, stagger = 0.08) {
                if (!wraps || !wraps.length) return;
                const tl = gsap.timeline({ delay: baseDelay });
                wraps.forEach((wrap, idx) => {
                    const curtain = wrap.querySelector('.kinetic-curtain');
                    const text = wrap.querySelector('.kinetic-text');
                    if (!curtain || !text) return;

                    const startAt = idx * stagger;
                    const dur = 0.38;

                    tl.fromTo(curtain,
                        { xPercent: -102, display: 'block' },
                        { xPercent: 0, duration: dur, ease: "power2.inOut" },
                        startAt
                    )
                    .call(() => { text.style.opacity = '1'; }, null, startAt + dur)
                    .to(curtain, {
                        xPercent: 102,
                        duration: dur,
                        ease: "power2.inOut",
                        onComplete: () => {
                            curtain.style.display = 'none';
                            text.style.opacity = '1';
                        }
                    }, startAt + dur);
                });

                // Per-batch safety timer: ensures this batch finishes gracefully even if window unfocused
                const batchTotal = (baseDelay + (wraps.length * stagger) + 0.9) * 1000;
                setTimeout(() => {
                    wraps.forEach(w => {
                        const c = w.querySelector('.kinetic-curtain');
                        const t = w.querySelector('.kinetic-text');
                        if (c) c.style.display = 'none';
                        if (t) t.style.opacity = '1';
                    });
                }, batchTotal);
            }

            // A. Initial Hero Section Kinetic Entrance
            const heroWraps = Array.from(document.querySelectorAll('#heroLeftContent .kinetic-wrap, #heroScrollCue .kinetic-wrap'));
            if (heroWraps.length) {
                heroWraps.forEach(w => {
                    const t = w.querySelector('.kinetic-text');
                    if (t) t.style.opacity = '0';
                });
                animateKineticBatch(heroWraps, 0.08, 0.08);
            }

            // Entrance for Right Centerpiece
            const rightVisual = document.getElementById('heroRightVisual');
            if (rightVisual) {
                gsap.from(rightVisual, {
                    scale: 0.94,
                    opacity: 0.7,
                    duration: 0.7,
                    delay: 0.25,
                    ease: "power3.out"
                });
            }

            // B. Scroll-Triggered Kinetic Text for All Other Sections (#stats, #mengapa, #program, etc.)
            // Strictly exclude #mainNavbar or any nav elements
            const nonHeroSections = document.querySelectorAll('section:not(#heroSection)');
            
            // Initial state: hide text for all non-hero sections
            nonHeroSections.forEach(sec => {
                const wraps = sec.querySelectorAll('.kinetic-wrap');
                wraps.forEach(w => {
                    const t = w.querySelector('.kinetic-text');
                    if (t) t.style.opacity = '0';
                });
            });

            if ('IntersectionObserver' in window) {
                const sectionObserver = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const wraps = Array.from(entry.target.querySelectorAll('.kinetic-wrap'));
                            if (wraps && wraps.length) {
                                animateKineticBatch(wraps, 0.04, 0.06);
                            }
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });

                nonHeroSections.forEach(sec => {
                    sectionObserver.observe(sec);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                nonHeroSections.forEach(sec => {
                    const wraps = Array.from(sec.querySelectorAll('.kinetic-wrap'));
                    animateKineticBatch(wraps, 0.08, 0.06);
                });
            }
        } else {
            // Reduced motion or no GSAP
            document.querySelectorAll('.kinetic-curtain').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.kinetic-text').forEach(t => t.style.opacity = '1');
        }

        // ========================================================
        // 3. INTERACTIVE LIQUID CURSOR MASK REVEAL (LANDO NORRIS SIGNATURE)
        // SVG Gooey Metaballs Trailing Cursor with Staggered GSAP quickTo Lag
        // Center Proximity & Click-Hold Expansion
        // ========================================================
        const portal = document.getElementById('liquidPortalContainer');
        const card = document.getElementById('liquidCard');
        const sheen = document.getElementById('liquidSheen');
        const blobs = document.querySelectorAll('.liquid-blob');

        if (portal && card && blobs.length && window.gsap) {
            const baseRadii = [82, 74, 66, 58, 48];
            const durations = [0.12, 0.19, 0.26, 0.34, 0.42];

            // State holding current (x, y) coordinates for each blob
            const blobState = [];
            const xSetters = [];
            const ySetters = [];

            blobs.forEach((blob, i) => {
                const state = { x: 250, y: 312.5 };
                blobState.push(state);

                // GSAP quickTo setters for fluid trailing with staggered delay
                xSetters.push(gsap.quickTo(state, "x", { duration: durations[i], ease: "power2.out" }));
                ySetters.push(gsap.quickTo(state, "y", { duration: durations[i], ease: "power2.out" }));
            });

            // GSAP Ticker: synchronizes SVG circle positions at screen refresh rate (60/120fps)
            gsap.ticker.add(() => {
                for (let i = 0; i < blobs.length; i++) {
                    blobs[i].setAttribute('cx', blobState[i].x.toFixed(2));
                    blobs[i].setAttribute('cy', blobState[i].y.toFixed(2));
                }
            });

            let isInside = false;
            let isHoldingClick = false;
            let isExpanded = false;

            function updateExpansion(shouldExpand) {
                if (isExpanded === shouldExpand) return;
                isExpanded = shouldExpand;

                if (shouldExpand) {
                    blobs.forEach((blob) => {
                        gsap.to(blob, {
                            attr: { r: 540 },
                            duration: 0.65,
                            ease: "power3.out",
                            overwrite: "auto"
                        });
                    });
                } else {
                    blobs.forEach((blob, i) => {
                        gsap.to(blob, {
                            attr: { r: isInside ? baseRadii[i] : 0 },
                            duration: 0.5,
                            ease: "power2.out",
                            overwrite: "auto"
                        });
                    });
                }
            }

            function onPointerEnter(e) {
                isInside = true;
                if (sheen) gsap.to(sheen, { opacity: 0.85, duration: 0.3 });

                const rect = portal.getBoundingClientRect();
                const clientX = e.clientX ?? (e.touches && e.touches[0] ? e.touches[0].clientX : rect.left + rect.width / 2);
                const clientY = e.clientY ?? (e.touches && e.touches[0] ? e.touches[0].clientY : rect.top + rect.height / 2);
                const startX = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width)) * 500;
                const startY = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height)) * 625;

                // Position all blobs directly at the cursor when entering freshly
                blobState.forEach((state) => {
                    state.x = startX;
                    state.y = startY;
                });
                blobs.forEach((blob) => {
                    blob.setAttribute('cx', startX.toFixed(2));
                    blob.setAttribute('cy', startY.toFixed(2));
                });

                // Smoothly swell blobs into view
                if (!isExpanded) {
                    blobs.forEach((blob, i) => {
                        gsap.to(blob, {
                            attr: { r: baseRadii[i] },
                            duration: 0.35 + i * 0.05,
                            ease: "back.out(1.5)",
                            overwrite: "auto"
                        });
                    });
                }
            }

            function onPointerLeave() {
                isInside = false;
                isHoldingClick = false;
                updateExpansion(false);

                // Smoothly collapse blob radii back to 0
                blobs.forEach((blob) => {
                    gsap.to(blob, {
                        attr: { r: 0 },
                        duration: 0.45,
                        ease: "power2.in",
                        overwrite: "auto"
                    });
                });

                // Reset 3D tilt
                gsap.to(card, {
                    rotateX: 0,
                    rotateY: 0,
                    scale: 1,
                    duration: 0.6,
                    ease: "elastic.out(1, 0.45)"
                });

                if (sheen) gsap.to(sheen, { opacity: 0, duration: 0.4 });
            }

            function onPointerMove(e) {
                if (!isInside) onPointerEnter(e);

                const rect = portal.getBoundingClientRect();
                const clientX = e.clientX ?? (e.touches && e.touches[0] ? e.touches[0].clientX : rect.left + rect.width / 2);
                const clientY = e.clientY ?? (e.touches && e.touches[0] ? e.touches[0].clientY : rect.top + rect.height / 2);

                const normX = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                const normY = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));

                // Map to SVG viewBox (500 x 625)
                const targetX = normX * 500;
                const targetY = normY * 625;

                // Feed coordinates to GSAP quickTo setters for staggered fluid trailing
                xSetters.forEach(setX => setX(targetX));
                ySetters.forEach(setY => setY(targetY));

                // Center proximity check: subject center is at (0.5, 0.5)
                const distFromCenter = Math.hypot(normX - 0.5, normY - 0.5);
                const isOverCenter = distFromCenter < 0.16;

                // Hold-to-expand or center hover reveal
                if (isHoldingClick || isOverCenter) {
                    updateExpansion(true);
                } else {
                    updateExpansion(false);
                }

                // Smooth 3D card tilt
                const rotateX = (normY - 0.5) * -14;
                const rotateY = (normX - 0.5) * 14;

                gsap.to(card, {
                    rotateX: rotateX,
                    rotateY: rotateY,
                    scale: 1.02,
                    duration: 0.22,
                    ease: "power2.out",
                    transformPerspective: 900
                });

                // Update specular sheen position
                if (sheen) {
                    sheen.style.background = `radial-gradient(circle 280px at ${(clientX - rect.left)}px ${(clientY - rect.top)}px, rgba(255,255,255,0.65) 0%, rgba(255,255,255,0.12) 40%, transparent 70%)`;
                }
            }

            portal.addEventListener('mouseenter', onPointerEnter);
            portal.addEventListener('mouseleave', onPointerLeave);
            portal.addEventListener('mousemove', onPointerMove);

            // Hold-click to expand
            portal.addEventListener('mousedown', function (e) {
                if (e.button === 0) {
                    isHoldingClick = true;
                    updateExpansion(true);
                }
            });

            window.addEventListener('mouseup', function () {
                if (isHoldingClick) {
                    isHoldingClick = false;
                    if (!isInside) {
                        updateExpansion(false);
                    }
                }
            });

            // Touch device support (mobile / tablet)
            portal.addEventListener('touchstart', function (e) {
                onPointerEnter(e);
                isHoldingClick = true;
                onPointerMove(e);
            }, { passive: true });

            portal.addEventListener('touchmove', function (e) {
                onPointerMove(e);
            }, { passive: true });

            portal.addEventListener('touchend', function () {
                isHoldingClick = false;
                setTimeout(onPointerLeave, 1200);
            }, { passive: true });
        }

        // ========================================================
        // 3B. HERO PARALLAX TRANSITION (CINEMATIC SCROLL-DOWN DEPTH)
        // Staggered scroll velocities between typography, liquid centerpiece,
        // ambient glows, and topographic contours into the next section.
        // ========================================================
        if (window.gsap && window.ScrollTrigger && !prefersReducedMotion) {
            const isDesktop = window.innerWidth >= 1024;
            
            const heroParallaxTl = gsap.timeline({
                scrollTrigger: {
                    trigger: '#heroSection',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 0.8, // Smooth physical lag (buttery interpolation)
                    invalidateOnRefresh: true
                }
            });

            // 1. Left Editorial Typography drifts upward & dims into the distance
            heroParallaxTl.to('#heroLeftContent', {
                y: isDesktop ? -90 : -45,
                opacity: 0.15,
                ease: 'power1.in'
            }, 0);

            // 2. Right Liquid Glass Centerpiece descends with subtle scale & 3D perspective
            heroParallaxTl.to('#heroRightVisual', {
                y: isDesktop ? 120 : 55,
                scale: 0.94,
                opacity: 0.82,
                ease: 'power1.out'
            }, 0);

            // 3. Topographic Contours background drifts with ambient depth
            const contour = document.getElementById('heroContourSvg');
            if (contour) {
                heroParallaxTl.to(contour, {
                    y: isDesktop ? 160 : 70,
                    scale: 1.08,
                    opacity: 0.05,
                    ease: 'none'
                }, 0);
            }

            // 4. Ambient Aura Glows drift organically
            heroParallaxTl.to('.hero-glow-1', {
                y: 140,
                x: -30,
                opacity: 0.04,
                ease: 'none'
            }, 0);
            heroParallaxTl.to('.hero-glow-2', {
                y: 90,
                x: 30,
                opacity: 0.04,
                ease: 'none'
            }, 0);

            // 5. Scroll cue vanishes swiftly on initial scroll down
            heroParallaxTl.to('#heroScrollCue', {
                y: 24,
                opacity: 0,
                duration: 0.25,
                ease: 'power2.out'
            }, 0);

            // 6. Seamless layered entrance for #stats as hero scrolls away
            gsap.fromTo('#stats', 
                { y: 35, opacity: 0.85 },
                {
                    y: 0,
                    opacity: 1,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '#stats',
                        start: 'top bottom',
                        end: 'top 75%',
                        scrub: 0.8
                    }
                }
            );
        }

        // ========================================================
        // 4. SMOOTH SCROLL WHEN JUMPING BETWEEN SECTIONS
        // ========================================================
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href*="#"]');
            if (!anchor) return;

            const href = anchor.getAttribute('href');
            if (!href || href === '#' || href === '#!') return;

            try {
                const url = new URL(anchor.href, window.location.href);
                if (url.pathname === window.location.pathname && url.hash && url.hash.length > 1) {
                    const targetEl = document.querySelector(url.hash);
                    if (targetEl) {
                        e.preventDefault();

                        // Compensate for floating navbar height (~76px)
                        const navOffset = 76;

                        if (window.gsap && window.ScrollToPlugin) {
                            gsap.to(window, {
                                duration: 0.95,
                                scrollTo: {
                                    y: targetEl,
                                    offsetY: navOffset,
                                    autoKill: true
                                },
                                ease: "power3.inOut"
                            });
                        } else {
                            const targetPos = targetEl.getBoundingClientRect().top + window.pageYOffset - navOffset;
                            window.scrollTo({
                                top: targetPos,
                                behavior: 'smooth'
                            });
                        }

                        if (history.pushState) {
                            history.pushState(null, '', url.hash);
                        }
                    }
                }
            } catch (err) {
                // Ignore any invalid URL parse
            }
        });
    })();
</script>
@endsection
