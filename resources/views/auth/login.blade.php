@extends('layouts.app')

@section('title', 'Masuk ke KickFlow')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-[#181825] p-8 sm:p-10 rounded-3xl border border-[#313244] shadow-2xl text-[#cdd6f4]">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#a6e3a1] to-[#94e2d5] flex items-center justify-center text-[#11111b] mx-auto shadow-md shadow-[#a6e3a1]/20 mb-3">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-[#cdd6f4] tracking-tight">Selamat Datang Kembali</h2>
            <p class="mt-1 text-xs text-[#a6adc8]">Masuk ke akun Anda untuk mengelola jadwal booking futsal</p>
        </div>

        <!-- Quick Demo Credentials Box (Catppuccin Surface0) -->
        <div class="p-4 bg-[#313244] rounded-2xl border border-[#45475a] text-xs text-[#cdd6f4] space-y-1.5">
            <span class="font-bold block text-[#a6e3a1]">💡 Akun Demo Siap Pakai:</span>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-[#bac2de]">Admin: <code class="text-[#89b4fa]">admin@kickflow.com</code></span>
                <span class="text-[#a6adc8]">pass: <code class="text-[#f9e2af]">password</code></span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-[#bac2de]">Customer: <code class="text-[#89b4fa]">budi@gmail.com</code></span>
                <span class="text-[#a6adc8]">pass: <code class="text-[#f9e2af]">password</code></span>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Alamat Email
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@email.com"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('email')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="••••••••"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('password')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#a6e3a1] focus:ring-[#a6e3a1] bg-[#313244] border-[#45475a]">
                    <span class="text-xs text-[#a6adc8]">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold text-sm rounded-2xl shadow-md shadow-[#a6e3a1]/20 transition duration-150">
                Masuk ke Akun
            </button>
        </form>

        <div class="text-center pt-2 border-t border-[#313244]">
            <p class="text-xs text-[#a6adc8]">
                Belum memiliki akun KickFlow? 
                <a href="{{ route('register') }}" class="font-bold text-[#a6e3a1] hover:text-[#94e2d5]">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
