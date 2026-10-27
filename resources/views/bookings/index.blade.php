@extends('layouts.app')

@section('title', 'Riwayat Pemesanan - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#181825] to-[#1e1e2e] text-[#cdd6f4] py-10 border-b border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Riwayat Pemesanan Saya</h1>
            <p class="mt-2 text-[#a6adc8] text-sm">
                Lihat status booking, jadwal main, dan rincian transaksi lapangan futsal Anda.
            </p>
        </div>
        <div>
            <a href="{{ route('fields.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#a6e3a1] text-[#11111b] font-bold text-xs rounded-xl hover:bg-[#94e2d5] shadow-sm transition">
                <span>➕</span> Pesan Lapangan Baru
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($bookings->isEmpty())
        <div class="bg-[#181825] rounded-3xl border border-[#313244] p-12 text-center max-w-xl mx-auto shadow-sm">
            <div class="w-20 h-20 bg-[#313244] text-[#a6e3a1] rounded-3xl border border-[#45475a] flex items-center justify-center text-4xl mx-auto mb-4">
                ⚽
            </div>
            <h3 class="text-xl font-bold text-[#cdd6f4]">Belum Ada Pemesanan</h3>
            <p class="text-sm text-[#a6adc8] mt-2 mb-6">
                Anda belum memiliki riwayat pemesanan lapangan futsal. Yuk, cari lapangan terbaik dan tentukan jadwal main tim Anda sekarang!
            </p>
            <a href="{{ route('fields.index') }}" class="inline-flex items-center px-6 py-3 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold text-sm rounded-xl shadow-md shadow-[#a6e3a1]/20 transition">
                Jelajahi Lapangan Futsal
            </a>
        </div>
    @else
        <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#313244]">
                    <thead class="bg-[#11111b] text-left text-xs font-bold text-[#a6adc8] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No. Booking</th>
                            <th class="px-6 py-4">Lapangan</th>
                            <th class="px-6 py-4">Jadwal Main</th>
                            <th class="px-6 py-4">Durasi</th>
                            <th class="px-6 py-4">Total Biaya</th>
                            <th class="px-6 py-4">Status Booking</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#313244] text-sm">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-[#313244]/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-semibold text-[#a6adc8]">
                                    #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-[#cdd6f4]">{{ $booking->field->name ?? 'Lapangan Tidak Ditemukan' }}</div>
                                    <div class="text-xs text-[#6c7086] capitalize">{{ $booking->field->type ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-[#cdd6f4]">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                                    </div>
                                    <div class="text-xs text-[#a6e3a1] font-medium">
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[#a6adc8]">
                                    {{ $booking->duration_hours }} Jam
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-[#a6e3a1]">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30">
                                            ⏳ Menunggu Konfirmasi
                                        </span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                                            ✅ Terkonfirmasi
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                                            ❌ Dibatalkan
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30">
                                            🏆 Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('bookings.show', $booking) }}" class="inline-block px-3 py-1.5 bg-[#313244] hover:bg-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition">
                                        Detail
                                    </a>

                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-[#f38ba8]/15 hover:bg-[#f38ba8]/25 text-[#f38ba8] border border-[#f38ba8]/30 text-xs font-semibold rounded-xl transition">
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

            <div class="px-6 py-4 border-t border-[#313244]">
                {{ $bookings->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
