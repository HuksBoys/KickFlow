@extends('layouts.app')

@section('title', 'Daftar Akun KickFlow')

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
            <h2 class="text-2xl font-extrabold text-[#cdd6f4] tracking-tight">Buat Akun KickFlow Baru</h2>
            <p class="mt-1 text-xs text-[#a6adc8]">Mulai reservasi lapangan futsal favoritmu dengan mudah dan instan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Nama Lengkap <span class="text-[#f38ba8]">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="Contoh: Budi Santoso"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('name')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Alamat Email <span class="text-[#f38ba8]">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@email.com"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('email')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Nomor WhatsApp / HP
                </label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="Contoh: 081234567890"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('phone')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Kata Sandi <span class="text-[#f38ba8]">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="Minimal 8 karakter"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
                @error('password')
                    <p class="text-xs text-[#f38ba8] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-[#a6adc8] mb-1.5">
                    Konfirmasi Kata Sandi <span class="text-[#f38ba8]">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       required
                       placeholder="Ulangi kata sandi"
                       class="w-full bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-2xl px-4 py-3 text-sm focus:ring-1 focus:ring-[#a6e3a1] focus:border-[#a6e3a1] focus:outline-none transition">
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold text-sm rounded-2xl shadow-md shadow-[#a6e3a1]/20 transition duration-150 mt-2">
                Daftar & Mulai Booking
            </button>
        </form>

        <div class="text-center pt-2 border-t border-[#313244]">
            <p class="text-xs text-[#a6adc8]">
                Sudah memiliki akun KickFlow? 
                <a href="{{ route('login') }}" class="font-bold text-[#a6e3a1] hover:text-[#94e2d5]">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
