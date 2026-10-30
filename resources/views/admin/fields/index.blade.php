@extends('layouts.app')

@section('title', 'Kelola Lapangan - Admin KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#11111b] to-[#181825] text-[#cdd6f4] py-8 border-b border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#cba6f7]/15 border border-[#cba6f7]/30 text-[#cba6f7] text-xs font-bold mb-2">
                <span>🏟️</span> Manajemen Lapangan • Admin Panel
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Daftar Lapangan Futsal</h1>
            <p class="mt-1 text-[#a6adc8] text-sm">
                Kelola master data lapangan, ubah tarif per jam, dan pantau status aktif lapangan.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cashier.index') }}" class="px-4 py-2.5 bg-[#313244] hover:bg-[#45475a] border border-[#45475a] text-[#cdd6f4] text-xs font-semibold rounded-xl transition flex items-center gap-2">
                <span>💳</span> Kasir & Pesanan
            </a>
            <a href="{{ route('admin.fields.create') }}" class="px-5 py-2.5 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] text-xs font-bold rounded-xl transition shadow-md shadow-[#a6e3a1]/20 flex items-center gap-2">
                <span>➕</span> Tambah Lapangan
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Filter & Search Bar -->
    <div class="bg-[#181825] rounded-2xl p-4 border border-[#313244]">
        <form method="GET" action="{{ route('admin.fields.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-semibold text-[#a6adc8] mb-1">Cari Nama Lapangan</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Contoh: Garuda Sport..." 
                       class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] placeholder-[#6c7086] focus:outline-none focus:border-[#a6e3a1]">
            </div>

            <!-- Filter Type -->
            <div>
                <label for="type" class="block text-xs font-semibold text-[#a6adc8] mb-1">Tipe Lapangan</label>
                <select id="type" name="type" class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1]">
                    <option value="semua">Semua Tipe</option>
                    <option value="indoor" {{ request('type') === 'indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="outdoor" {{ request('type') === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-[#a6adc8] mb-1">Status Lapangan</label>
                <select id="status" name="status" class="w-full bg-[#313244] border border-[#45475a] rounded-xl px-3.5 py-2 text-xs text-[#cdd6f4] focus:outline-none focus:border-[#a6e3a1]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#cba6f7] hover:bg-[#b4befe] text-[#11111b] text-xs font-bold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.fields.index') }}" class="py-2 px-3 bg-[#313244] hover:bg-[#45475a] text-[#a6adc8] text-xs font-semibold rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Fields Table -->
    <div class="bg-[#181825] rounded-3xl border border-[#313244] shadow-sm overflow-hidden text-[#cdd6f4]">
        <div class="p-5 border-b border-[#313244] flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-[#cdd6f4]">Daftar Lapangan ({{ $fields->total() }})</h3>
                <p class="text-xs text-[#a6adc8]">Total seluruh lapangan yang terdaftar dalam sistem KickFlow</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#313244] text-sm">
                <thead class="bg-[#11111b] text-left text-xs font-bold text-[#a6adc8] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Foto & Lapangan</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Tarif Sewa</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#313244]">
                    @forelse($fields as $field)
                        <tr class="hover:bg-[#313244]/40 transition">
                            <!-- Nama & Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-[#313244] border border-[#45475a] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        @if($field->image_path)
                                            <img src="{{ asset('storage/' . $field->image_path) }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl">🏟️</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('fields.show', $field) }}" target="_blank" class="font-bold text-[#cdd6f4] hover:text-[#a6e3a1] transition">
                                            {{ $field->name }}
                                        </a>
                                        <p class="text-xs text-[#6c7086] line-clamp-1 max-w-sm mt-0.5">
                                            {{ $field->description ?? 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipe -->
                            <td class="px-6 py-4">
                                @if($field->type === 'indoor')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#89b4fa]/15 text-[#89b4fa] border border-[#89b4fa]/30">
                                        🏢 Indoor
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-[#fab387]/15 text-[#fab387] border border-[#fab387]/30">
                                        🌤️ Outdoor
                                    </span>
                                @endif
                            </td>

                            <!-- Tarif -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#a6e3a1] text-sm">
                                    Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                                </div>
                                <span class="text-[11px] text-[#6c7086]">/ jam</span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($field->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#a6e3a1]"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-[#f38ba8]/15 text-[#f38ba8] border border-[#f38ba8]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#f38ba8]"></span> Non-aktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Toggle Status Button -->
                                    <form method="POST" action="{{ route('admin.fields.toggle-status', $field) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="{{ $field->is_active ? 'Nonaktifkan Lapangan' : 'Aktifkan Lapangan' }}"
                                                class="p-2 rounded-xl border border-[#45475a] hover:border-[#a6e3a1] text-xs font-semibold text-[#a6adc8] hover:text-[#a6e3a1] bg-[#313244] transition">
                                            @if($field->is_active)
                                                ⏸️
                                            @else
                                                ▶️
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.fields.edit', $field) }}" 
                                       title="Edit Lapangan"
                                       class="p-2 rounded-xl border border-[#45475a] hover:border-[#cba6f7] text-xs font-semibold text-[#a6adc8] hover:text-[#cba6f7] bg-[#313244] transition">
                                        ✏️
                                    </a>

                                    <!-- Delete Button -->
                                    <form method="POST" 
                                          action="{{ route('admin.fields.destroy', $field) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini? Data yang terkait mungkin akan terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Lapangan"
                                                class="p-2 rounded-xl border border-[#45475a] hover:border-[#f38ba8] text-xs font-semibold text-[#a6adc8] hover:text-[#f38ba8] bg-[#313244] transition">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#6c7086]">
                                Belum ada lapangan yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fields->hasPages())
            <div class="p-4 border-t border-[#313244]">
                {{ $fields->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
