@extends('layouts.app')

@section('title', 'Daftar Akun KickFlow')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-[#101814] p-6 sm:p-8 rounded-xl border border-[#24362d] shadow-2xl text-[#f8fafc]">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-xl bg-[#15201a] border border-[#24362d] flex items-center justify-center text-[#10b981] mx-auto mb-3">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
            </div>
            <h2 class="font-display text-2xl font-bold tracking-tight">Buat Akun KickFlow Baru</h2>
            <p class="mt-1 text-xs text-[#94a3b8]">Mulai reservasi lapangan futsal favoritmu dengan mudah dan instan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Nama Lengkap <span class="text-[#ef4444]">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="Contoh: Budi Santoso"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('name')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Alamat Email <span class="text-[#ef4444]">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@email.com"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('email')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Nomor WhatsApp / HP
                </label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="Contoh: 081234567890"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('phone')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Kata Sandi <span class="text-[#ef4444]">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="Minimal 8 karakter"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('password')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Konfirmasi Kata Sandi <span class="text-[#ef4444]">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       required
                       placeholder="Ulangi kata sandi"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-sm rounded-lg transition-colors shadow-sm">
                Daftar Akun Baru
            </button>
        </form>

        <div class="pt-4 border-t border-[#1b2922] text-center text-xs text-[#94a3b8]">
            Sudah memiliki akun KickFlow? 
            <a href="{{ route('login') }}" class="font-bold text-[#10b981] hover:underline">
                Masuk di sini
            </a>
        </div>
    </div>
</div>
@endsection
