@extends('layouts.app')

@section('title', 'Daftar Lapangan Futsal - KickFlow')

@section('content')
<!-- Header Banner -->
<div class="bg-[#0b110e] border-b border-[#1b2922] py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-2">
            <span>[VENUES]</span>
            <span>KIKFLOW ARENA CATALOG</span>
        </div>
        <h1 class="font-display text-3xl font-bold tracking-tight text-[#f8fafc]">Daftar Lapangan Futsal</h1>
        <p class="mt-1 text-sm text-[#94a3b8] max-w-xl">
            Pilih arena futsal berstandar kompetisi dengan fasilitas lengkap dan booking jadwal secara instan online.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filter and Sort Bar -->
    <div class="bg-[#101814] rounded-xl border border-[#24362d] p-3.5 mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Type Filter Buttons -->
        <div class="flex items-center gap-1.5 overflow-x-auto">
            <span class="text-xs font-mono text-[#64748b] uppercase tracking-wider mr-2 hidden sm:inline">Tipe:</span>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'semua'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ !request('type') || request('type') === 'semua' ? 'bg-[#10b981] text-[#070b09]' : 'bg-[#15201a] text-[#94a3b8] hover:text-[#f8fafc] border border-[#24362d]' }}">
                Semua Lapangan
            </a>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'indoor'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('type') === 'indoor' ? 'bg-[#10b981] text-[#070b09]' : 'bg-[#15201a] text-[#94a3b8] hover:text-[#f8fafc] border border-[#24362d]' }}">
                Indoor Vinyl
            </a>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'outdoor'])) }}" 
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('type') === 'outdoor' ? 'bg-[#10b981] text-[#070b09]' : 'bg-[#15201a] text-[#94a3b8] hover:text-[#f8fafc] border border-[#24362d]' }}">
                Outdoor Sintetis
            </a>
        </div>

        <!-- Sort Filter -->
        <form method="GET" action="{{ route('fields.index') }}" class="flex items-center gap-2 justify-end">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            <label for="sort" class="text-xs font-mono text-[#64748b] uppercase tracking-wider whitespace-nowrap">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" 
                    class="bg-[#15201a] border border-[#24362d] text-[#f8fafc] text-xs rounded-lg px-3 py-1.5 focus:ring-1 focus:ring-[#10b981] focus:border-[#10b981] focus:outline-none">
                <option value="nama" {{ request('sort') === 'nama' ? 'selected' : '' }}>Nama Lapangan (A-Z)</option>
                <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Tarif: Termurah</option>
                <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Tarif: Termahal</option>
            </select>
        </form>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fields as $field)
            <div class="bg-[#101814] rounded-xl border border-[#24362d] hover:border-[#10b981]/50 transition-all duration-150 flex flex-col group overflow-hidden">
                
                <!-- Image / Pitch Graphics Header -->
                <div class="h-44 bg-[#0b110e] border-b border-[#1b2922] relative flex items-center justify-center p-4">
                    <div class="w-full h-full border border-[#24362d] rounded-lg flex items-center justify-center relative bg-[#070b09]/60">
                        <div class="absolute inset-y-0 left-1/2 w-px bg-[#24362d]"></div>
                        <div class="w-14 h-14 rounded-full border border-[#24362d] flex items-center justify-center text-[#4a6b5c]">
                            <svg class="w-6 h-6 group-hover:text-[#10b981] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m4.93 4.93 4.24 4.24"/>
                                <path d="m14.83 9.17 4.24-4.24"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                    </div>

                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30' : 'bg-[#f59e0b]/15 text-[#f59e0b] border border-[#f59e0b]/30' }}">
                        {{ $field->type }}
                    </span>

                    <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-mono font-semibold bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30">
                        Tersedia
                    </span>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-display text-lg font-bold text-[#f8fafc] group-hover:text-[#34d399] transition-colors">
                            {{ $field->name }}
                        </h3>
                        <p class="mt-1.5 text-xs text-[#94a3b8] line-clamp-2 leading-relaxed">
                            {{ $field->description ?? 'Lapangan futsal profesional dengan rumput berkualitas dan fasilitas pencahayaan memadai.' }}
                        </p>
                    </div>

                    <!-- Facility Badges -->
                    <div class="flex items-center gap-1.5 text-[10px] font-mono text-[#64748b]">
                        <span class="px-2 py-0.5 rounded bg-[#15201a] border border-[#24362d]">Standar Turnamen</span>
                        <span class="px-2 py-0.5 rounded bg-[#15201a] border border-[#24362d]">Pencahayaan LED</span>
                    </div>

                    <!-- Price & Actions -->
                    <div class="pt-3 border-t border-[#1b2922] flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-[#64748b] uppercase tracking-wider block">Tarif Sewa</span>
                            <span class="font-mono text-base font-bold text-[#34d399]">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-[#64748b]">/jam</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('fields.show', $field) }}" 
                               class="px-3 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] font-medium text-xs rounded-lg transition-colors">
                                Detail
                            </a>
                            <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}" 
                               class="px-3.5 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-xs rounded-lg transition-colors shadow-sm">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-[#94a3b8] bg-[#101814] rounded-xl border border-[#24362d]">
                Tidak ada lapangan yang sesuai dengan kriteria filter Anda.
            </div>
        @endforelse
    </div>
</div>
@endsection
