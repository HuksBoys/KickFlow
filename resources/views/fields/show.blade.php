@extends('layouts.app')

@section('title', $field->name . ' - KickFlow')

@section('content')
<div class="bg-gray-100 py-6 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-medium text-gray-500 gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-green-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('fields.index') }}" class="hover:text-green-600">Daftar Lapangan</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">{{ $field->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Field Overview & Schedules -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Main Hero Card -->
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="h-64 sm:h-80 bg-gradient-to-tr from-green-900 to-emerald-600 relative flex items-center justify-center">
                    <svg class="w-32 h-32 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                    </svg>

                    <div class="absolute top-6 left-6 flex gap-2">
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white' }}">
                            {{ strtoupper($field->type) }}
                        </span>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-green-700 shadow-sm">
                            Status: Aktif
                        </span>
                    </div>

                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight drop-shadow-md">
                            {{ $field->name }}
                        </h1>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Lapangan</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ $field->description ?? 'Lapangan futsal profesional dengan standar kompetisi resmi, rumput sintetis lembut anti-cedera, pencahayaan malam lampu sorot LED 1000 watt, dan sirkulasi udara optimal untuk permainan maksimal.' }}
                    </p>

                    <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Fasilitas yang Tersedia</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-gray-700">
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>🚿</span> Kamar Mandi & Bilas
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>🅿️</span> Parkir Luas & Aman
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>💡</span> Lampu Sorot LED
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>🥤</span> Kantin & Minuman
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>🦺</span> Rompi Tim Tersedia
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <span>📶</span> Wi-Fi Gratis
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Check Card (Interactive Alpine.js) -->
            <div x-data="fieldScheduleChecker({{ $field->id }})" class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Jadwal & Ketersediaan Slot</h3>
                        <p class="text-xs text-gray-500 mt-1">Pilih tanggal untuk melihat slot jam bermain yang masih kosong</p>
                    </div>

                    <div>
                        <input type="date" 
                               x-model="selectedDate" 
                               @change="fetchSchedules()"
                               min="{{ date('Y-m-d') }}"
                               class="bg-gray-50 border border-gray-200 text-gray-800 text-sm font-semibold rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="py-12 text-center text-gray-500 text-sm">
                    <div class="w-8 h-8 border-4 border-green-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                    Memeriksa ketersediaan slot...
                </div>

                <!-- Slot List -->
                <div x-show="!loading" class="space-y-4">
                    <template x-if="schedules.length === 0">
                        <div class="p-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-sm font-medium text-gray-600">Tidak ada slot jadwal khusus yang tercatat pada tanggal ini.</p>
                            <p class="text-xs text-gray-400 mt-1">Anda tetap dapat memesan secara langsung dengan memilih jam di formulir booking.</p>
                            <a :href="'{{ route('bookings.create') }}?field_id={{ $field->id }}&date=' + selectedDate" 
                               class="mt-4 inline-block px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-xs rounded-xl transition">
                                Pesan Langsung di Tanggal Ini 📅
                            </a>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <template x-for="item in schedules" :key="item.id">
                            <div class="p-3.5 rounded-2xl border text-center transition"
                                 :class="item.is_available 
                                    ? 'bg-green-50/50 border-green-200 text-green-900 hover:border-green-400 cursor-pointer' 
                                    : 'bg-gray-50 border-gray-200 text-gray-400 opacity-60 cursor-not-allowed'">
                                <div class="font-bold text-sm" x-text="item.start_time.substring(0, 5) + ' - ' + item.end_time.substring(0, 5)"></div>
                                <div class="text-[11px] font-semibold mt-1" :class="item.is_available ? 'text-green-700' : 'text-gray-400'">
                                    <span x-text="item.is_available ? 'Tersedia' : 'Penuh'"></span>
                                </div>
                                <template x-if="item.is_available">
                                    <a :href="'{{ route('bookings.create') }}?field_id={{ $field->id }}&date=' + selectedDate + '&start_time=' + item.start_time.substring(0, 5)" 
                                       class="mt-2 block w-full py-1 text-[11px] bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                        Pilih Slot
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Booking Summary Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">Ringkasan Tarif</h3>

                <div class="py-4 space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Jenis Lapangan</span>
                        <span class="font-semibold text-gray-800 capitalize">{{ $field->type }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tarif per Jam</span>
                        <span class="font-extrabold text-green-600 text-base">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Sistem Booking</span>
                        <span class="text-xs bg-green-100 text-green-800 font-semibold px-2 py-0.5 rounded-full">Real-Time Safe</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}" 
                       class="block w-full py-3.5 bg-green-600 hover:bg-green-700 text-white text-center font-bold text-sm rounded-xl shadow-md shadow-green-600/30 transition duration-150">
                        ⚡ Pesan Lapangan Ini
                    </a>
                    
                    <p class="text-[11px] text-gray-400 text-center mt-3">
                        Garansi perlindungan jadwal: sistem menolak otomatis jika terdapat jadwal bentrok.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fieldScheduleChecker(fieldId) {
        return {
            selectedDate: '{{ date('Y-m-d') }}',
            schedules: [],
            loading: false,

            init() {
                this.fetchSchedules();
            },

            async fetchSchedules() {
                if (!this.selectedDate) return;
                this.loading = true;
                try {
                    const res = await fetch(`/api/fields/${fieldId}/schedules?date=${this.selectedDate}`);
                    if (res.ok) {
                        this.schedules = await res.json();
                    } else {
                        this.schedules = [];
                    }
                } catch (e) {
                    console.error('Error fetching schedules:', e);
                    this.schedules = [];
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
@endsection
