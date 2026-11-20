@extends('layouts.app')

@section('title', 'Detail Pesanan #KF-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) . ' - Kasir KickFlow')

@section('content')
<!-- Header Banner (Hidden when printing) -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8 print:hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">
                <span>[RECEIPT]</span>
                <span>KASIR & STRUK NOTA</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">
                Detail Pesanan #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Periksa data reservasi, proses konfirmasi pembayaran, dan cetak nota transaksi pelanggan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#34d399] text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect width="12" height="8" x="6" y="14"/>
                </svg>
                <span>Cetak Struk</span>
            </button>
            <a href="{{ route('admin.cashier.index') }}" 
               class="px-3.5 py-2 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] text-[#94a3b8] text-xs font-medium rounded-lg transition-colors">
                ← Kembali ke Kasir
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Action Bar for Cashier Operations (Hidden when printing) -->
    <div class="bg-[#101814] rounded-xl border border-[#24362d] p-4 shadow-sm flex flex-wrap items-center justify-between gap-4 print:hidden">
        <div>
            <span class="text-[10px] font-mono font-bold uppercase text-[#64748b] tracking-wider block">Status Transaksi Saat Ini</span>
            <div class="flex items-center gap-2 mt-1">
                @if($booking->status === 'pending')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">
                        ⏳ Menunggu Konfirmasi
                    </span>
                @elseif($booking->status === 'confirmed')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                        ✅ Terkonfirmasi
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                        🏁 Selesai
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                        ❌ Dibatalkan
                    </span>
                @endif

                @if($booking->payment_status === 'paid')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                        ✓ Lunas
                    </span>
                @elseif($booking->payment_status === 'refunded')
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                        ↩ Refund
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                        ✕ Belum Lunas
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Konfirmasi Pesanan -->
            @if($booking->status === 'pending')
                <form method="POST" action="{{ route('admin.cashier.confirm', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-3.5 py-2 rounded-lg bg-[#38bdf8] hover:bg-[#7dd3fc] text-[#070b09] text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm">
                        <span>✓</span> Konfirmasi Pesanan
                    </button>
                </form>
            @endif

            <!-- Tandai Sudah Bayar -->
            @if($booking->payment_status === 'unpaid' && $booking->status !== 'cancelled')
                <form method="POST" action="{{ route('admin.cashier.mark-paid', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-3.5 py-2 rounded-lg bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm">
                        <span>💵</span> Tandai Lunas
                    </button>
                </form>
            @endif

            <!-- Selesaikan Pesanan -->
            @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('admin.cashier.complete', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-3.5 py-2 rounded-lg bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#34d399] text-xs font-medium transition-colors flex items-center gap-1.5">
                        <span>🏁</span> Selesaikan Jadwal
                    </button>
                </form>
            @endif

            <!-- Batalkan Pesanan -->
            @if(!in_array($booking->status, ['completed', 'cancelled'], true))
                <form method="POST" action="{{ route('admin.cashier.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-3 py-2 rounded-lg border border-[#ef4444]/40 hover:bg-[#ef4444]/10 text-[#ef4444] text-xs font-medium transition-colors">
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Printable Receipt Card -->
    <div class="print-receipt bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        <!-- Receipt Header -->
        <div class="p-6 border-b border-[#1b2922] bg-[#0b110e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#15201a] border border-[#24362d] flex items-center justify-center text-[#10b981]">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-lg font-bold tracking-tight">Kick<span class="text-[#10b981]">Flow</span> Futsal Arena</h2>
                    <p class="text-xs text-[#94a3b8]">Struk Resmi Pemesanan Lapangan</p>
                </div>
            </div>
            <div class="sm:text-right">
                <div class="text-[10px] font-mono uppercase tracking-wider text-[#64748b]">KODE BOOKING</div>
                <div class="text-xl font-mono font-bold text-[#34d399]">#KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="text-[11px] font-mono text-[#64748b]">Dibuat: {{ $booking->created_at->format('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <!-- Receipt Body Details -->
        <div class="p-6 sm:p-8 space-y-6">
            <!-- Grid Pelanggan & Lapangan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-6 border-b border-[#1b2922]">
                <!-- Pelanggan -->
                <div class="bg-[#0b110e] rounded-lg p-4 border border-[#1b2922]">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-[#64748b] block mb-2 font-bold">Informasi Pemesan</span>
                    <div class="space-y-1">
                        <div class="font-display font-bold text-sm text-[#f8fafc]">{{ $booking->user->name ?? 'Customer' }}</div>
                        <div class="text-xs text-[#94a3b8]">{{ $booking->user->email ?? '-' }}</div>
                        <div class="text-xs text-[#94a3b8] font-mono">{{ $booking->user->phone ?? 'Tidak ada nomor telepon' }}</div>
                    </div>
                </div>

                <!-- Lapangan -->
                <div class="bg-[#0b110e] rounded-lg p-4 border border-[#1b2922]">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-[#64748b] block mb-2 font-bold">Informasi Lapangan</span>
                    <div class="space-y-1">
                        <div class="font-display font-bold text-sm text-[#34d399]">{{ $booking->field->name ?? 'Lapangan Futsal' }}</div>
                        <div class="text-xs text-[#94a3b8]">
                            Tipe: <span class="capitalize font-semibold text-[#f8fafc]">{{ $booking->field->type ?? 'Indoor' }}</span>
                        </div>
                        <div class="text-xs font-mono text-[#94a3b8]">
                            Tarif: Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }} / jam
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Waktu Bermain -->
            <div class="space-y-2">
                <span class="text-[10px] font-mono uppercase tracking-wider text-[#64748b] block font-bold">Jadwal Reservasi</span>
                <div class="bg-[#0b110e] rounded-lg p-4 border border-[#1b2922] grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <span class="text-[10px] font-mono text-[#64748b] block">Tanggal Main</span>
                        <div class="font-bold text-xs text-[#f8fafc] mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono text-[#64748b] block">Jam Sewa</span>
                        <div class="font-mono font-bold text-xs text-[#34d399] mt-0.5">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-mono text-[#64748b] block">Total Durasi</span>
                        <div class="font-mono font-bold text-xs text-[#f8fafc] mt-0.5">
                            {{ $booking->duration_hours }} Jam
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Pemesan -->
            @if($booking->notes)
                <div class="space-y-1">
                    <span class="text-[10px] font-mono uppercase tracking-wider text-[#64748b] block font-bold">Catatan Tambahan:</span>
                    <div class="bg-[#0b110e] rounded-lg p-3 border border-[#1b2922] text-xs text-[#cbd5e1]">
                        {{ $booking->notes }}
                    </div>
                </div>
            @endif

            <!-- Rincian Biaya & Total -->
            <div class="bg-[#0b110e] rounded-lg p-4 border border-[#1b2922] space-y-2 text-xs">
                <div class="flex justify-between items-center text-[#94a3b8]">
                    <span>Sewa {{ $booking->field->name ?? 'Lapangan' }} ({{ $booking->duration_hours }} jam × Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }})</span>
                    <span class="font-mono font-semibold text-[#f8fafc]">Rp {{ number_format(($booking->field->price_per_hour ?? 0) * $booking->duration_hours, 0, ',', '.') }}</span>
                </div>
                @foreach($booking->addons as $addon)
                    <div class="flex justify-between items-center text-[#94a3b8]">
                        <span>{{ $addon->name }} ({{ $addon->pivot->quantity }} × Rp {{ number_format($addon->pivot->unit_price, 0, ',', '.') }})</span>
                        <span class="font-mono font-semibold text-[#f8fafc]">Rp {{ number_format($addon->pivot->quantity * $addon->pivot->unit_price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between items-center text-[#94a3b8]">
                    <span>Biaya Layanan & Administrasi</span>
                    <span class="font-mono text-[#34d399] font-medium">GRATIS (Rp 0)</span>
                </div>
                <div class="pt-3 border-t border-[#1b2922] flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#f8fafc]">TOTAL PEMBAYARAN</span>
                    <span class="font-mono text-xl font-bold text-[#34d399]">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Receipt Footer -->
        <div class="p-4 bg-[#0b110e] border-t border-[#1b2922] text-center text-xs text-[#64748b] space-y-0.5">
            <p>Harap tiba 15 menit sebelum waktu pemesanan dimulai.</p>
            <p>Terima kasih telah mempercayakan KickFlow Futsal Arena sebagai arena bermain Anda!</p>
        </div>
    </div>
</div>
@endsection
