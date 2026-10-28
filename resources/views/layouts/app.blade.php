<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Monitoring Pohon QR') &bull; EcoTree QR</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
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
                            950: '#032311',
                        }
                    },
                    boxShadow: {
                        'subtle': '0 2px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02)',
                        'card': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
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
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col md:flex-row selection:bg-emerald-500 selection:text-white">

    <!-- Mobile Sidebar Backdrop (Klik untuk tutup di layar HP) -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-30 hidden md:hidden transition-opacity duration-300"></div>

    <!-- Sidebar Navigation (Collapsible / Bisa Disembunyikan) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 bg-gradient-to-b from-brand-950 via-slate-950 to-slate-950 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl border-r border-white/5">
        
        <!-- Brand Header & Tombol Sembunyikan -->
        <div class="p-5 border-b border-white/10 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-emerald-400 flex items-center justify-center text-slate-950 text-lg font-black shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tree text-white"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <span class="font-extrabold text-base tracking-tight text-white leading-tight">EcoTree</span>
                        <span class="font-extrabold text-base tracking-tight text-emerald-400 leading-tight">QR</span>
                    </div>
                    <p class="text-[10px] text-emerald-300/70 font-semibold tracking-wider uppercase">Sistem Monitoring Pohon</p>
                </div>
            </a>
            
            <!-- Tombol Sembunyikan Menu (Bekerja di HP & Desktop) -->
            <button onclick="toggleSidebar()" type="button" title="Sembunyikan Sidebar" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all active:scale-95">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Menu Utama Admin</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-6 text-sm {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Dashboard Ringkasan</span>
            </a>

            <a href="{{ route('trees.index') }}" class="flex items-center px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('trees.index', 'trees.show', 'trees.edit') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-tree-city w-6 text-sm {{ request()->routeIs('trees.index', 'trees.show', 'trees.edit') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Daftar Data Pohon</span>
            </a>

            <a href="{{ route('trees.create') }}" class="flex items-center px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('trees.create') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-circle-plus w-6 text-sm {{ request()->routeIs('trees.create') ? 'text-white' : 'text-emerald-400' }}"></i>
                <span>Tambah Pohon Baru</span>
            </a>
        </nav>

        <!-- Admin Profile & Logout -->
        <div class="p-4 border-t border-white/10 bg-slate-950/60">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-800 text-emerald-200 flex items-center justify-center font-bold text-sm ring-2 ring-emerald-500/30">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="truncate max-w-[120px]">
                        <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@pohon.id' }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-red-500/20 text-slate-400 hover:text-red-400 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area (Auto-Expand Saat Menu Disembunyikan) -->
    <div id="main-content" class="flex-1 flex flex-col md:ml-72 min-h-screen transition-all duration-300 ease-in-out">
        
        <!-- Universal Topbar (Desktop & Mobile) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-20 px-4 sm:px-6 py-3 flex items-center justify-between shadow-2xs">
            <div class="flex items-center space-x-3">
                <!-- Tombol Toggle Sidebar -->
                <button onclick="toggleSidebar()" type="button" title="Sembunyikan / Tampilkan Menu" class="inline-flex items-center space-x-2 px-3 py-2 rounded-2xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-bold transition-all active:scale-95 border border-slate-200/80 shadow-2xs">
                    <i class="fa-solid fa-bars text-sm"></i>
                    <span id="btn-sidebar-label" class="hidden sm:inline">Menu Navigasi</span>
                </button>

                <div class="hidden sm:flex items-center space-x-2 pl-2 border-l border-slate-200 text-xs text-slate-500 font-medium">
                    <span class="font-bold text-slate-800 truncate">@yield('title', 'Sistem Monitoring Pohon QR')</span>
                </div>
            </div>

            <div class="flex items-center space-x-2.5">
                <a href="{{ route('trees.create') }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm flex items-center space-x-1.5 transition-all active:scale-95">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span class="hidden xs:inline">Pohon Baru</span>
                </a>

                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                <div class="hidden sm:flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages & Main Views -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-3 shadow-subtle animate-fade-in">
                    <div class="w-7 h-7 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-emerald-900">Operasi Berhasil</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-start space-x-3 shadow-subtle">
                    <div class="w-7 h-7 rounded-xl bg-red-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-red-900">Terjadi Kendala</h4>
                        <p class="text-xs text-red-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200/80 px-6 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} Sistem Monitoring Pohon Berbasis QR Code &bull; SMKKN</p>
            <p class="text-slate-400">Laravel 12 &bull; Simple QrCode &bull; Modern UI</p>
        </footer>
    </div>

    <!-- Toggle Script for Sidebar (Desktop & Mobile with LocalStorage Persistence) -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const mainContent = document.getElementById('main-content');
            
            const isDesktop = window.innerWidth >= 768;
            
            if (isDesktop) {
                const isCollapsed = sidebar.classList.contains('md:-translate-x-full');
                if (isCollapsed) {
                    sidebar.classList.remove('md:-translate-x-full');
                    mainContent.classList.remove('md:ml-0');
                    localStorage.setItem('sidebar_collapsed', 'false');
                } else {
                    sidebar.classList.add('md:-translate-x-full');
                    mainContent.classList.add('md:ml-0');
                    localStorage.setItem('sidebar_collapsed', 'true');
                }
            } else {
                const isMobileOpen = !sidebar.classList.contains('-translate-x-full');
                if (isMobileOpen) {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                }
            }
        }

        // Restore saved sidebar collapsed state on Desktop
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth >= 768 && localStorage.getItem('sidebar_collapsed') === 'true') {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                if (sidebar && mainContent) {
                    sidebar.classList.add('md:-translate-x-full');
                    mainContent.classList.add('md:ml-0');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
