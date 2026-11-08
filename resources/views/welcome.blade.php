@extends('layouts.app')

@section('title', 'KickFlow - Booking Lapangan Futsal Online Real-Time')

@section('content')
<!-- Hero Section (Asymmetric Modern Split) -->
<section class="relative bg-[#070b09] border-b border-[#1b2922] overflow-hidden py-16 lg:py-24">
    <!-- Subtle tactical background grid -->
    <div class="absolute inset-0 tactical-grid pointer-events-none opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Value Prop & Quick Venue Search (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Tactical Pill Badge -->
                <div class="inline-flex items-center gap-2.5 px-3 py-1 rounded-md bg-[#101814] border border-[#24362d] text-xs font-mono text-[#34d399]">
                    <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                    <span>SISTEM RESERVASI RESMI KICKFLOW</span>
                    <span class="text-[#24362d]">|</span>
                    <span class="text-[#94a3b8]">LIVE STATUS</span>
                </div>

                <!-- Main Heading -->
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-[#f8fafc] leading-[1.15]">
                    Pesan Lapangan Futsal Favoritmu <br>
                    <span class="text-[#10b981]">Tanpa Khawatir Bentrok Jadwal.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-[#94a3b8] max-w-xl leading-relaxed">
                    Sistem pemesanan cerdas dengan validasi ketersediaan per menit, proteksi ketat anti-double-booking, dan asisten AI Gemini untuk kemudahan reservasi tim Anda.
                </p>

                <!-- Tactical Quick Action / Direct CTAs -->
                <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <a href="{{ route('fields.index') }}" 
                       class="px-6 py-3.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-sm rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a10 10 0 0 1 10 10"/>
                        </svg>
                        <span>Cari & Pesan Lapangan</span>
                    </a>
                    
                    @guest
                        <a href="{{ route('register') }}" 
                           class="px-5 py-3.5 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#f8fafc] font-semibold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span>Daftar Akun Baru</span>
                        </a>
                    @else
                        <a href="{{ route('bookings.index') }}" 
                           class="px-5 py-3.5 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#cbd5e1] font-semibold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span>Riwayat Pemesanan</span>
                        </a>
                    @endguest
                </div>

                <!-- Trust Points & Specs Strip -->
                <div class="pt-6 border-t border-[#1b2922] grid grid-cols-3 gap-4 text-xs font-mono">
                    <div>
                        <span class="block text-xl font-bold text-[#f8fafc]">5+</span>
                        <span class="text-[#64748b]">Lapangan Berstandar</span>
                    </div>
                    <div>
                        <span class="block text-xl font-bold text-[#10b981]">100%</span>
                        <span class="text-[#64748b]">Anti-Double Booking</span>
                    </div>
                    <div>
                        <span class="block text-xl font-bold text-[#38bdf8]">24/7</span>
                        <span class="text-[#64748b]">AI Booking Assistant</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Match Board & Court Tactical Visual (5 cols) -->
            <div class="lg:col-span-5">
                <div class="bg-[#101814] rounded-2xl border border-[#24362d] p-6 shadow-2xl relative overflow-hidden">
                    
                    <!-- Board Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-[#1b2922]">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                            <span class="font-display font-bold text-sm text-[#f8fafc]">Status Arena Hari Ini</span>
                        </div>
                        <span class="text-[11px] font-mono text-[#34d399] bg-[#10b981]/15 px-2 py-0.5 rounded border border-[#10b981]/30">
                            {{ date('d M Y') }}
                        </span>
                    </div>

                    <!-- Mini Tactical Court Preview Diagram -->
                    <div class="my-5 p-4 bg-[#0b110e] rounded-xl border border-[#1b2922] relative">
                        <div class="h-28 w-full border border-[#10b981]/25 rounded-lg flex items-center justify-center relative overflow-hidden">
                            <!-- Center line & circle -->
                            <div class="absolute inset-y-0 left-1/2 w-px bg-[#10b981]/20"></div>
                            <div class="w-12 h-12 rounded-full border border-[#10b981]/20"></div>
                            <!-- Goal areas -->
                            <div class="absolute inset-y-4 left-0 w-8 border-r border-y border-[#10b981]/20 rounded-r-md"></div>
                            <div class="absolute inset-y-4 right-0 w-8 border-l border-y border-[#10b981]/20 rounded-l-md"></div>

                            <div class="z-10 text-center">
                                <span class="text-[11px] font-mono text-[#34d399] uppercase tracking-wider block font-bold">KICKFLOW ARENA COMPLEX</span>
                                <span class="text-[10px] text-[#64748b]">Indoor Taraflex & Rumput FIFA Standard</span>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Quick Schedule Status -->
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#0b110e] border border-[#1b2922]">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>
                                <span class="font-semibold text-[#f8fafc]">Lapangan Indoor VVIP</span>
                            </div>
                            <span class="text-[11px] text-[#10b981] font-mono">Slot Siang Kosong</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#0b110e] border border-[#1b2922]">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>
                                <span class="font-semibold text-[#f8fafc]">Lapangan Sintetis Outdoor</span>
                            </div>
                            <span class="text-[11px] text-[#10b981] font-mono">Tersedia Sore & Malam</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#0b110e] border border-[#1b2922]">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#38bdf8]"></span>
                                <span class="font-semibold text-[#f8fafc]">Garuda Sport Arena</span>
                            </div>
                            <span class="text-[11px] text-[#38bdf8] font-mono">Konfirmasi Cepat AI</span>
                        </div>
                    </div>

                    <!-- Action Link -->
                    <div class="mt-5 pt-4 border-t border-[#1b2922]">
                        <a href="{{ route('fields.index') }}" 
                           class="w-full py-2.5 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#34d399] font-medium text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5">
                            <span>Periksa Ketersediaan Semua Lapangan</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Featured Fields Section (Diverse Layout - Dominant + Grid) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 pb-4 border-b border-[#1b2922]">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold">
                <span>[01]</span>
                <span>PILIHAN ARENA TERBAIK</span>
            </div>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#f8fafc] mt-1">
                Katalog Lapangan Futsal
            </h2>
        </div>
        <a href="{{ route('fields.index') }}" 
           class="mt-3 md:mt-0 text-xs font-semibold text-[#10b981] hover:text-[#34d399] flex items-center gap-1.5 transition-colors">
            <span>Lihat Seluruh Lapangan</span>
            <span aria-hidden="true">→</span>
        </a>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fields as $index => $field)
            <div class="bg-[#101814] rounded-xl border border-[#24362d] hover:border-[#10b981]/50 transition-all duration-150 flex flex-col group overflow-hidden">
                
                <!-- Card Visual Header -->
                <div class="h-44 bg-[#0b110e] border-b border-[#1b2922] relative flex items-center justify-center p-4">
                    <!-- Tactical pitch lines SVG graphic -->
                    <div class="w-full h-full border border-[#24362d] rounded-lg flex items-center justify-center relative bg-[#070b09]/60">
                        <div class="absolute inset-y-0 left-1/2 w-px bg-[#24362d]"></div>
                        <div class="w-14 h-14 rounded-full border border-[#24362d] flex items-center justify-center text-[#4a6b5c]">
                            <svg class="w-6 h-6 group-hover:text-[#10b981] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m4.93 4.93 4.24 4.24"/>
                                <path d="m14.83 9.17 4.24-4.24"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Type Badge -->
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30' : 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30' }}">
                        {{ $field->type }}
                    </span>

                    <!-- Availability Badge -->
                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                        Tersedia
                    </span>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-display text-lg font-bold text-[#f8fafc] group-hover:text-[#34d399] transition-colors">
                            {{ $field->name }}
                        </h3>
                        <p class="mt-1.5 text-xs text-[#94a3b8] line-clamp-2 leading-relaxed">
                            {{ $field->description ?? 'Lapangan berstandar turnamen resmi dengan permukaan berkualitas prima, pencahayaan LED sorot profesional, dan sirkulasi optimal.' }}
                        </p>
                    </div>

                    <!-- Specs Pills -->
                    <div class="flex items-center gap-2 text-[11px] text-[#64748b] font-mono">
                        <span class="px-2 py-0.5 rounded bg-[#15201a] border border-[#24362d]">Futsal Standar</span>
                        <span class="px-2 py-0.5 rounded bg-[#15201a] border border-[#24362d]">Lampu LED</span>
                    </div>

                    <!-- Price & CTA -->
                    <div class="pt-3 border-t border-[#1b2922] flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-[#64748b] uppercase tracking-wider block">Tarif Sewa</span>
                            <span class="font-mono text-base font-bold text-[#34d399]">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-[#64748b]">/jam</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('fields.show', $field) }}" 
                               class="px-3 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] font-medium text-xs rounded-lg transition-colors">
                                Detail
                            </a>
                            <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}" 
                               class="px-3.5 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors shadow-sm">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-[#94a3b8] bg-[#101814] rounded-xl border border-[#24362d]">
                Belum ada data lapangan futsal yang terdaftar saat ini.
            </div>
        @endforelse
    </div>
</section>

<!-- Why KickFlow Bento Section (Varied Card Hierarchy) -->
<section class="bg-[#0b110e] border-y border-[#1b2922] py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-2xl mb-12">
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold">
                <span>[02]</span>
                <span>KEUNGGULAN SISTEM</span>
            </div>
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#f8fafc] mt-1">
                Dirancang untuk Pertandingan Futsal Tanpa Masalah
            </h2>
            <p class="mt-2 text-sm text-[#94a3b8]">
                Setiap komponen arsitektur KickFlow didedikasikan agar reservasi jadwal berjalan lancar tanpa kendala teknis.
            </p>
        </div>

        <!-- Bento Grid with 1 dominant cell + 3 supportive cells -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Cell 1 (Dominant 2 cols on md) -->
            <div class="md:col-span-2 bg-[#101814] rounded-2xl border border-[#24362d] p-8 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#15201a] border border-[#24362d] text-[#10b981] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-[#34d399] uppercase">Proteksi Ketat Database</span>
                    <h3 class="font-display text-xl sm:text-2xl font-bold text-[#f8fafc] mt-1">
                        Garansi 100% Bebas Jadwal Bentrok (Anti Double-Booking)
                    </h3>
                    <p class="mt-3 text-sm text-[#94a3b8] leading-relaxed max-w-xl">
                        Kami menerapkan constraint database relasional tingkat atomik. Tidak ada celah bagi dua tim untuk memesan lapangan yang sama di jam yang identik, memberi rasa tenang bagi manajer tim dan pengelola venue.
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-[#1b2922] flex flex-wrap gap-4 text-xs font-mono text-[#64748b]">
                    <span class="flex items-center gap-1.5 text-[#34d399]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Unique Compound Key
                    </span>
                    <span class="flex items-center gap-1.5 text-[#34d399]">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Verifikasi Transaksional
                    </span>
                </div>
            </div>

            <!-- Cell 2: Gemini AI Integration -->
            <div class="bg-[#101814] rounded-2xl border border-[#24362d] p-8 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#15201a] border border-[#24362d] text-[#38bdf8] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="16" height="12" x="4" y="8" rx="2"/>
                            <path d="M12 8V4H8"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-[#38bdf8] uppercase">Gemini 2.0 Flash</span>
                    <h3 class="font-display text-lg font-bold text-[#f8fafc] mt-1">
                        Asisten AI Booking
                    </h3>
                    <p class="mt-2 text-xs text-[#94a3b8] leading-relaxed">
                        Cukup ketik permintaan seperti <em>"Pesan lapangan Garuda besok jam 19"</em>, AI langsung memproses reservasi secara otomatis.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#1b2922]">
                    <span class="text-[11px] font-mono text-[#38bdf8]">Function Calling Active</span>
                </div>
            </div>

            <!-- Cell 3: Kasir & POS Cetak Struk -->
            <div class="bg-[#101814] rounded-2xl border border-[#24362d] p-8 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#15201a] border border-[#24362d] text-[#f59e0b] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2h12a2 2 0 0 1 2 2v18l-4-2-4 2-4-2-4 2V4a2 2 0 0 1 2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-[#f59e0b] uppercase">Point of Sale</span>
                    <h3 class="font-display text-lg font-bold text-[#f8fafc] mt-1">
                        Kasir & Struk Nota Resmi
                    </h3>
                    <p class="mt-2 text-xs text-[#94a3b8] leading-relaxed">
                        Pengelola arena dapat memverifikasi pembayaran kasir di tempat dan mencetak struk nota resmi dalam satu klik.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#1b2922]">
                    <span class="text-[11px] font-mono text-[#f59e0b]">Thermal Print Ready</span>
                </div>
            </div>

            <!-- Cell 4 (2 cols): Arena Standar Turnamen -->
            <div class="md:col-span-2 bg-[#101814] rounded-2xl border border-[#24362d] p-8 flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#15201a] border border-[#24362d] text-[#10b981] flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-mono text-[#34d399] uppercase">Fasilitas Arena Unggulan</span>
                    <h3 class="font-display text-xl font-bold text-[#f8fafc] mt-1">
                        Permukaan Vinyl Taraflex & Rumput Monofilament
                    </h3>
                    <p class="mt-2 text-sm text-[#94a3b8] leading-relaxed max-w-xl">
                        Setiap lapangan dirawat secara teratur dengan spesifikasi tinggi, meminimalkan risiko cedera dan memastikan pantulan bola sesuai standar liga resmi.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-[#1b2922] flex items-center gap-4 text-xs font-mono text-[#64748b]">
                    <span>LED Sorot 1000W</span>
                    <span>•</span>
                    <span>Kamar Mandi Bilas</span>
                    <span>•</span>
                    <span>Area Parkir Luas</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Simple 3-Step Process (Alur Pemesanan) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <div class="text-center max-w-xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold">
            <span>[03]</span>
            <span>ALUR PEMESANAN</span>
        </div>
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#f8fafc] mt-1">
            Cara Reservasi dalam 3 Langkah
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#101814] p-6 rounded-xl border border-[#24362d] relative">
            <span class="font-mono text-2xl font-bold text-[#10b981] mb-2 block">01</span>
            <h4 class="font-display font-bold text-base text-[#f8fafc] mb-2">Pilih Lapangan</h4>
            <p class="text-xs text-[#94a3b8] leading-relaxed">
                Tentukan tipe lapangan (indoor vinyl atau outdoor sintetis) sesuai kebutuhan tim futsal Anda.
            </p>
        </div>

        <div class="bg-[#101814] p-6 rounded-xl border border-[#24362d] relative">
            <span class="font-mono text-2xl font-bold text-[#10b981] mb-2 block">02</span>
            <h4 class="font-display font-bold text-base text-[#f8fafc] mb-2">Tentukan Jadwal Main</h4>
            <p class="text-xs text-[#94a3b8] leading-relaxed">
                Pilih tanggal dan jam bermain yang kosong. Sistem secara instan mengunci slot tersebut.
            </p>
        </div>

        <div class="bg-[#101814] p-6 rounded-xl border border-[#24362d] relative">
            <span class="font-mono text-2xl font-bold text-[#10b981] mb-2 block">03</span>
            <h4 class="font-display font-bold text-base text-[#f8fafc] mb-2">Konfirmasi & Tanding</h4>
            <p class="text-xs text-[#94a3b8] leading-relaxed">
                Dapatkan nota tiket resmi booking Anda dan bawa tim untuk bertanding tepat waktu di arena.
            </p>
        </div>
    </div>
</section>

<!-- Final Call to Action -->
<section class="bg-[#0b110e] border-t border-[#1b2922] py-14">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-[#f8fafc]">
            Siap Bertanding Minggu Ini?
        </h2>
        <p class="text-sm text-[#94a3b8] max-w-lg mx-auto">
            Amankan jadwal bermain tim Anda sekarang sebelum jam favorit terisi oleh tim lain.
        </p>
        <div class="pt-2">
            <a href="{{ route('fields.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-sm rounded-lg transition-colors shadow-sm">
                <span>Pilih Lapangan & Booking Sekarang</span>
                <span>→</span>
            </a>
        </div>
    </div>
</section>
@endsection
