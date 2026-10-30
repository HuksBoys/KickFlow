@extends('layouts.app')

@section('title', 'Detail Pesanan #KF-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT) . ' - Kasir KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#11111b] to-[#181825] text-[#cdd6f4] py-8 border-b border-[#313244] print:hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#a6e3a1]/15 border border-[#a6e3a1]/30 text-[#a6e3a1] text-xs font-bold mb-2">
                <span>🧾</span> Kasir & Struk Pesanan
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Detail Pesanan #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</h1>
            <p class="mt-1 text-[#a6adc8] text-sm">
                Periksa data reservasi dan proses konfirmasi atau cetak nota pembayaran pelanggan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span>🖨️</span> Cetak Struk
            </button>
            <a href="{{ route('admin.cashier.index') }}" class="px-4 py-2 bg-[#181825] hover:bg-[#313244] border border-[#45475a] text-[#a6adc8] text-xs font-semibold rounded-xl transition">
                ← Kembali ke Kasir
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Action Bar for Cashier Operations (Hidden when printing) -->
    <div class="bg-[#181825] rounded-3xl border border-[#313244] p-5 shadow-sm flex flex-wrap items-center justify-between gap-4 print:hidden">
        <div>
            <span class="text-xs font-bold uppercase text-[#a6adc8] tracking-wider block">Status Transaksi Saat Ini</span>
            <div class="flex items-center gap-2 mt-1">
                @if($booking->status === 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f9e2af]/15 text-[#f9e2af] border border-[#f9e2af]/30 animate-pulse">
                        ⏳ Menunggu Konfirmasi
                    </span>
                @elseif($booking->status === 'confirmed')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30">
                        ✅ Terkonfirmasi
                    </span>
                @elseif($booking->status === 'completed')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                        🏁 Selesai
                    </span>
                @elseif($booking->status === 'cancelled')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                        ❌ Dibatalkan
                    </span>
                @endif

                @if($booking->payment_status === 'paid')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                        ✓ Lunas
                    </span>
                @elseif($booking->payment_status === 'refunded')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#cba6f7]/15 text-[#cba6f7] border border-[#cba6f7]/30">
                        ↩ Refund
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
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
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#89b4fa] hover:bg-[#74c7ec] text-[#11111b] text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <span>✓</span> Konfirmasi Pesanan
                    </button>
                </form>
            @endif

            <!-- Tandai Sudah Bayar -->
            @if($booking->payment_status === 'unpaid' && $booking->status !== 'cancelled')
                <form method="POST" action="{{ route('admin.cashier.mark-paid', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <span>💵</span> Tandai Lunas
                    </button>
                </form>
            @endif

            <!-- Selesaikan Pesanan -->
            @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('admin.cashier.complete', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#cba6f7] hover:bg-[#b4befe] text-[#11111b] text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <span>🏁</span> Selesaikan Jadwal
                    </button>
                </form>
            @endif

            <!-- Batalkan Pesanan -->
            @if(!in_array($booking->status, ['completed', 'cancelled'], true))
                <form method="POST" action="{{ route('admin.cashier.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-[#f38ba8]/40 hover:bg-[#f38ba8]/10 text-[#f38ba8] text-xs font-bold transition">
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Printable Receipt Card -->
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden text-[#cdd6f4]">
        <!-- Receipt Header -->
        <div class="p-6 sm:p-8 border-b border-[#313244] bg-[#11111b]/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#a6e3a1] to-[#94e2d5] flex items-center justify-center text-[#11111b] font-bold text-xl shadow-md shadow-[#a6e3a1]/20">
                    ⚽
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight">Kick<span class="text-[#a6e3a1]">Flow</span> Futsal Arena</h2>
                    <p class="text-xs text-[#a6adc8]">Struk Resmi Pemesanan Lapangan</p>
                </div>
            </div>
            <div class="sm:text-right">
                <div class="text-xs font-mono font-bold text-[#cba6f7]">KODE BOOKING</div>
                <div class="text-2xl font-mono font-extrabold text-[#cdd6f4]">#KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="text-[11px] text-[#6c7086]">Dibuat: {{ $booking->created_at->format('d/m/Y H:i') }} WIB</div>
            </div>
        </div>

        <!-- Receipt Body Details -->
        <div class="p-6 sm:p-8 space-y-6">
            <!-- Grid Pelanggan & Lapangan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#313244]">
                <!-- Pelanggan -->
                <div class="bg-[#11111b]/40 rounded-2xl p-4 border border-[#313244]">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#a6adc8] block mb-2">Informasi Pemesan</span>
                    <div class="space-y-1">
                        <div class="font-bold text-sm text-[#cdd6f4]">{{ $booking->user->name ?? 'Customer' }}</div>
                        <div class="text-xs text-[#a6adc8]">✉️ {{ $booking->user->email ?? '-' }}</div>
                        <div class="text-xs text-[#a6adc8]">📱 {{ $booking->user->phone ?? 'Tidak ada nomor telepon' }}</div>
                    </div>
                </div>

                <!-- Lapangan -->
                <div class="bg-[#11111b]/40 rounded-2xl p-4 border border-[#313244]">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#a6adc8] block mb-2">Informasi Lapangan</span>
                    <div class="space-y-1">
                        <div class="font-bold text-sm text-[#a6e3a1]">{{ $booking->field->name ?? 'Lapangan Futsal' }}</div>
                        <div class="text-xs text-[#a6adc8]">
                            🏷️ Tipe: <span class="capitalize font-semibold text-[#cdd6f4]">{{ $booking->field->type ?? 'Indoor' }}</span>
                        </div>
                        <div class="text-xs text-[#a6adc8]">
                            💰 Tarif: Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }} / jam
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Waktu Bermain -->
            <div class="space-y-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#a6adc8] block">Jadwal Reservasi</span>
                <div class="bg-[#11111b]/40 rounded-2xl p-4 border border-[#313244] grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <span class="text-xs text-[#6c7086] block">Tanggal Main</span>
                        <div class="font-bold text-sm text-[#cdd6f4] mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-[#6c7086] block">Jam Sewa</span>
                        <div class="font-bold text-sm text-[#a6e3a1] mt-0.5">
                            {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                        </div>
                    </div>
                    <div>
                        <span class="text-xs text-[#6c7086] block">Total Durasi</span>
                        <div class="font-bold text-sm text-[#cdd6f4] mt-0.5">
                            {{ $booking->duration_hours }} Jam
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Pemesan -->
            @if($booking->notes)
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#a6adc8] block">Catatan Tambahan:</span>
                    <div class="bg-[#11111b]/40 rounded-2xl p-3 border border-[#313244] text-xs text-[#bac2de]">
                        {{ $booking->notes }}
                    </div>
                </div>
            @endif

            <!-- Rincian Biaya & Total -->
            <div class="bg-[#11111b] rounded-2xl p-5 border border-[#313244] space-y-3">
                <div class="flex justify-between items-center text-xs text-[#a6adc8]">
                    <span>Sewa {{ $booking->field->name ?? 'Lapangan' }} ({{ $booking->duration_hours }} jam × Rp {{ number_format($booking->field->price_per_hour ?? 0, 0, ',', '.') }})</span>
                    <span class="font-semibold text-[#cdd6f4]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs text-[#a6adc8]">
                    <span>Biaya Layanan & Administrasi</span>
                    <span class="font-semibold text-[#a6e3a1]">GRATIS (Rp 0)</span>
                </div>
                <div class="pt-3 border-t border-[#313244] flex justify-between items-center">
                    <span class="text-sm font-bold text-[#cdd6f4]">TOTAL PEMBAYARAN</span>
                    <span class="text-xl font-extrabold text-[#a6e3a1]">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Receipt Footer -->
        <div class="p-6 bg-[#11111b]/80 border-t border-[#313244] text-center text-xs text-[#6c7086] space-y-1">
            <p>Harap tiba 15 menit sebelum waktu pemesanan dimulai.</p>
            <p>Terima kasih telah mempercayakan KickFlow Futsal Arena sebagai arena bermain Anda!</p>
        </div>
    </div>
</div>
@endsection
