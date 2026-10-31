<!DOCTYPE html>
<html lang="id" class="h-full bg-[#1e1e2e]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KickFlow - Sistem Pemesanan Lapangan Futsal')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|inter:400,500,600,700" rel="stylesheet" />

    <!-- Alpine.js CDN for immediate functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS CDN fallback & Vite assets with Catppuccin Theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ctp: {
                            rosewater: '#f5e0dc',
                            flamingo: '#f2cdcd',
                            pink: '#f5c2e7',
                            mauve: '#cba6f7',
                            red: '#f38ba8',
                            maroon: '#eba0ac',
                            peach: '#fab387',
                            yellow: '#f9e2af',
                            green: '#a6e3a1',
                            teal: '#94e2d5',
                            sky: '#89dceb',
                            sapphire: '#74c7ec',
                            blue: '#89b4fa',
                            lavender: '#b4befe',
                            text: '#cdd6f4',
                            subtext1: '#bac2de',
                            subtext0: '#a6adc8',
                            overlay2: '#9399b2',
                            overlay1: '#7f849c',
                            overlay0: '#6c7086',
                            surface2: '#585b70',
                            surface1: '#45475a',
                            surface0: '#313244',
                            base: '#1e1e2e',
                            mantle: '#181825',
                            crust: '#11111b',
                        }
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-[#1e1e2e] text-[#cdd6f4] antialiased font-sans selection:bg-[#a6e3a1]/20 selection:text-[#a6e3a1]">

    <!-- Navbar (Catppuccin Mantle) -->
    <nav x-data="{ mobileOpen: false }" class="bg-[#181825] border-b border-[#313244] sticky top-0 z-40 backdrop-blur-md bg-opacity-95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#a6e3a1] to-[#94e2d5] flex items-center justify-center text-[#11111b] shadow-md shadow-[#a6e3a1]/20 group-hover:scale-105 transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <path d="M12 2a10 10 0 0 1 10 10M12 22a10 10 0 0 1-10-10" stroke="currentColor" stroke-width="1.5"/>
                                <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-[#cdd6f4] group-hover:text-[#a6e3a1] transition">Kick<span class="text-[#a6e3a1]">Flow</span></span>
                            <span class="text-[10px] uppercase tracking-wider font-semibold text-[#a6adc8] -mt-1">Futsal Arena</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex md:ml-10 md:space-x-1">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('home') ? 'bg-[#313244] text-[#a6e3a1]' : 'text-[#bac2de] hover:text-[#a6e3a1] hover:bg-[#313244]/50' }} transition">
                            Beranda
                        </a>
                        <a href="{{ route('fields.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('fields.*') ? 'bg-[#313244] text-[#a6e3a1]' : 'text-[#bac2de] hover:text-[#a6e3a1] hover:bg-[#313244]/50' }} transition">
                            Daftar Lapangan
                        </a>
                        @auth
                            <a href="{{ route('bookings.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('bookings.*') ? 'bg-[#313244] text-[#a6e3a1]' : 'text-[#bac2de] hover:text-[#a6e3a1] hover:bg-[#313244]/50' }} transition">
                                Riwayat Pemesanan
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#313244] text-[#cba6f7]' : 'text-[#bac2de] hover:text-[#cba6f7] hover:bg-[#313244]/50' }} transition">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.fields.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('admin.fields.*') ? 'bg-[#313244] text-[#a6e3a1]' : 'text-[#bac2de] hover:text-[#a6e3a1] hover:bg-[#313244]/50' }} transition">
                                    Kelola Lapangan
                                </a>
                                <a href="{{ route('admin.cashier.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('admin.cashier.*') ? 'bg-[#313244] text-[#89b4fa]' : 'text-[#bac2de] hover:text-[#89b4fa] hover:bg-[#313244]/50' }} transition">
                                    Kasir
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side Auth Menu -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#cdd6f4] hover:text-[#a6e3a1] px-4 py-2 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-[#11111b] bg-[#a6e3a1] rounded-xl hover:bg-[#94e2d5] shadow-sm shadow-[#a6e3a1]/20 transition duration-150">
                            Daftar Sekarang
                        </a>
                    @else
                        <div x-data="{ userMenu: false }" class="relative">
                            <button @click="userMenu = !userMenu" class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-[#45475a] hover:border-[#a6e3a1] bg-[#313244] text-sm font-medium text-[#cdd6f4] focus:outline-none transition">
                                <span class="w-7 h-7 rounded-full bg-[#a6e3a1] text-[#11111b] flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span>{{ auth()->user()->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ auth()->user()->role === 'admin' ? 'bg-[#cba6f7]/20 text-[#cba6f7] font-semibold' : 'bg-[#a6e3a1]/20 text-[#a6e3a1]' }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                                <svg class="w-4 h-4 text-[#a6adc8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="userMenu" @click.outside="userMenu = false" x-transition class="absolute right-0 mt-2 w-48 bg-[#181825] rounded-xl shadow-xl border border-[#313244] py-1.5 z-50">
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-[#cdd6f4] hover:bg-[#313244] hover:text-[#cba6f7]">Dashboard Admin</a>
                                    <a href="{{ route('admin.fields.index') }}" class="block px-4 py-2 text-sm text-[#cdd6f4] hover:bg-[#313244] hover:text-[#a6e3a1]">Kelola Lapangan</a>
                                    <a href="{{ route('admin.cashier.index') }}" class="block px-4 py-2 text-sm text-[#cdd6f4] hover:bg-[#313244] hover:text-[#89b4fa]">Kasir & Konfirmasi</a>
                                @endif
                                <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-[#cdd6f4] hover:bg-[#313244] hover:text-[#a6e3a1]">Pemesanan Saya</a>
                                <hr class="my-1 border-[#313244]">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#f38ba8] hover:bg-[#313244]">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-xl text-[#cdd6f4] hover:bg-[#313244] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" class="md:hidden border-t border-[#313244] px-4 pt-2 pb-4 space-y-1 bg-[#181825]">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#cdd6f4] hover:bg-[#313244] hover:text-[#a6e3a1]">Beranda</a>
            <a href="{{ route('fields.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#cdd6f4] hover:bg-[#313244] hover:text-[#a6e3a1]">Daftar Lapangan</a>
            @auth
                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#cdd6f4] hover:bg-[#313244] hover:text-[#a6e3a1]">Riwayat Pemesanan</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#cba6f7] hover:bg-[#313244]">Dashboard Admin</a>
                    <a href="{{ route('admin.fields.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#a6e3a1] hover:bg-[#313244]">Kelola Lapangan</a>
                    <a href="{{ route('admin.cashier.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-[#89b4fa] hover:bg-[#313244]">Kasir & Konfirmasi</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-base font-medium text-[#f38ba8] hover:bg-[#313244]">Keluar ({{ auth()->user()->name }})</button>
                </form>
            @else
                <div class="pt-3 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 px-4 border border-[#45475a] rounded-xl text-sm font-semibold text-[#cdd6f4] hover:bg-[#313244]">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 px-4 bg-[#a6e3a1] text-[#11111b] rounded-xl text-sm font-semibold hover:bg-[#94e2d5]">Daftar Sekarang</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages (Catppuccin Styled) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 text-[#a6e3a1] rounded-2xl bg-[#a6e3a1]/10 border border-[#a6e3a1]/30">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#a6e3a1] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-[#a6e3a1] hover:text-[#94e2d5] text-sm">✕</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 text-[#f38ba8] rounded-2xl bg-[#f38ba8]/10 border border-[#f38ba8]/30">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#f38ba8] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm font-medium">
                        {{ session('error') ?? $errors->first() }}
                    </div>
                </div>
                <button @click="show = false" class="text-[#f38ba8] hover:text-[#eba0ac] text-sm">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (Catppuccin Crust) -->
    <footer class="bg-[#11111b] text-[#a6adc8] mt-16 border-t border-[#181825]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-[#a6e3a1] to-[#94e2d5] flex items-center justify-center text-[#11111b]">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                <polygon points="12,7 16,10 14.5,15 9.5,15 8,10" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-[#cdd6f4] tracking-tight">Kick<span class="text-[#a6e3a1]">Flow</span></span>
                    </div>
                    <p class="text-[#9399b2] text-sm max-w-sm">
                        Platform pintar pemesanan lapangan futsal dengan ketersediaan real-time, perlindungan anti double-booking, dan asisten AI bertenaga Google Gemini.
                    </p>
                    <div class="text-xs text-[#6c7086] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#a6e3a1]"></span>
                        Catppuccin Mocha Palette • Laravel 13 & Google Gemini
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#cdd6f4] mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-sm text-[#a6adc8]">
                        <li><a href="{{ route('home') }}" class="hover:text-[#a6e3a1] transition">Beranda</a></li>
                        <li><a href="{{ route('fields.index') }}" class="hover:text-[#a6e3a1] transition">Daftar Lapangan</a></li>
                        <li><a href="{{ route('bookings.index') }}" class="hover:text-[#a6e3a1] transition">Riwayat Pemesanan</a></li>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <li><a href="{{ route('dashboard') }}" class="hover:text-[#cba6f7] transition">Dashboard Admin</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                <!-- Contact & Support -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#cdd6f4] mb-4">Bantuan & Kontak</h3>
                    <ul class="space-y-2 text-sm text-[#a6adc8]">
                        <li>📧 support@kickflow.com</li>
                        <li>📱 WhatsApp: 0812-3456-7890</li>
                        <li>📍 Jakarta, Indonesia</li>
                        <li>⏰ Operasional: 07:00 - 23:00 WIB</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-[#181825] text-center text-xs text-[#6c7086]">
                &copy; {{ date('Y') }} KickFlow Futsal Arena. Catppuccin Aesthetic Edition.
            </div>
        </div>
    </footer>

    <!-- Floating Chatbot Widget (KickFlow Assistant - Catppuccin Mocha) -->
    <div x-data="kickflowChatbot()" class="fixed bottom-6 right-6 z-50">
        
        <!-- Floating Trigger Button -->
        <button @click="toggleChat()" 
                class="group relative flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-[#a6e3a1] to-[#94e2d5] text-[#11111b] rounded-full shadow-lg shadow-[#a6e3a1]/25 hover:shadow-[#a6e3a1]/40 hover:scale-105 transition-all duration-200 focus:outline-none">
            <!-- Ping indicator when closed -->
            <span x-show="!isOpen" class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#a6e3a1] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-[#a6e3a1] border-2 border-[#181825]"></span>
            </span>

            <!-- Open Icon (Soccer Ball / Sparkles) -->
            <svg x-show="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>

            <!-- Close Icon -->
            <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Chat Window Panel (Catppuccin Mantle) -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 right-0 w-[90vw] sm:w-[400px] h-[520px] bg-[#181825] rounded-3xl shadow-2xl border border-[#313244] flex flex-col overflow-hidden">
            
            <!-- Header (Catppuccin Crust) -->
            <div class="bg-gradient-to-r from-[#11111b] to-[#181825] border-b border-[#313244] text-[#cdd6f4] p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-[#313244] flex items-center justify-center text-lg border border-[#45475a]">
                            🤖
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#a6e3a1] border-2 border-[#181825] rounded-full"></span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm tracking-tight flex items-center gap-1.5 text-[#cdd6f4]">
                            KickFlow Assistant
                            <span class="text-[10px] bg-[#a6e3a1]/20 text-[#a6e3a1] px-1.5 py-0.5 rounded font-mono font-semibold">AI</span>
                        </h4>
                        <p class="text-xs text-[#a6adc8]">Tanya jadwal & booking lapangan</p>
                    </div>
                </div>
                <button @click="toggleChat()" class="text-[#a6adc8] hover:text-[#cdd6f4] p-1.5 rounded-xl hover:bg-[#313244] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Quick Suggestions Bar -->
            <div class="bg-[#11111b]/60 border-b border-[#313244] px-3 py-2 flex items-center gap-1.5 overflow-x-auto text-xs whitespace-nowrap scrollbar-none">
                <button @click="sendQuick('Cek lapangan yang tersedia')" class="px-2.5 py-1 bg-[#313244] border border-[#45475a] text-[#a6e3a1] rounded-full hover:bg-[#45475a] transition">
                    🏟️ Lapangan Tersedia
                </button>
                <button @click="sendQuick('Pesan lapangan Garuda Sport besok jam 19:00 selama 2 jam')" class="px-2.5 py-1 bg-[#313244] border border-[#45475a] text-[#94e2d5] rounded-full hover:bg-[#45475a] transition font-medium">
                    ⚡ Booking via AI
                </button>
                <button @click="sendQuick('Berapa harga sewa per jam?')" class="px-2.5 py-1 bg-[#313244] border border-[#45475a] text-[#fab387] rounded-full hover:bg-[#45475a] transition">
                    💰 Info Harga
                </button>
                <button @click="sendQuick('Cek status booking saya')" class="px-2.5 py-1 bg-[#313244] border border-[#45475a] text-[#89b4fa] rounded-full hover:bg-[#45475a] transition">
                    📋 Cek Booking
                </button>
            </div>

            <!-- Messages Container (Catppuccin Base) -->
            <div x-ref="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-[#1e1e2e] text-sm">
                <!-- Initial Welcome Message -->
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-[#313244] text-[#a6e3a1] border border-[#45475a] flex items-center justify-center flex-shrink-0 text-xs">
                        🤖
                    </div>
                    <div class="bg-[#313244] border border-[#45475a] rounded-2xl rounded-tl-none p-3 shadow-sm text-[#cdd6f4] max-w-[85%]">
                        <p class="font-semibold text-[#a6e3a1] text-xs mb-1">KickFlow Assistant</p>
                        Halo! Saya KickFlow Assistant. Saya bisa membantu Anda mencari lapangan, cek jadwal kosong, serta **MEMBUAT BOOKING** atau **MEMBATALKAN PEMESANAN** langsung dari percakapan ini! Mau main kapan? ⚽
                    </div>
                </div>

                <!-- Chat History Loop -->
                <template x-for="(item, index) in messages" :key="index">
                    <div class="flex items-start gap-2.5" :class="item.role === 'user' ? 'justify-end' : 'justify-start'">
                        <!-- Bot avatar -->
                        <template x-if="item.role === 'assistant'">
                            <div class="w-7 h-7 rounded-full bg-[#313244] text-[#a6e3a1] border border-[#45475a] flex items-center justify-center flex-shrink-0 text-xs">
                                🤖
                            </div>
                        </template>

                        <!-- Bubble -->
                        <div class="p-3 shadow-sm max-w-[85%] whitespace-pre-line text-sm"
                             :class="item.role === 'user' 
                                ? 'bg-[#a6e3a1] text-[#11111b] font-medium rounded-2xl rounded-tr-none' 
                                : 'bg-[#313244] border border-[#45475a] rounded-2xl rounded-tl-none text-[#cdd6f4]'">
                            <span x-text="item.text"></span>
                            
                            <!-- Function call badge if triggered -->
                            <template x-if="item.functionCalled">
                                <div class="mt-2 pt-1.5 border-t border-[#45475a] text-[10px] text-[#a6e3a1] flex items-center gap-1 font-mono">
                                    <span>⚡ Query DB:</span>
                                    <span x-text="item.functionCalled"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Typing indicator -->
                <div x-show="isTyping" class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-[#313244] text-[#a6e3a1] border border-[#45475a] flex items-center justify-center flex-shrink-0 text-xs">
                        🤖
                    </div>
                    <div class="bg-[#313244] border border-[#45475a] rounded-2xl rounded-tl-none p-3 shadow-sm">
                        <div class="flex items-center space-x-1.5">
                            <div class="w-2 h-2 bg-[#a6e3a1] rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-[#a6e3a1] rounded-full animate-bounce [animation-delay:0.2s]"></div>
                            <div class="w-2 h-2 bg-[#a6e3a1] rounded-full animate-bounce [animation-delay:0.4s]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Form (Catppuccin Mantle) -->
            <div class="p-3 bg-[#181825] border-t border-[#313244]">
                <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                    <input type="text" 
                           x-model="inputMessage" 
                           :disabled="isTyping"
                           placeholder="Ketik pertanyaan Anda..." 
                           class="flex-1 bg-[#313244] border border-[#45475a] text-[#cdd6f4] placeholder-[#6c7086] rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:border-[#a6e3a1] focus:ring-1 focus:ring-[#a6e3a1] transition disabled:opacity-50">
                    <button type="submit" 
                            :disabled="isTyping || !inputMessage.trim()"
                            class="p-2.5 bg-[#a6e3a1] hover:bg-[#94e2d5] text-[#11111b] font-bold rounded-xl disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Chatbot Alpine Component Script -->
    <script>
        function kickflowChatbot() {
            return {
                isOpen: false,
                isTyping: false,
                inputMessage: '',
                messages: [],
                sessionId: 'kf_session_' + Math.random().toString(36).substring(2, 12) + '_' + Date.now(),

                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                sendQuick(text) {
                    this.inputMessage = text;
                    this.sendMessage();
                },

                async sendMessage() {
                    const text = this.inputMessage.trim();
                    if (!text || this.isTyping) return;

                    // Push user message
                    this.messages.push({ role: 'user', text: text });
                    this.inputMessage = '';
                    this.isTyping = true;
                    this.$nextTick(() => this.scrollToBottom());

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch('/api/chat', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                message: text,
                                session_id: this.sessionId
                            })
                        });

                        const data = await res.json();
                        
                        this.messages.push({
                            role: 'assistant',
                            text: data.reply || 'Maaf, saya tidak dapat memahami permintaan Anda.',
                            functionCalled: data.function_called || null
                        });
                    } catch (err) {
                        console.error('Chat error:', err);
                        this.messages.push({
                            role: 'assistant',
                            text: 'Maaf, terjadi kendala koneksi ke server. Silakan coba lagi sebentar lagi.',
                            functionCalled: null
                        });
                    } finally {
                        this.isTyping = false;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                scrollToBottom() {
                    const container = this.$refs.chatMessages;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
