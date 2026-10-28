<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) &bull; EcoTree QR</title>
    
    <!-- Meta SEO & Open Graph -->
    <meta name="description" content="Informasi botani & serapan karbon {{ $tree->nama_pohon }} ({{ $tree->nama_latin ?? 'Pohon Tropis' }}). Diameter {{ $tree->diameter }} cm, serapan karbon {{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e di Arboretum SMKKN.">
    <meta property="og:title" content="{{ $tree->nama_pohon }} &bull; {{ $tree->id_pohon }}">
    <meta property="og:description" content="Pohon ber-QR di Hutan Arboretum SMKKN. Telah menyerap {{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e.">
    
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
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
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
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
                        'subtle': '0 2px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02)',
                        'card': '0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02)',
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Top Floating Navbar -->
    <header class="sticky top-0 z-40 w-full bg-slate-950/90 backdrop-blur-md border-b border-white/10 transition-all">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-tree text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <span class="font-black text-white text-base">EcoTree</span>
                        <span class="font-black text-emerald-400 text-base">QR</span>
                    </div>
                    <p class="text-[10px] text-emerald-300/80 font-medium tracking-wide">Monitoring Pohon SMKKN</p>
                </div>
            </a>

            <div class="flex items-center space-x-2">
                <button onclick="shareTree()" type="button" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold backdrop-blur-md border border-white/10 transition-all active:scale-95">
                    <i class="fa-solid fa-share-nodes text-emerald-400"></i>
                    <span>Bagikan</span>
                </button>
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition-all active:scale-95">
                    <i class="fa-solid fa-compass"></i>
                    <span class="hidden sm:inline">Katalog</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container: Clean, Mobile-First, No Photo -->
    <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6">

        <!-- 1. KOTAK UTAMA: IDENTITAS & PENJELASAN POHON -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-6 sm:p-8 space-y-6">
            
            <!-- Badges Bar -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="font-mono font-black text-xs px-3.5 py-1.5 rounded-xl bg-emerald-600 text-white shadow-sm tracking-wider">
                        <i class="fa-solid fa-qrcode mr-1"></i> {{ $tree->id_pohon }}
                    </span>
                    @if($tree->tingkat)
                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/70">
                            <i class="fa-solid fa-seedling mr-1 text-emerald-600"></i> {{ $tree->tingkat }}
                        </span>
                    @endif
                </div>

                <div class="inline-flex items-center space-x-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Terverifikasi di Sistem</span>
                </div>
            </div>

            <!-- Nama Pohon & Nama Latin -->
            <div class="space-y-1.5">
                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-700 block">
                    Nama Spesies Pohon
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $tree->nama_pohon }}
                </h1>
                @if($tree->nama_latin)
                    <p class="text-base sm:text-lg text-emerald-800 font-serif italic font-semibold">
                        {{ $tree->nama_latin }}
                    </p>
                @endif

                <p class="text-xs text-slate-500 flex items-center pt-1">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5 flex-shrink-0"></i>
                    <span>{{ $tree->lokasi_taman ?? 'Area Lingkungan Sekolah SMKKN' }}</span>
                </p>
            </div>

            <!-- Umur Pohon Terhitung Otomatis -->
            <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 block">
                        Umur Pohon (Dihitung Otomatis)
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-emerald-900 tracking-tight">
                        {{ $tree->umur }}
                    </h2>
                    <p class="text-xs text-emerald-700/80">
                        <i class="fa-regular fa-calendar-check mr-1 text-emerald-600"></i>
                        Tanggal Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d F Y') : 'Tercatat di Sistem' }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white text-emerald-600 shadow-sm flex items-center justify-center text-xl flex-shrink-0 self-start sm:self-center">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>

            <!-- Deskripsi & Penjelasan Pohon -->
            <div class="space-y-2 pt-2 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center">
                    <i class="fa-solid fa-book-open text-emerald-600 mr-2"></i>
                    Penjelasan & Karakteristik Pohon
                </h3>
                <div class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80">
                    {{ $tree->deskripsi ?? 'Pohon ' . $tree->nama_pohon . ' merupakan salah satu vegetasi penting di lingkungan SMKKN yang berperan aktif dalam menyerap emisi karbon dioksida serta menjaga keseimbangan iklim mikro kampus.' }}
                </div>
            </div>

        </section>

        <!-- 2. KOTAK TERPISAH: STATUS EMISI & SERAPAN KARBON -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-6 sm:p-8 space-y-6">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Dampak Lingkungan</span>
                        <h2 class="text-lg font-black text-slate-900 leading-tight">Status Emisi & Serapan Karbon</h2>
                    </div>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 border border-teal-200/70">
                    IPCC Forestry
                </span>
            </div>

            <!-- Grid Metrik Karbon Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Serapan Karbon CO2e -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 via-emerald-50 to-green-50 border border-emerald-200/80">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 block">
                        Serapan Karbon (CO₂e)
                    </span>
                    <div class="text-3xl font-black text-emerald-700 mt-1 flex items-baseline space-x-1.5">
                        <span>{{ number_format($tree->estimasi_karbon, 1) }}</span>
                        <span class="text-base font-bold text-slate-600">kg</span>
                    </div>
                    <p class="text-xs text-emerald-800 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i>
                        Setara {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e
                    </p>
                </div>

                <!-- Cadangan Karbon Carbon Stock -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-teal-500/10 via-teal-50 to-cyan-50 border border-teal-200/80">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-800 block">
                        Cadangan Karbon (Carbon Stock)
                    </span>
                    <div class="text-3xl font-black text-teal-800 mt-1 flex items-baseline space-x-1.5">
                        <span>{{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) : '-' }}</span>
                        <span class="text-base font-bold text-slate-600">Ton C</span>
                    </div>
                    <p class="text-xs text-teal-800 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-tree text-teal-600 mr-1.5"></i>
                        Biomassa AGB: {{ number_format($tree->biomassa_agb, 1) }} kg
                    </p>
                </div>
            </div>

            <!-- Komparasi Visual Edukasi Emisi -->
            <div class="space-y-3 pt-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                    Ekuivalen Penyerapan Emisi Riil:
                </span>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 text-base">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Emisi Mobil Dinetralkan</span>
                            <span class="text-base font-extrabold text-slate-900 block">~{{ number_format($carKmEquivalent) }} km</span>
                            <span class="text-[10px] text-slate-400">Jarak tempuh kendaraan bensin</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 text-base">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Setara Daya Baterai HP</span>
                            <span class="text-base font-extrabold text-slate-900 block">~{{ number_format($phoneChargesEquivalent) }} kali</span>
                            <span class="text-[10px] text-slate-400">Pengisian smartphone penuh</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- 3. KOTAK TERPISAH: PARAMETER DIMENSI & SILVIKULTUR -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-6 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center">
                <i class="fa-solid fa-ruler-combined text-emerald-600 mr-2"></i>
                Parameter Dimensi & Silvikultur
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Diameter Batang</span>
                    <span class="text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->diameter ? number_format($tree->diameter, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">cm</span>
                    </span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Tinggi Pohon</span>
                    <span class="text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->tinggi ? number_format($tree->tinggi, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">m</span>
                    </span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Berat Jenis Kayu</span>
                    <span class="text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->berat_jenis ?? '-' }}
                    </span>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Biomassa AGB</span>
                    <span class="text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">kg</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- 4. KOTAK TERPISAH: LOKASI & NAVIGASI -->
        @if($tree->hasCoordinates())
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card overflow-hidden">
            <button type="button" onclick="toggleLocationMap()" id="btn-toggle-location"
                class="w-full p-5 sm:p-6 flex items-center justify-between text-left hover:bg-slate-50 transition-colors focus:outline-none">
                <div class="flex items-center space-x-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">
                            Peta GPS & Lokasi Penanaman
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }} &bull; <span class="text-emerald-600 font-semibold">Buka Peta Satelit</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span id="badge-toggle-status" class="hidden sm:inline-flex text-[11px] font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                        Tersembunyi
                    </span>
                    <div id="icon-toggle-arrow" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center transform transition-transform duration-300">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </button>

            <div id="location-map-container" class="hidden border-t border-slate-100 p-5 sm:p-6 space-y-4 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <span class="font-medium text-slate-700">
                        <i class="fa-solid fa-crosshairs text-emerald-600 mr-1"></i>
                        Koordinat GPS: <strong class="font-mono text-slate-900">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</strong>
                    </span>
                    <span class="text-slate-400">Peta OpenStreetMap</span>
                </div>

                <div id="public-tree-map" class="w-full h-64 sm:h-72 rounded-2xl border border-slate-200 z-10 shadow-inner overflow-hidden"></div>

                <a href="{{ $tree->google_maps_url }}" target="_blank"
                    class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all flex items-center justify-center space-x-2 shadow-md">
                    <i class="fa-solid fa-diamond-turn-right text-emerald-400"></i>
                    <span>Buka Rute Langsung di Google Maps</span>
                </a>
            </div>
        </section>
        @else
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-5 sm:p-6 flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Area Penanaman</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $tree->lokasi_taman ?? 'Area Lingkungan Sekolah SMKKN' }}
                    </p>
                </div>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                Area Terdata
            </span>
        </section>
        @endif

        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-3 pt-2">
            <a href="{{ route('home') }}" class="py-3.5 px-4 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs sm:text-sm shadow-subtle hover:bg-slate-50 transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-compass text-emerald-600"></i>
                <span>Lihat Semua Pohon</span>
            </a>
            <button onclick="shareTree()" type="button" class="py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-share-nodes"></i>
                <span>Bagikan Info</span>
            </button>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-slate-200 py-6 mt-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3 text-center sm:text-left">
            <div>
                <p class="font-bold text-slate-700">EcoTree QR &bull; Sistem Monitoring Karbon Pohon</p>
                <p class="text-slate-400 mt-0.5">Kawasan Arboretum SMK Kehutanan Negeri (SMKKN)</p>
            </div>
            <div class="flex items-center space-x-4 font-medium">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Portal Publik</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="hover:text-emerald-600 transition-colors">Akses Administrator</a>
            </div>
        </div>
    </footer>

    <!-- Interactive Script -->
    <script>
        let mapInstance = null;
        let mapInitialized = false;

        function toggleLocationMap() {
            const container = document.getElementById('location-map-container');
            const arrow = document.getElementById('icon-toggle-arrow');
            const badge = document.getElementById('badge-toggle-status');

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                if (badge) {
                    badge.textContent = 'Ditampilkan';
                    badge.classList.remove('bg-slate-100', 'text-slate-600');
                    badge.classList.add('bg-emerald-100', 'text-emerald-800');
                }

                if (!mapInitialized) {
                    initLeafletMap();
                    mapInitialized = true;
                } else if (mapInstance) {
                    setTimeout(() => {
                        mapInstance.invalidateSize();
                    }, 150);
                }
            } else {
                container.classList.add('hidden');
                arrow.classList.remove('rotate-180');
                if (badge) {
                    badge.textContent = 'Tersembunyi';
                    badge.classList.remove('bg-emerald-100', 'text-emerald-800');
                    badge.classList.add('bg-slate-100', 'text-slate-600');
                }
            }
        }

        @if($tree->hasCoordinates())
        function initLeafletMap() {
            const lat = {{ $tree->kordinat_lat ?? 0 }};
            const lng = {{ $tree->kordinat_lng ?? 0 }};

            setTimeout(() => {
                mapInstance = L.map('public-tree-map', {
                    scrollWheelZoom: false
                }).setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapInstance);

                const marker = L.marker([lat, lng]).addTo(mapInstance);
                marker.bindPopup("<b>{{ $tree->nama_pohon }}</b><br><span class='text-xs font-mono text-emerald-700'>{{ $tree->id_pohon }}</span><br><span class='text-xs text-slate-500'>{{ $tree->lokasi_taman }}</span>").openPopup();

                mapInstance.invalidateSize();
            }, 100);
        }
        @else
        function initLeafletMap() {}
        @endif

        function shareTree() {
            const shareData = {
                title: "{{ $tree->nama_pohon }} ({{ $tree->id_pohon }})",
                text: "Pelajari pohon {{ $tree->nama_pohon }} ({{ $tree->nama_latin }}). Serapan karbon: {{ number_format($tree->estimasi_karbon, 1) }} kg CO2e di kawasan SMKKN!",
                url: window.location.href,
            };

            if (navigator.share) {
                navigator.share(shareData).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert("Tautan halaman pohon berhasil disalin ke clipboard!");
                }).catch(() => {
                    prompt("Salin tautan ini:", window.location.href);
                });
            }
        }
    </script>
</body>
</html>