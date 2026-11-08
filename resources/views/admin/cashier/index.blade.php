@extends('layouts.app')

@section('title', 'Kasir & Konfirmasi Pesanan - Admin KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">
                <span>[POINT OF SALE]</span>
                <span>KASIR & OPERASIONAL</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">
                Kasir & Konfirmasi Pesanan
            </h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Kelola status reservasi lapangan, verifikasi pembayaran customer, dan konfirmasi jadwal pertandingan.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.fields.index') }}" 
               class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                <span>Kelola Lapangan</span>
            </a>
            <a href="{{ route('dashboard') }}" 
               class="px-3.5 py-2 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#38bdf8] text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                <span>Dashboard</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Pending -->
        <div class="bg-[#101814] rounded-xl p-5 border border-[#24362d] flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#f59e0b] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Perlu Konfirmasi</span>
                <div class="font-mono text-2xl font-bold text-[#f59e0b] mt-0.5">{{ $pendingCount }}</div>
                <span class="text-[11px] text-[#94a3b8]">Status pesanan pending</span>
            </div>
        </div>

        <!-- Card 2: Confirmed Today -->
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
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Terkonfirmasi Hari Ini</span>
                <div class="font-mono text-2xl font-bold text-[#38bdf8] mt-0.5">{{ $confirmedTodayCount }}</div>
                <span class="text-[11px] text-[#94a3b8]">Jadwal main hari ini</span>
            </div>
        </div>

        <!-- Card 3: Unpaid Bookings -->
        <div class="bg-[#101814] rounded-xl p-5 border border-[#24362d] flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#ef4444] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                    <line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Belum Lunas</span>
                <div class="font-mono text-2xl font-bold text-[#ef4444] mt-0.5">{{ $unpaidCount }}</div>
                <span class="text-[11px] text-[#94a3b8]">Menunggu pembayaran kasir</span>
            </div>
        </div>

        <!-- Card 4: Today Revenue -->
        <div class="bg-[#101814] rounded-xl p-5 border border-[#24362d] flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-[#15201a] text-[#10b981] border border-[#24362d] flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="12" x2="12" y1="2" y2="22"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider">Pemasukan Hari Ini</span>
                <div class="font-mono text-xl font-bold text-[#34d399] mt-0.5">
                    Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-[#94a3b8]">Kasir lunas hari ini</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-[#101814] rounded-xl p-4 border border-[#24362d]">
        <form method="GET" action="{{ route('admin.cashier.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Cari ID / Nama</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ID #KF-... atau Nama" 
                       class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981]">
            </div>

            <!-- Status Booking -->
            <div>
                <label for="status" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Status Pesanan</label>
                <select id="status" name="status" 
                        class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] focus:outline-none focus:border-[#10b981]">
                    <option value="semua">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Perlu Konfirmasi)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed (Terkonfirmasi)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                </select>
            </div>

            <!-- Status Pembayaran -->
            <div>
                <label for="payment" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Status Bayar</label>
                <select id="payment" name="payment" 
                        class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] focus:outline-none focus:border-[#10b981]">
                    <option value="semua">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="refunded" {{ request('payment') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <!-- Tanggal Main -->
            <div>
                <label for="date" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Tanggal Main</label>
                <input type="date" 
                       id="date" 
                       name="date" 
                       value="{{ request('date') }}" 
                       class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] font-mono focus:outline-none focus:border-[#10b981]">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-3 bg-[#15201a] hover:bg-[#1b2922] text-[#34d399] border border-[#24362d] text-xs font-bold rounded-lg transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'payment', 'date']))
                    <a href="{{ route('admin.cashier.index') }}" 
                       class="py-2 px-3 bg-[#101814] hover:bg-[#15201a] text-[#64748b] text-xs font-medium rounded-lg border border-[#24362d] transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Cashier Table -->
    <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        <div class="p-4 sm:p-5 border-b border-[#1b2922] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-display text-sm font-bold text-[#f8fafc]">Daftar Pesanan & Antrean Kasir ({{ $bookings->total() }})</h3>
                <p class="text-xs text-[#94a3b8]">Klik aksi untuk memvalidasi jadwal, bayar kasir, atau cetak nota</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#1b2922] text-xs">
                <thead class="bg-[#0b110e] text-left text-[11px] font-mono text-[#64748b] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID & Pemesan</th>
                        <th class="px-5 py-3.5">Lapangan & Jadwal</th>
                        <th class="px-5 py-3.5">Total Biaya</th>
                        <th class="px-5 py-3.5">Status Pesanan</th>
                        <th class="px-5 py-3.5">Status Bayar</th>
                        <th class="px-5 py-3.5 text-right">Aksi Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1b2922]">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[#15201a]/60 transition-colors">
                            <!-- ID & Pemesan -->
                            <td class="px-5 py-4">
                                <div class="font-mono text-xs font-bold text-[#34d399]">
                                    #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="font-display font-bold text-[#f8fafc] mt-0.5">{{ $booking->user->name ?? 'User Umum' }}</div>
                                <div class="text-[11px] text-[#64748b] font-mono">{{ $booking->user->phone ?? $booking->user->email ?? '-' }}</div>
                            </td>

                            <!-- Lapangan & Jadwal -->
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#f8fafc]">{{ $booking->field->name ?? 'Lapangan Futsal' }}</div>
                                <div class="text-[11px] text-[#94a3b8] mt-0.5">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[11px] font-mono font-bold text-[#34d399]">
                                    {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} ({{ $booking->duration_hours }} Jam)
                                </div>
                            </td>

                            <!-- Total Biaya -->
                            <td class="px-5 py-4 font-mono font-bold text-[#34d399]">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>

                            <!-- Status Pesanan -->
                            <td class="px-5 py-4">
                                @if($booking->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f59e0b]"></span> Pending
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#38bdf8]"></span> Confirmed
                                    </span>
                                @elseif($booking->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span> Completed
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#ef4444]"></span> Cancelled
                                    </span>
                                @endif
                            </td>

                            <!-- Status Bayar -->
                            <td class="px-5 py-4">
                                @if($booking->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                                        ✓ Lunas
                                    </span>
                                @elseif($booking->payment_status === 'refunded')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                                        ↩ Refund
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                                        ✕ Belum Lunas
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Kasir -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- Konfirmasi Pesanan -->
                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.cashier.confirm', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Konfirmasi Pesanan"
                                                    class="px-2.5 py-1 rounded-md bg-[#38bdf8] hover:bg-[#7dd3fc] text-[#070b09] text-xs font-bold transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Terima Pembayaran -->
                                    @if($booking->payment_status === 'unpaid' && $booking->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.cashier.mark-paid', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Tandai Sudah Bayar Lunas"
                                                    class="px-2.5 py-1 rounded-md bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold transition-colors">
                                                Bayar
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tandai Selesai -->
                                    @if($booking->status === 'confirmed')
                                        <form method="POST" action="{{ route('admin.cashier.complete', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Selesaikan Pemesanan"
                                                    class="px-2.5 py-1 rounded-md bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#34d399] text-xs font-medium transition-colors">
                                                Selesai
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Lihat Detail Struk -->
                                    <a href="{{ route('admin.cashier.show', $booking) }}" 
                                       title="Lihat Detail Pesanan / Cetak Struk"
                                       class="p-1.5 rounded-md border border-[#24362d] hover:border-[#10b981] text-[#94a3b8] hover:text-[#f8fafc] bg-[#15201a] text-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    <!-- Batalkan Pesanan -->
                                    @if(!in_array($booking->status, ['completed', 'cancelled'], true))
                                        <form method="POST" 
                                              action="{{ route('admin.cashier.cancel', $booking) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Batalkan Booking"
                                                    class="p-1.5 rounded-md border border-[#24362d] hover:border-[#ef4444] text-[#64748b] hover:text-[#ef4444] bg-[#15201a] text-xs transition-colors">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="18" x2="6" y1="6" y2="18"/>
                                                    <line x1="6" x2="18" y1="6" y2="18"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-[#64748b]">
                                Belum ada pesanan yang sesuai dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-[#1b2922]">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
