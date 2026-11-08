@extends('layouts.app')

@section('title', 'Kelola Lapangan - Admin KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">
                <span>[VENUE MANAGEMENT]</span>
                <span>ADMIN PANEL</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">Daftar Lapangan Futsal</h1>
            <p class="mt-1 text-xs text-[#94a3b8]">
                Kelola master data lapangan, sesuaikan tarif per jam, dan pantau status ketersediaan venue.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.cashier.index') }}" 
               class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors flex items-center gap-1.5">
                <span>Kasir & Pesanan</span>
            </a>
            <a href="{{ route('admin.fields.create') }}" 
               class="px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold rounded-lg transition-colors shadow-sm flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Tambah Lapangan</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Filter & Search Bar -->
    <div class="bg-[#101814] rounded-xl p-4 border border-[#24362d]">
        <form method="GET" action="{{ route('admin.fields.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Cari Nama Lapangan</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Contoh: Garuda Sport..." 
                       class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] placeholder-[#64748b] focus:outline-none focus:border-[#10b981]">
            </div>

            <!-- Filter Type -->
            <div>
                <label for="type" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Tipe Lapangan</label>
                <select id="type" name="type" 
                        class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] focus:outline-none focus:border-[#10b981]">
                    <option value="semua">Semua Tipe</option>
                    <option value="indoor" {{ request('type') === 'indoor' ? 'selected' : '' }}>Indoor</option>
                    <option value="outdoor" {{ request('type') === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-xs font-mono text-[#64748b] uppercase mb-1">Status Lapangan</label>
                <select id="status" name="status" 
                        class="w-full bg-[#15201a] border border-[#24362d] rounded-lg px-3 py-2 text-xs text-[#f8fafc] focus:outline-none focus:border-[#10b981]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-3 bg-[#15201a] hover:bg-[#1b2922] text-[#34d399] border border-[#24362d] text-xs font-bold rounded-lg transition-colors">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.fields.index') }}" 
                       class="py-2 px-3 bg-[#101814] hover:bg-[#15201a] text-[#64748b] text-xs font-medium rounded-lg border border-[#24362d] transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Fields Table -->
    <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        <div class="p-4 sm:p-5 border-b border-[#1b2922] flex items-center justify-between">
            <div>
                <h3 class="font-display text-sm font-bold text-[#f8fafc]">Daftar Lapangan ({{ $fields->total() }})</h3>
                <p class="text-xs text-[#94a3b8]">Total seluruh lapangan yang terdaftar dalam sistem KickFlow</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#1b2922] text-xs">
                <thead class="bg-[#0b110e] text-left text-[11px] font-mono text-[#64748b] uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Foto & Lapangan</th>
                        <th class="px-5 py-3.5">Tipe</th>
                        <th class="px-5 py-3.5">Tarif Sewa</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1b2922]">
                    @forelse($fields as $field)
                        <tr class="hover:bg-[#15201a]/60 transition-colors">
                            <!-- Nama & Info -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-lg bg-[#15201a] border border-[#24362d] overflow-hidden flex-shrink-0 flex items-center justify-center text-[#10b981]">
                                        @if($field->image_path)
                                            <img src="{{ asset('storage/' . $field->image_path) }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-[#4a6b5c]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <circle cx="12" cy="12" r="10"/>
                                                <circle cx="12" cy="12" r="2"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('fields.show', $field) }}" target="_blank" class="font-display font-bold text-[#f8fafc] hover:text-[#34d399] transition-colors">
                                            {{ $field->name }}
                                        </a>
                                        <p class="text-[11px] text-[#64748b] line-clamp-1 max-w-sm mt-0.5">
                                            {{ $field->description ?? 'Tidak ada deskripsi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipe -->
                            <td class="px-5 py-4">
                                @if($field->type === 'indoor')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30">
                                        Indoor Vinyl
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30">
                                        Outdoor Sintetis
                                    </span>
                                @endif
                            </td>

                            <!-- Tarif -->
                            <td class="px-5 py-4 font-mono font-bold text-[#34d399]">
                                <div>Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                                <span class="text-[10px] text-[#64748b] font-normal">/ jam</span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @if($field->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#ef4444]"></span> Non-aktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Toggle Status Button -->
                                    <form method="POST" action="{{ route('admin.fields.toggle-status', $field) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="{{ $field->is_active ? 'Nonaktifkan Lapangan' : 'Aktifkan Lapangan' }}"
                                                class="p-2 rounded-lg border border-[#24362d] hover:border-[#10b981] text-[#94a3b8] hover:text-[#34d399] bg-[#15201a] transition-colors">
                                            @if($field->is_active)
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="4" height="16" x="6" y="4"/>
                                                    <rect width="4" height="16" x="14" y="4"/>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.fields.edit', $field) }}" 
                                       title="Edit Lapangan"
                                       class="p-2 rounded-lg border border-[#24362d] hover:border-[#38bdf8] text-[#94a3b8] hover:text-[#38bdf8] bg-[#15201a] transition-colors">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form method="POST" 
                                          action="{{ route('admin.fields.destroy', $field) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini? Data yang terkait mungkin akan terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Lapangan"
                                                class="p-2 rounded-lg border border-[#24362d] hover:border-[#ef4444] text-[#64748b] hover:text-[#ef4444] bg-[#15201a] transition-colors">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-[#64748b]">
                                Belum ada lapangan yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fields->hasPages())
            <div class="p-4 border-t border-[#1b2922]">
                {{ $fields->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
