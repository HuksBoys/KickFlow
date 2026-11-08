@extends('layouts.app')

@section('title', 'Pemesanan Lapangan - KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-2">
            <span>[RESERVATION]</span>
            <span>FORMULIR BOOKING RESMI</span>
        </div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">Formulir Pemesanan Lapangan</h1>
        <p class="mt-1 text-xs text-[#94a3b8]">
            Isi detail reservasi jadwal tanding Anda. Sistem akan memvalidasi slot secara otomatis untuk mencegah bentrok jadwal.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#101814] rounded-xl border border-[#24362d] p-6 sm:p-8 text-[#f8fafc]" 
         x-data="bookingForm({
            fields: {{ json_encode($fields) }},
            selectedFieldId: '{{ old('field_id', $field?->id ?? ($fields->first()?->id ?? '')) }}',
            duration: {{ old('duration_hours', 1) }}
         })">
        
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
            @csrf

            <!-- Field Selection -->
            <div>
                <label for="field_id" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Pilih Lapangan Futsal <span class="text-[#ef4444]">*</span>
                </label>
                <select name="field_id" 
                        id="field_id" 
                        x-model="selectedFieldId"
                        class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] rounded-lg px-3.5 py-2.5 text-sm focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none transition-colors">
                    <template x-for="f in fields" :key="f.id">
                        <option :value="f.id" x-text="f.name + ' (' + f.type.toUpperCase() + ') - Rp ' + Number(f.price_per_hour).toLocaleString('id-ID') + '/jam'"></option>
                    </template>
                </select>
                @error('field_id')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Start Time Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Date -->
                <div>
                    <label for="booking_date" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                        Tanggal Bermain <span class="text-[#ef4444]">*</span>
                    </label>
                    <input type="date" 
                           name="booking_date" 
                           id="booking_date" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('booking_date', $date ?? date('Y-m-d')) }}"
                           required
                           class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] font-mono text-sm rounded-lg px-3.5 py-2.5 focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none transition-colors">
                    @error('booking_date')
                        <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                        Jam Mulai <span class="text-[#ef4444]">*</span>
                    </label>
                    <select name="start_time" 
                            id="start_time" 
                            required
                            class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] font-mono text-sm rounded-lg px-3.5 py-2.5 focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none transition-colors">
                        @php
                            $selectedTime = old('start_time', $startTime ?? '08:00');
                        @endphp
                        @for($hour = 7; $hour <= 22; $hour++)
                            @php
                                $formattedHour = sprintf('%02d:00', $hour);
                            @endphp
                            <option value="{{ $formattedHour }}" {{ $selectedTime === $formattedHour ? 'selected' : '' }}>
                                {{ $formattedHour }} WIB
                            </option>
                        @endfor
                    </select>
                    @error('start_time')
                        <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Duration Selection -->
            <div>
                <label class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Durasi Sewa <span class="text-[#ef4444]">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <label class="border rounded-lg p-3 text-center cursor-pointer transition-colors"
                           :class="duration == 1 ? 'border-[#10b981] bg-[#15201a] text-[#34d399] font-bold' : 'border-[#24362d] bg-[#0b110e] text-[#94a3b8] hover:border-[#334a3f]'">
                        <input type="radio" name="duration_hours" value="1" x-model="duration" class="sr-only">
                        <span class="block text-sm font-mono">1 Jam</span>
                    </label>
                    <label class="border rounded-lg p-3 text-center cursor-pointer transition-colors"
                           :class="duration == 2 ? 'border-[#10b981] bg-[#15201a] text-[#34d399] font-bold' : 'border-[#24362d] bg-[#0b110e] text-[#94a3b8] hover:border-[#334a3f]'">
                        <input type="radio" name="duration_hours" value="2" x-model="duration" class="sr-only">
                        <span class="block text-sm font-mono">2 Jam</span>
                    </label>
                    <label class="border rounded-lg p-3 text-center cursor-pointer transition-colors"
                           :class="duration == 3 ? 'border-[#10b981] bg-[#15201a] text-[#34d399] font-bold' : 'border-[#24362d] bg-[#0b110e] text-[#94a3b8] hover:border-[#334a3f]'">
                        <input type="radio" name="duration_hours" value="3" x-model="duration" class="sr-only">
                        <span class="block text-sm font-mono">3 Jam</span>
                    </label>
                    <label class="border rounded-lg p-3 text-center cursor-pointer transition-colors"
                           :class="duration == 4 ? 'border-[#10b981] bg-[#15201a] text-[#34d399] font-bold' : 'border-[#24362d] bg-[#0b110e] text-[#94a3b8] hover:border-[#334a3f]'">
                        <input type="radio" name="duration_hours" value="4" x-model="duration" class="sr-only">
                        <span class="block text-sm font-mono">4 Jam</span>
                    </label>
                </div>
                @error('duration_hours')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          placeholder="Contoh: Butuh 2 set rompi tim dan bola futsal tambahan..."
                          class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none transition-colors">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Summary Box -->
            <div class="bg-[#0b110e] rounded-lg p-5 border border-[#1b2922] space-y-2.5">
                <div class="flex justify-between items-center text-xs text-[#94a3b8]">
                    <span>Tarif Lapangan / Jam</span>
                    <span class="font-mono text-[#cbd5e1]" x-text="'Rp ' + getPricePerHour().toLocaleString('id-ID')"></span>
                </div>
                <div class="flex justify-between items-center text-xs text-[#94a3b8]">
                    <span>Durasi Pertandingan</span>
                    <span class="font-mono text-[#cbd5e1]" x-text="duration + ' Jam'"></span>
                </div>
                <div class="h-px bg-[#1b2922] my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#f8fafc]">Total Biaya Reservasi</span>
                    <span class="font-mono text-xl font-bold text-[#34d399]" x-text="'Rp ' + getTotalPrice().toLocaleString('id-ID')"></span>
                </div>
            </div>

            <!-- Security Assurance Note -->
            <div class="flex items-start gap-3 p-3.5 rounded-lg bg-[#0b110e] border border-[#38bdf8]/30 text-xs text-[#38bdf8]">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span class="leading-relaxed text-[#cbd5e1]">
                    <strong class="text-[#38bdf8]">Proteksi Jadwal KickFlow:</strong> Database kami menerapkan validasi atomic constraint untuk memastikan tidak ada pemesan lain yang dapat memesan slot waktu yang sama dengan tim Anda.
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-sm rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                <span>Konfirmasi Pemesanan Lapangan</span>
                <span aria-hidden="true">→</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function bookingForm(config) {
        return {
            fields: config.fields || [],
            selectedFieldId: config.selectedFieldId,
            duration: config.duration,

            getSelectedField() {
                return this.fields.find(f => f.id == this.selectedFieldId) || this.fields[0] || {};
            },

            getPricePerHour() {
                const f = this.getSelectedField();
                return Number(f.price_per_hour || 0);
            },

            getTotalPrice() {
                return this.getPricePerHour() * Number(this.duration || 1);
            }
        }
    }
</script>
@endpush
@endsection
