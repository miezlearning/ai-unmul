@extends('guest.layouts.main')

@section('document.head')
<style>
    .text-gradient-brand {
        background: linear-gradient(120deg, #8D021F 0%, #8D021F 45%, #009B4C 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .dark .text-gradient-brand,
    [data-theme="ai_unmul_dark"] .text-gradient-brand {
        background: linear-gradient(120deg, #e11d48 0%, #e11d48 50%, #10b981 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .dev-window {
        border-radius: 1.25rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        overflow: hidden;
    }
    .dark .dev-window,
    [data-theme="ai_unmul_dark"] .dev-window {
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: rgba(18, 20, 24, 0.85);
    }

    .dev-window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        background: rgba(0, 0, 0, 0.02);
    }
    .dark .dev-window-header,
    [data-theme="ai_unmul_dark"] .dev-window-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        background: rgba(255, 255, 255, 0.02);
    }

    .git-branch-line {
        position: relative;
    }
    .git-branch-line::before {
        content: '';
        position: absolute;
        top: 28px;
        bottom: 0;
        left: 17px;
        width: 2px;
        background: rgba(0, 0, 0, 0.1);
    }
    .dark .git-branch-line::before {
        background: rgba(255, 255, 255, 0.12);
    }
</style>
@endsection

@section('document.body')
<div class="pt-28 pb-20 sm:pt-36 sm:pb-28 bg-base-100">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <!-- Breadcrumb & Git Status Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <nav class="breadcrumbs text-xs text-base-content/60" aria-label="Breadcrumb">
                <ul>
                    <li><a href="{{ route('guest.beranda.index') }}" class="hover:text-primary transition-colors">home</a></li>
                    <li class="text-base-content/40">profil</li>
                    <li class="text-primary font-mono font-semibold">CHANGELOG.md</li>
                </ul>
            </nav>

            <div class="inline-flex items-center gap-2 self-start sm:self-auto px-3 py-1 rounded-full bg-base-200 border border-base-300 dark:border-white/10 text-[11px] font-mono text-base-content/70">
                <i class="fa-solid fa-code-commit text-primary text-xs"></i>
                <span>GIT_BRANCH: origin/main*</span>
            </div>
        </div>

        <!-- Monumental Tech Header -->
        <header class="text-center mb-12 sm:mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary/10 border border-secondary/25 text-xs font-mono text-secondary uppercase tracking-wider mb-4">
                <i class="fa-solid fa-code-branch text-[10px]"></i>
                <span>RELEASE LOG // NAPAK TILAS KRONOLOGIS</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                Dari Masa ke <span class="text-gradient-brand">Masa</span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-base-content/75 max-w-2xl mx-auto leading-relaxed">
                Napak tilas berdirinya Association of Informatics (AI) Fakultas Teknik Universitas Mulawarman serta dinamika estafet kepemimpinan dari masa ke masa.
            </p>
        </header>

        <!-- ================= SEMVER RELEASE TIMELINE ================= -->
        <div class="space-y-10 sm:space-y-12 git-branch-line">
            
            <!-- Release 01: v1.0.0-genesis (2021) -->
            <article class="relative pl-10 sm:pl-12 group">
                <!-- Git Commit Node Pin -->
                <div class="absolute left-0 top-3 w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center font-mono text-xs shadow-md ring-4 ring-base-100 flex-shrink-0 z-10">
                    <i class="fa-solid fa-flag text-[11px]"></i>
                </div>

                <!-- Release Window Card -->
                <div class="dev-window shadow-md hover:border-primary/50 transition-colors">
                    <div class="dev-window-header font-mono text-xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-primary text-white font-bold text-[11px]">
                                v1.0.0-genesis
                            </span>
                            <span class="text-base-content/60 hidden sm:inline">commit 310121a</span>
                        </div>
                        <div class="flex items-center gap-2 text-base-content/60">
                            <span class="text-[11px]">31 Januari 2021</span>
                            <span class="px-2 py-0.5 rounded bg-base-300 dark:bg-white/10 text-primary font-bold text-[10px]">STABLE</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                            <h2 class="text-xl sm:text-2xl font-black text-base-content">
                                Initial Commit: Deklarasi &amp; Pondasi Konstitusional
                            </h2>
                            <span class="text-xs font-mono text-base-content/50">MUBES I • Samarinda</span>
                        </div>
                        
                        <p class="text-xs sm:text-sm text-base-content/75 mt-2 leading-relaxed">
                            Berdasarkan Bab II Pasal 2 Anggaran Dasar, Association of Informatics resmi didirikan pada 31 Januari 2021 melalui Musyawarah Besar perdana sebagai wadah resmi mahasiswa Program Studi Informatika Fakultas Teknik Universitas Mulawarman.
                        </p>

                        <!-- Git Diff / Feature Log -->
                        <div class="mt-5 p-4 rounded-xl bg-base-100/80 border border-base-300 dark:border-white/5 font-mono text-xs space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(constitution):</strong> Pengesahan Anggaran Dasar &amp; Anggaran Rumah Tangga (AD/ART) perdana.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(identity):</strong> Penetapan Lambang Resmi, Makna Filosofi 6 Elemen, dan Warna Tridatu.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(governance):</strong> Pemilihan Ketua Umum AI dan Ketua Dewan Pertimbangan Organisasi (DPO) pertama.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(motto):</strong> Pengumandangan jargon resmi persatuan: &ldquo;SATU, PADU, INFORMATIKA&rdquo;.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Release 02: v2.0.0-lts (2022 - 2023) -->
            <article class="relative pl-10 sm:pl-12 group">
                <!-- Git Commit Node Pin -->
                <div class="absolute left-0 top-3 w-9 h-9 rounded-full bg-accent text-white flex items-center justify-center font-mono text-xs shadow-md ring-4 ring-base-100 flex-shrink-0 z-10">
                    <i class="fa-solid fa-code-branch text-[11px]"></i>
                </div>

                <!-- Release Window Card -->
                <div class="dev-window shadow-md hover:border-accent/50 transition-colors">
                    <div class="dev-window-header font-mono text-xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-accent text-white font-bold text-[11px]">
                                v2.0.0-lts
                            </span>
                            <span class="text-base-content/60 hidden sm:inline">commit 2223b8c</span>
                        </div>
                        <div class="flex items-center gap-2 text-base-content/60">
                            <span class="text-[11px]">2022 — 2023</span>
                            <span class="px-2 py-0.5 rounded bg-base-300 dark:bg-white/10 text-accent font-bold text-[10px]">LTS</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                            <h2 class="text-xl sm:text-2xl font-black text-base-content">
                                System Stabilization: Standarisasi Tata Kelola &amp; Kaderisasi
                            </h2>
                            <span class="text-xs font-mono text-base-content/50">Fakultas Teknik UNMUL</span>
                        </div>
                        
                        <p class="text-xs sm:text-sm text-base-content/75 mt-2 leading-relaxed">
                            Fase konsolidasi kelembagaan melalui pembakuan Garis-Garis Besar Haluan Kerja (GBHK), Standard Operating Procedure (SOP) kesekretariatan dan kebendaharaan, serta kurikulum pengaderan berjenjang yang menjadi syarat keanggotaan aktif mahasiswa Informatika.
                        </p>

                        <!-- Git Diff / Feature Log -->
                        <div class="mt-5 p-4 rounded-xl bg-base-100/80 border border-base-300 dark:border-white/5 font-mono text-xs space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(cadre-pipeline):</strong> Pembakuan alur pengaderan resmi himpunan berbasis kurikulum ilmiah &amp; soft-skill.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(supervision):</strong> Penguatan mekanisme pengawasan berkala melalui Sidang Evaluasi DPO AI.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(outreach):</strong> Inisiasi program studi banding teknologi dan pengabdian masyarakat perdana.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(finance):</strong> Penataan inventarisasi dan transparansi pelaporan kas keuangan himpunan.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Release 03: v3.0.0-current (2024 - Sekarang) -->
            <article class="relative pl-10 sm:pl-12 group">
                <!-- Git Commit Node Pin -->
                <div class="absolute left-0 top-3 w-9 h-9 rounded-full bg-secondary text-white flex items-center justify-center font-mono text-xs shadow-md ring-4 ring-base-100 flex-shrink-0 z-10">
                    <i class="fa-solid fa-rocket text-[11px]"></i>
                </div>

                <!-- Release Window Card -->
                <div class="dev-window shadow-md hover:border-secondary/50 transition-colors">
                    <div class="dev-window-header font-mono text-xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-secondary text-secondary-content font-bold text-[11px]">
                                v3.0.0-current
                            </span>
                            <span class="text-base-content/60 hidden sm:inline">commit 2024now</span>
                        </div>
                        <div class="flex items-center gap-2 text-base-content/60">
                            <span class="text-[11px]">2024 — Sekarang</span>
                            <span class="px-2 py-0.5 rounded bg-secondary/15 text-secondary font-bold text-[10px]">ACTIVE / HEAD</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-7">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                            <h2 class="text-xl sm:text-2xl font-black text-base-content">
                                Digital Acceleration &amp; Competitive Engineering
                            </h2>
                            <span class="text-xs font-mono text-secondary font-bold">Era Berjalan</span>
                        </div>
                        
                        <p class="text-xs sm:text-sm text-base-content/75 mt-2 leading-relaxed">
                            AI UNMUL melangkah ke fase kematangan organisasi modern dengan meluncurkan infrastruktur portal web terpadu, pembinaan riset kecerdasan buatan dan rekayasa perangkat lunak, serta perluasan jejaring kerja sama dengan industri teknologi.
                        </p>

                        <!-- Git Diff / Feature Log -->
                        <div class="mt-5 p-4 rounded-xl bg-base-100/80 border border-base-300 dark:border-white/5 font-mono text-xs space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(portal-web):</strong> Peluncuran portal web resmi AI UNMUL dengan arsitektur UI/UX modern berkinerja tinggi.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(incubator):</strong> Inkubasi intensif delegasi kompetisi IT nasional (Gemastik, Hackathon, PKM).</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(helpdesk):</strong> Pemberdayaan Helpdesk Advokasi mahasiswa yang cepat tanggap dan transparan.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-accent font-bold">+</span>
                                <span class="text-base-content/85"><strong class="text-base-content">feat(community):</strong> Memperkokoh budaya kolaboratif lintas angkatan berlandaskan asas kekeluargaan.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

        </div>

        <!-- Back Navigation -->
        <div class="text-center pt-14">
            <a href="{{ route('guest.beranda.index') }}" class="btn btn-ghost hover:bg-base-200 rounded-full px-6 text-sm font-mono">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i> cd .. / beranda
            </a>
        </div>

    </div>
</div>
@endsection