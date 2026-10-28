<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="#064e3b">
    <title>{{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) &bull; EcoTree QR</title>
    
    <!-- Meta SEO & Open Graph -->
    <meta name="description" content="Informasi botani & serapan karbon {{ $tree->nama_pohon }} ({{ $tree->nama_latin ?? 'Pohon Tropis' }}). Diameter {{ $tree->diameter }} cm, serapan karbon {{ number_format($tree->estimasi_karbon, 1) }} kg CO2e di SMKKN.">
    <meta property="og:title" content="{{ $tree->nama_pohon }} &bull; {{ $tree->id_pohon }}">
    <meta property="og:description" content="Pohon ber-QR di SMKKN. Telah menyerap {{ number_format($tree->estimasi_karbon, 1) }} kg CO2e.">
    
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
        .safe-bottom {
            padding-bottom: max(0.6rem, env(safe-area-inset-bottom));
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .animate-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased selection:bg-emerald-500 selection:text-white pb-20 sm:pb-0">

    <!-- Top Floating Navbar -->
    <header class="sticky top-0 z-40 w-full bg-slate-950/90 backdrop-blur-md border-b border-white/10 transition-all">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-tree text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-1">
                        <span class="font-black text-white text-base">EcoTree</span>
                        <span class="font-black text-emerald-400 text-base">QR</span>
                    </div>
                    <p class="text-[10px] text-emerald-300/80 font-medium tracking-wide">Monitoring Pohon SMKKN</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Halo! Saya menemukan pohon ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') di SMKKN. Serapan karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. Cek selengkapnya: ' . url()->current()) }}" 
                   target="_blank" 
                   class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold backdrop-blur-md transition-all active:scale-95 shadow-sm">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Kirim ke WA</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container: Mobile-First Optimization -->
    <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-5 sm:py-8 space-y-5 sm:space-y-6">

        <!-- 1. KOTAK UTAMA: IDENTITAS & PENJELASAN POHON -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-5 sm:p-8 space-y-5">
            
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

            <!-- Foto Asli Pohon (Jika Diunggah oleh Admin) -->
            @if($tree->foto)
            <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm bg-slate-100 cursor-pointer group" onclick="openPhotoModal('{{ asset('storage/' . $tree->foto) }}', '{{ addslashes($tree->nama_pohon) }}')">
                <img src="{{ asset('storage/' . $tree->foto) }}" alt="{{ $tree->nama_pohon }}" class="w-full h-52 sm:h-72 object-cover group-hover:scale-[1.02] transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end justify-between p-3.5">
                    <span class="text-[11px] text-white font-medium flex items-center bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-camera mr-1.5 text-emerald-400"></i> Dokumentasi Lapangan
                    </span>
                    <span class="text-[11px] text-white/90 font-medium bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-lg">
                        <i class="fa-solid fa-expand mr-1"></i> Perbesar
                    </span>
                </div>
            </div>
            @endif

            <!-- Nama Pohon & Nama Latin -->
            <div class="space-y-1.5">
                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-700 block">
                    Nama Spesies Pohon
                </span>
                <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $tree->nama_pohon }}
                </h1>
                @if($tree->nama_latin)
                    <p class="text-base sm:text-lg text-emerald-800 font-serif italic font-semibold">
                        {{ $tree->nama_latin }}
                    </p>
                @endif

                <p class="text-xs text-slate-500 flex items-center pt-1">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5 flex-shrink-0"></i>
                    <span>{{ $tree->lokasi_taman ?? 'Area Kawasan SMKKN' }}</span>
                </p>
            </div>

            <!-- Tombol Audio Narasi Suara (Khusus Lapangan / Mobile) -->
            <div class="p-3.5 sm:p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 flex items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <div id="audio-indicator" class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                        <i id="audio-icon" class="fa-solid fa-volume-high"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 block" id="audio-title">Dengarkan Audio Narasi</span>
                        <p class="text-[11px] text-emerald-800 font-medium" id="audio-desc">Tekan untuk mendengarkan rangkuman pohon ini</p>
                    </div>
                </div>
                <button type="button" onclick="toggleAudioNarration()" id="btn-audio-action" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-sm transition-all active:scale-95 flex-shrink-0">
                    <span id="btn-audio-text">Putar Suara</span>
                </button>
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
                <div id="tree-text-content" class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 sm:p-5 rounded-2xl border border-slate-200/80">
                    {{ $tree->deskripsi ?? 'Pohon ' . $tree->nama_pohon . ' merupakan salah satu vegetasi penting di lingkungan SMKKN yang berperan aktif dalam menyerap emisi karbon dioksida serta menjaga keseimbangan iklim mikro kampus.' }}
                </div>
            </div>

        </section>

        <!-- 2. KOTAK TERPISAH: STATUS EMISI & SERAPAN KARBON -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-5 sm:p-8 space-y-5">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Dampak Lingkungan</span>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-tight">Status Emisi & Serapan Karbon</h2>
                    </div>
                </div>
                <span class="text-[11px] sm:text-xs font-bold px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 border border-teal-200/70">
                    IPCC Forestry
                </span>
            </div>

            <!-- Grid Metrik Karbon Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Serapan Karbon CO2e -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 via-emerald-50 to-green-50 border border-emerald-200/80">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 block">
                        Serapan Karbon (CO2e)
                    </span>
                    <div class="text-3xl font-black text-emerald-700 mt-1 flex items-baseline space-x-1.5">
                        <span>{{ number_format($tree->estimasi_karbon, 1) }}</span>
                        <span class="text-base font-bold text-slate-600">kg</span>
                    </div>
                    <p class="text-xs text-emerald-800 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i>
                        Setara {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO2e
                    </p>
                </div>

                <!-- Cadangan Karbon Carbon Stock -->
                <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-teal-500/10 via-teal-50 to-cyan-50 border border-teal-200/80">
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
            <div class="space-y-2.5 pt-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">
                    Ekuivalen Penyerapan Emisi Riil:
                </span>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 text-base">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Emisi Mobil Dinetralkan</span>
                            <span class="text-base font-extrabold text-slate-900 block">~{{ number_format($carKmEquivalent) }} km</span>
                            <span class="text-[10px] text-slate-500">Jarak tempuh mobil bensin</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 text-base">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Setara Daya Baterai HP</span>
                            <span class="text-base font-extrabold text-slate-900 block">~{{ number_format($phoneChargesEquivalent) }} kali</span>
                            <span class="text-[10px] text-slate-500">Pengisian smartphone penuh</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- 3. KOTAK TERPISAH: PARAMETER DIMENSI & SILVIKULTUR -->
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-5 sm:p-8 space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center">
                <i class="fa-solid fa-ruler-combined text-emerald-600 mr-2"></i>
                Parameter Dimensi & Silvikultur
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3 text-center">
                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Diameter Batang</span>
                    <span class="text-base sm:text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->diameter ? number_format($tree->diameter, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">cm</span>
                    </span>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Tinggi Pohon</span>
                    <span class="text-base sm:text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->tinggi ? number_format($tree->tinggi, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">m</span>
                    </span>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Berat Jenis Kayu</span>
                    <span class="text-base sm:text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->berat_jenis ?? '-' }}
                    </span>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Biomassa AGB</span>
                    <span class="text-base sm:text-lg font-black text-slate-900 mt-1 block">
                        {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) : '-' }} <span class="text-xs text-slate-400 font-normal">kg</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- 4. KOTAK TERPISAH: LOKASI & NAVIGASI -->
        @if($tree->hasCoordinates())
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card overflow-hidden">
            <button type="button" onclick="toggleLocationMap()" id="btn-toggle-location"
                class="w-full p-4 sm:p-6 flex items-center justify-between text-left hover:bg-slate-50 transition-colors focus:outline-none">
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

            <div id="location-map-container" class="hidden border-t border-slate-100 p-4 sm:p-6 space-y-4 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <span class="font-medium text-slate-700">
                        <i class="fa-solid fa-crosshairs text-emerald-600 mr-1"></i>
                        Koordinat GPS: <strong class="font-mono text-slate-900">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</strong>
                    </span>
                    <span class="text-slate-400">Peta OpenStreetMap</span>
                </div>

                <div id="public-tree-map" class="w-full h-64 sm:h-72 rounded-2xl border border-slate-200 z-10 shadow-inner overflow-hidden"></div>

                <a href="{{ $tree->google_maps_url }}" target="_blank"
                    class="w-full py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all flex items-center justify-center space-x-2 shadow-md active:scale-95">
                    <i class="fa-solid fa-diamond-turn-right text-emerald-400"></i>
                    <span>Buka Navigasi Rute di Google Maps</span>
                </a>
            </div>
        </section>
        @else
        <section class="bg-white rounded-3xl border border-slate-200/80 shadow-card p-4 sm:p-6 flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Area Penanaman</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $tree->lokasi_taman ?? 'Area Kawasan SMKKN' }}
                    </p>
                </div>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                Area Terdata
            </span>
        </section>
        @endif

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Halo! Saya menemukan pohon ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') di SMKKN. Serapan karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. Cek selengkapnya: ' . url()->current()) }}" 
               target="_blank"
               class="py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2 active:scale-95">
                <i class="fa-brands fa-whatsapp text-base"></i>
                <span>Kirim Hasil ke WhatsApp</span>
            </a>
            <button onclick="shareTree()" type="button" class="py-3.5 px-4 rounded-2xl bg-white border border-slate-200 text-slate-800 font-bold text-xs sm:text-sm shadow-subtle hover:bg-slate-50 transition-all flex items-center justify-center space-x-2 active:scale-95">
                <i class="fa-solid fa-share-nodes text-emerald-600"></i>
                <span>Salin Tautan / Bagikan</span>
            </button>
        </div>

    </main>

    <!-- Mobile Sticky Bottom Quick Action Bar (Khusus Layar HP / Satu Tangan) -->
    <aside aria-label="Mobile Bottom Navigation" class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 px-3 py-2 z-40 shadow-2xl safe-bottom flex items-center justify-around gap-1.5">
        <button onclick="toggleAudioNarration()" type="button" class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-700 active:bg-slate-100 focus:outline-none transition-transform active:scale-95">
            <div id="bottom-audio-icon-wrap" class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs mb-0.5">
                <i id="bottom-audio-icon" class="fa-solid fa-volume-high"></i>
            </div>
            <span id="bottom-audio-label" class="text-[10px] font-bold text-slate-700">Dengar</span>
        </button>

        <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Halo! Saya menemukan pohon ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') di SMKKN. Serapan karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. Cek detail lengkapnya di: ' . url()->current()) }}" 
           target="_blank" 
           class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-700 active:bg-slate-100 focus:outline-none transition-transform active:scale-95">
            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs mb-0.5 shadow-sm shadow-emerald-600/30">
                <i class="fa-brands fa-whatsapp text-sm"></i>
            </div>
            <span class="text-[10px] font-bold text-emerald-800">Kirim WA</span>
        </a>

        @if($tree->hasCoordinates())
        <a href="{{ $tree->google_maps_url }}" target="_blank" class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-700 active:bg-slate-100 focus:outline-none transition-transform active:scale-95">
            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs mb-0.5">
                <i class="fa-solid fa-location-arrow"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-700">Rute GPS</span>
        </a>
        @else
        <button onclick="shareTree()" type="button" class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-700 active:bg-slate-100 focus:outline-none transition-transform active:scale-95">
            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-xs mb-0.5">
                <i class="fa-solid fa-copy"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-700">Salin Link</span>
        </button>
        @endif

        <button onclick="shareTree()" type="button" class="flex-1 flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-700 active:bg-slate-100 focus:outline-none transition-transform active:scale-95">
            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-xs mb-0.5">
                <i class="fa-solid fa-share-nodes"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-700">Bagikan</span>
        </button>
    </aside>

    <!-- Modal Pratinjau Foto -->
    <div id="photo-modal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4" onclick="closePhotoModal()">
        <div class="max-w-2xl w-full bg-transparent text-center space-y-3" onclick="event.stopPropagation()">
            <div class="relative inline-block rounded-2xl overflow-hidden shadow-2xl border border-white/20">
                <img id="photo-modal-img" src="" alt="Pratinjau Foto Pohon" class="max-h-[80vh] w-auto mx-auto object-contain">
                <button onclick="closePhotoModal()" type="button" class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p id="photo-modal-caption" class="text-white text-xs sm:text-sm font-semibold"></p>
        </div>
    </div>

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

    <!-- Interactive Scripts -->
    <script>
        let mapInstance = null;
        let mapInitialized = false;

        function toggleLocationMap() {
            const container = document.getElementById('location-map-container');
            const arrow = document.getElementById('icon-toggle-arrow');
            const badge = document.getElementById('badge-toggle-status');

            if (!container) return;

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
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
                if (arrow) arrow.classList.remove('rotate-180');
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
                marker.bindPopup("<b>{{ addslashes($tree->nama_pohon) }}</b><br><span class='text-xs font-mono text-emerald-700'>{{ $tree->id_pohon }}</span><br><span class='text-xs text-slate-500'>{{ addslashes($tree->lokasi_taman ?? 'Arboretum SMKKN') }}</span>").openPopup();

                mapInstance.invalidateSize();
            }, 100);
        }
        @else
        function initLeafletMap() {}
        @endif

        function shareTree() {
            const shareData = {
                title: "{{ addslashes($tree->nama_pohon) }} ({{ $tree->id_pohon }})",
                text: "Pelajari pohon {{ addslashes($tree->nama_pohon) }} ({{ addslashes($tree->nama_latin ?? '') }}). Serapan karbon: {{ number_format($tree->estimasi_karbon, 1) }} kg CO2e di kawasan SMKKN!",
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

        // Fitur Modal Foto
        function openPhotoModal(src, caption) {
            const modal = document.getElementById('photo-modal');
            const img = document.getElementById('photo-modal-img');
            const cap = document.getElementById('photo-modal-caption');
            img.src = src;
            cap.textContent = caption;
            modal.classList.remove('hidden');
        }

        function closePhotoModal() {
            const modal = document.getElementById('photo-modal');
            modal.classList.add('hidden');
        }

        // Fitur Audio Narasi Suara (Web Speech API Native)
        let isSpeaking = false;
        let speechSynth = window.speechSynthesis;
        let speechUtterance = null;

        function toggleAudioNarration() {
            if (!('speechSynthesis' in window)) {
                alert('Fitur suara tidak didukung oleh browser ini.');
                return;
            }

            if (isSpeaking) {
                speechSynth.cancel();
                setSpeakingState(false);
                return;
            }

            const treeName = "{{ addslashes($tree->nama_pohon) }}";
            const latinName = "{{ addslashes($tree->nama_latin ?? '') }}";
            const idTree = "{{ $tree->id_pohon }}";
            const carbonKg = "{{ number_format($tree->estimasi_karbon, 1) }}";
            const location = "{{ addslashes($tree->lokasi_taman ?? 'Arboretum SMKKN') }}";
            const age = "{{ $tree->umur }}";

            const textToSpeak = `Pohon ${treeName}. Nama latin: ${latinName}. Kode pohon ${idTree}. Berlokasi di ${location}. Umur pohon saat ini ${age}. Pohon ini telah menyerap estimasi karbon sebesar ${carbonKg} kilogram C O 2 e. Mari lestarikan keanekaragaman hayati dan pohon kita!`;

            speechUtterance = new SpeechSynthesisUtterance(textToSpeak);
            speechUtterance.lang = 'id-ID';
            speechUtterance.rate = 0.95; // Kecepatan nyaman

            speechUtterance.onstart = function() {
                setSpeakingState(true);
            };

            speechUtterance.onend = function() {
                setSpeakingState(false);
            };

            speechUtterance.onerror = function() {
                setSpeakingState(false);
            };

            speechSynth.speak(speechUtterance);
        }

        function setSpeakingState(speaking) {
            isSpeaking = speaking;
            const btnText = document.getElementById('btn-audio-text');
            const audioDesc = document.getElementById('audio-desc');
            const audioIcon = document.getElementById('audio-icon');
            const bottomIconWrap = document.getElementById('bottom-audio-icon-wrap');
            const bottomLabel = document.getElementById('bottom-audio-label');

            if (speaking) {
                if (btnText) btnText.textContent = 'Hentikan Suara';
                if (audioDesc) audioDesc.textContent = 'Sedang membacakan narasi pohon...';
                if (audioIcon) {
                    audioIcon.classList.remove('fa-volume-high');
                    audioIcon.classList.add('fa-pause');
                }
                if (bottomIconWrap) {
                    bottomIconWrap.classList.remove('bg-emerald-100', 'text-emerald-700');
                    bottomIconWrap.classList.add('bg-red-500', 'text-white', 'animate-pulse');
                }
                if (bottomLabel) bottomLabel.textContent = 'Berhenti';
            } else {
                if (btnText) btnText.textContent = 'Putar Suara';
                if (audioDesc) audioDesc.textContent = 'Tekan untuk mendengarkan rangkuman pohon ini';
                if (audioIcon) {
                    audioIcon.classList.remove('fa-pause');
                    audioIcon.classList.add('fa-volume-high');
                }
                if (bottomIconWrap) {
                    bottomIconWrap.classList.remove('bg-red-500', 'text-white', 'animate-pulse');
                    bottomIconWrap.classList.add('bg-emerald-100', 'text-emerald-700');
                }
                if (bottomLabel) bottomLabel.textContent = 'Dengar';
            }
        }
    </script>
</body>
</html>
