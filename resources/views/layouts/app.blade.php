<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventarisasi Pohon') &bull; SI-Pohon SMKKN</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                    },
                    colors: {
                        forest: {
                            50: '#f2f7f4',
                            100: '#e1ede6',
                            600: '#1e6f43',
                            700: '#185936',
                            800: '#14472c',
                            900: '#0f3621',
                            950: '#092114',
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
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased flex flex-col md:flex-row">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 hidden md:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out flex flex-col border-r border-slate-800">
        
        <!-- Brand Header -->
        <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between bg-slate-950/40">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-tight">SI-POHON SMKKN</h1>
                    <p class="text-[11px] text-slate-400">Database & Inventarisasi</p>
                </div>
            </div>
            
            <button onclick="toggleSidebar()" type="button" title="Tutup Menu" class="text-slate-400 hover:text-white p-1.5 rounded-md hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <div class="px-3 pt-2 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Navigasi Utama</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-700 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-4 text-center text-sm"></i>
                <span>Ringkasan Dashboard</span>
            </a>

            <a href="{{ route('trees.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('trees.index', 'trees.show', 'trees.edit') ? 'bg-emerald-700 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-table-list w-4 text-center text-sm"></i>
                <span>Database Pohon</span>
            </a>

            <a href="{{ route('trees.create') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('trees.create') ? 'bg-emerald-700 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-square-plus w-4 text-center text-sm"></i>
                <span>Input Pohon Baru</span>
            </a>
        </nav>

        <!-- Admin Profile & Logout -->
        <div class="p-3 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between">
            <div class="flex items-center space-x-2.5 truncate">
                <div class="w-7 h-7 rounded-md bg-slate-800 text-slate-300 flex items-center justify-center font-bold text-xs border border-slate-700">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@pohon.id' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Keluar Akun" class="p-1.5 rounded-md text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div id="main-content" class="flex-1 flex flex-col md:ml-64 min-h-screen transition-all duration-200">
        
        <!-- Institutional Header Topbar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-20 px-4 sm:px-6 py-2.5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" type="button" class="p-1.5 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors text-xs font-medium flex items-center space-x-1.5">
                    <i class="fa-solid fa-bars"></i>
                    <span class="hidden sm:inline">Menu</span>
                </button>

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <div class="text-xs">
                    <span class="text-slate-500 font-normal">Panel Admin: </span>
                    <span class="font-semibold text-slate-800">@yield('title', 'Sistem Inventarisasi Pohon')</span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('trees.create') }}" class="px-3 py-1.5 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center space-x-1.5 transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Data</span>
                </a>
            </div>
        </header>

        <!-- Flash Messages & Main Views -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-5">
            @if(session('success'))
                <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center space-x-2.5 text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 flex items-center space-x-2.5 text-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Minimal Footer -->
        <footer class="bg-white border-t border-slate-200 px-6 py-3 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-1">
            <p>Sistem Informasi Inventarisasi Pohon &bull; SMK Kehutanan Negeri</p>
            <p class="text-slate-400 font-mono text-[11px]">&copy; {{ date('Y') }} SI-Pohon</p>
        </footer>
    </div>

    <!-- Toggle Script -->
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
