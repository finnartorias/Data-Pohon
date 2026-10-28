<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) - SMKKN Tree Monitoring</title>
    
    <!-- Meta SEO & Social Sharing -->
    <meta name="description" content="Informasi pohon {{ $tree->nama_pohon }} ({{ $tree->nama_latin }}). Diameter {{ $tree->diameter }} cm, tingkat {{ $tree->tingkat }}, serapan karbon: {{ $tree->estimasi_karbon }} kg CO2e di kawasan SMKKN.">
    
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
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
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased flex flex-col items-center justify-between">

    <!-- Top Navigation Header (Flexible on Mobile & PC) -->
    <header class="w-full bg-slate-900/90 backdrop-blur-md text-white border-b border-white/10 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-green-400 flex items-center justify-center text-slate-950 text-base font-extrabold shadow-md shadow-emerald-500/20">
                    <i class="fa-solid fa-tree text-white"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-sm sm:text-base tracking-tight text-white leading-tight">EcoTree <span class="text-emerald-400">QR</span></h1>
                    <p class="text-[10px] text-emerald-300 font-medium tracking-wider uppercase">Kawasan Hutan Arboretum SMKKN</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button onclick="shareTree()" class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md flex items-center space-x-1.5 transition-all border border-white/10" title="Bagikan Info Pohon">
                    <i class="fa-solid fa-share-nodes text-xs"></i>
                    <span class="hidden sm:inline">Bagikan</span>
                </button>
                <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-lock text-[11px]"></i>
                    <span class="hidden sm:inline">Login Admin</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container: Fully Flexible (Single column on Mobile, 2-Column Wide Grid on PC) -->
    <main class="w-full max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 flex-1">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            <!-- LEFT COLUMN: Tree Photo & Quick Identity (Sticky on Desktop) -->
            <div class="lg:col-span-5 space-y-5 lg:sticky lg:top-20">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-md overflow-hidden">
                    <!-- Photo Header -->
                    <div class="relative h-72 sm:h-80 md:h-96 w-full bg-slate-900 overflow-hidden group">
                        <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>

                        <!-- Badge Overlay -->
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="font-mono font-extrabold text-xs px-3 py-1.5 rounded-xl bg-emerald-500/95 text-white shadow-lg backdrop-blur-md tracking-wider">
                                {{ $tree->id_pohon }}
                            </span>
                            @if($tree->tingkat)
                                <span class="text-xs px-3 py-1.5 rounded-xl bg-slate-900/80 text-emerald-300 backdrop-blur-md font-semibold border border-white/10">
                                    <i class="fa-solid fa-layer-group mr-1"></i> {{ $tree->tingkat }}
                                </span>
                            @endif
                        </div>

                        <!-- Tree Title on Photo bottom -->
                        <div class="absolute bottom-5 inset-x-5 text-white">
                            <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight tracking-tight">
                                {{ $tree->nama_pohon }}
                            </h2>
                            @if($tree->nama_latin)
                                <p class="text-sm sm:text-base text-emerald-300 font-serif italic mt-0.5">
                                    {{ $tree->nama_latin }}
                                </p>
                            @endif
                            <p class="text-xs text-slate-300 mt-2 flex items-center">
                                <i class="fa-solid fa-location-dot text-emerald-400 mr-1.5 flex-shrink-0"></i>
                                <span class="truncate">{{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Left Card Footer Specs -->
                    <div class="p-5 bg-slate-50/80 border-t border-slate-100 grid grid-cols-2 gap-3 text-center">
                        <div class="bg-white p-3 rounded-2xl border border-slate-200/80">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Diameter Batang</span>
                            <span class="text-lg font-black text-slate-900">{{ $tree->diameter ? number_format($tree->diameter, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">cm</span></span>
                        </div>
                        <div class="bg-white p-3 rounded-2xl border border-slate-200/80">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Berat Jenis Kayu</span>
                            <span class="text-lg font-black text-slate-900">{{ $tree->berat_jenis ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Call to Share (Desktop Visible) -->
                <div class="hidden lg:block bg-gradient-to-br from-emerald-900 to-forest-950 rounded-3xl p-6 text-white text-center shadow-lg">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-xl mx-auto mb-3 text-emerald-400">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <h4 class="font-bold text-sm text-white">Sistem Monitoring Pohon SMKKN</h4>
                    <p class="text-xs text-emerald-200/80 mt-1">Data pohon ini terhubung langsung dengan barcode QR fisik di pohon.</p>
                    <button onclick="shareTree()" class="mt-4 w-full py-2.5 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs transition-colors flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-share-nodes"></i>
                        <span>Bagikan Tautan Pohon Ini</span>
                    </button>
                </div>
            </div>

            <!-- RIGHT COLUMN: Metrics, Carbon, Hidden Map, Forestry Info -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. Age Card (Perhitungan Umur Otomatis) -->
                <div class="bg-gradient-to-br from-emerald-600 via-green-600 to-teal-700 rounded-3xl p-6 text-white shadow-xl shadow-emerald-600/20 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-white/20 text-white text-[11px] font-bold uppercase tracking-wider">
                                    Umur Pohon Terhitung Otomatis
                                </span>
                            </div>
                            <h3 class="text-3xl sm:text-4xl font-black tracking-tight mt-2 flex items-center">
                                {{ $tree->umur }}
                            </h3>
                            <p class="text-xs sm:text-sm text-emerald-100 mt-1.5 flex items-center">
                                <i class="fa-regular fa-calendar-check mr-2"></i>
                                Perkiraan Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-bold self-start sm:self-center flex-shrink-0">
                            <i class="fa-solid fa-hourglass-half text-white"></i>
                        </div>
                    </div>
                </div>

                <!-- 2. Carbon & Biomass Metrics Grid -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Dampak Ekologis & Karbon</span>
                            <h3 class="text-lg font-black text-slate-900 mt-0.5">Cadangan & Serapan Karbon</h3>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-leaf"></i>
                        </div>
                    </div>

                    <!-- Highlight Carbon Serapan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Serapan Karbon (kg) -->
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200/70">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block">Serapan Karbon (CO₂e)</span>
                            <div class="text-2xl sm:text-3xl font-black text-emerald-700 mt-1">
                                {{ number_format($tree->estimasi_karbon, 1) }} <span class="text-sm font-bold text-slate-600">kg</span>
                            </div>
                            <p class="text-[11px] text-emerald-800/80 mt-1">
                                Setara dengan <strong>{{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</strong>
                            </p>
                        </div>

                        <!-- Cadangan Karbon (Ton C) -->
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-teal-50 to-cyan-50 border border-teal-200/70">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700 block">Cadangan Karbon (Carbon Stock)</span>
                            <div class="text-2xl sm:text-3xl font-black text-teal-800 mt-1">
                                {{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) : '-' }} <span class="text-sm font-bold text-slate-600">Ton C</span>
                            </div>
                            <p class="text-[11px] text-teal-800/80 mt-1">
                                Biomassa Atas Tanah (AGB): <strong>{{ number_format($tree->biomassa_agb, 1) }} kg</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Edu-graphics Equivalents -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-car text-xs"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400 block">Emisi Mobil Dinetralkan</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900">~{{ number_format($carKmEquivalent) }} km</span>
                                <span class="text-[10px] text-slate-400 block">Jarak tempuh kendaraan</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-bolt text-xs"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400 block">Setara Daya Baterai HP</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900">~{{ number_format($phoneChargesEquivalent) }} kali</span>
                                <span class="text-[10px] text-slate-400 block">Isi daya smartphone</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Forestry Parameter Details Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Parameter Silvikultur & Botani</h3>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Tingkat Tumbuh</span>
                            <span class="font-bold text-slate-800">{{ $tree->tingkat ?? 'Pohon' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Diameter</span>
                            <span class="font-bold text-slate-800">{{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Berat Jenis (BJ)</span>
                            <span class="font-bold text-slate-800">{{ $tree->berat_jenis ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Biomassa AGB</span>
                            <span class="font-bold text-slate-800">{{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}</span>
                        </div>
                    </div>

                    @if($tree->deskripsi)
                        <div class="pt-2 text-xs text-slate-600 leading-relaxed border-t border-slate-100">
                            {{ $tree->deskripsi }}
                        </div>
                    @endif
                </div>

                <!-- 4. LOKASI PENANAMAN (Peta GPS jika ada, atau Info Area Sekolah) -->
                @if($tree->hasCoordinates())
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden transition-all">
                    
                    <!-- Header Action Bar with Interactive Toggle -->
                    <button type="button" onclick="toggleLocationMap()" id="btn-toggle-location"
                        class="w-full p-5 sm:p-6 flex items-center justify-between text-left hover:bg-slate-50/80 transition-colors focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-base flex-shrink-0">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center">
                                    <span>Lokasi Penanaman & Peta GPS</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $tree->lokasi_taman ?? 'Lingkungan Sekolah SMKKN' }} &bull; <span class="text-emerald-600 font-semibold">Klik untuk melihat peta</span>
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

                    <!-- Collapsible Map Container (Hidden by Default) -->
                    <div id="location-map-container" class="hidden border-t border-slate-100 p-5 sm:p-6 space-y-4 bg-slate-50/50">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <span class="font-semibold text-slate-700">
                                <i class="fa-solid fa-location-crosshairs text-emerald-600 mr-1.5"></i>
                                Titik Koordinat: <strong class="font-mono text-slate-900">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</strong>
                            </span>
                            <span class="text-slate-400">Peta Interaktif OpenStreetMap</span>
                        </div>

                        <!-- Leaflet Interactive Map -->
                        <div id="public-tree-map" class="w-full h-64 sm:h-72 rounded-2xl border border-slate-200 z-10 shadow-inner"></div>

                        <!-- Google Maps Navigation Button -->
                        <a href="{{ $tree->google_maps_url }}" target="_blank"
                            class="w-full py-3.5 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all flex items-center justify-center space-x-2 shadow-md">
                            <i class="fa-solid fa-diamond-turn-right text-emerald-400"></i>
                            <span>Buka Petunjuk Rute Langsung di Google Maps</span>
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Lokasi Penanaman</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $tree->lokasi_taman ?? 'Area Lingkungan Sekolah SMKKN' }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                        Area Terdata
                    </span>
                </div>
                @endif

                <!-- 5. Mobile Share Button (Visible only on Mobile) -->
                <div class="lg:hidden pt-2">
                    <button onclick="shareTree()" class="w-full py-4 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-share-nodes"></i>
                        <span>Bagikan Informasi Pohon Ini</span>
                    </button>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer: Flexible for PC & Mobile -->
    <footer class="w-full bg-white border-t border-slate-200 py-6 mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-3 text-center sm:text-left">
            <div>
                <p class="font-semibold text-slate-700">Sistem Manajemen & Monitoring Pohon Berbasis QR Code</p>
                <p class="text-slate-400 mt-0.5">Kawasan Konservasi Hutan Arboretum SMK Kehutanan Negeri (SMKKN)</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="hover:text-emerald-600 transition-colors">Daftar Pohon</a>
                <span>&bull;</span>
                <a href="{{ route('login') }}" class="hover:text-emerald-600 transition-colors">Admin Login</a>
            </div>
        </div>
    </footer>

    <!-- Interactive Leaflet & Toggle Script -->
    <script>
        let mapInstance = null;
        let mapInitialized = false;

        function toggleLocationMap() {
            const container = document.getElementById('location-map-container');
            const arrow = document.getElementById('icon-toggle-arrow');
            const badge = document.getElementById('badge-toggle-status');

            if (container.classList.contains('hidden')) {
                // Tampilkan container
                container.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                if (badge) {
                    badge.textContent = 'Ditampilkan';
                    badge.classList.remove('bg-slate-100', 'text-slate-600');
                    badge.classList.add('bg-emerald-100', 'text-emerald-800');
                }

                // Inisialisasi peta hanya saat dibuka pertama kali
                if (!mapInitialized) {
                    initLeafletMap();
                    mapInitialized = true;
                } else if (mapInstance) {
                    setTimeout(() => {
                        mapInstance.invalidateSize();
                    }, 150);
                }
            } else {
                // Sembunyikan container
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
                marker.bindPopup("<b>{{ $tree->nama_pohon }}</b><br><span class='text-xs font-mono'>{{ $tree->id_pohon }}</span><br><span class='text-xs text-emerald-700 font-semibold'>{{ $tree->lokasi_taman }}</span>").openPopup();

                mapInstance.invalidateSize();
            }, 100);
        }
        @else
        function initLeafletMap() {}
        @endif

        function shareTree() {
            if (navigator.share) {
                navigator.share({
                    title: "{{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) - SMKKN",
                    text: "Lihat data pohon {{ $tree->nama_pohon }} ({{ $tree->nama_latin }}) dengan serapan karbon {{ $tree->estimasi_karbon }} kg CO2e di kawasan SMKKN!",
                    url: window.location.href,
                }).catch((err) => console.log('Share canceled', err));
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert("Tautan halaman pohon telah disalin ke clipboard!");
            }
        }
    </script>
</body>
</html>
