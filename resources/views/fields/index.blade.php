@extends('layouts.app')

@section('title', 'Daftar Lapangan Futsal - KickFlow')

@section('content')
<div class="bg-gradient-to-b from-[#181825] to-[#1e1e2e] text-[#cdd6f4] py-12 border-b border-[#313244]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Daftar Lapangan Futsal</h1>
        <p class="mt-2 text-[#a6adc8] text-sm max-w-xl">
            Pilih lapangan berkualitas terbaik dengan fasilitas lengkap dan booking langsung secara online.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter and Sort Bar (Catppuccin Mantle) -->
    <div class="bg-[#181825] rounded-3xl shadow-sm border border-[#313244] p-4 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Type Filter -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
            <span class="text-xs font-semibold text-[#a6adc8] uppercase tracking-wider mr-2">Tipe:</span>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'semua'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('type') || request('type') === 'semua' ? 'bg-[#a6e3a1] text-[#11111b] shadow-sm font-bold' : 'bg-[#313244] text-[#bac2de] hover:bg-[#45475a]' }}">
                Semua
            </a>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'indoor'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('type') === 'indoor' ? 'bg-[#a6e3a1] text-[#11111b] shadow-sm font-bold' : 'bg-[#313244] text-[#bac2de] hover:bg-[#45475a]' }}">
                Indoor 🏟️
            </a>
            <a href="{{ route('fields.index', array_merge(request()->query(), ['type' => 'outdoor'])) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('type') === 'outdoor' ? 'bg-[#a6e3a1] text-[#11111b] shadow-sm font-bold' : 'bg-[#313244] text-[#bac2de] hover:bg-[#45475a]' }}">
                Outdoor ☀️
            </a>
        </div>

        <!-- Sort Filter -->
        <form method="GET" action="{{ route('fields.index') }}" class="flex items-center gap-2 w-full md:w-auto justify-end">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            <label for="sort" class="text-xs font-semibold text-[#a6adc8] uppercase tracking-wider whitespace-nowrap">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" class="bg-[#313244] border border-[#45475a] text-[#cdd6f4] text-xs rounded-xl px-3 py-2 focus:ring-1 focus:ring-[#a6e3a1] focus:outline-none">
                <option value="nama" {{ request('sort') === 'nama' ? 'selected' : '' }}>Nama Lapangan (A-Z)</option>
                <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga: Termahal</option>
            </select>
        </form>
    </div>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($fields as $field)
            <div class="bg-[#181825] rounded-3xl border border-[#313244] overflow-hidden shadow-sm hover:shadow-xl hover:border-[#45475a] transition duration-200 flex flex-col group">
                <!-- Image Header -->
                <div class="h-48 bg-gradient-to-tr from-[#11111b] to-[#313244] relative flex items-center justify-center border-b border-[#313244]">
                    <svg class="w-16 h-16 text-[#45475a] group-hover:scale-110 group-hover:text-[#a6e3a1]/30 transition duration-200" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                    </svg>

                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $field->type === 'indoor' ? 'bg-[#89b4fa]/20 text-[#89b4fa] border border-[#89b4fa]/30' : 'bg-[#fab387]/20 text-[#fab387] border border-[#fab387]/30' }}">
                        {{ strtoupper($field->type) }}
                    </span>

                    <span class="absolute top-4 right-4 px-2.5 py-1 rounded-full text-xs font-bold bg-[#a6e3a1]/15 text-[#a6e3a1] border border-[#a6e3a1]/30">
                        Tersedia
                    </span>
                </div>

                <!-- Body -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-[#cdd6f4] group-hover:text-[#a6e3a1] transition">
                            {{ $field->name }}
                        </h3>
                        <p class="mt-2 text-sm text-[#a6adc8] line-clamp-3">
                            {{ $field->description ?? 'Lapangan futsal profesional dengan rumput berkualitas dan fasilitas pencahayaan memadai.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#313244] flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#6c7086] block">Harga Sewa</span>
                            <span class="text-lg font-extrabold text-[#a6e3a1]">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-[#6c7086]">/ jam</span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('fields.show', $field) }}" class="px-3.5 py-2 bg-[#313244] hover:bg-[#45475a] text-[#cdd6f4] font-semibold text-xs rounded-xl transition">
                                Detail
                            </a>
                            <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}" class="px-4 py-2 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold text-xs rounded-xl shadow-sm shadow-[#a6e3a1]/20 transition">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-[#181825] rounded-3xl border border-[#313244]">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-lg font-bold text-[#cdd6f4]">Tidak ada lapangan yang cocok</h3>
                <p class="text-sm text-[#a6adc8] mt-1">Coba ubah filter pencarian Anda di atas.</p>
                <a href="{{ route('fields.index') }}" class="mt-4 inline-block px-4 py-2 bg-[#a6e3a1] text-[#11111b] text-xs font-bold rounded-xl">
                    Reset Filter
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $fields->links() }}
    </div>
</div>
@endsection
