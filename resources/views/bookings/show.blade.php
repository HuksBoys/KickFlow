@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) . ' - KickFlow')

@section('content')
<div class="bg-gray-100 py-6 border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-medium text-gray-500 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-green-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('bookings.index') }}" class="hover:text-green-600">Riwayat Pemesanan</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">#KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </nav>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-800 to-emerald-700 text-white p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-green-200 font-bold block mb-1">Tiket Pemesanan Resmi</span>
                <h1 class="text-2xl font-extrabold tracking-tight">Booking #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-xs text-green-100 mt-1">Dibuat pada {{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div>
                @if($booking->status === 'pending')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-400 text-amber-950 inline-block shadow-sm">
                        ⏳ Menunggu Konfirmasi
                    </span>
                @elseif($booking->status === 'confirmed')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-green-800 inline-block shadow-sm">
                        ✅ Terkonfirmasi
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-red-500 text-white inline-block shadow-sm">
                        ❌ Dibatalkan
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-500 text-white inline-block shadow-sm">
                        🏆 Selesai
                    </span>
                @endif
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="p-6 sm:p-8 space-y-8">
            <!-- Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-gray-100">
                <div>
                    <h3 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-2">Informasi Pemesan</h3>
                    <p class="text-base font-bold text-gray-900">{{ $booking->user->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->user->email ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->user->phone ?? 'Tidak ada nomor telepon' }}</p>
                </div>

                <div>
                    <h3 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-2">Informasi Lapangan</h3>
                    <p class="text-base font-bold text-green-700">{{ $booking->field->name ?? '-' }}</p>
                    <p class="text-sm text-gray-500 capitalize">Tipe: {{ $booking->field->type ?? '-' }}</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }} / jam</p>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <h3 class="text-xs uppercase font-bold text-gray-500 tracking-wider mb-4">Waktu Pertandingan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <span class="text-xs text-gray-400 block mb-1">Tanggal Main</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <span class="text-xs text-gray-400 block mb-1">Jam Kick-Off</span>
                        <span class="text-sm font-bold text-green-700">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                        <span class="text-xs text-gray-400 block mb-1">Durasi Sewa</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $booking->duration_hours }} Jam
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($booking->notes)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-sm">
                    <span class="font-bold text-amber-900 block mb-1">Catatan Tambahan:</span>
                    <p class="text-amber-800">{{ $booking->notes }}</p>
                </div>
            @endif

            <!-- Price Breakdown -->
            <div class="border-t border-gray-100 pt-6">
                <div class="flex justify-between items-center text-sm text-gray-600 mb-2">
                    <span>Biaya Sewa ({{ $booking->duration_hours }} jam x Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }})</span>
                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                    <span>Biaya Layanan Platform</span>
                    <span class="text-green-600 font-semibold">Gratis</span>
                </div>
                <div class="flex justify-between items-center text-lg font-extrabold text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total Pembayaran</span>
                    <span class="text-2xl text-green-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl text-center transition">
                    ← Kembali ke Riwayat
                </a>

                @if($booking->status === 'pending')
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-xl transition">
                            Batalkan Pemesanan Ini
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
