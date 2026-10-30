@extends('layouts.app')

@section('title', 'Kasir & Konfirmasi Pesanan - Admin KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#11111b] to-[#181825] text-[#cdd6f4] py-8 border-b border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#a6e3a1]/15 border border-[#a6e3a1]/30 text-[#a6e3a1] text-xs font-bold mb-2">
                <span>⚡</span> Kasir & Point of Sale • Admin Panel
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Kasir & Konfirmasi Pesanan</h1>
            <p class="mt-1 text-[#a6adc8] text-sm">
                Kelola status reservasi lapangan, verifikasi pembayaran customer, dan konfirmasi jadwal pertandingan.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.fields.index') }}" class="px-4 py-2.5 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition flex items-center gap-2">
                <span>🏟️</span> Kelola Lapangan
            </a>
            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-[#cba6f7] hover:bg-[#b4befe] text-[#11111b] text-xs font-bold rounded-xl transition flex items-center gap-2 shadow-md shadow-[#cba6f7]/20">
                <span>📊</span> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Pending (Needs Confirmation) -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30 flex items-center justify-center text-2xl flex-shrink-0">
                ⏳
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Perlu Konfirmasi</span>
                <div class="text-2xl font-extrabold text-[#f9e2af] mt-0.5">{{ $pendingCount }}</div>
                <span class="text-[11px] text-[#6c7086]">Status pesanan pending</span>
            </div>
        </div>

        <!-- Card 2: Confirmed Today -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30 flex items-center justify-center text-2xl flex-shrink-0">
                📅
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Terkonfirmasi Hari Ini</span>
                <div class="text-2xl font-extrabold text-[#89b4fa] mt-0.5">{{ $confirmedTodayCount }}</div>
                <span class="text-[11px] text-[#6c7086]">Jadwal main hari ini</span>
            </div>
        </div>

        <!-- Card 3: Unpaid Bookings -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30 flex items-center justify-center text-2xl flex-shrink-0">
                🧾
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Belum Lunas</span>
                <div class="text-2xl font-extrabold text-[#f38ba8] mt-0.5">{{ $unpaidCount }}</div>
                <span class="text-[11px] text-[#6c7086]">Menunggu pembayaran</span>
            </div>
        </div>

        <!-- Card 4: Today Revenue -->
        <div class="bg-[#181825] rounded-3xl p-6 border border-[#313244] shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30 flex items-center justify-center text-2xl flex-shrink-0">
                💰
            </div>
            <div>
                <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider">Pemasukan Hari Ini</span>
                <div class="text-2xl font-extrabold text-[#a6e3a1] mt-0.5">
                    Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                </div>
                <span class="text-[11px] text-[#6c7086]">Pembayaran lunas hari ini</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-[#181825] rounded-2xl p-4 border border-[#313244]">
        <form method="GET" action="{{ route('admin.cashier.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-semibold text-[#a6adc8] mb-1">Cari ID / Nama</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ID #KF-... atau Nama" 
                       class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] placeholder-[#6c7086] focus:outline-none focus:border-[#a6e3a1]">
            </div>

            <!-- Status Booking -->
            <div>
                <label for="status" class="block text-xs font-semibold text-[#a6adc8] mb-1">Status Pesanan</label>
                <select id="status" name="status" class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1]">
                    <option value="semua">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending (Perlu Konfirmasi)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>✅ Confirmed (Terkonfirmasi)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>🏁 Completed (Selesai)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Cancelled (Batal)</option>
                </select>
            </div>

            <!-- Status Pembayaran -->
            <div>
                <label for="payment" class="block text-xs font-semibold text-[#a6adc8] mb-1">Status Pembayaran</label>
                <select id="payment" name="payment" class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1]">
                    <option value="semua">Semua Pembayaran</option>
                    <option value="unpaid" {{ request('payment') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="refunded" {{ request('payment') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <!-- Tanggal Main -->
            <div>
                <label for="date" class="block text-xs font-semibold text-[#a6adc8] mb-1">Tanggal Main</label>
                <input type="date" 
                       id="date" 
                       name="date" 
                       value="{{ request('date') }}" 
                       class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1]">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'payment', 'date']))
                    <a href="{{ route('admin.cashier.index') }}" class="py-2 px-3 bg-[#313244] hover:bg-[#45475a] text-[#a6adc8] text-xs font-semibold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bookings Cashier Table -->
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden text-[#cdd6f4]">
        <div class="p-5 border-b border-[#313244] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-[#cdd6f4]">Daftar Pesanan & Antrean Kasir ({{ $bookings->total() }})</h3>
                <p class="text-xs text-[#a6adc8]">Klik tombol konfirmasi untuk langsung memvalidasi reservasi dan status bayar</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#313244] text-sm">
                <thead class="bg-[#11111b] text-left text-xs font-bold text-[#a6adc8] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">ID & Pemesan</th>
                        <th class="px-5 py-4">Lapangan & Jadwal</th>
                        <th class="px-5 py-4">Total Biaya</th>
                        <th class="px-5 py-4">Status Pesanan</th>
                        <th class="px-5 py-4">Status Bayar</th>
                        <th class="px-5 py-4 text-right">Aksi Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#313244]">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[#313244]/40 transition">
                            <!-- ID & Pemesan -->
                            <td class="px-5 py-4">
                                <div class="font-mono text-xs font-bold text-[#cba6f7]">
                                    #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="font-bold text-[#cdd6f4] mt-0.5">{{ $booking->user->name ?? 'User Umum' }}</div>
                                <div class="text-xs text-[#6c7086]">{{ $booking->user->phone ?? $booking->user->email ?? '-' }}</div>
                            </td>

                            <!-- Lapangan & Jadwal -->
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#cdd6f4]">{{ $booking->field->name ?? 'Lapangan Futsal' }}</div>
                                <div class="text-xs text-[#a6adc8] mt-0.5">
                                    📅 {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs font-bold text-[#a6e3a1]">
                                    ⏰ {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} ({{ $booking->duration_hours }} Jam)
                                </div>
                            </td>

                            <!-- Total Biaya -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#a6e3a1] text-sm">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Status Pesanan -->
                            <td class="px-5 py-4">
                                @if($booking->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f9e2af]"></span> Pending
                                    </span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#89b4fa]"></span> Confirmed
                                    </span>
                                @elseif($booking->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#a6e3a1]"></span> Completed
                                    </span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f38ba8]"></span> Cancelled
                                    </span>
                                @endif
                            </td>

                            <!-- Status Bayar -->
                            <td class="px-5 py-4">
                                @if($booking->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                                        ✓ Lunas
                                    </span>
                                @elseif($booking->payment_status === 'refunded')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-[#cba6f7]/15 text-[#cba6f7] border border-[#cba6f7]/30">
                                        ↩ Refund
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                                        ✕ Belum Lunas
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Kasir -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <!-- Konfirmasi Pesanan (Jika Pending) -->
                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.cashier.confirm', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Konfirmasi Pesanan"
                                                    class="px-2.5 py-1.5 rounded-xl bg-[#89b4fa] hover:bg-[#74c7ec] text-[#11111b] text-xs font-bold transition shadow-sm flex items-center gap-1">
                                                <span>✓</span> Konfirmasi
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Terima Pembayaran (Jika Belum Lunas & Tidak Batal) -->
                                    @if($booking->payment_status === 'unpaid' && $booking->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.cashier.mark-paid', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Tandai Sudah Bayar Lunas"
                                                    class="px-2.5 py-1.5 rounded-xl bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold transition shadow-sm flex items-center gap-1">
                                                <span>💵</span> Bayar
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tandai Selesai (Jika Confirmed) -->
                                    @if($booking->status === 'confirmed')
                                        <form method="POST" action="{{ route('admin.cashier.complete', $booking) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Selesaikan Pemesanan"
                                                    class="px-2.5 py-1.5 rounded-xl bg-[#cba6f7] hover:bg-[#b4befe] text-[#11111b] text-xs font-bold transition shadow-sm flex items-center gap-1">
                                                <span>🏁</span> Selesai
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Lihat Detail Struk -->
                                    <a href="{{ route('admin.cashier.show', $booking) }}" 
                                       title="Lihat Detail Pesanan / Cetak Struk"
                                       class="p-1.5 rounded-xl border border-[#45475a] hover:border-[#a6e3a1] text-[#a6adc8] hover:text-[#cdd6f4] bg-[#313244] text-xs transition">
                                        👁️
                                    </a>

                                    <!-- Batalkan Pesanan (Jika Bukan Completed/Cancelled) -->
                                    @if(!in_array($booking->status, ['completed', 'cancelled'], true))
                                        <form method="POST" 
                                              action="{{ route('admin.cashier.cancel', $booking) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Batalkan Booking"
                                                    class="p-1.5 rounded-xl border border-[#45475a] hover:border-[#f38ba8] text-[#a6adc8] hover:text-[#f38ba8] bg-[#313244] text-xs transition">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#6c7086]">
                                Belum ada pesanan yang sesuai dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-[#313244]">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
