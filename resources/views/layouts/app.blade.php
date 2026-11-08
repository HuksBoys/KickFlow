<!DOCTYPE html>
<html lang="id" class="h-full bg-[#070b09]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KickFlow - Sistem Pemesanan Lapangan Futsal Modern')</title>
    
    <!-- Athletic Modern Typography -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:500,600,700,800|instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CDN fallback with Pitch & Turf Theme Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        pitch: {
                            950: '#070b09',
                            900: '#0b110e',
                            850: '#101814',
                            800: '#15201a',
                            750: '#1b2922',
                            700: '#24362d',
                            600: '#334a3f',
                            500: '#4a6b5c',
                        },
                        turf: {
                            500: '#10b981',
                            400: '#34d399',
                            300: '#6ee7b7',
                            neon: '#00e575',
                        },
                        // Backward compatibility for existing components
                        ctp: {
                            base: '#0b110e',
                            mantle: '#101814',
                            crust: '#070b09',
                            surface0: '#15201a',
                            surface1: '#1b2922',
                            surface2: '#24362d',
                            overlay0: '#4a6b5c',
                            overlay1: '#64748b',
                            overlay2: '#94a3b8',
                            subtext0: '#cbd5e1',
                            subtext1: '#e2e8f0',
                            text: '#f8fafc',
                            green: '#10b981',
                            teal: '#14b8a6',
                            sky: '#38bdf8',
                            blue: '#3b82f6',
                            mauve: '#10b981',
                            peach: '#f59e0b',
                            yellow: '#eab308',
                            red: '#ef4444',
                            maroon: '#f43f5e',
                        }
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-[#070b09] text-[#f8fafc] antialiased font-sans selection:bg-[#10b981]/25 selection:text-[#34d399]">

    <!-- Top Status Bar (Operational Notice) -->
    <div class="bg-[#0b110e] border-b border-[#1b2922] text-xs py-1.5 px-4 hidden sm:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between text-[#94a3b8]">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-[#34d399] font-medium">
                    <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                    Arena Buka Hari Ini: 07:00 – 24:00 WIB
                </span>
                <span class="text-[#24362d]">•</span>
                <span>Standar Kompetisi FIFA & BFA</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="text-[#64748b]">Hotline WhatsApp: <strong class="text-[#e2e8f0]">0812-3456-7890</strong></span>
                <span class="text-[#24362d]">•</span>
                <span class="text-[#64748b]">Garansi 100% Anti Double-Booking</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav x-data="{ mobileOpen: false }" class="bg-[#0b110e]/95 border-b border-[#1b2922] sticky top-0 z-40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Brand Logo & Main Nav -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-lg bg-[#101814] border border-[#24362d] group-hover:border-[#10b981] flex items-center justify-center text-[#10b981] transition-colors">
                            <!-- Futsal Ball / Tactical Field Icon -->
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="m4.93 4.93 4.24 4.24"/>
                                <path d="m14.83 9.17 4.24-4.24"/>
                                <path d="m14.83 14.83 4.24 4.24"/>
                                <path d="m9.17 14.83-4.24 4.24"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-display text-lg font-bold tracking-tight text-[#f8fafc] group-hover:text-[#34d399] transition-colors">
                                Kick<span class="text-[#10b981]">Flow</span>
                            </span>
                            <span class="text-[9px] uppercase tracking-widest font-bold text-[#4a6b5c] -mt-1">
                                Futsal Arena & Booking
                            </span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('home') }}" 
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-[#15201a] text-[#10b981] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#101814]' }}">
                            Beranda
                        </a>
                        <a href="{{ route('fields.index') }}" 
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('fields.*') ? 'bg-[#15201a] text-[#10b981] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#101814]' }}">
                            Daftar Lapangan
                        </a>
                        @auth
                            <a href="{{ route('bookings.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('bookings.*') ? 'bg-[#15201a] text-[#10b981] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#101814]' }}">
                                Riwayat Pemesanan
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <div class="h-4 w-px bg-[#1b2922] mx-2"></div>
                                <a href="{{ route('dashboard') }}" 
                                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-[#15201a] text-[#38bdf8] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#38bdf8] hover:bg-[#101814]' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.fields.index') }}" 
                                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.fields.*') ? 'bg-[#15201a] text-[#10b981] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#10b981] hover:bg-[#101814]' }}">
                                    Kelola Lapangan
                                </a>
                                <a href="{{ route('admin.cashier.index') }}" 
                                   class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.cashier.*') ? 'bg-[#15201a] text-[#f59e0b] font-semibold border border-[#24362d]' : 'text-[#94a3b8] hover:text-[#f59e0b] hover:bg-[#101814]' }}">
                                    Kasir
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side Auth Menu -->
                <div class="hidden md:flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" 
                           class="text-sm font-medium text-[#cbd5e1] hover:text-[#f8fafc] px-3.5 py-2 rounded-lg hover:bg-[#101814] transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-[#070b09] bg-[#10b981] hover:bg-[#34d399] rounded-lg transition-colors shadow-sm">
                            Daftar Sekarang
                        </a>
                    @else
                        <div x-data="{ userMenu: false }" class="relative">
                            <button @click="userMenu = !userMenu" 
                                    class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg border border-[#24362d] hover:border-[#334a3f] bg-[#101814] text-sm text-[#f8fafc] focus:outline-none transition-colors">
                                <span class="w-7 h-7 rounded bg-[#15201a] border border-[#24362d] text-[#10b981] flex items-center justify-center text-xs font-bold font-mono">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="font-medium max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded font-mono uppercase {{ auth()->user()->role === 'admin' ? 'bg-[#38bdf8]/15 text-[#38bdf8] border border-[#38bdf8]/30' : 'bg-[#10b981]/15 text-[#10b981] border border-[#10b981]/30' }}">
                                    {{ auth()->user()->role }}
                                </span>
                                <svg class="w-4 h-4 text-[#64748b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="userMenu" 
                                 @click.outside="userMenu = false" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="absolute right-0 mt-2 w-52 bg-[#0e1411] rounded-xl shadow-2xl border border-[#24362d] py-1.5 z-50">
                                <div class="px-3 py-2 border-b border-[#1b2922]">
                                    <p class="text-xs text-[#94a3b8]">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-[#f8fafc] truncate">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->role === 'admin')
                                    <div class="py-1">
                                        <a href="{{ route('dashboard') }}" class="block px-3 py-1.5 text-xs text-[#94a3b8] hover:text-[#38bdf8] hover:bg-[#15201a]">Dashboard Admin</a>
                                        <a href="{{ route('admin.fields.index') }}" class="block px-3 py-1.5 text-xs text-[#94a3b8] hover:text-[#10b981] hover:bg-[#15201a]">Kelola Lapangan</a>
                                        <a href="{{ route('admin.cashier.index') }}" class="block px-3 py-1.5 text-xs text-[#94a3b8] hover:text-[#f59e0b] hover:bg-[#15201a]">Kasir & Konfirmasi</a>
                                    </div>
                                    <div class="h-px bg-[#1b2922]"></div>
                                @endif
                                <div class="py-1">
                                    <a href="{{ route('bookings.index') }}" class="block px-3 py-1.5 text-xs text-[#94a3b8] hover:text-[#10b981] hover:bg-[#15201a]">Riwayat Pemesanan</a>
                                </div>
                                <div class="h-px bg-[#1b2922]"></div>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-3 py-1.5 text-xs text-[#ef4444] hover:bg-[#15201a]">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileOpen = !mobileOpen" 
                            class="p-2 rounded-lg text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#101814] focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileOpen" 
             x-transition 
             class="md:hidden border-t border-[#1b2922] px-4 pt-3 pb-4 space-y-1 bg-[#0b110e]">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#94a3b8] hover:text-[#10b981] hover:bg-[#101814]">Beranda</a>
            <a href="{{ route('fields.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#94a3b8] hover:text-[#10b981] hover:bg-[#101814]">Daftar Lapangan</a>
            @auth
                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#94a3b8] hover:text-[#10b981] hover:bg-[#101814]">Riwayat Pemesanan</a>
                @if(auth()->user()->role === 'admin')
                    <div class="h-px bg-[#1b2922] my-1"></div>
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#38bdf8] hover:bg-[#101814]">Dashboard Admin</a>
                    <a href="{{ route('admin.fields.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#10b981] hover:bg-[#101814]">Kelola Lapangan</a>
                    <a href="{{ route('admin.cashier.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-[#f59e0b] hover:bg-[#101814]">Kasir & Konfirmasi</a>
                @endif
                <div class="h-px bg-[#1b2922] my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-[#ef4444] hover:bg-[#101814]">
                        Keluar ({{ auth()->user()->name }})
                    </button>
                </form>
            @else
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 px-4 border border-[#24362d] rounded-lg text-sm font-medium text-[#cbd5e1] hover:bg-[#101814]">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 px-4 bg-[#10b981] text-[#070b09] font-semibold rounded-lg hover:bg-[#34d399] text-sm">Daftar Sekarang</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Flash Messages (Athletic Toast / Alert) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-3.5 mb-4 text-[#34d399] rounded-xl bg-[#101814] border border-[#10b981]/40 shadow-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#10b981] flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-[#f8fafc]">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-[#94a3b8] hover:text-[#f8fafc] text-sm p-1">✕</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-3.5 mb-4 text-[#fca5a5] rounded-xl bg-[#151010] border border-[#ef4444]/40 shadow-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-[#ef4444] flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm font-medium text-[#f8fafc]">
                        {{ session('error') ?? $errors->first() }}
                    </div>
                </div>
                <button @click="show = false" class="text-[#94a3b8] hover:text-[#f8fafc] text-sm p-1">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Athletic Arena Footer -->
    <footer class="bg-[#0b110e] border-t border-[#1b2922] mt-20 text-[#94a3b8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#101814] border border-[#24362d] flex items-center justify-center text-[#10b981]">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <span class="font-display text-lg font-bold text-[#f8fafc] tracking-tight">
                            Kick<span class="text-[#10b981]">Flow</span>
                        </span>
                    </div>
                    <p class="text-sm text-[#94a3b8] max-w-md leading-relaxed">
                        Sistem manajemen dan reservasi lapangan futsal modern berstandar turnamen. Dilengkapi teknologi anti double-booking real-time dan asisten AI pintar bertenaga Google Gemini.
                    </p>
                    <div class="text-xs text-[#64748b] flex items-center gap-2 pt-1 font-mono">
                        <span class="w-2 h-2 rounded-full bg-[#10b981]"></span>
                        Sistem Operasional Aktif • Laravel 13 & Google Gemini
                    </div>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#f8fafc] mb-4">Navigasi Utama</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-[#10b981] transition-colors">Beranda</a></li>
                        <li><a href="{{ route('fields.index') }}" class="hover:text-[#10b981] transition-colors">Daftar Lapangan</a></li>
                        <li><a href="{{ route('bookings.index') }}" class="hover:text-[#10b981] transition-colors">Riwayat Pemesanan</a></li>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <li><a href="{{ route('dashboard') }}" class="hover:text-[#38bdf8] transition-colors">Dashboard Admin</a></li>
                                <li><a href="{{ route('admin.fields.index') }}" class="hover:text-[#10b981] transition-colors">Kelola Lapangan</a></li>
                                <li><a href="{{ route('admin.cashier.index') }}" class="hover:text-[#f59e0b] transition-colors">Kasir & Point of Sale</a></li>
                            @endif
                        @endauth
                    </ul>
                </div>

                <!-- Contact & Support -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#f8fafc] mb-4">Kontak & Lokasi</h3>
                    <ul class="space-y-2 text-sm text-[#94a3b8]">
                        <li class="flex items-center gap-2">
                            <span class="text-[#10b981]">📍</span> Komplek Olahraga Senayan, Jakarta
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#10b981]">📱</span> WhatsApp: 0812-3456-7890
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#10b981]">✉️</span> support@kickflow.com
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-[#10b981]">⏰</span> 07:00 – 24:00 WIB (Setiap Hari)
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-[#1b2922] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#64748b]">
                <p>&copy; {{ date('Y') }} KickFlow Futsal Arena. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <span>Sistem Reservasi Presisi</span>
                    <span>•</span>
                    <span>Validasi Multi-Kriteria</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating AI Chatbot Widget (KickFlow Tactical Arena Assistant) -->
    <div x-data="kickflowChatbot()" class="fixed bottom-6 right-6 z-50">
        
        <!-- Proactive Speech Bubble Notification (Appears when closed) -->
        <div x-show="!isOpen && showTooltip" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="absolute bottom-20 right-0 w-72 bg-[#0e1612] border border-[#24362d] rounded-2xl p-3.5 shadow-2xl text-xs text-[#cbd5e1] mb-1 pointer-events-auto">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#10b981] animate-pulse"></span>
                    <span class="font-display font-bold text-xs text-[#f8fafc]">KickFlow Tactical AI</span>
                </div>
                <button @click="showTooltip = false" class="text-[#64748b] hover:text-[#f8fafc] text-xs">✕</button>
            </div>
            <p class="mt-1.5 text-[11px] text-[#94a3b8] leading-relaxed">
                Mau cari slot kosong hari ini atau langsung pesan jadwal futsal lewat percakapan AI?
            </p>
            <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-[#1b2922]">
                <button @click="toggleChat()" class="font-semibold text-[11px] text-[#34d399] hover:underline flex items-center gap-1">
                    <span>Mulai Tanya AI</span>
                    <span>→</span>
                </button>
                <span class="text-[9px] font-mono text-[#64748b]">Gemini 2.0 Flash</span>
            </div>
        </div>

        <!-- Floating Trigger Capsule (Expandable HUD Button) -->
        <button @click="toggleChat()" 
                class="group relative flex items-center gap-2.5 p-2.5 sm:px-4 sm:py-3 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] hover:border-[#10b981] text-[#f8fafc] rounded-full shadow-2xl shadow-black/80 hover:scale-[1.02] active:scale-95 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#10b981]/50"
                aria-label="Buka Asisten AI KickFlow">
            
            <!-- Animated Radar Ring on Icon -->
            <div class="relative w-9 h-9 rounded-full bg-[#15201a] border border-[#10b981]/40 flex items-center justify-center flex-shrink-0 group-hover:border-[#10b981] transition-colors">
                <span class="absolute inset-0 rounded-full bg-[#10b981]/20 radar-ring pointer-events-none"></span>
                <span class="relative z-10 text-[#10b981]">
                    <!-- Closed Icon: Ball / Bot Vector -->
                    <svg x-show="!isOpen" class="w-5 h-5 text-[#34d399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m4.93 4.93 4.24 4.24"/>
                        <path d="m14.83 9.17 4.24-4.24"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                    <!-- Opened Icon: Close Vector -->
                    <svg x-show="isOpen" class="w-5 h-5 text-[#fca5a5]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </div>

            <!-- Capsule Text (Desktop) -->
            <div class="hidden sm:flex flex-col text-left pr-1">
                <span class="font-display text-xs font-bold text-[#f8fafc] flex items-center gap-1.5 leading-tight">
                    <span x-text="isOpen ? 'Tutup Asisten' : 'KickFlow Assistant'"></span>
                    <span class="text-[9px] bg-[#10b981]/20 text-[#34d399] border border-[#10b981]/40 px-1 py-0.2 rounded font-mono font-bold">AI</span>
                </span>
                <span class="text-[10px] text-[#94a3b8] leading-tight" x-text="isOpen ? 'Kembali ke halaman' : 'Cek Jadwal & Reservasi'"></span>
            </div>
        </button>

        <!-- Chat Window Panel (The Arena Command Terminal) -->
        <div x-show="isOpen" 
             @click.outside="isOpen = false"
             @keydown.escape.window="isOpen = false"
             x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-16 sm:bottom-20 right-0 w-[92vw] sm:w-[390px] h-[520px] max-h-[calc(100vh-6.5rem)] bg-[#0a100d]/98 backdrop-blur-2xl rounded-2xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.85),0_0_35px_rgba(16,185,129,0.15)] border border-[#1f2f25] flex flex-col overflow-hidden">
            
            <!-- Window Command Header -->
            <div class="bg-[#0e1612] border-b border-[#1b2922] px-4 py-3 flex items-center justify-between relative overflow-hidden flex-shrink-0">
                <!-- Subtle tactical background line -->
                <div class="absolute inset-0 tactical-grid opacity-20 pointer-events-none"></div>

                <div class="flex items-center gap-3 relative z-10">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-xl bg-[#15201a] border border-[#10b981]/30 flex items-center justify-center text-[#10b981] shadow-inner">
                            <svg class="w-5 h-5 text-[#34d399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="16" height="12" x="4" y="8" rx="2"/>
                                <path d="M12 8V4H8"/>
                                <circle cx="9" cy="14" r="1" fill="currentColor"/>
                                <circle cx="15" cy="14" r="1" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 bg-[#10b981] border-2 border-[#0e1612] rounded-full animate-ping"></span>
                        <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 bg-[#00e575] border-2 border-[#0e1612] rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h4 class="font-display font-bold text-sm text-[#f8fafc]">KickFlow Tactical AI</h4>
                            <span class="text-[9px] bg-[#10b981]/20 text-[#34d399] border border-[#10b981]/30 px-1.5 py-0.2 rounded font-mono font-bold">2.0 FLASH</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] text-[#94a3b8] font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>
                            <span>Siap Reservasi & Cek Jadwal</span>
                        </div>
                    </div>
                </div>

                <!-- Window Actions -->
                <div class="flex items-center gap-1 relative z-10">
                    <!-- Clear Chat History -->
                    <button @click="clearChat()" 
                            title="Reset percakapan"
                            class="p-1.5 rounded-lg text-[#64748b] hover:text-[#f8fafc] hover:bg-[#15201a] transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                    </button>

                    <!-- Minimize / Close button -->
                    <button @click="isOpen = false" 
                            class="p-1.5 rounded-lg text-[#94a3b8] hover:text-[#f8fafc] hover:bg-[#15201a] transition-colors" 
                            title="Kecilkan / Tutup chat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Log -->
            <div x-ref="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-[#070b09] text-sm">
                
                <!-- Tactical Briefing Screen (Displayed when no user messages) -->
                <template x-if="messages.length === 0">
                    <div class="space-y-4 py-2">
                        <!-- Briefing Card -->
                        <div class="bg-[#101814] border border-[#24362d] rounded-2xl p-4 sm:p-5 relative overflow-hidden">
                            <div class="flex items-center gap-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#15201a] border border-[#10b981]/40 flex items-center justify-center text-[#10b981]">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polygon points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-display font-bold text-sm text-[#f8fafc]">Pusat Asisten Taktis Futsal</h5>
                                    <span class="text-[10px] font-mono text-[#34d399]">Siap melayani reservasi instan 24/7</span>
                                </div>
                            </div>
                            <p class="text-xs text-[#94a3b8] leading-relaxed">
                                Halo tim! Saya asisten cerdas resmi KickFlow. Anda dapat langsung menanyakan jadwal lapangan yang kosong, harga sewa, atau langsung menginstruksikan saya membuat booking.
                            </p>
                        </div>

                        <!-- 4 Bento Action Prompt Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            <button @click="sendQuick('Cek lapangan yang tersedia')" 
                                    class="p-3 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] hover:border-[#10b981]/60 rounded-xl text-left transition-all duration-150 group">
                                <div class="flex items-center gap-1.5 font-bold text-[#f8fafc] group-hover:text-[#34d399] transition-colors">
                                    <span>🏟️</span>
                                    <span>Cek Slot Kosong</span>
                                </div>
                                <p class="text-[11px] text-[#64748b] mt-1 line-clamp-1">
                                    Lihat arena yang tersedia hari ini
                                </p>
                            </button>

                            <button @click="sendQuick('Pesan lapangan Garuda Sport besok jam 19:00 selama 2 jam')" 
                                    class="p-3 bg-[#101814] hover:bg-[#15201a] border border-[#10b981]/30 hover:border-[#10b981] rounded-xl text-left transition-all duration-150 group">
                                <div class="flex items-center gap-1.5 font-bold text-[#34d399] transition-colors">
                                    <span>⚡</span>
                                    <span>Booking via Chat</span>
                                </div>
                                <p class="text-[11px] text-[#64748b] mt-1 line-clamp-1">
                                    Reservasi otomatis tanpa form ribet
                                </p>
                            </button>

                            <button @click="sendQuick('Berapa tarif sewa lapangan indoor vs outdoor per jam?')" 
                                    class="p-3 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] hover:border-[#f59e0b]/60 rounded-xl text-left transition-all duration-150 group">
                                <div class="flex items-center gap-1.5 font-bold text-[#f8fafc] group-hover:text-[#f59e0b] transition-colors">
                                    <span>💰</span>
                                    <span>Tarif & Spesifikasi</span>
                                </div>
                                <p class="text-[11px] text-[#64748b] mt-1 line-clamp-1">
                                    Info rumput sintetis & taraflex vinyl
                                </p>
                            </button>

                            <button @click="sendQuick('Cek status booking saya')" 
                                    class="p-3 bg-[#101814] hover:bg-[#15201a] border border-[#24362d] hover:border-[#38bdf8]/60 rounded-xl text-left transition-all duration-150 group">
                                <div class="flex items-center gap-1.5 font-bold text-[#f8fafc] group-hover:text-[#38bdf8] transition-colors">
                                    <span>📋</span>
                                    <span>Status Reservasi</span>
                                </div>
                                <p class="text-[11px] text-[#64748b] mt-1 line-clamp-1">
                                    Lihat kode tiket & jadwal main tim
                                </p>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Conversation Stream -->
                <template x-for="(item, index) in messages" :key="index">
                    <div class="flex items-start gap-2.5" :class="item.role === 'user' ? 'justify-end' : 'justify-start'">
                        
                        <!-- Assistant Avatar -->
                        <template x-if="item.role === 'assistant'">
                            <div class="w-7 h-7 rounded-lg bg-[#15201a] border border-[#10b981]/40 text-[#10b981] flex items-center justify-center flex-shrink-0 text-xs mt-1 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-[#34d399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            </div>
                        </template>

                        <!-- Bubble Box -->
                        <div class="max-w-[88%] flex flex-col" :class="item.role === 'user' ? 'items-end' : 'items-start'">
                            
                            <!-- Bubble Container -->
                            <div class="p-3.5 shadow-md leading-relaxed text-xs sm:text-sm break-words"
                                 :class="item.role === 'user' 
                                    ? 'bg-gradient-to-br from-[#059669] to-[#047857] text-white font-medium rounded-2xl rounded-tr-xs shadow-md shadow-emerald-950/40 border border-[#10b981]/40' 
                                    : 'bg-[#101814] border border-[#24362d] rounded-2xl rounded-tl-xs text-[#f1f5f9]'">
                                
                                <!-- Formatted Content -->
                                <div class="whitespace-pre-line" x-html="formatMessage(item.text, item.role)"></div>

                                <!-- Dynamic Tactical Function Badge -->
                                <template x-if="item.functionCalled">
                                    <div class="mt-2.5 pt-2 border-t border-[#1b2922] flex items-center justify-between gap-2 text-[10px] font-mono">
                                        <div class="flex items-center gap-1.5"
                                             :class="{
                                                'text-[#34d399]': item.functionCalled === 'create_booking',
                                                'text-[#fca5a5]': item.functionCalled === 'cancel_booking',
                                                'text-[#38bdf8]': item.functionCalled !== 'create_booking' && item.functionCalled !== 'cancel_booking'
                                             }">
                                            <span>⚡</span>
                                            <span class="font-bold uppercase" x-text="getFunctionLabel(item.functionCalled)"></span>
                                        </div>
                                        <span class="text-[#64748b]" x-text="item.functionCalled"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Meta Timestamp & Quick Copy -->
                            <div class="mt-1 flex items-center gap-2 text-[10px] font-mono text-[#64748b] px-1">
                                <span x-text="item.time || 'Baru saja'"></span>
                                <template x-if="item.role === 'assistant'">
                                    <button @click="copyText(item.text, index)" 
                                            class="hover:text-[#34d399] transition-colors"
                                            :title="copiedIndex === index ? 'Tersalin!' : 'Salin pesan'">
                                        <span x-text="copiedIndex === index ? '✓ Tersalin' : 'Salin'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- User Avatar -->
                        <template x-if="item.role === 'user'">
                            <div class="w-7 h-7 rounded-lg bg-[#059669] border border-[#10b981]/50 text-white flex items-center justify-center flex-shrink-0 text-[10px] font-bold font-mono mt-1 shadow-sm">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'User', 0, 1)) }}
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Tactical Live Analyzing / Typing Bar -->
                <div x-show="isTyping" class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#15201a] border border-[#10b981]/40 text-[#10b981] flex items-center justify-center flex-shrink-0 text-xs mt-1">
                        <svg class="w-3.5 h-3.5 text-[#34d399]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <div class="bg-[#101814] border border-[#24362d] rounded-2xl rounded-tl-xs p-3 shadow-sm flex items-center gap-3">
                        <span class="text-xs text-[#94a3b8] font-mono">Menganalisis jadwal database</span>
                        <div class="flex items-center space-x-1">
                            <div class="w-1.5 h-1.5 bg-[#10b981] rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-[#10b981] rounded-full animate-bounce [animation-delay:0.15s]"></div>
                            <div class="w-1.5 h-1.5 bg-[#10b981] rounded-full animate-bounce [animation-delay:0.3s]"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Docked Input Terminal -->
            <div class="p-3 bg-[#0a100d] border-t border-[#1b2922]">
                <form @submit.prevent="sendMessage()" class="relative flex items-center">
                    <input type="text" 
                           x-ref="chatInput"
                           x-model="inputMessage" 
                           :disabled="isTyping"
                           placeholder="Ketik pertanyaan atau 'Pesan lapangan besok jam 19:00'..." 
                           class="w-full bg-[#101814] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-xl pl-3.5 pr-20 py-2.5 text-xs sm:text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] transition disabled:opacity-50">
                    
                    <div class="absolute right-1.5 flex items-center gap-1">
                        <!-- Clear input button -->
                        <button type="button" 
                                x-show="inputMessage.trim().length > 0"
                                @click="inputMessage = ''; $refs.chatInput.focus()"
                                class="p-1.5 text-[#64748b] hover:text-[#f8fafc] rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" x2="6" y1="6" y2="18"/>
                                <line x1="6" x2="18" y1="6" y2="18"/>
                            </svg>
                        </button>

                        <!-- Send Button -->
                        <button type="submit" 
                                :disabled="isTyping || !inputMessage.trim()"
                                class="p-2 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold rounded-lg disabled:opacity-30 disabled:cursor-not-allowed shadow-sm transition-all duration-150 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Micro Footer Security Notice -->
                <div class="mt-1.5 flex items-center justify-between text-[10px] font-mono text-[#64748b] px-1">
                    <span class="flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#10b981]"></span>
                        Google Gemini 2.0 AI
                    </span>
                    <span>100% Anti Double-Booking</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Chatbot Logic -->
    <script>
        function kickflowChatbot() {
            return {
                isOpen: false,
                showTooltip: true,
                isTyping: false,
                inputMessage: '',
                messages: [],
                copiedIndex: null,
                sessionId: 'kf_' + Math.random().toString(36).substring(2, 10) + '_' + Date.now(),

                toggleChat() {
                    this.isOpen = !this.isOpen;
                    this.showTooltip = false;
                    if (this.isOpen) {
                        this.$nextTick(() => {
                            this.scrollToBottom();
                            if (this.$refs.chatInput) {
                                this.$refs.chatInput.focus();
                            }
                        });
                    }
                },

                clearChat() {
                    if (this.messages.length === 0) return;
                    if (confirm('Hapus seluruh riwayat percakapan sesi ini?')) {
                        this.messages = [];
                        this.sessionId = 'kf_' + Math.random().toString(36).substring(2, 10) + '_' + Date.now();
                    }
                },

                sendQuick(text) {
                    this.inputMessage = text;
                    this.sendMessage();
                },

                formatMessage(text, role = 'assistant') {
                    if (!text) return '';
                    // Escape basic HTML tags to avoid injection
                    let escaped = text
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');

                    if (role === 'user') {
                        // User message: keep bold text clean and bright white
                        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-bold">$1</strong>');
                    } else {
                        // Assistant message: highlight bold keywords and booking ID badges
                        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="text-[#34d399] font-bold">$1</strong>');
                        escaped = escaped.replace(/(#KF-\d+)/g, '<span class="px-1.5 py-0.5 rounded bg-[#15201a] text-[#34d399] border border-[#10b981]/30 font-mono font-bold">$1</span>');
                    }

                    return escaped;
                },

                getFunctionLabel(fnName) {
                    switch(fnName) {
                        case 'create_booking':
                            return 'Reservasi Disimpan';
                        case 'cancel_booking':
                            return 'Pembatalan Diproses';
                        case 'check_field_availability':
                        case 'get_available_fields':
                            return 'Kueri Jadwal DB';
                        case 'get_booking_info':
                            return 'Kueri Data Tiket';
                        default:
                            return 'Eksekusi Database';
                    }
                },

                copyText(text, index) {
                    if (!navigator.clipboard) return;
                    navigator.clipboard.writeText(text).then(() => {
                        this.copiedIndex = index;
                        setTimeout(() => {
                            if (this.copiedIndex === index) this.copiedIndex = null;
                        }, 2000);
                    });
                },

                getCurrentTime() {
                    const now = new Date();
                    return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ' WIB';
                },

                async sendMessage() {
                    const text = this.inputMessage.trim();
                    if (!text || this.isTyping) return;

                    // Push user message with time
                    this.messages.push({ 
                        role: 'user', 
                        text: text,
                        time: this.getCurrentTime()
                    });
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
                            text: data.reply || 'Maaf, saya tidak dapat memahami permintaan tersebut.',
                            functionCalled: data.function_called || null,
                            time: this.getCurrentTime()
                        });
                    } catch (err) {
                        console.error('Chat error:', err);
                        this.messages.push({
                            role: 'assistant',
                            text: 'Maaf, terjadi kendala koneksi ke server KickFlow. Silakan coba kembali sesaat lagi.',
                            functionCalled: null,
                            time: this.getCurrentTime()
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
