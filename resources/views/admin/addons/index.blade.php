@extends('layouts.app')

@section('title', 'Item Tambahan Booking - Admin KickFlow')

@section('content')
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">[BOOKING ADD-ONS]</div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-[#f8fafc]">Item Tambahan Booking</h1>
            <p class="mt-1 text-xs text-[#94a3b8]">Kelola rompi, minuman, dan kebutuhan tambahan customer.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fields.index') }}" class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors">Kelola Lapangan</a>
            <a href="{{ route('admin.addons.create') }}" class="px-4 py-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold rounded-lg transition-colors">+ Tambah Item</a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#101814] rounded-xl border border-[#24362d] overflow-hidden text-[#f8fafc]">
        <table class="min-w-full divide-y divide-[#1b2922] text-xs">
            <thead class="bg-[#0b110e] text-left text-[11px] font-mono text-[#64748b] uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Item</th>
                    <th class="px-5 py-3.5">Harga</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1b2922]">
                @forelse($addons as $addon)
                    <tr class="hover:bg-[#15201a]/60 transition-colors">
                        <td class="px-5 py-4 font-display font-bold text-[#f8fafc]">{{ $addon->name }}</td>
                        <td class="px-5 py-4 font-mono font-bold text-[#34d399]">Rp {{ number_format($addon->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold {{ $addon->is_active ? 'bg-[#10b981]/15 text-[#34d399] border border-[#10b981]/30' : 'bg-[#ef4444]/15 text-[#fca5a5] border border-[#ef4444]/30' }}">{{ $addon->is_active ? 'Aktif' : 'Non-aktif' }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.addons.edit', $addon) }}" class="px-3 py-1.5 rounded-lg bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#38bdf8] text-xs font-medium transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.addons.destroy', $addon) }}" onsubmit="return confirm('Hapus item {{ $addon->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-[#ef4444]/40 hover:bg-[#ef4444]/10 text-[#ef4444] text-xs font-medium transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-[#64748b]">Belum ada item tambahan. Tambahkan rompi, minuman, atau kebutuhan lain.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($addons->hasPages())
            <div class="p-4 border-t border-[#1b2922]">{{ $addons->links() }}</div>
        @endif
    </div>
</div>
@endsection
