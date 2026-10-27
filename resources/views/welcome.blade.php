@extends('layouts.app')

@section('title', 'KickFlow - Booking Lapangan Futsal Online Real-Time')

@section('content')
<!-- Hero Section (Catppuccin Mantle to Base) -->
<section class="relative bg-gradient-to-b from-[#181825] via-[#1e1e2e] to-[#11111b] text-[#cdd6f4] overflow-hidden py-20 lg:py-28 border-b border-[#313244]">
    <!-- Background soccer ball graphic accent with Catppuccin tint -->
    <div class="absolute inset-0 opacity-5 pointer-events-none flex items-center justify-center">
        <svg class="w-[800px] h-[800px] text-[#a6e3a1]" fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Catppuccin Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#313244]/80 backdrop-blur-md border border-[#45475a] text-[#a6e3a1] text-xs sm:text-sm font-medium mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-[#a6e3a1] animate-pulse"></span>
            Sistem Pemesanan Lapangan Futsal Real-Time • Catppuccin Aesthetic
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight text-[#cdd6f4]">
            Pesan Lapangan Futsal Favoritmu <br class="hidden sm:inline">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#a6e3a1] via-[#94e2d5] to-[#89dceb]">
                Tanpa Khawatir Bentrok Jadwal
            </span>
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-[#a6adc8] max-w-2xl mx-auto leading-relaxed">
            Booking lapangan futsal indoor & outdoor secara instan dengan verifikasi ketersediaan real-time dan asisten AI pintar bertenaga Google Gemini.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('fields.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold rounded-xl shadow-lg shadow-[#a6e3a1]/20 hover:scale-105 transition duration-150">
                ⚽ Cari & Pesan Lapangan
            </a>
            @guest
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] font-semibold rounded-xl backdrop-blur-sm transition duration-150">
                    Daftar Akun Baru
                </a>
            @else
                <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] font-semibold rounded-xl backdrop-blur-sm transition duration-150">
                    📋 Riwayat Pemesanan Saya
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Stats Bar (Catppuccin Mantle) -->
<section class="relative -mt-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
    <div class="bg-[#181825] rounded-3xl shadow-xl border border-[#313244] grid grid-cols-2 md:grid-cols-4 gap-6 p-6 sm:p-8">
        <div class="text-center">
            <div class="text-3xl font-extrabold text-[#a6e3a1]">5+</div>
            <div class="text-xs sm:text-sm font-medium text-[#a6adc8] mt-1">Lapangan Berstandar</div>
        </div>
        <div class="text-center border-l border-[#313244]">
            <div class="text-3xl font-extrabold text-[#94e2d5]">100%</div>
            <div class="text-xs sm:text-sm font-medium text-[#a6adc8] mt-1">Anti Double-Booking</div>
        </div>
        <div class="text-center border-l border-[#313244]">
            <div class="text-3xl font-extrabold text-[#cba6f7]">24/7</div>
            <div class="text-xs sm:text-sm font-medium text-[#a6adc8] mt-1">Asisten AI Chatbot</div>
        </div>
        <div class="text-center border-l border-[#313244]">
            <div class="text-3xl font-extrabold text-[#fab387]">Instan</div>
            <div class="text-xs sm:text-sm font-medium text-[#a6adc8] mt-1">Konfirmasi Cepat</div>
        </div>
    </div>
</section>

<!-- Featured Fields Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <h2 class="text-xs uppercase tracking-widest font-bold text-[#a6e3a1]">Pilihan Terbaik</h2>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#cdd6f4] mt-1">Lapangan Futsal Tersedia</p>
        </div>
        <a href="{{ route('fields.index') }}" class="mt-4 md:mt-0 text-sm font-semibold text-[#a6e3a1] hover:text-[#94e2d5] flex items-center gap-1 group">
            Lihat Semua Lapangan
            <span class="group-hover:translate-x-1 transition duration-150">→</span>
        </a>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($fields as $field)
            <div class="bg-[#181825] rounded-3xl border border-[#313244] overflow-hidden shadow-sm hover:shadow-xl hover:border-[#45475a] transition duration-300 flex flex-col group">
                <!-- Card Header -->
                <div class="h-48 bg-gradient-to-tr from-[#11111b] to-[#313244] relative flex items-center justify-center overflow-hidden border-b border-[#313244]">
                    <svg class="w-20 h-20 text-[#45475a] group-hover:scale-110 group-hover:text-[#a6e3a1]/30 transition duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                    </svg>
                    
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-[#89b4fa]/20 text-[#89b4fa] border border-[#89b4fa]/30' : 'bg-[#fab387]/20 text-[#fab387] border border-[#fab387]/30' }}">
                        {{ strtoupper($field->type) }}
                    </span>

                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30 backdrop-blur-sm">
                        Aktif
                    </span>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-[#cdd6f4] group-hover:text-[#a6e3a1] transition">
                            {{ $field->name }}
                        </h3>
                        <p class="mt-2 text-sm text-[#a6adc8] line-clamp-2">
                            {{ $field->description ?? 'Lapangan futsal berkualitas dengan rumput sintetis standar kompetisi dan fasilitas pencahayaan LED profesional.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#313244] flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#6c7086] block">Tarif sewa</span>
                            <span class="text-lg font-extrabold text-[#a6e3a1]">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-[#6c7086]">/ jam</span>
                        </div>

                        <a href="{{ route('fields.show', $field) }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#313244] text-[#cdd6f4] hover:bg-[#a6e3a1] hover:text-[#11111b] font-semibold text-xs rounded-xl transition duration-150">
                            Lihat & Booking
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-[#a6adc8]">
                Belum ada data lapangan yang terdaftar.
            </div>
        @endforelse
    </div>
</section>

<!-- How it Works (Catppuccin Mantle Section) -->
<section class="bg-[#181825] py-16 sm:py-24 border-y border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs uppercase tracking-widest font-bold text-[#a6e3a1]">Langkah Mudah</h2>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#cdd6f4] mt-1">Cara Melakukan Pemesanan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="bg-[#1e1e2e] p-6 rounded-3xl shadow-sm border border-[#313244] flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30 flex items-center justify-center text-2xl font-bold mb-4">
                    1
                </div>
                <h4 class="font-bold text-[#cdd6f4] mb-2">Pilih Lapangan</h4>
                <p class="text-xs text-[#a6adc8]">Pilih lapangan indoor atau outdoor sesuai kenyamanan tim Anda.</p>
            </div>

            <div class="bg-[#1e1e2e] p-6 rounded-3xl shadow-sm border border-[#313244] flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-[#94e2d5]/15 text-[#94e2d5] border border-[#94e2d5]/30 flex items-center justify-center text-2xl font-bold mb-4">
                    2
                </div>
                <h4 class="font-bold text-[#cdd6f4] mb-2">Tentukan Jadwal</h4>
                <p class="text-xs text-[#a6adc8]">Pilih tanggal dan slot jam main yang masih kosong secara real-time.</p>
            </div>

            <div class="bg-[#1e1e2e] p-6 rounded-3xl shadow-sm border border-[#313244] flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-[#cba6f7]/15 text-[#cba6f7] border border-[#cba6f7]/30 flex items-center justify-center text-2xl font-bold mb-4">
                    3
                </div>
                <h4 class="font-bold text-[#cdd6f4] mb-2">Konfirmasi Booking</h4>
                <p class="text-xs text-[#a6adc8]">Isi formulir dan sistem otomatis mengunci jadwal Anda dari bentrok.</p>
            </div>

            <div class="bg-[#1e1e2e] p-6 rounded-3xl shadow-sm border border-[#313244] flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-[#fab387]/15 text-[#fab387] border border-[#fab387]/30 flex items-center justify-center text-2xl font-bold mb-4">
                    4
                </div>
                <h4 class="font-bold text-[#cdd6f4] mb-2">Kick-Off Main!</h4>
                <p class="text-xs text-[#a6adc8]">Datang ke lapangan dan nikmati pertandingan seru bersama tim!</p>
            </div>
        </div>
    </div>
</section>

<!-- AI Assistant Highlight Callout (Catppuccin Card) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-gradient-to-r from-[#181825] via-[#313244] to-[#181825] rounded-3xl p-8 sm:p-12 text-[#cdd6f4] border border-[#45475a] shadow-2xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-[#a6e3a1]/20 text-[#a6e3a1] border border-[#a6e3a1]/30 text-xs font-bold rounded-full mb-4">
                🤖 FITUR PINTAR ASISTEN AI
            </span>
            <h2 class="text-3xl font-extrabold tracking-tight mb-4 text-[#cdd6f4]">
                Punya Pertanyaan? Tanya KickFlow Assistant!
            </h2>
            <p class="text-[#bac2de] text-sm sm:text-base leading-relaxed mb-8">
                Asisten kecerdasan buatan kami siap mengecek jadwal lapangan kosong secara langsung dari database, memberikan rincian harga, hingga memandu proses pemesanan. Coba klik tombol chat di pojok kanan bawah!
            </p>
            <div class="flex flex-wrap gap-3">
                <div class="px-4 py-2 bg-[#181825] rounded-xl text-xs border border-[#45475a] text-[#a6e3a1]">
                    💡 "Cek ketersediaan besok"
                </div>
                <div class="px-4 py-2 bg-[#181825] rounded-xl text-xs border border-[#45475a] text-[#94e2d5]">
                    💡 "Daftar lapangan indoor"
                </div>
                <div class="px-4 py-2 bg-[#181825] rounded-xl text-xs border border-[#45475a] text-[#fab387]">
                    💡 "Berapa tarif per jam?"
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
