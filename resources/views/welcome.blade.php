<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTree QR SMKKN &bull; Sistem Monitoring & Eksplorasi Pohon Berbasis QR Code</title>
    
    <meta name="description" content="Eksplorasi pohon, estimasi cadangan karbon, dan katalog flora digital di kawasan konservasi Arboretum SMK Kehutanan Negeri.">
    
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
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        forest: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#032311',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-pattern {
            background-color: #032311;
            background-image: radial-gradient(rgba(34, 197, 94, 0.15) 1px, transparent 0);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="w-full bg-slate-950/90 backdrop-blur-md text-white border-b border-white/10 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tree text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <span class="font-extrabold text-base text-white">EcoTree</span>
                        <span class="font-extrabold text-base text-emerald-400">QR</span>
                    </div>
                    <p class="text-[10px] text-emerald-300/80 font-medium tracking-wide">Kawasan Hutan Arboretum SMKKN</p>
                </div>
            </a>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Buka Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/10 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-lock text-emerald-400"></i>
                        <span>Login Admin</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Header with Forest Theme -->
    <section class="hero-pattern text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden border-b border-white/10">
        <div class="max-w-4xl mx-auto text-center space-y-5 relative z-10">
            
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-md">
                <i class="fa-solid fa-leaf text-emerald-400"></i>
                <span>Sistem Monitoring Karbon & Pohon Berbasis QR Code</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white leading-tight">
                Jelajahi Pohon & Karbon di <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-green-300 to-teal-300">
                    Arboretum SMKKN
                </span>
            </h1>

            <p class="text-sm sm:text-base text-emerald-100/80 max-w-2xl mx-auto leading-relaxed">
                Pindai kode QR fisik di pohon kawasan sekolah atau gunakan pencarian di bawah untuk mengetahui taksonomi, taksiran umur, serta kapasitas serapan karbon tiap pohon.
            </p>

            <!-- Search Form in Hero -->
            <form action="{{ route('home') }}" method="GET" class="max-w-xl mx-auto pt-4">
                <div class="relative flex items-center">
                    <div class="absolute left-4 text-emerald-400 text-base pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Ketik nama pohon, kode ID (misal: TREE-001), atau lokasi..."
                        class="w-full pl-12 pr-28 py-3.5 sm:py-4 rounded-2xl bg-white/10 hover:bg-white/15 focus:bg-white text-white focus:text-slate-900 border border-white/20 focus:border-emerald-500 text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/20 placeholder-emerald-200/50 focus:placeholder-slate-400 backdrop-blur-md transition-all shadow-xl">
                    <button type="submit" class="absolute right-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-all shadow-md">
                        Cari
                    </button>
                </div>
            </form>

            <!-- Summary Stats Pill -->
            <div class="pt-6 grid grid-cols-3 gap-3 sm:gap-6 max-w-xl mx-auto text-center">
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-emerald-400">{{ number_format($totalTrees) }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase tracking-wider font-semibold mt-0.5">Pohon Terdata</div>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-emerald-400">
                        @if($totalCarbon >= 1000)
                            {{ number_format($totalCarbon / 1000, 2) }} <span class="text-xs text-emerald-200">Ton</span>
                        @else
                            {{ number_format($totalCarbon, 0) }} <span class="text-xs text-emerald-200">kg</span>
                        @endif
                    </div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase tracking-wider font-semibold mt-0.5">Serapan CO₂e</div>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-emerald-400">{{ $locationsCount }}</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 uppercase tracking-wider font-semibold mt-0.5">Titik Lokasi</div>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Content: Tree Cards Grid -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-10 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Katalog Flora Digital</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    @if(request('q'))
                        Hasil pencarian untuk "<span class="font-semibold text-emerald-600">{{ request('q') }}</span>" ({{ $trees->total() }} pohon)
                        <a href="{{ route('home') }}" class="text-emerald-700 underline ml-2 font-bold">Reset</a>
                    @else
                        Menampilkan seluruh data pohon di area SMKKN
                    @endif
                </p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-200/80 text-slate-700">
                Total: {{ $trees->total() }} Pohon
            </span>
        </div>

        @if($trees->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak ada data pohon yang cocok</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Coba gunakan kata kunci lain seperti nama pohon, nama ilmiah, atau klik tombol di bawah untuk melihat semua data.
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold">
                    Lihat Semua Pohon
                </a>
            </div>
        @else
            <!-- Grid of Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trees as $tree)
                    <a href="{{ route('pohon.public', $tree->id_pohon) }}"
                        class="group bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 overflow-hidden flex flex-col transform hover:-translate-y-1">
                        
                        <!-- Photo Header -->
                        <div class="relative h-48 w-full bg-slate-900 overflow-hidden">
                            <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex items-center space-x-1.5">
                                <span class="font-mono font-black text-[11px] px-2.5 py-1 rounded-lg bg-emerald-600 text-white shadow-sm backdrop-blur-md">
                                    {{ $tree->id_pohon }}
                                </span>
                            </div>

                            @if($tree->tingkat)
                                <div class="absolute top-3 right-3">
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-900/80 text-emerald-300 backdrop-blur-md border border-white/10">
                                        {{ $tree->tingkat }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute bottom-3 left-3 right-3 text-white">
                                <h3 class="font-extrabold text-lg text-white leading-tight group-hover:text-emerald-300 transition-colors">
                                    {{ $tree->nama_pohon }}
                                </h3>
                                @if($tree->nama_latin)
                                    <p class="text-xs text-emerald-200 font-serif italic mt-0.5 truncate">
                                        {{ $tree->nama_latin }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-4">
                            
                            <div class="grid grid-cols-2 gap-2 text-center text-xs">
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Diameter</span>
                                    <span class="font-extrabold text-slate-800">{{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                                </div>
                                <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100">
                                    <span class="text-[10px] uppercase font-bold text-emerald-700 block">Serapan CO₂e</span>
                                    <span class="font-extrabold text-emerald-800">{{ number_format($tree->estimasi_karbon, 1) }} kg</span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500 truncate max-w-[170px]">
                                    <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                                    {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                                </span>
                                <span class="text-emerald-600 font-bold group-hover:translate-x-1 transition-transform flex items-center space-x-1">
                                    <span>Lihat Detail</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $trees->links() }}
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-slate-200 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4 text-center sm:text-left">
            <div>
                <p class="font-bold text-slate-800 text-sm">Sistem Manajemen & Monitoring Pohon Berbasis QR Code</p>
                <p class="text-slate-400 mt-1">Kawasan Konservasi Hutan Arboretum SMK Kehutanan Negeri (SMKKN)</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 font-medium transition-colors">Beranda Katalog</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="text-emerald-700 hover:text-emerald-800 font-bold transition-colors">
                    <i class="fa-solid fa-lock mr-1"></i> Login Pengelola / Admin
                </a>
            </div>
        </div>
    </footer>

</body>
</html>