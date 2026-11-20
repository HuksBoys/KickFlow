@extends('layouts.app')

@section('title', 'Edit Item Tambahan - Admin KickFlow')

@section('content')
<div class="bg-[#0b110e] border-b border-[#1b2922] py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
        <div>
            <div class="text-xs font-mono uppercase tracking-wider text-[#10b981] font-semibold mb-1">[BOOKING ADD-ONS]</div>
            <h1 class="font-display text-2xl font-bold tracking-tight text-[#f8fafc]">Edit Item Tambahan</h1>
            <p class="mt-1 text-xs text-[#94a3b8]">Perbarui nama, harga, atau status ketersediaan item.</p>
        </div>
        <a href="{{ route('admin.addons.index') }}" class="px-3.5 py-2 bg-[#15201a] hover:bg-[#1b2922] border border-[#24362d] text-[#cbd5e1] text-xs font-medium rounded-lg transition-colors">← Kembali</a>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#101814] rounded-xl border border-[#24362d] p-6 sm:p-8 text-[#f8fafc]">
        <form method="POST" action="{{ route('admin.addons.update', $addon) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.addons._form')
            <div class="pt-6 border-t border-[#1b2922] flex justify-end gap-3">
                <a href="{{ route('admin.addons.index') }}" class="px-4 py-2 rounded-lg border border-[#24362d] text-xs font-medium text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#15201a] transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-[#10b981] hover:bg-[#34d399] text-[#070b09] text-xs font-bold transition-colors">Perbarui Item</button>
            </div>
        </form>
    </div>
</div>
@endsection
