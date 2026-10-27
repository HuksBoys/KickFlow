@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) . ' - KickFlow')

@section('content')
<div class="bg-[#181825] py-5 border-b border-[#313244]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-medium text-[#a6adc8] gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-[#a6e3a1] transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('bookings.index') }}" class="hover:text-[#a6e3a1] transition">Riwayat Pemesanan</a>
            <span>/</span>
            <span class="text-[#cdd6f4] font-semibold">#KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </nav>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden text-[#cdd6f4]">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#11111b] to-[#181825] border-b border-[#313244] p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-widest text-[#a6e3a1] font-bold block mb-1">Tiket Pemesanan Resmi</span>
                <h1 class="text-2xl font-extrabold tracking-tight">Booking #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-xs text-[#a6adc8] mt-1">Dibuat pada {{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div>
                @if($booking->status === 'pending')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30 inline-block shadow-sm">
                        ⏳ Menunggu Konfirmasi
                    </span>
                @elseif($booking->status === 'confirmed')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30 inline-block shadow-sm">
                        ✅ Terkonfirmasi
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30 inline-block shadow-sm">
                        ❌ Dibatalkan
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-4 py-2 rounded-xl text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30 inline-block shadow-sm">
                        🏆 Selesai
                    </span>
                @endif
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="p-6 sm:p-8 space-y-8">
            <!-- Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#313244]">
                <div>
                    <h3 class="text-xs uppercase font-bold text-[#6c7086] tracking-wider mb-2">Informasi Pemesan</h3>
                    <p class="text-base font-bold text-[#cdd6f4]">{{ $booking->user->name ?? '-' }}</p>
                    <p class="text-sm text-[#a6adc8]">{{ $booking->user->email ?? '-' }}</p>
                    <p class="text-sm text-[#a6adc8]">{{ $booking->user->phone ?? 'Tidak ada nomor telepon' }}</p>
                </div>

                <div>
                    <h3 class="text-xs uppercase font-bold text-[#6c7086] tracking-wider mb-2">Informasi Lapangan</h3>
                    <p class="text-base font-bold text-[#a6e3a1]">{{ $booking->field->name ?? '-' }}</p>
                    <p class="text-sm text-[#a6adc8] capitalize">Tipe: {{ $booking->field->type ?? '-' }}</p>
                    <p class="text-sm text-[#a6adc8]">Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }} / jam</p>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-[#1e1e2e] rounded-3xl p-6 border border-[#313244]">
                <h3 class="text-xs uppercase font-bold text-[#a6adc8] tracking-wider mb-4">Waktu Pertandingan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    <div class="bg-[#313244] p-4 rounded-2xl border border-[#45475a]">
                        <span class="text-xs text-[#a6adc8] block mb-1">Tanggal Main</span>
                        <span class="text-sm font-bold text-[#cdd6f4]">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <div class="bg-[#313244] p-4 rounded-2xl border border-[#45475a]">
                        <span class="text-xs text-[#a6adc8] block mb-1">Jam Kick-Off</span>
                        <span class="text-sm font-bold text-[#a6e3a1]">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </span>
                    </div>

                    <div class="bg-[#313244] p-4 rounded-2xl border border-[#45475a]">
                        <span class="text-xs text-[#a6adc8] block mb-1">Durasi Sewa</span>
                        <span class="text-sm font-bold text-[#cdd6f4]">
                            {{ $booking->duration_hours }} Jam
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($booking->notes)
                <div class="p-4 rounded-2xl bg-[#f9e2af]/10 border border-[#f9e2af]/25 text-sm">
                    <span class="font-bold text-[#f9e2af] block mb-1">Catatan Tambahan:</span>
                    <p class="text-[#cdd6f4]">{{ $booking->notes }}</p>
                </div>
            @endif

            <!-- Price Breakdown -->
            <div class="border-t border-[#313244] pt-6">
                <div class="flex justify-between items-center text-sm text-[#a6adc8] mb-2">
                    <span>Biaya Sewa ({{ $booking->duration_hours }} jam x Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }})</span>
                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-[#a6adc8] mb-4">
                    <span>Biaya Layanan Platform</span>
                    <span class="text-[#a6e3a1] font-semibold">Gratis</span>
                </div>
                <div class="flex justify-between items-center text-lg font-extrabold text-[#cdd6f4] pt-3 border-t border-[#313244]">
                    <span>Total Pembayaran</span>
                    <span class="text-2xl text-[#a6e3a1]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-[#313244]">
                <a href="{{ route('bookings.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-[#313244] hover:bg-[#45475a] text-[#cdd6f4] text-sm font-semibold rounded-xl text-center transition">
                    ← Kembali ke Riwayat
                </a>

                @if($booking->status === 'pending')
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#f38ba8]/15 hover:bg-[#f38ba8]/25 text-[#f38ba8] border border-[#f38ba8]/30 text-sm font-semibold rounded-xl transition">
                            Batalkan Pemesanan Ini
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
