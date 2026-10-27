@extends('layouts.app')

@section('title', 'KickFlow - Booking Lapangan Futsal Online Real-Time')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-green-900 via-green-800 to-gray-900 text-white overflow-hidden py-20 lg:py-28">
    <!-- Background soccer ball graphic accent -->
    <div class="absolute inset-0 opacity-10 pointer-events-none flex items-center justify-center">
        <svg class="w-[800px] h-[800px]" fill="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-green-300 text-xs sm:text-sm font-medium mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Sistem Pemesanan Lapangan Futsal Modern & Real-Time
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight">
            Pesan Lapangan Futsal Favoritmu <br class="hidden sm:inline">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-300">Tanpa Khawatir Bentrok Jadwal</span>
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
            Booking lapangan futsal indoor & outdoor secara instan dengan verifikasi ketersediaan real-time dan asisten AI pintar yang siap melayani 24/7.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('fields.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 hover:scale-105 transition duration-150">
                ⚽ Cari & Pesan Lapangan
            </a>
            @guest
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold rounded-xl backdrop-blur-sm transition duration-150">
                    Daftar Akun Baru
                </a>
            @else
                <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold rounded-xl backdrop-blur-sm transition duration-150">
                    📋 Riwayat Pemesanan Saya
                </a>
            @endguest
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="relative -mt-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 grid grid-cols-2 md:grid-cols-4 gap-6 p-6 sm:p-8">
        <div class="text-center">
            <div class="text-3xl font-extrabold text-green-600">5+</div>
            <div class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Lapangan Berstandar</div>
        </div>
        <div class="text-center border-l border-gray-100">
            <div class="text-3xl font-extrabold text-green-600">100%</div>
            <div class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Anti Double-Booking</div>
        </div>
        <div class="text-center border-l border-gray-100">
            <div class="text-3xl font-extrabold text-green-600">24/7</div>
            <div class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Asisten AI Chatbot</div>
        </div>
        <div class="text-center border-l border-gray-100">
            <div class="text-3xl font-extrabold text-green-600">Instan</div>
            <div class="text-xs sm:text-sm font-medium text-gray-500 mt-1">Konfirmasi Cepat</div>
        </div>
    </div>
</section>

<!-- Featured Fields Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
        <div>
            <h2 class="text-xs uppercase tracking-widest font-bold text-green-600">Pilihan Terbaik</h2>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">Lapangan Futsal Tersedia</p>
        </div>
        <a href="{{ route('fields.index') }}" class="mt-4 md:mt-0 text-sm font-semibold text-green-600 hover:text-green-700 flex items-center gap-1 group">
            Lihat Semua Lapangan
            <span class="group-hover:translate-x-1 transition duration-150">→</span>
        </a>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($fields as $field)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl transition duration-300 flex flex-col group">
                <!-- Card Header / Image placeholder -->
                <div class="h-48 bg-gradient-to-tr from-green-800 to-emerald-600 relative flex items-center justify-center overflow-hidden">
                    <svg class="w-20 h-20 text-white/20 group-hover:scale-110 transition duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                    </svg>
                    
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white' }}">
                        {{ strtoupper($field->type) }}
                    </span>

                    <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold bg-white/90 text-green-800 backdrop-blur-sm">
                        Aktif
                    </span>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition">
                            {{ $field->name }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                            {{ $field->description ?? 'Lapangan futsal berkualitas dengan rumput sintetis standar kompetisi dan fasilitas pencahayaan LED profesional.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 block">Tarif sewa</span>
                            <span class="text-lg font-extrabold text-green-600">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-gray-400">/ jam</span>
                        </div>

                        <a href="{{ route('fields.show', $field) }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-semibold text-xs rounded-xl transition duration-150">
                            Lihat & Booking
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Belum ada data lapangan yang terdaftar.
            </div>
        @endforelse
    </div>
</section>

<!-- How it Works -->
<section class="bg-gray-100 py-16 sm:py-24 border-y border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-xs uppercase tracking-widest font-bold text-green-600">Langkah Mudah</h2>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">Cara Melakukan Pemesanan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl font-bold mb-4">
                    1
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Pilih Lapangan</h4>
                <p class="text-xs text-gray-500">Pilih lapangan indoor atau outdoor sesuai kenyamanan tim Anda.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl font-bold mb-4">
                    2
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Tentukan Jadwal</h4>
                <p class="text-xs text-gray-500">Pilih tanggal dan slot jam main yang masih kosong secara real-time.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl font-bold mb-4">
                    3
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Konfirmasi Booking</h4>
                <p class="text-xs text-gray-500">Isi formulir dan sistem otomatis mengunci jadwal Anda dari bentrok.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-2xl font-bold mb-4">
                    4
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Kick-Off Main!</h4>
                <p class="text-xs text-gray-500">Datang ke lapangan dan nikmati pertandingan seru bersama tim!</p>
            </div>
        </div>
    </div>
</section>

<!-- AI Assistant Highlight Callout -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-gradient-to-r from-emerald-800 to-green-900 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-green-200 text-xs font-bold rounded-full mb-4">
                🤖 FITUR PINTAR BARU
            </span>
            <h2 class="text-3xl font-extrabold tracking-tight mb-4">
                Punya Pertanyaan? Tanya KickFlow Assistant!
            </h2>
            <p class="text-green-100 text-sm sm:text-base leading-relaxed mb-8">
                Asisten kecerdasan buatan kami siap mengecek jadwal lapangan kosong secara langsung dari database, memberikan rincian harga, hingga memandu proses pemesanan. Coba klik tombol chat di pojok kanan bawah!
            </p>
            <div class="flex flex-wrap gap-3">
                <div class="px-4 py-2 bg-white/10 rounded-xl text-xs backdrop-blur-sm border border-white/20">
                    💡 "Cek ketersediaan besok"
                </div>
                <div class="px-4 py-2 bg-white/10 rounded-xl text-xs backdrop-blur-sm border border-white/20">
                    💡 "Daftar lapangan indoor"
                </div>
                <div class="px-4 py-2 bg-white/10 rounded-xl text-xs backdrop-blur-sm border border-white/20">
                    💡 "Berapa tarif per jam?"
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
