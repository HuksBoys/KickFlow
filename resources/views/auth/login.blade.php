@extends('layouts.app')

@section('title', 'Masuk ke KickFlow')

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
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="mt-1 text-xs text-gray-500">Masuk ke akun Anda untuk mengelola jadwal booking futsal</p>
        </div>

        <!-- Quick Demo Credentials Box -->
        <div class="p-3.5 bg-green-50/80 rounded-2xl border border-green-200/80 text-xs text-green-900 space-y-1">
            <span class="font-bold block text-green-800">💡 Akun Demo Tersedia:</span>
            <div class="flex justify-between items-center text-[11px]">
                <span>Admin: <code>admin@kickflow.com</code></span>
                <span class="text-gray-500">pass: <code>password</code></span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span>Customer: <code>budi@gmail.com</code></span>
                <span class="text-gray-500">pass: <code>password</code></span>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Alamat Email
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@email.com"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Kata Sandi
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       placeholder="••••••••"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 focus:bg-white focus:outline-none transition">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-green-600 focus:ring-green-500 border-gray-300">
                    <span class="text-xs text-gray-600">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow-md shadow-green-600/30 transition duration-150">
                Masuk ke Akun
            </button>
        </form>

        <div class="text-center pt-2 border-t border-gray-100">
            <p class="text-xs text-gray-500">
                Belum memiliki akun KickFlow? 
                <a href="{{ route('register') }}" class="font-bold text-green-600 hover:text-green-700">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
