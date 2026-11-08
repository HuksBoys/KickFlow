@extends('layouts.app')

@section('title', 'Masuk ke KickFlow')

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
            <h2 class="font-display text-2xl font-bold tracking-tight">Selamat Datang Kembali</h2>
            <p class="mt-1 text-xs text-[#94a3b8]">Masuk ke akun Anda untuk mengelola reservasi futsal</p>
        </div>

        <!-- Quick Demo Credentials Box -->
        <div class="p-3.5 bg-[#0b110e] rounded-lg border border-[#1b2922] text-xs space-y-1.5 font-mono">
            <span class="font-bold text-[#10b981] block text-[11px] uppercase tracking-wider">AKUN DEMO CEPAT:</span>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-[#94a3b8]">Admin: <code class="text-[#38bdf8]">admin@kickflow.com</code></span>
                <span class="text-[#64748b]">pass: <code class="text-[#f59e0b]">password</code></span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-[#94a3b8]">Customer: <code class="text-[#38bdf8]">budi@gmail.com</code></span>
                <span class="text-[#64748b]">pass: <code class="text-[#f59e0b]">password</code></span>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Alamat Email
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@email.com"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('email')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-mono uppercase tracking-wider text-[#94a3b8] mb-1.5 font-semibold">
                    Kata Sandi
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="••••••••"
                       class="w-full bg-[#15201a] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#10b981] transition-colors">
                @error('password')
                    <p class="text-xs text-[#ef4444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer select-none text-[#94a3b8]">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#15201a] border-[#24362d] text-[#10b981] focus:ring-[#10b981]">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold text-sm rounded-lg transition-colors shadow-sm">
                Masuk ke Akun
            </button>
        </form>

        <div class="pt-4 border-t border-[#1b2922] text-center text-xs text-[#94a3b8]">
            Belum memiliki akun KickFlow? 
            <a href="{{ route('register') }}" class="font-bold text-[#10b981] hover:underline">
                Daftar sekarang
            </a>
        </div>
    </div>
</div>
@endsection
