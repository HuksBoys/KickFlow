@extends('layouts.app')

@section('title', 'Pemesanan Lapangan - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#181825] to-[#1e1e2e] text-[#cdd6f4] py-10 border-b border-[#313244]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Formulir Pemesanan Lapangan</h1>
        <p class="mt-2 text-[#a6adc8] text-sm">
            Isi detail pemesanan di bawah ini. Sistem KickFlow akan memvalidasi jadwal secara instan untuk mencegah bentrok.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm p-6 sm:p-10 text-[#cdd6f4]" 
         x-data="bookingForm({
            fields: {{ json_encode($fields) }},
            selectedFieldId: '{{ old('field_id', $field?->id ?? ($fields->first()?->id ?? '')) }}',
            duration: {{ old('duration_hours', 1) }}
         })">
        
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
            @csrf

            <!-- Field Selection -->
            <div>
                <label for="field_id" class="block text-sm font-bold text-[#cdd6f4] mb-2">
                    Pilih Lapangan Futsal <span class="text-[#f38ba8]">*</span>
                </label>
                <select name="field_id" 
                        id="field_id" 
                        x-model="selectedFieldId"
                        class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] rounded-2xl px-4 py-3.5 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                    <template x-for="f in fields" :key="f.id">
                        <option :value="f.id" x-text="f.name + ' (' + f.type.toUpperCase() + ') - Rp ' + Number(f.price_per_hour).toLocaleString('id-ID') + '/jam'"></option>
                    </template>
                </select>
                @error('field_id')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Start Time Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label for="booking_date" class="block text-sm font-bold text-[#cdd6f4] mb-2">
                        Tanggal Bermain <span class="text-[#f38ba8]">*</span>
                    </label>
                    <input type="date" 
                           name="booking_date" 
                           id="booking_date" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('booking_date', $date ?? date('Y-m-d')) }}"
                           required
                           class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                    @error('booking_date')
                        <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-bold text-[#cdd6f4] mb-2">
                        Jam Mulai <span class="text-[#f38ba8]">*</span>
                    </label>
                    <select name="start_time" 
                            id="start_time" 
                            required
                            class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
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
                        <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Duration Selection -->
            <div>
                <label class="block text-sm font-bold text-[#cdd6f4] mb-2">
                    Durasi Sewa <span class="text-[#f38ba8]">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 1 ? 'border-[#a6e3a1] bg-[#a6e3a1]/15 text-[#a6e3a1] font-bold' : 'border-[#45475a] bg-[#313244] text-[#cdd6f4] hover:border-[#585b70]'">
                        <input type="radio" name="duration_hours" value="1" x-model="duration" class="sr-only">
                        <span class="block text-base">1 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 2 ? 'border-[#a6e3a1] bg-[#a6e3a1]/15 text-[#a6e3a1] font-bold' : 'border-[#45475a] bg-[#313244] text-[#cdd6f4] hover:border-[#585b70]'">
                        <input type="radio" name="duration_hours" value="2" x-model="duration" class="sr-only">
                        <span class="block text-base">2 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 3 ? 'border-[#a6e3a1] bg-[#a6e3a1]/15 text-[#a6e3a1] font-bold' : 'border-[#45475a] bg-[#313244] text-[#cdd6f4] hover:border-[#585b70]'">
                        <input type="radio" name="duration_hours" value="3" x-model="duration" class="sr-only">
                        <span class="block text-base">3 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 4 ? 'border-[#a6e3a1] bg-[#a6e3a1]/15 text-[#a6e3a1] font-bold' : 'border-[#45475a] bg-[#313244] text-[#cdd6f4] hover:border-[#585b70]'">
                        <input type="radio" name="duration_hours" value="4" x-model="duration" class="sr-only">
                        <span class="block text-base">4 Jam</span>
                    </label>
                </div>
                @error('duration_hours')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-bold text-[#cdd6f4] mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          placeholder="Contoh: Butuh 2 set rompi tim dan bola futsal tambahan..."
                          class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Summary Box -->
            <div class="bg-[#11111b] rounded-2xl p-6 border border-[#313244] space-y-3">
                <div class="flex justify-between items-center text-sm text-[#a6adc8]">
                    <span>Harga Sewa Per Jam</span>
                    <span class="font-medium text-[#cdd6f4]" x-text="'Rp ' + getPricePerHour().toLocaleString('id-ID')"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-[#a6adc8]">
                    <span>Durasi</span>
                    <span class="font-medium text-[#cdd6f4]" x-text="duration + ' Jam'"></span>
                </div>
                <hr class="border-[#313244]">
                <div class="flex justify-between items-center">
                    <span class="text-base font-bold text-[#cdd6f4]">Total Pembayaran</span>
                    <span class="text-2xl font-extrabold text-[#a6e3a1]" x-text="'Rp ' + getTotalPrice().toLocaleString('id-ID')"></span>
                </div>
            </div>

            <!-- Security Assurance Note (Catppuccin Blue/Sapphire) -->
            <div class="flex items-start gap-3 p-4 rounded-2xl bg-[#89b4fa]/10 border border-[#89b4fa]/30 text-xs text-[#89b4fa]">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>
                    <strong>Perlindungan Jadwal KickFlow:</strong> Sistem database kami menerapkan kunci jadwal unik untuk memastikan tidak ada pemesan lain yang dapat memesan slot waktu yang sama dengan Anda secara bersamaan.
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold text-base rounded-2xl shadow-lg shadow-[#a6e3a1]/20 hover:scale-[1.01] transition duration-150">
                Konfirmasi Pemesanan Lapangan ⚽
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
