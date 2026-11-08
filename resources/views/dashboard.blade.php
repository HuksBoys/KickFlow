@extends('layouts.app')

@section('title', 'Dashboard Admin - KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-[#101814] border border-[#38bdf8]/30 text-[#38bdf8] text-[10px] font-mono font-bold mb-2">
                <span>⚡</span>
                <span>PANEL ADMINISTRATOR RESMI</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">
                Dashboard Manajemen KickFlow
            </h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Pantau performa reservasi lapangan, pendapatan platform, dan status operasional arena secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.fields.create') }}" 
               class="px-3.5 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Tambah Lapangan</span>
            </a>
            <a href="{{ route('admin.cashier.index') }}" 
               class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#f59e0b]/40 text-[#f59e0b] text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                <span>⚡ Kasir / Konfirmasi</span>
            </a>
            <a href="{{ route('admin.fields.index') }}" 
               class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors">
                Kelola Lapangan
            </a>
            <a href="{{ route('home') }}" 
               class="px-3 py-2 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#64748b] hover:text-[#f8fafc] text-xs font-medium rounded-lg transition-colors">
                Halaman Depan
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Today Bookings -->
        <div class="bg-[#101814] rounded-xl p-5 border border-[#24362d] flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#38bdf8] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Booking Hari Ini</span>
                <div class="font-mono text-2xl font-bold text-[#f8fafc] mt-0.5">{{ $todayBookings }}</div>
                <span class="text-[11px] text-[#94a3b8]">Jadwal tanding hari ini</span>
            </div>
        </div>

        <!-- Card 2: Revenue -->
        <div class="bg-[#101814] rounded-xl p-5 border border-[#24362d] flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#10b981] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="12" x2="12" y1="2" y2="22"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Pendapatan Bulan Ini</span>
                <div class="font-mono text-xl font-bold text-[#34d399] mt-0.5">
                    Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-[#94a3b8]">Total sewa terkonfirmasi</span>
            </div>
        </div>

        <!-- Card 3: Active Fields -->
        <a href="{{ route('admin.fields.index') }}" 
           class="bg-[#101814] hover:bg-[#15201a] rounded-xl p-5 border border-[#24362d] flex items-center gap-4 transition-colors group">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#34d399] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m4.93 4.93 4.24 4.24"/>
                    <path d="m14.83 9.17 4.24-4.24"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Lapangan Aktif</span>
                <div class="font-mono text-2xl font-bold text-[#f8fafc] mt-0.5">{{ $activeFields }}</div>
                <span class="text-[11px] text-[#10b981] group-hover:underline">Kelola arena →</span>
            </div>
        </a>

        <!-- Card 4: Pending Bookings -->
        <a href="{{ route('admin.cashier.index', ['status' => 'pending']) }}" 
           class="bg-[#101814] hover:bg-[#15201a] rounded-xl p-5 border border-[#24362d] flex items-center gap-4 transition-colors group">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#f59e0b] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Perlu Konfirmasi</span>
                <div class="font-mono text-2xl font-bold text-[#f59e0b] mt-0.5">{{ $pendingBookings }}</div>
                <span class="text-[11px] text-[#f59e0b] group-hover:underline">Buka kasir konfirmasi →</span>
            </div>
        </a>

    </div>

    <!-- Recent Bookings Table -->
    <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        <div class="p-5 border-b border-[#1b2922] flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-[#f8fafc]">10 Pemesanan Terbaru</h3>
                <p class="text-xs text-[#94a3b8]">Daftar transaksi booking paling mutakhir di sistem KickFlow</p>
            </div>
            <a href="{{ route('admin.cashier.index') }}" 
               class="px-3 py-1.5 rounded-lg bg-[#15201a] hover:bg-[#1b2922] text-[#34d399] text-xs font-medium border border-[#24362d] transition-colors flex items-center gap-1.5">
                <span>Buka Semua di Kasir</span>
                <span>→</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#1b2922] text-xs">
                <thead class="bg-[#0b110e] text-left text-[11px] font-mono text-[#64748b] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID Booking</th>
                        <th class="px-5 py-3.5">Pemesan</th>
                        <th class="px-5 py-3.5">Lapangan</th>
                        <th class="px-5 py-3.5">Tanggal Main</th>
                        <th class="px-5 py-3.5">Jam</th>
                        <th class="px-5 py-3.5">Total Biaya</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1b2922]">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-[#15201a]/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-semibold text-[#cbd5e1]">
                                #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-display font-bold text-[#f8fafc]">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-[11px] text-[#64748b]">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 font-medium text-[#cbd5e1]">
                                {{ $booking->field->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-[#94a3b8]">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-4 font-mono font-semibold text-[#34d399]">
                                {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-[#34d399]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($booking->status === 'pending')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">
                                        Pending
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                                        Confirmed
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                                        Cancelled
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.cashier.show', $booking) }}" 
                                   class="px-3 py-1.5 rounded-lg bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-xs font-medium text-[#34d399] transition-colors">
                                    Proses di Kasir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-[#64748b]">
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
