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

    .brand-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }
    .dark .brand-card,
    [data-theme="ai_unmul_dark"] .brand-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .brand-card:hover {
        border-color: rgba(141, 2, 31, 0.3);
    }
    .dark .brand-card:hover,
    [data-theme="ai_unmul_dark"] .brand-card:hover {
        border-color: rgba(225, 29, 72, 0.4);
    }
</style>
@endsection

@section('document.body')
<div class="pt-32 pb-24 sm:pt-40 sm:pb-32 bg-base-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
        
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumbs text-xs sm:text-sm mb-8 text-base-content/60" aria-label="Breadcrumb">
            <ul>
                <li><a href="{{ route('guest.beranda.index') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                <li class="text-base-content/40">Profil</li>
                <li class="text-primary font-semibold">Struktur Kepengurusan &amp; Kelembagaan</li>
            </ul>
        </nav>

        <!-- Page Header -->
        <header class="text-center mb-16 sm:mb-20">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-accent/10 border border-accent/25 text-xs font-semibold text-accent uppercase tracking-wider mb-5">
                <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                <span>TATA KELOLA RESMI &bull; AD BAB VII &amp; ART BAB V&ndash;VI</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                Struktur Kepengurusan <span class="text-gradient-brand">AI UNMUL</span>
            </h1>
            <p class="mt-5 text-base sm:text-lg lg:text-xl text-base-content/75 max-w-3xl mx-auto leading-relaxed">
                Peta tata kelola konstitusional yang memadukan supremasi kedaulatan mahasiswa, fungsi pengawasan legislatif, kepemimpinan eksekutif harian, dan divisi teknis pelaksana.
            </p>
        </header>

        <!-- ================= LEVEL 1: KELEMBAGAAN TERTINGGI (MUBES AI) ================= -->
        <section class="mb-14 sm:mb-18" aria-label="Musyawarah Besar">
            <div class="rounded-3xl bg-gradient-to-r from-base-200 via-base-200/90 to-primary/10 brand-card p-8 sm:p-10 shadow-sm border-l-4 border-l-primary">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-base-300 dark:border-white/10">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-primary block">Tingkat I &bull; Konstitusi &amp; Kedaulatan</span>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-base-content mt-0.5">Musyawarah Besar (MUBES AI)</h2>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-3.5 py-1.5 rounded-full bg-primary/15 text-primary">
                        Kekuasaan Tertinggi (AD Pasal 16)
                    </span>
                </div>

                <div class="grid sm:grid-cols-3 gap-6 pt-6">
                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-base-content/60 mb-1.5">Periodesasi</h4>
                        <div class="text-base font-bold text-base-content">1 Kali per Periode</div>
                        <p class="text-xs text-base-content/70 mt-1.5 leading-relaxed">Dihadiri seluruh mahasiswa aktif Informatika untuk menentukan arah garis besar organisasi.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-base-content/60 mb-1.5">Kewenangan Konstitusional</h4>
                        <div class="text-base font-bold text-base-content">AD/ART &amp; GBHK</div>
                        <p class="text-xs text-base-content/70 mt-1.5 leading-relaxed">Menetapkan dan merevisi AD/ART, GBHK, serta menilai Laporan Pertanggungjawaban (LPJ).</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-base-content/60 mb-1.5">Mandat Pemilihan</h4>
                        <div class="text-base font-bold text-base-content">Ketua Umum &amp; DPO</div>
                        <p class="text-xs text-base-content/70 mt-1.5 leading-relaxed">Memilih dan mengesahkan Ketua Umum AI dan Ketua Dewan Pertimbangan Organisasi secara sah.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= LEVEL 2: DEWAN PERTIMBANGAN ORGANISASI (DPO AI) ================= -->
        <section class="mb-14 sm:mb-18" aria-label="Dewan Pertimbangan Organisasi">
            <div class="rounded-3xl bg-base-200/70 brand-card p-8 sm:p-10 shadow-sm border-l-4 border-l-accent">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-base-300 dark:border-white/10">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-accent text-white flex items-center justify-center text-2xl shadow-md flex-shrink-0">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-accent block">Tingkat II &bull; Pengawasan &amp; Pertimbangan</span>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-base-content mt-0.5">Dewan Pertimbangan Organisasi (DPO AI)</h2>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-3.5 py-1.5 rounded-full bg-accent/15 text-accent">
                        ART Bab VI (Pasal 22&ndash;27)
                    </span>
                </div>

                <div class="grid sm:grid-cols-3 gap-6 pt-6">
                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <div class="flex items-center gap-2 mb-2 text-accent">
                            <i class="fa-solid fa-users text-sm"></i>
                            <h4 class="font-bold text-sm text-base-content">Komposisi Lembaga</h4>
                        </div>
                        <p class="text-xs text-base-content/75 leading-relaxed">
                            Terdiri dari 1 orang Ketua DPO dan maksimal 6 anggota demisioner yang dipilih melalui MUBES untuk masa bakti 1 periode.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <div class="flex items-center gap-2 mb-2 text-accent">
                            <i class="fa-solid fa-eye text-sm"></i>
                            <h4 class="font-bold text-sm text-base-content">Fungsi Pengawasan</h4>
                        </div>
                        <p class="text-xs text-base-content/75 leading-relaxed">
                            Mengawasi pelaksanaan AD/ART, GBHK, dan realisasi program kerja yang dijalankan oleh pengurus eksekutif harian.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-base-100 border border-base-300 dark:border-white/5">
                        <div class="flex items-center gap-2 mb-2 text-accent">
                            <i class="fa-solid fa-clipboard-check text-sm"></i>
                            <h4 class="font-bold text-sm text-base-content">Hak &amp; Sidang Evaluasi</h4>
                        </div>
                        <p class="text-xs text-base-content/75 leading-relaxed">
                            Berhak meminta penjelasan berkala, memberi pertimbangan tertulis, serta menyelenggarakan Sidang Evaluasi Paruh Periode.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= LEVEL 3: BADAN PENGURUS INTI (BPI) ================= -->
        <section class="mb-16 sm:mb-20" aria-label="Badan Pengurus Inti">
            <div class="text-center mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-primary block">TINGKAT III &bull; PIMPINAN EKSEKUTIF</span>
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight mt-2">Badan Pengurus Inti (BPI)</h2>
                <p class="text-sm sm:text-base text-base-content/65 max-w-xl mx-auto mt-2">
                    Pemegang mandat eksekutif yang bertanggung jawab atas kepemimpinan harian organisasi (ART Bab V Pasal 14).
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Ketua Umum -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-7 flex flex-col justify-between hover:border-primary/50 transition-colors group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary block mb-1">Pimpinan Tertinggi</span>
                        <h3 class="text-xl font-bold text-base-content">Ketua Umum</h3>
                        <p class="text-xs sm:text-sm text-base-content/75 mt-3 leading-relaxed">
                            Penanggung jawab penuh atas arah kebijakan organisasi, representasi resmi internal &amp; eksternal kampus, dan pemegang amanah MUBES.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-base-300 dark:border-white/10 text-xs text-base-content/60 flex items-center justify-between">
                        <span>Mandat MUBES</span>
                        <i class="fa-solid fa-arrow-right text-primary text-xs"></i>
                    </div>
                </div>

                <!-- Wakil Ketua Umum -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-7 flex flex-col justify-between hover:border-accent/50 transition-colors group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-accent block mb-1">Kemitraan Strategis</span>
                        <h3 class="text-xl font-bold text-base-content">Wakil Ketua</h3>
                        <p class="text-xs sm:text-sm text-base-content/75 mt-3 leading-relaxed">
                            Mengkoordinir pelaksanaan program kerja lintas departemen, pengawasan manajerial internal, dan pelaksana mandat Ketua saat berhalangan.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-base-300 dark:border-white/10 text-xs text-base-content/60 flex items-center justify-between">
                        <span>Koordinasi Internal</span>
                        <i class="fa-solid fa-arrow-right text-accent text-xs"></i>
                    </div>
                </div>

                <!-- Sekretaris Umum -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-7 flex flex-col justify-between hover:border-secondary/50 transition-colors group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-secondary block mb-1">Administrasi &amp; SOP</span>
                        <h3 class="text-xl font-bold text-base-content">Sekretaris</h3>
                        <p class="text-xs sm:text-sm text-base-content/75 mt-3 leading-relaxed">
                            Mengelola Standar Operasional Prosedur (SOP) persuratan resmi, tata kelola kearsipan digital, notulensi rapat, dan ketertiban administrasi.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-base-300 dark:border-white/10 text-xs text-base-content/60 flex items-center justify-between">
                        <span>Kearsipan &amp; Notulensi</span>
                        <i class="fa-solid fa-arrow-right text-secondary text-xs"></i>
                    </div>
                </div>

                <!-- Bendahara Umum -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-7 flex flex-col justify-between hover:border-primary/50 transition-colors group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl mb-5 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary block mb-1">Keuangan &amp; Akuntabilitas</span>
                        <h3 class="text-xl font-bold text-base-content">Bendahara</h3>
                        <p class="text-xs sm:text-sm text-base-content/75 mt-3 leading-relaxed">
                            Mengatur alur kas organisasi, menyusun pembukuan keuangan transparan, pengelolaan iuran mahasiswa, dan inventarisasi aset himpunan.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-base-300 dark:border-white/10 text-xs text-base-content/60 flex items-center justify-between">
                        <span>Transparansi Kas</span>
                        <i class="fa-solid fa-arrow-right text-primary text-xs"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= LEVEL 4: DEPARTEMEN & BIRO OPERASIONAL ================= -->
        <section class="mb-16 sm:mb-20" aria-label="Departemen dan Biro Pelaksana">
            <div class="text-center mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-accent block">TINGKAT IV &bull; PELAKSANA PROGRAM KERJA</span>
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight mt-2">Departemen &amp; Biro Pelaksana</h2>
                <p class="text-sm sm:text-base text-base-content/65 max-w-xl mx-auto mt-2">
                    Garda terdepan yang merancang dan merealisasikan program kerja operasional sesuai Garis-Garis Besar Haluan Kerja (GBHK).
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-8">
                <!-- Departemen 1: Riset & Teknologi -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-8 flex flex-col justify-between hover:border-primary/50 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                                <i class="fa-solid fa-laptop-code"></i>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-base-100 border border-base-300 dark:border-white/10 text-base-content/70">
                                Riset &amp; IT
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-base-content mb-2">Departemen Riset &amp; Teknologi (Ristek)</h3>
                        <p class="text-sm text-base-content/75 leading-relaxed">
                            Pusat eksplorasi keilmuan informatika, riset kecerdasan buatan, rekayasa perangkat lunak, dan inkubasi delegasi mahasiswa dalam kompetisi IT nasional.
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-5">
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Coding Bootcamp</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">AI &amp; Data Study Jam</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Hackathon Squad</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Tech Talk</span>
                        </div>
                    </div>
                </div>

                <!-- Departemen 2: PSDM & Kaderisasi -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-8 flex flex-col justify-between hover:border-accent/50 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center text-xl">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-base-100 border border-base-300 dark:border-white/10 text-base-content/70">
                                Kaderisasi
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-base-content mb-2">Departemen PSDM (Kaderisasi)</h3>
                        <p class="text-sm text-base-content/75 leading-relaxed">
                            Mengelola alur pengaderan himpunan berjenjang (syarat mutlak keanggotaan aktif Pasal 4 ART), pelatihan soft-skill kepemimpinan, dan regenerasi kepengurusan.
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-5">
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Pengaderan Himpunan</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Leadership Camp</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Upgrading Skill</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Family Gathering</span>
                        </div>
                    </div>
                </div>

                <!-- Departemen 3: Media & Informasi -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-8 flex flex-col justify-between hover:border-secondary/50 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center text-xl">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-base-100 border border-base-300 dark:border-white/10 text-base-content/70">
                                Media &amp; Humas
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-base-content mb-2">Departemen Media &amp; Informasi (Medinfo)</h3>
                        <p class="text-sm text-base-content/75 leading-relaxed">
                            Pengelola citra dan branding resmi organisasi, administrasi portal digital website AI UNMUL, publikasi sosial media, serta dokumentasi audio visual kegiatan.
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-5">
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Portal Web Resmi</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Desain &amp; Branding</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Publikasi Konten</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Dokumentasi Arsip</span>
                        </div>
                    </div>
                </div>

                <!-- Departemen 4: Humas & Advokasi -->
                <div class="rounded-3xl bg-base-200/70 brand-card p-8 flex flex-col justify-between hover:border-primary/50 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-base-100 border border-base-300 dark:border-white/10 text-base-content/70">
                                Advokasi
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-base-content mb-2">Departemen Humas &amp; Advokasi</h3>
                        <p class="text-sm text-base-content/75 leading-relaxed">
                            Layanan advokasi aspirasi akademik mahasiswa Informatika, jembatan komunikasi dengan pimpinan fakultas/prodi, jejaring alumni, dan pengabdian masyarakat.
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-5">
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Helpdesk Advokasi</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Kunjungan Industri</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Sinergi Ormawa</span>
                            <span class="text-xs px-3 py-1 rounded-lg bg-base-100 border border-base-300 dark:border-white/5 text-base-content/80 font-medium">Informatika Mengabdi</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Back to Home -->
        <div class="text-center pt-4">
            <a href="{{ route('guest.beranda.index') }}" class="btn btn-ghost hover:bg-base-200 rounded-full px-7 text-sm">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i> Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection