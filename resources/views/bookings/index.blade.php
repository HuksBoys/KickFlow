@extends('layouts.app')

@section('title', 'Riwayat Pemesanan - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-green-800 to-green-700 text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Riwayat Pemesanan Saya</h1>
            <p class="mt-2 text-green-100 text-sm">
                Lihat status booking, jadwal main, dan rincian transaksi lapangan futsal Anda.
            </p>
        </div>
        <div>
            <a href="{{ route('fields.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-green-800 font-bold text-xs rounded-xl hover:bg-green-50 shadow-sm transition">
                <span>➕</span> Pesan Lapangan Baru
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($bookings->isEmpty())
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-xl mx-auto shadow-sm">
            <div class="w-20 h-20 bg-green-50 text-green-600 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-4">
                ⚽
            </div>
            <h3 class="text-xl font-bold text-gray-900">Belum Ada Pemesanan</h3>
            <p class="text-sm text-gray-500 mt-2 mb-6">
                Anda belum memiliki riwayat pemesanan lapangan futsal. Yuk, cari lapangan terbaik dan tentukan jadwal main tim Anda sekarang!
            </p>
            <a href="{{ route('fields.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow-md shadow-green-600/30 transition">
                Jelajahi Lapangan Futsal
            </a>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
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
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-semibold text-gray-500">
                                    #KF-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $booking->field->name ?? 'Lapangan Tidak Ditemukan' }}</div>
                                    <div class="text-xs text-gray-400 capitalize">{{ $booking->field->type ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                                    </div>
                                    <div class="text-xs text-green-700 font-medium">
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $booking->duration_hours }} Jam
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            ⏳ Menunggu Konfirmasi
                                        </span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            ✅ Terkonfirmasi
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                            ❌ Dibatalkan
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            🏆 Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('bookings.show', $booking) }}" class="inline-block px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">
                                        Detail
                                    </a>

                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition">
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

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
