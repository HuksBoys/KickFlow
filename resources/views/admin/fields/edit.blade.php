@extends('layouts.app')

@section('title', 'Edit Lapangan ' . $field->name . ' - Admin KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">
                <span>[MASTER DATA]</span>
                <span>UPDATE VENUE</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">Edit Lapangan</h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Perbarui detail lapangan: {{ $field->name }}
            </p>
        </div>
        <div>
            <a href="{{ route('admin.fields.index') }}" 
               class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors inline-flex items-center gap-1.5">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#101814] rounded-xl border border-[#24362d] p-6 sm:p-8 text-[#f8fafc]">
        <form method="POST" action="{{ route('admin.fields.update', $field) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Lapangan -->
            <div>
                <label for="name" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Nama Lapangan <span class="text-[#ef4444]">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $field->name) }}" 
                       required 
                       placeholder="Contoh: Arena Futsal Garuda Court 1" 
                       class="w-full bg-[#15201a] border @error('name') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg px-3.5 py-2.5 text-sm text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981] transition-colors">
                @error('name')
                    <p class="mt-1.5 text-xs text-[#ef4444] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid 2 Kolom: Tipe & Harga per Jam -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Tipe Lapangan -->
                <div>
                    <label for="type" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                        Tipe Lapangan <span class="text-[#ef4444]">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            required 
                            class="w-full bg-[#15201a] border @error('type') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg px-3.5 py-2.5 text-sm text-[#f8fafc] focus:outline-none focus:border-[#10b981] transition-colors">
                        <option value="indoor" {{ old('type', $field->type) === 'indoor' ? 'selected' : '' }}>Indoor (Dalam Ruangan)</option>
                        <option value="outdoor" {{ old('type', $field->type) === 'outdoor' ? 'selected' : '' }}>Outdoor (Luar Ruangan)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-[#ef4444] font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarif per Jam -->
                <div>
                    <label for="price_per_hour" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                        Tarif Sewa per Jam (Rp) <span class="text-[#ef4444]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-mono text-[#64748b]">Rp</span>
                        <input type="number" 
                               id="price_per_hour" 
                               name="price_per_hour" 
                               value="{{ old('price_per_hour', (int) $field->price_per_hour) }}" 
                               required 
                               min="10000" 
                               step="1000" 
                               placeholder="150000" 
                               class="w-full bg-[#15201a] border @error('price_per_hour') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg pl-10 pr-3.5 py-2.5 text-sm text-[#f8fafc] font-mono placeholder-[#64748b] focus:outline-none focus:border-[#10b981] transition-colors">
                    </div>
                    @error('price_per_hour')
                        <p class="mt-1.5 text-xs text-[#ef4444] font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Deskripsi & Fasilitas Lapangan
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="w-full bg-[#15201a] border @error('description') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg p-3.5 text-sm text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981] transition-colors">{{ old('description', $field->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-[#ef4444] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Lapangan Saat Ini & Unggah Baru -->
            <div>
                <label for="image" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-2 font-semibold">
                    Foto Lapangan
                </label>
                
                @if($field->image_path)
                    <div class="mb-3 flex items-center gap-3 p-3 bg-[#0b110e] rounded-lg border border-[#1b2922] max-w-md">
                        <img src="{{ $field->imageUrl() }}" alt="{{ $field->name }}" class="w-14 h-14 rounded-md object-cover border border-[#24362d]">
                        <div>
                            <span class="text-xs font-bold text-[#f8fafc] block">Foto Saat Ini</span>
                            <span class="text-[11px] text-[#64748b]">Pilih file baru di bawah jika ingin mengganti gambar.</span>
                        </div>
                    </div>
                @endif

                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/jpeg,image/png,image/webp,image/jpg" 
                       class="w-full bg-[#15201a] border @error('image') border-[#ef4444] @else border-[#24362d] @enderror rounded-lg p-2 text-xs text-[#cbd5e1] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-[#10b981] file:text-[#070b09] hover:file:bg-[#34d399] file:cursor-pointer transition">
                <span class="text-[11px] text-[#64748b] block mt-1.5">Format file: JPG, PNG, WebP. Maksimal 2MB.</span>
                @error('image')
                    <p class="mt-1.5 text-xs text-[#ef4444] font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif Checkbox -->
            <div class="pt-2">
                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $field->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded bg-[#15201a] border-[#24362d] text-[#10b981] focus:ring-[#10b981]">
                    <div>
                        <span class="text-sm font-bold text-[#f8fafc]">Lapangan Aktif</span>
                        <p class="text-xs text-[#94a3b8]">Lapangan dapat dipesan pengguna jika dicentang aktif.</p>
                    </div>
                </label>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-[#1b2922] flex items-center justify-end gap-3">
                <a href="{{ route('admin.fields.index') }}" 
                   class="px-4 py-2 rounded-lg border border-[#24362d] text-xs font-medium text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#15201a] transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold transition-colors shadow-sm">
                    Perbarui Lapangan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
