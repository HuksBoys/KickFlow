@extends('layouts.app')

@section('title', 'Dashboard Admin - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-semibold mb-2">
                <span>👑</span> Administrator Panel
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Manajemen KickFlow</h1>
            <p class="mt-1 text-gray-400 text-sm">
                Pantau performa reservasi lapangan, pendapatan platform, dan status operasional real-time.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('fields.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold rounded-xl transition">
                🏟️ Lihat Lapangan
            </a>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-xl transition">
                ⚽ Halaman Depan
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
                📅
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Booking Hari Ini</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $todayBookings }}</div>
                <span class="text-[11px] text-gray-500">Jadwal main hari ini</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl flex-shrink-0">
                💰
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Pendapatan Bulan Ini</span>
                <div class="text-2xl font-extrabold text-green-600 mt-0.5">
                    Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-gray-500">Total sewa terkonfirmasi</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl flex-shrink-0">
                🏟️
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Lapangan Aktif</span>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $activeFields }}</div>
                <span class="text-[11px] text-gray-500">Siap menerima pesanan</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0">
                ⏳
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-gray-400 tracking-wider">Perlu Konfirmasi</span>
                <div class="text-2xl font-extrabold text-amber-600 mt-0.5">{{ $pendingBookings }}</div>
                <span class="text-[11px] text-gray-500">Pemesanan pending</span>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">10 Pemesanan Terbaru</h3>
                <p class="text-xs text-gray-500">Daftar transaksi booking paling mutakhir di sistem KickFlow</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
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
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">
                                #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $booking->field->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-green-700">
                                {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
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
