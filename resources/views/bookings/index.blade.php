@extends('layouts.app')

@section('title', 'Riwayat Pemesanan - KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">
                <span>[BOOKINGS]</span>
                <span>CUSTOMER RESERVATION HISTORY</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">Riwayat Pemesanan Saya</h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Pantau status konfirmasi, jadwal main, dan rincian tiket transaksi lapangan futsal Anda.
            </p>
        </div>
        <div>
            <a href="{{ route('fields.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Pesan Lapangan Baru</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($bookings->isEmpty())
        <div class="bg-[#101814] rounded-xl border border-[#24362d] p-12 text-center max-w-lg mx-auto">
            <div class="w-14 h-14 bg-[#15201a] text-[#10b981] rounded-xl border border-[#24362d] flex items-center justify-center text-xl mx-auto mb-4">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m4.93 4.93 4.24 4.24"/>
                    <path d="m14.83 9.17 4.24-4.24"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
            </div>
            <h3 class="font-display text-lg font-bold text-[#f8fafc]">Belum Ada Pemesanan</h3>
            <p class="text-xs text-[#94a3b8] mt-1.5 mb-6 leading-relaxed">
                Anda belum memiliki riwayat reservasi lapangan futsal. Pilih arena terbaik dan tentukan jadwal tanding tim Anda sekarang.
            </p>
            <a href="{{ route('fields.index') }}" 
               class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors">
                <span>Jelajahi Lapangan Futsal</span>
                <span aria-hidden="true">→</span>
            </a>
        </div>
    @else
        <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#1b2922]">
                    <thead class="bg-[#0b110e] text-left text-[11px] font-mono text-[#64748b] uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">No. Booking</th>
                            <th class="px-5 py-3.5">Lapangan</th>
                            <th class="px-5 py-3.5">Jadwal Tanding</th>
                            <th class="px-5 py-3.5">Durasi</th>
                            <th class="px-5 py-3.5">Total Biaya</th>
                            <th class="px-5 py-3.5">Status Booking</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1b2922] text-xs">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-[#15201a]/60 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap font-mono font-semibold text-[#cbd5e1]">
                                    #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-display font-bold text-[#f8fafc]">{{ $booking->field->name ?? 'Lapangan Tidak Ditemukan' }}</div>
                                    <div class="text-[11px] font-mono text-[#64748b] capitalize">{{ $booking->field->type ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-medium text-[#f8fafc]">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                                    </div>
                                    <div class="text-[11px] font-mono text-[#34d399]">
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono text-[#cbd5e1]">
                                    {{ $booking->duration_hours }} Jam
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono font-bold text-[#34d399]">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($booking->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">
                                            ⏳ Menunggu Konfirmasi
                                        </span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                                            ✅ Terkonfirmasi
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                                            ❌ Dibatalkan
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                                            🏆 Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                    <a href="{{ route('bookings.show', $booking) }}" 
                                       class="inline-block px-3 py-1.5 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors">
                                        Detail
                                    </a>

                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 bg-[#151010] hover:bg-[#201515] text-[#ef4444] border border-[#ef4444]/30 text-xs font-medium rounded-lg transition-colors">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-5 py-3.5 border-t border-[#1b2922]">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
