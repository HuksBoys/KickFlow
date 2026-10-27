@extends('layouts.app')

@section('title', 'Dashboard Admin - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#11111b] to-[#181825] text-[#cdd6f4] py-10 border-b border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#cba6f7]/15 border border-[#cba6f7]/30 text-[#cba6f7] text-xs font-bold mb-2">
                <span>👑</span> Administrator Panel • Catppuccin Theme
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Manajemen KickFlow</h1>
            <p class="mt-1 text-[#a6adc8] text-sm">
                Pantau performa reservasi lapangan, pendapatan platform, dan status operasional real-time.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('fields.index') }}" class="px-4 py-2 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition">
                🏟️ Lihat Lapangan
            </a>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold rounded-xl transition">
                ⚽ Halaman Depan
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Stat Cards (Catppuccin Palette) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30 flex items-center justify-center text-2xl flex-shrink-0">
                📅
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Booking Hari Ini</span>
                <div class="text-2xl font-extrabold text-[#cdd6f4] mt-0.5">{{ $todayBookings }}</div>
                <span class="text-[11px] text-[#6c7086]">Jadwal main hari ini</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30 flex items-center justify-center text-2xl flex-shrink-0">
                💰
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Pendapatan Bulan Ini</span>
                <div class="text-2xl font-extrabold text-[#a6e3a1] mt-0.5">
                    Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-[#6c7086]">Total sewa terkonfirmasi</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#cba6f7]/15 text-[#cba6f7] border border-[#cba6f7]/30 flex items-center justify-center text-2xl flex-shrink-0">
                🏟️
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Lapangan Aktif</span>
                <div class="text-2xl font-extrabold text-[#cba6f7] mt-0.5">{{ $activeFields }}</div>
                <span class="text-[11px] text-[#6c7086]">Siap menerima pesanan</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#fab387]/15 text-[#fab387] border border-[#fab387]/30 flex items-center justify-center text-2xl flex-shrink-0">
                ⏳
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Perlu Konfirmasi</span>
                <div class="text-2xl font-extrabold text-[#fab387] mt-0.5">{{ $pendingBookings }}</div>
                <span class="text-[11px] text-[#6c7086]">Pemesanan pending</span>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden text-[#cdd6f4]">
        <div class="p-6 border-b border-[#313244] flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-[#cdd6f4]">10 Pemesanan Terbaru</h3>
                <p class="text-xs text-[#a6adc8]">Daftar transaksi booking paling mutakhir di sistem KickFlow</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#313244] text-sm">
                <thead class="bg-[#11111b] text-left text-xs font-bold text-[#a6adc8] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID Booking</th>
                        <th class="px-6 py-4">Pemesan</th>
                        <th class="px-6 py-4">Lapangan</th>
                        <th class="px-6 py-4">Tanggal Main</th>
                        <th class="px-6 py-4">Jam</th>
                        <th class="px-6 py-4">Total Biaya</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#313244]">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-[#313244]/40 transition">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-[#a6adc8]">
                                #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#cdd6f4]">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-xs text-[#6c7086]">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-[#cdd6f4]">
                                {{ $booking->field->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-[#a6adc8]">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-[#a6e3a1]">
                                {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#a6e3a1]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30">
                                        Pending
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                                        Confirmed
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#6c7086]">
                                Belum ada data pemesanan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
