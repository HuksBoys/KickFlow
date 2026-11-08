@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) . ' - KickFlow')

@section('content')
<!-- Breadcrumbs -->
<div class="bg-[#0b110e] py-3.5 border-b border-[#1b2922]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-mono text-[#64748b] gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-[#10b981] transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('bookings.index') }}" class="hover:text-[#10b981] transition-colors">Riwayat Pemesanan</a>
            <span>/</span>
            <span class="text-[#f8fafc] font-semibold">#KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </nav>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        
        <!-- Header / Ticket Bar -->
        <div class="bg-[#0b110e] border-b border-[#1b2922] p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-widest text-[#10b981] font-bold block mb-1">
                    TIKET RESERVASI RESMI KICKFLOW
                </span>
                <h1 class="font-display text-2xl font-bold tracking-tight">
                    Booking #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-xs font-mono text-[#64748b] mt-0.5">Dibuat pada {{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
            </div>

            <div>
                @if($booking->status === 'pending')
                    <span class="px-3.5 py-1.5 rounded-md text-xs font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30 inline-block">
                        ⏳ Menunggu Konfirmasi
                    </span>
                @elseif($booking->status === 'confirmed')
                    <span class="px-3.5 py-1.5 rounded-md text-xs font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30 inline-block">
                        ✅ Terkonfirmasi
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-3.5 py-1.5 rounded-md text-xs font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30 inline-block">
                        ❌ Dibatalkan
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-3.5 py-1.5 rounded-md text-xs font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30 inline-block">
                        🏆 Selesai
                    </span>
                @endif
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="p-6 sm:p-8 space-y-6">
            <!-- Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#1b2922]">
                <div>
                    <h3 class="text-xs font-mono uppercase font-bold text-[#64748b] tracking-wider mb-2">Informasi Pemesan</h3>
                    <p class="text-base font-display font-bold text-[#f8fafc]">{{ $booking->user->name ?? '-' }}</p>
                    <p class="text-xs text-[#94a3b8]">{{ $booking->user->email ?? '-' }}</p>
                    <p class="text-xs text-[#94a3b8] font-mono mt-0.5">{{ $booking->user->phone ?? 'Tidak ada nomor telepon' }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-mono uppercase font-bold text-[#64748b] tracking-wider mb-2">Informasi Lapangan</h3>
                    <p class="text-base font-display font-bold text-[#34d399]">{{ $booking->field->name ?? '-' }}</p>
                    <p class="text-xs text-[#94a3b8] capitalize">Tipe: {{ $booking->field->type ?? '-' }}</p>
                    <p class="text-xs font-mono text-[#94a3b8] mt-0.5">Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }} / jam</p>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-[#0b110e] rounded-lg p-5 border border-[#1b2922]">
                <h3 class="text-xs font-mono uppercase font-bold text-[#10b981] tracking-wider mb-3">Waktu Pertandingan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                    <div class="bg-[#15201a] p-3 rounded-lg border border-[#24362d]">
                        <span class="text-[10px] font-mono text-[#64748b] block mb-1">Tanggal Main</span>
                        <span class="text-xs font-bold text-[#f8fafc]">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <div class="bg-[#15201a] p-3 rounded-lg border border-[#24362d]">
                        <span class="text-[10px] font-mono text-[#64748b] block mb-1">Jam Kick-Off</span>
                        <span class="text-xs font-mono font-bold text-[#34d399]">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </span>
                    </div>

                    <div class="bg-[#15201a] p-3 rounded-lg border border-[#24362d]">
                        <span class="text-[10px] font-mono text-[#64748b] block mb-1">Durasi Sewa</span>
                        <span class="text-xs font-mono font-bold text-[#f8fafc]">
                            {{ $booking->duration_hours }} Jam
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($booking->notes)
                <div class="p-3.5 rounded-lg bg-[#0b110e] border border-[#f59e0b]/30 text-xs">
                    <span class="font-bold text-[#f59e0b] block mb-1 font-mono uppercase tracking-wider text-[10px]">Catatan Tambahan:</span>
                    <p class="text-[#cbd5e1]">{{ $booking->notes }}</p>
                </div>
            @endif

            <!-- Price Breakdown -->
            <div class="border-t border-[#1b2922] pt-5 space-y-2 text-xs">
                <div class="flex justify-between items-center text-[#94a3b8]">
                    <span>Biaya Sewa Lapangan ({{ $booking->duration_hours }} jam x Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }})</span>
                    <span class="font-mono text-[#cbd5e1]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[#94a3b8]">
                    <span>Biaya Platform & Layanan</span>
                    <span class="font-mono text-[#34d399] font-medium">Gratis</span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold text-[#f8fafc] pt-3 border-t border-[#1b2922]">
                    <span>Total Pembayaran</span>
                    <span class="font-mono text-xl text-[#34d399]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-[#1b2922]">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('bookings.index') }}" 
                       class="w-full sm:w-auto px-4 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg text-center transition-colors">
                        ← Kembali ke Riwayat
                    </a>
                    <button onclick="window.print()" 
                            class="px-4 py-2 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#34d399] text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"/>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <rect width="12" height="8" x="6" y="14"/>
                        </svg>
                        <span>Cetak Tiket</span>
                    </button>
                </div>

                @if($booking->status === 'pending')
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 py-2 bg-[#151010] hover:bg-[#201515] text-[#ef4444] border border-[#ef4444]/30 text-xs font-medium rounded-lg transition-colors">
                            Batalkan Pemesanan Ini
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
