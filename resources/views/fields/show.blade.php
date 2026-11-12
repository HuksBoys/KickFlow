@extends('layouts.app')

@section('title', $field->name . ' - KickFlow Futsal Arena')

@section('content')
<!-- Breadcrumbs -->
<div class="bg-[#0b110e] py-3.5 border-b border-[#1b2922]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-mono text-[#64748b] gap-2 items-center">
            <a href="{{ route('home') }}" class="hover:text-[#10b981] transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('fields.index') }}" class="hover:text-[#10b981] transition-colors">Daftar Lapangan</a>
            <span>/</span>
            <span class="text-[#f8fafc] font-semibold">{{ $field->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Main Content (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Hero Arena Card -->
            <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden">
                <!-- Visual Header -->
                <div class="h-60 sm:h-72 bg-[#0b110e] border-b border-[#1b2922] relative flex items-center justify-center p-6">
                    @if($field->imageUrl())
                        <img src="{{ $field->imageUrl() }}" alt="{{ $field->name }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#070b09] via-[#070b09]/30 to-transparent"></div>
                    @else
                        <div class="w-full h-full border border-[#24362d] rounded-lg flex items-center justify-center relative bg-[#070b09]/70">
                            <div class="absolute inset-y-0 left-1/2 w-px bg-[#24362d]"></div>
                            <div class="w-20 h-20 rounded-full border border-[#24362d] flex items-center justify-center text-[#4a6b5c]">
                                <svg class="w-10 h-10 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="m4.93 4.93 4.24 4.24"/>
                                    <path d="m14.83 9.17 4.24-4.24"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            </div>
                        </div>
                    @endif

                    <!-- Top Tags -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-2.5 py-1 rounded-md text-xs font-mono font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30' : 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30' }}">
                            {{ $field->type }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                            Aktif
                        </span>
                    </div>

                    <!-- Bottom Overlay Title -->
                    <div class="absolute bottom-4 left-4 right-4 text-[#f8fafc]">
                        <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">
                            {{ $field->name }}
                        </h1>
                    </div>
                </div>

                <!-- Description & Details -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="font-display text-sm font-bold uppercase tracking-wider text-[#10b981] mb-2">Deskripsi Lapangan</h3>
                        <p class="text-sm text-[#cbd5e1] leading-relaxed">
                            {{ $field->description ?? 'Lapangan futsal profesional dengan standar kompetisi resmi, rumput sintetis lembut anti-cedera, pencahayaan malam lampu sorot LED 1000 watt, dan sirkulasi udara optimal untuk permainan maksimal.' }}
                        </p>
                    </div>

                    <!-- Clean Technical Specs Grid -->
                    <div>
                        <h3 class="font-display text-sm font-bold uppercase tracking-wider text-[#10b981] mb-3">Fasilitas Arena</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-[#cbd5e1]">
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                    <path d="M10 12V4h4v8"/>
                                </svg>
                                <span>Kamar Mandi Bilas</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                    <path d="M9 17V7h4a3 3 0 0 1 0 6H9"/>
                                </svg>
                                <span>Parkir Luas & Aman</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                <span>Lampu Sorot LED 1000W</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                </svg>
                                <span>Kantin & Minuman Dingin</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10h12V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                                </svg>
                                <span>Rompi Tanding Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2.5 p-3 rounded-lg bg-[#15201a] border border-[#24362d]">
                                <svg class="w-4 h-4 text-[#10b981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                    <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                                    <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                    <circle cx="12" cy="20" r="1"/>
                                </svg>
                                <span>Wi-Fi Kecepatan Tinggi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Schedule Checker -->
            <div x-data="fieldScheduleChecker({{ $field->id }})" class="bg-[#101814] rounded-xl border border-[#24362d] p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-[#1b2922]">
                    <div>
                        <h3 class="font-display text-lg font-bold text-[#f8fafc]">Jadwal & Ketersediaan Slot</h3>
                        <p class="text-xs text-[#94a3b8] mt-0.5">Pilih tanggal untuk melihat slot jam bermain yang masih kosong</p>
                    </div>

                    <div>
                        <input type="date" 
                               x-model="selectedDate" 
                               @change="fetchSchedules()"
                               min="{{ date('Y-m-d') }}"
                               class="bg-[#15201a] border border-[#24362d] text-[#f8fafc] text-xs font-mono font-semibold rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none">
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="py-10 text-center text-[#94a3b8] text-xs">
                    <div class="w-6 h-6 border-2 border-[#10b981] border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                    Memeriksa slot ketersediaan di database...
                </div>

                <!-- Slot List -->
                <div x-show="!loading" class="space-y-4">
                    <template x-if="schedules.length === 0">
                        <div class="p-8 text-center bg-[#0b110e] rounded-xl border border-dashed border-[#24362d]">
                            <p class="text-xs font-medium text-[#f8fafc]">Belum ada jadwal kustom pada tanggal ini.</p>
                            <p class="text-[11px] text-[#94a3b8] mt-1">Anda tetap dapat memesan secara langsung dengan memilih jam di formulir booking.</p>
                            <a :href="'{{ route('bookings.create') }}?field_id={{ $field->id }}&date=' + selectedDate" 
                               class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors">
                                <span>Pesan Langsung di Tanggal Ini</span>
                                <span>→</span>
                            </a>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        <template x-for="item in schedules" :key="item.id">
                            <div class="p-3 rounded-lg border text-center transition-colors"
                                 :class="item.is_available 
                                    ? 'bg-[#15201a] border-[#10b981]/40 text-[#f8fafc] hover:border-[#10b981]' 
                                    : 'bg-[#0b110e] border-[#1b2922] text-[#64748b] opacity-60 cursor-not-allowed'">
                                <div class="font-mono font-bold text-xs" x-text="item.start_time.substring(0, 5) + ' - ' + item.end_time.substring(0, 5)"></div>
                                <div class="text-[10px] font-mono mt-1" :class="item.is_available ? 'text-[#34d399]' : 'text-[#64748b]'">
                                    <span x-text="item.is_available ? '• Tersedia' : '• Terisi'"></span>
                                </div>
                                <template x-if="item.is_available">
                                    <a :href="'{{ route('bookings.create') }}?field_id={{ $field->id }}&date=' + selectedDate + '&start_time=' + item.start_time.substring(0, 5)" 
                                       class="mt-2 block w-full py-1 text-[10px] bg-[#10b981] text-[#070b09] font-bold rounded hover:bg-[#34d399] transition-colors">
                                        Pilih Slot
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Summary & Action (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-[#101814] rounded-xl border border-[#24362d] p-6 sticky top-24 space-y-5">
                <div class="pb-4 border-b border-[#1b2922]">
                    <span class="text-xs font-mono uppercase tracking-wider text-[#64748b] block mb-1">Tarif Resmi</span>
                    <div class="flex items-baseline gap-1">
                        <span class="font-mono text-2xl font-bold text-[#34d399]">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-[#94a3b8]">/jam</span>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-[#94a3b8]">Tipe Arena</span>
                        <span class="font-semibold text-[#f8fafc] capitalize">{{ $field->type }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-[#94a3b8]">Jam Operasional</span>
                        <span class="font-mono text-[#f8fafc]">07:00 – 24:00 WIB</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-[#94a3b8]">Perlindungan Jadwal</span>
                        <span class="text-[10px] font-mono bg-[#10b981]/15 text-[#34d399] px-2 py-0.5 rounded border border-[#10b981]/30">
                            Anti Double-Booking
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#1b2922] space-y-3">
                    <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}" 
                       class="w-full py-3 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <span>Lanjut ke Formulir Booking</span>
                        <span>→</span>
                    </a>
                    
                    <p class="text-[11px] text-[#64748b] text-center leading-relaxed">
                        Sistem mengunci slot jadwal secara real-time untuk mencegah reservasi ganda.
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
