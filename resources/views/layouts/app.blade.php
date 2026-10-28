<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Monitoring Pohon QR') - EcoTree QR</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Sidebar Mobile Toggle Overlay -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden md:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-brand-950 via-slate-900 to-slate-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
        <!-- Brand Header -->
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-brand-400 flex items-center justify-center text-slate-950 text-xl font-extrabold shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tree text-white"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-lg tracking-tight text-white leading-tight">EcoTree <span class="text-emerald-400">QR</span></h1>
                    <p class="text-[11px] text-emerald-300/70 font-medium tracking-wider uppercase">Sistem Monitoring Pohon</p>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-6 text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Dashboard Ringkasan</span>
            </a>

            <a href="{{ route('trees.index') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('trees.index', 'trees.show', 'trees.edit') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-tree-city w-6 text-base {{ request()->routeIs('trees.index', 'trees.show', 'trees.edit') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Daftar Data Pohon</span>
            </a>

            <a href="{{ route('trees.create') }}" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('trees.create') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-plus-circle w-6 text-base {{ request()->routeIs('trees.create') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Tambah Pohon Baru</span>
            </a>

            <div class="pt-6 px-3 pb-2 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Simulasi Publik</div>
            
            <a href="{{ route('pohon.public', 'TREE-001') }}" target="_blank" class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-emerald-300 group transition-all">
                <i class="fa-solid fa-qrcode w-6 text-base text-emerald-400 group-hover:scale-110 transition-transform"></i>
                <div class="flex-1">
                    <span>Pratinjau Scan QR</span>
                    <span class="block text-[10px] text-slate-400">(Buka di Tab Baru)</span>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-500"></i>
            </a>
        </nav>

        <!-- Admin Profile & Logout -->
        <div class="p-4 border-t border-white/10 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-800 text-emerald-200 flex items-center justify-center font-bold text-sm ring-2 ring-emerald-500/30">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="truncate max-w-[130px]">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@pohon.id' }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-red-500/20 text-slate-400 hover:text-red-400 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col md:ml-72 min-h-screen">
        <!-- Topbar Mobile -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-20 px-4 py-3.5 flex items-center justify-between md:hidden">
            <button onclick="toggleSidebar()" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-sm">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <span class="font-extrabold text-slate-800">EcoTree QR</span>
            </div>
            <a href="{{ route('trees.create') }}" class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-plus"></i>
            </a>
        </header>

        <!-- Flash Messages -->
        <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-3 shadow-sm animate-fade-in">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm">Berhasil!</h4>
                        <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-start space-x-3 shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm">Terjadi Kesalahan</h4>
                        <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200/80 px-6 py-4 text-center md:text-left text-xs text-slate-500 flex flex-col md:flex-row items-center justify-between">
            <p>&copy; {{ date('Y') }} Sistem Manajemen & Monitoring Pohon Berbasis QR Code.</p>
            <p class="mt-2 md:mt-0 text-slate-400">Didukung oleh Laravel 12 & Simple QrCode.</p>
        </footer>
    </div>

    <!-- Toggle Script for Sidebar -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
