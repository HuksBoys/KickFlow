@extends('layouts.app')

@section('title', 'Daftar Akun KickFlow')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-gray-100 shadow-xl">
        
        <!-- Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-green-700 to-emerald-500 flex items-center justify-center text-white mx-auto shadow-md shadow-green-500/30 mb-3">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Akun KickFlow Baru</h2>
            <p class="mt-1 text-xs text-gray-500">Mulai reservasi lapangan futsal favoritmu dengan mudah dan instan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus
                       placeholder="Contoh: Budi Santoso"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@email.com"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Nomor WhatsApp / HP
                </label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}" 
                       placeholder="Contoh: 081234567890"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('phone')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Kata Sandi <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="Minimal 8 karakter"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       required
                       placeholder="Ulangi kata sandi"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow-md shadow-green-600/30 transition duration-150 mt-2">
                Daftar & Mulai Booking
            </button>
        </form>

        <div class="text-center pt-2 border-t border-gray-100">
            <p class="text-xs text-gray-500">
                Sudah memiliki akun KickFlow? 
                <a href="{{ route('login') }}" class="font-bold text-green-600 hover:text-green-700">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
