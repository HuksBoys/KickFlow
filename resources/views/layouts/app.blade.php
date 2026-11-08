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

    <!-- Floating AI Chatbot Widget (KickFlow Tactical Assistant) -->
    <div x-data="kickflowChatbot()" class="fixed bottom-6 right-6 z-50">
        
        <!-- Trigger Button -->
        <button @click="toggleChat()" 
                class="group relative flex items-center justify-center w-13 h-13 p-3.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] rounded-xl shadow-xl shadow-[#10b981]/20 hover:scale-105 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#10b981]/50"
                aria-label="Buka Asisten AI KickFlow">
            <!-- Active Ping Indicator -->
            <span x-show="!isOpen" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#34d399] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#00e575] border-2 border-[#070b09]"></span>
            </span>

            <!-- Open Icon (Tactical Bot Vector) -->
            <svg x-show="!isOpen" class="w-6 h-6 text-[#070b09]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 8V4H8"/>
                <rect width="16" height="12" x="4" y="8" rx="2"/>
                <path d="M2 14h2"/>
                <path d="M20 14h2"/>
                <path d="M15 13v2"/>
                <path d="M9 13v2"/>
            </svg>

            <!-- Close Icon -->
            <svg x-show="isOpen" class="w-6 h-6 text-[#070b09]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Chat Window Panel -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 scale-95"
             class="absolute bottom-16 right-0 w-[92vw] sm:w-[410px] h-[530px] bg-[#0b110e] rounded-2xl shadow-2xl border border-[#24362d] flex flex-col overflow-hidden">
            
            <!-- Window Header -->
            <div class="bg-[#101814] border-b border-[#1b2922] px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-lg bg-[#15201a] border border-[#24362d] flex items-center justify-center text-[#10b981]">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="16" height="12" x="4" y="8" rx="2"/>
                                <path d="M12 8V4H8"/>
                                <circle cx="9" cy="14" r="1" fill="currentColor"/>
                                <circle cx="15" cy="14" r="1" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-[#10b981] border-2 border-[#101814] rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h4 class="font-display font-bold text-sm text-[#f8fafc]">KickFlow Assistant</h4>
                            <span class="text-[9px] bg-[#10b981]/20 text-[#34d399] border border-[#10b981]/30 px-1.5 py-0.2 rounded font-mono font-bold">AI</span>
                        </div>
                        <p class="text-[11px] text-[#94a3b8]">Cek jadwal, reservasi & info lapangan</p>
                    </div>
                </div>
                <button @click="toggleChat()" class="text-[#64748b] hover:text-[#f8fafc] p-1.5 rounded-lg hover:bg-[#15201a] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Quick Suggestions Bar -->
            <div class="bg-[#0e1411] border-b border-[#1b2922] px-3 py-2 flex items-center gap-1.5 overflow-x-auto text-xs whitespace-nowrap scrollbar-none">
                <button @click="sendQuick('Cek lapangan yang tersedia')" 
                        class="px-2.5 py-1 bg-[#15201a] border border-[#24362d] text-[#cbd5e1] hover:text-[#10b981] hover:border-[#10b981]/50 rounded-md transition-colors flex items-center gap-1">
                    <span>🏟️</span> Lapangan Tersedia
                </button>
                <button @click="sendQuick('Pesan lapangan Garuda Sport besok jam 19:00 selama 2 jam')" 
                        class="px-2.5 py-1 bg-[#15201a] border border-[#10b981]/40 text-[#34d399] hover:bg-[#10b981]/10 rounded-md transition-colors flex items-center gap-1 font-medium">
                    <span>⚡</span> Booking via AI
                </button>
                <button @click="sendQuick('Berapa tarif harga per jam?')" 
                        class="px-2.5 py-1 bg-[#15201a] border border-[#24362d] text-[#cbd5e1] hover:text-[#f59e0b] hover:border-[#f59e0b]/50 rounded-md transition-colors flex items-center gap-1">
                    <span>💰</span> Tarif Sewa
                </button>
                <button @click="sendQuick('Cek status booking saya')" 
                        class="px-2.5 py-1 bg-[#15201a] border border-[#24362d] text-[#cbd5e1] hover:text-[#38bdf8] hover:border-[#38bdf8]/50 rounded-md transition-colors flex items-center gap-1">
                    <span>📋</span> Cek Booking
                </button>
            </div>

            <!-- Messages Log -->
            <div x-ref="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-[#070b09] text-sm">
                <!-- Welcome Prompt -->
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded bg-[#101814] border border-[#24362d] text-[#10b981] flex items-center justify-center flex-shrink-0 text-xs">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="16" height="12" x="4" y="8" rx="2"/>
                            <path d="M12 8V4H8"/>
                        </svg>
                    </div>
                    <div class="bg-[#101814] border border-[#24362d] rounded-xl rounded-tl-none p-3.5 shadow-sm text-[#cbd5e1] max-w-[85%] leading-relaxed">
                        <p class="font-semibold text-[#10b981] text-xs mb-1">KickFlow Tactical Assistant</p>
                        Halo! Saya asisten resmi KickFlow. Anda dapat menanyakan ketersediaan lapangan, cek jadwal jam kosong, serta **membuat atau membatalkan reservasi** langsung lewat percakapan ini. Ada yang bisa saya bantu untuk jadwal tim Anda?
                    </div>
                </div>

                <!-- Chat History Loop -->
                <template x-for="(item, index) in messages" :key="index">
                    <div class="flex items-start gap-2.5" :class="item.role === 'user' ? 'justify-end' : 'justify-start'">
                        <template x-if="item.role === 'assistant'">
                            <div class="w-7 h-7 rounded bg-[#101814] border border-[#24362d] text-[#10b981] flex items-center justify-center flex-shrink-0 text-xs">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="16" height="12" x="4" y="8" rx="2"/>
                                    <path d="M12 8V4H8"/>
                                </svg>
                            </div>
                        </template>

                        <div class="p-3.5 shadow-sm max-w-[85%] whitespace-pre-line text-sm leading-relaxed"
                             :class="item.role === 'user' 
                                ? 'bg-[#10b981] text-[#070b09] font-medium rounded-xl rounded-tr-none' 
                                : 'bg-[#101814] border border-[#24362d] rounded-xl rounded-tl-none text-[#cbd5e1]'">
                            <span x-text="item.text"></span>
                            
                            <!-- Function call execution indicator -->
                            <template x-if="item.functionCalled">
                                <div class="mt-2 pt-1.5 border-t border-[#1b2922] text-[10px] text-[#34d399] flex items-center gap-1 font-mono">
                                    <span>⚡ Eksekusi DB:</span>
                                    <span x-text="item.functionCalled"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Typing indicator -->
                <div x-show="isTyping" class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded bg-[#101814] border border-[#24362d] text-[#10b981] flex items-center justify-center flex-shrink-0 text-xs">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="16" height="12" x="4" y="8" rx="2"/>
                            <path d="M12 8V4H8"/>
                        </svg>
                    </div>
                    <div class="bg-[#101814] border border-[#24362d] rounded-xl rounded-tl-none p-3 shadow-sm">
                        <div class="flex items-center space-x-1.5">
                            <div class="w-2 h-2 bg-[#10b981] rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-[#10b981] rounded-full animate-bounce [animation-delay:0.15s]"></div>
                            <div class="w-2 h-2 bg-[#10b981] rounded-full animate-bounce [animation-delay:0.3s]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Bar -->
            <div class="p-3 bg-[#0b110e] border-t border-[#1b2922]">
                <form @submit.prevent="sendMessage()" class="flex items-center gap-2">
                    <input type="text" 
                           x-model="inputMessage" 
                           :disabled="isTyping"
                           placeholder="Ketik pertanyaan atau permintaan booking..." 
                           class="flex-1 bg-[#101814] border border-[#24362d] text-[#f8fafc] placeholder-[#64748b] rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] transition disabled:opacity-50">
                    <button type="submit" 
                            :disabled="isTyping || !inputMessage.trim()"
                            class="p-2.5 bg-[#10b981] hover:bg-[#34d399] text-[#070b09] font-bold rounded-lg disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine Chatbot Logic -->
    <script>
        function kickflowChatbot() {
            return {
                isOpen: false,
                isTyping: false,
                inputMessage: '',
                messages: [],
                sessionId: 'kf_' + Math.random().toString(36).substring(2, 10) + '_' + Date.now(),

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
                            text: data.reply || 'Maaf, saya tidak dapat memahami permintaan tersebut.',
                            functionCalled: data.function_called || null
                        });
                    } catch (err) {
                        console.error('Chat error:', err);
                        this.messages.push({
                            role: 'assistant',
                            text: 'Maaf, terjadi kendala koneksi ke server. Silakan coba kembali sesaat lagi.',
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
