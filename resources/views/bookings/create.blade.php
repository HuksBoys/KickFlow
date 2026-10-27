@extends('layouts.app')

@section('title', 'Pemesanan Lapangan - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-green-800 to-green-700 text-white py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Formulir Pemesanan Lapangan</h1>
        <p class="mt-2 text-green-100 text-sm">
            Isi detail pemesanan di bawah ini. Sistem KickFlow akan memvalidasi jadwal secara instan untuk mencegah bentrok.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10" 
         x-data="bookingForm({
            fields: {{ json_encode($fields) }},
            selectedFieldId: '{{ old('field_id', $field?->id ?? ($fields->first()?->id ?? '')) }}',
            duration: {{ old('duration_hours', 1) }}
         })">
        
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
            @csrf

            <!-- Field Selection -->
            <div>
                <label for="field_id" class="block text-sm font-bold text-gray-700 mb-2">
                    Pilih Lapangan Futsal <span class="text-red-500">*</span>
                </label>
                <select name="field_id" 
                        id="field_id" 
                        x-model="selectedFieldId"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                    <template x-for="f in fields" :key="f.id">
                        <option :value="f.id" x-text="f.name + ' (' + f.type.toUpperCase() + ') - Rp ' + Number(f.price_per_hour).toLocaleString('id-ID') + '/jam'"></option>
                    </template>
                </select>
                @error('field_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date and Start Time Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label for="booking_date" class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Bermain <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="booking_date" 
                           id="booking_date" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('booking_date', $date ?? date('Y-m-d')) }}"
                           required
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                    @error('booking_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-bold text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <select name="start_time" 
                            id="start_time" 
                            required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
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
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Duration Selection -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Durasi Sewa <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 1 ? 'border-green-600 bg-green-50 text-green-800 font-bold' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                        <input type="radio" name="duration_hours" value="1" x-model="duration" class="sr-only">
                        <span class="block text-base">1 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 2 ? 'border-green-600 bg-green-50 text-green-800 font-bold' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                        <input type="radio" name="duration_hours" value="2" x-model="duration" class="sr-only">
                        <span class="block text-base">2 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 3 ? 'border-green-600 bg-green-50 text-green-800 font-bold' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                        <input type="radio" name="duration_hours" value="3" x-model="duration" class="sr-only">
                        <span class="block text-base">3 Jam</span>
                    </label>
                    <label class="border rounded-2xl p-3.5 text-center cursor-pointer transition"
                           :class="duration == 4 ? 'border-green-600 bg-green-50 text-green-800 font-bold' : 'border-gray-200 text-gray-700 hover:bg-gray-50'">
                        <input type="radio" name="duration_hours" value="4" x-model="duration" class="sr-only">
                        <span class="block text-base">4 Jam</span>
                    </label>
                </div>
                @error('duration_hours')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="3" 
                          placeholder="Contoh: Butuh 2 set rompi tim dan bola futsal tambahan..."
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Summary Box -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200/80 space-y-3">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Harga Sewa Per Jam</span>
                    <span class="font-medium" x-text="'Rp ' + getPricePerHour().toLocaleString('id-ID')"></span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Durasi</span>
                    <span class="font-medium" x-text="duration + ' Jam'"></span>
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                    <span class="text-2xl font-extrabold text-green-600" x-text="'Rp ' + getTotalPrice().toLocaleString('id-ID')"></span>
                </div>
            </div>

            <!-- Security Assurance Note -->
            <div class="flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100 text-xs text-blue-800">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>
                    <strong>Perlindungan Jadwal KickFlow:</strong> Sistem database kami menerapkan kunci jadwal unik untuk memastikan tidak ada pemesan lain yang dapat memesan slot waktu yang sama dengan Anda secara bersamaan.
                </span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-base rounded-2xl shadow-lg shadow-green-600/30 hover:scale-[1.01] transition duration-150">
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
