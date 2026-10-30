@extends('layouts.app')

@section('title', 'Tambah Lapangan Baru - Admin KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#11111b] to-[#181825] text-[#cdd6f4] py-8 border-b border-[#313244]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#a6e3a1]/15 border border-[#a6e3a1]/30 text-[#a6e3a1] text-xs font-bold mb-2">
                <span>➕</span> Master Data Lapangan
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Tambah Lapangan Baru</h1>
            <p class="mt-1 text-[#a6adc8] text-sm">
                Daftarkan arena atau lapangan baru untuk disewakan kepada pengguna KickFlow.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.fields.index') }}" class="px-4 py-2 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#181825] rounded-3xl border border-[#313244] p-6 sm:p-8 shadow-sm text-[#cdd6f4]">
        <form method="POST" action="{{ route('admin.fields.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Nama Lapangan -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-2">
                    Nama Lapangan <span class="text-[#f38ba8]">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Contoh: Arena Futsal Garuda Court 1" 
                       class="w-full bg-[#313244] border @error('name') border-[#f38ba8] @else border-[#45475a] @enderror rounded-2xl px-4 py-3 text-sm text-[#cdd6f4] placeholder-[#6c7086] focus:outline-none focus:border-[#a6e3a1] transition">
                @error('name')
                    <p class="mt-1.5 text-xs text-[#f38ba8] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Tipe & Harga per Jam -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tipe Lapangan -->
                <div>
                    <label for="type" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-2">
                        Tipe Lapangan <span class="text-[#f38ba8]">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            required 
                            class="w-full bg-[#313244] border @error('type') border-[#f38ba8] @else border-[#45475a] @enderror rounded-2xl px-4 py-3 text-sm text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1] transition">
                        <option value="">Pilih Tipe Lapangan</option>
                        <option value="indoor" {{ old('type') === 'indoor' ? 'selected' : '' }}>🏢 Indoor (Dalam Ruangan)</option>
                        <option value="outdoor" {{ old('type') === 'outdoor' ? 'selected' : '' }}>🌤️ Outdoor (Luar Ruangan)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-[#f38ba8] font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarif per Jam -->
                <div>
                    <label for="price_per_hour" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-2">
                        Tarif Sewa per Jam (Rp) <span class="text-[#f38ba8]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[#a6adc8]">Rp</span>
                        <input type="number" 
                               id="price_per_hour" 
                               name="price_per_hour" 
                               value="{{ old('price_per_hour') }}" 
                               required 
                               min="10000" 
                               step="1000" 
                               placeholder="150000" 
                               class="w-full bg-[#313244] border @error('price_per_hour') border-[#f38ba8] @else border-[#45475a] @enderror rounded-2xl pl-11 pr-4 py-3 text-sm text-[#cdd6f4] placeholder-[#6c7086] focus:outline-none focus:border-[#a6e3a1] transition">
                    </div>
                    @error('price_per_hour')
                        <p class="mt-1.5 text-xs text-[#f38ba8] font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-2">
                    Deskripsi & Fasilitas Lapangan
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          placeholder="Jelaskan jenis lantai (rumput sintetis, vinyl, interlock), fasilitas pencahayaan LED, ruang ganti, tribun penonton, atau area parkir..." 
                          class="w-full bg-[#313244] border @error('description') border-[#f38ba8] @else border-[#45475a] @enderror rounded-2xl p-4 text-sm text-[#cdd6f4] placeholder-[#6c7086] focus:outline-none focus:border-[#a6e3a1] transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-[#f38ba8] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto Lapangan -->
            <div>
                <label for="image" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-2">
                    Foto Lapangan (Opsional)
                </label>
                <div class="flex items-center gap-4">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/jpeg,image/png,image/webp,image/jpg" 
                           class="w-full bg-[#313244] border @error('image') border-[#f38ba8] @else border-[#45475a] @enderror rounded-2xl p-2.5 text-xs text-[#cdd6f4] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#a6e3a1] file:text-[#11111b] hover:file:bg-[#94e2d5] file:cursor-pointer transition">
                </div>
                <span class="text-[11px] text-[#6c7086] block mt-1.5">Format file: JPG, PNG, WebP. Maksimal 2MB.</span>
                @error('image')
                    <p class="mt-1.5 text-xs text-[#f38ba8] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif Checkbox -->
            <div class="pt-2">
                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                           class="w-5 h-5 rounded-lg bg-[#313244] border-[#45475a] text-[#a6e3a1] focus:ring-[#a6e3a1] focus:ring-offset-[#181825]">
                    <div>
                        <span class="text-sm font-bold text-[#cdd6f4]">Aktifkan Lapangan Sekarang</span>
                        <p class="text-xs text-[#a6adc8]">Lapangan yang aktif akan langsung muncul di katalog dan dapat dipesan oleh pelanggan.</p>
                    </div>
                </label>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-[#313244] flex items-center justify-end gap-3">
                <a href="{{ route('admin.fields.index') }}" class="px-5 py-2.5 rounded-xl border border-[#45475a] text-xs font-semibold text-[#a6adc8] hover:text-[#cdd6f4] hover:bg-[#313244] transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold transition shadow-md shadow-[#a6e3a1]/20">
                    💾 Simpan Lapangan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
