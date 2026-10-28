<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="#14472c">
    <title>{{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) &bull; Lembar Identitas Spesimen</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Informasi botani & estimasi serapan karbon {{ $tree->nama_pohon }} ({{ $tree->nama_latin ?? 'Pohon Tropis' }}). Kode registrasi {{ $tree->id_pohon }} di SMK Kehutanan Negeri.">
    
    <!-- Google Fonts: Inter & JetBrains Mono -->
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
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f1f5f9;
            -webkit-tap-highlight-color: transparent;
        }
        .safe-bottom {
            padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-slate-800 antialiased pb-20 sm:pb-8">

    <!-- Official Field Survey Header Banner -->
    <header class="w-full bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-30">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded bg-emerald-700 flex items-center justify-center text-white text-xs font-bold">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <div>
                    <span class="text-xs font-bold tracking-tight block">SMK KEHUTANAN NEGERI</span>
                    <span class="text-[10px] text-slate-400 block -mt-0.5">Inventarisasi Flora & Karbon Arboretum</span>
                </div>
            </div>

            <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Identitas Pohon: ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') - No. Register ' . $tree->id_pohon . '. Serapan karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. Lihat rincian: ' . url()->current()) }}" 
               target="_blank" 
               class="px-2.5 py-1.5 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold transition-colors flex items-center space-x-1.5">
                <i class="fa-brands fa-whatsapp"></i>
                <span>Bagikan</span>
            </a>
        </div>
    </header>

    <!-- Main Container: Botanical Specimen Sheet -->
    <main class="flex-1 max-w-2xl w-full mx-auto px-3 sm:px-4 py-4 sm:py-6 space-y-4">

        <!-- SPECIMEN IDENTITY CARD -->
        <div class="bg-white rounded-lg border border-slate-300 shadow-xs overflow-hidden">
            
            <!-- Card Header Bar -->
            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-[11px] text-slate-500 font-medium">No. Register:</span>
                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded bg-slate-200 text-slate-800">
                        {{ $tree->id_pohon }}
                    </span>
                </div>
                @if($tree->tingkat)
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Fase {{ $tree->tingkat }}
                    </span>
                @endif
            </div>

            <div class="p-4 sm:p-5 space-y-4">
                
                <!-- Nama Pohon & Taksonomi -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $tree->nama_pohon }}
                    </h1>
                    @if($tree->nama_latin)
                        <p class="text-base sm:text-lg text-emerald-800 italic font-medium mt-0.5">
                            {{ $tree->nama_latin }}
                        </p>
                    @endif
                    <div class="text-xs text-slate-500 flex items-center mt-2 space-x-1">
                        <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>
                        <span>Lokasi: <strong>{{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}</strong></span>
                    </div>
                </div>

                <!-- Tombol Audio Narasi Suara (Web Speech API) -->
                <div class="p-3 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded bg-emerald-700 text-white flex items-center justify-center text-xs flex-shrink-0">
                            <i id="audio-icon" class="fa-solid fa-volume-high"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block" id="audio-title">Narasi Audio Botani</span>
                            <span class="text-[11px] text-slate-500 block" id="audio-desc">Dengarkan deskripsi botani pohon ini</span>
                        </div>
                    </div>
                    <button type="button" onclick="toggleAudioNarration()" id="btn-audio-action" class="px-3 py-1.5 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold transition-colors flex-shrink-0">
                        <span id="btn-audio-text">Putar Suara</span>
                    </button>
                </div>

                <!-- Ringkasan Silvikultur & Umur -->
                <div class="grid grid-cols-2 gap-2.5 pt-1">
                    <div class="p-2.5 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] uppercase text-slate-500 font-semibold block">Taksiran Umur</span>
                        <span class="text-sm font-bold text-slate-900 block font-mono mt-0.5">{{ $tree->umur }}</span>
                        <span class="text-[10px] text-slate-500">Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d M Y') : 'Tercatat' }}</span>
                    </div>
                    <div class="p-2.5 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] uppercase text-slate-500 font-semibold block">Status Registrasi</span>
                        <span class="text-sm font-bold text-emerald-800 block mt-0.5">Terverifikasi</span>
                        <span class="text-[10px] text-slate-500">Database SMKKN</span>
                    </div>
                </div>

                <!-- Deskripsi & Karakteristik -->
                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                    <span class="text-[11px] uppercase font-bold text-slate-500 tracking-wider block">Karakteristik & Deskripsi Botani</span>
                    <div id="tree-text-content" class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-3.5 rounded border border-slate-200">
                        {{ $tree->deskripsi ?? 'Pohon ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') merupakan salah satu tegakan penting di kawasan arboretum SMKKN yang terdata dalam program inventarisasi karbon dan pelestarian flora lokal.' }}
                    </div>
                </div>

            </div>
        </div>

        <!-- SILVICULTURAL & CARBON MEASUREMENT CARD -->
        <div class="bg-white rounded-lg border border-slate-300 shadow-xs p-4 sm:p-5 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Audit Karbon & Parameter Silvikultur</h2>
                <span class="text-[11px] font-mono text-slate-500">Metode Allometrik IPCC</span>
            </div>

            <!-- Carbon Primary Metrics -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3.5 rounded bg-slate-50 border border-slate-200">
                    <span class="text-[11px] font-semibold text-slate-600 block">Estimasi Serapan Karbon</span>
                    <div class="text-2xl font-bold font-mono text-emerald-800 mt-1">
                        {{ number_format($tree->estimasi_karbon, 1) }} <span class="text-xs font-sans text-slate-500 font-normal">kg CO₂e</span>
                    </div>
                    <span class="text-[11px] text-slate-500 font-mono mt-1 block">
                        ≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e
                    </span>
                </div>

                <div class="p-3.5 rounded bg-slate-50 border border-slate-200">
                    <span class="text-[11px] font-semibold text-slate-600 block">Cadangan Karbon (C-Stock)</span>
                    <div class="text-2xl font-bold font-mono text-slate-900 mt-1">
                        {{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) : '-' }} <span class="text-xs font-sans text-slate-500 font-normal">Ton C</span>
                    </div>
                    <span class="text-[11px] text-slate-500 font-mono mt-1 block">
                        Biomassa: {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}
                    </span>
                </div>
            </div>

            <!-- Silvicultural Dimensions -->
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-2">Parameter Dimensi Tegakan:</span>
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    <div class="p-2 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-500 block">Diameter</span>
                        <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ $tree->diameter ? number_format($tree->diameter, 1) : '-' }} cm</span>
                    </div>
                    <div class="p-2 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-500 block">Tinggi</span>
                        <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ $tree->tinggi ? number_format($tree->tinggi, 1) : '-' }} m</span>
                    </div>
                    <div class="p-2 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-500 block">Berat Jenis</span>
                        <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ $tree->berat_jenis ?? '-' }}</span>
                    </div>
                    <div class="p-2 rounded bg-slate-50 border border-slate-200">
                        <span class="text-[10px] text-slate-500 block">Biomassa</span>
                        <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 0) : '-' }} kg</span>
                    </div>
                </div>
            </div>

            <!-- Environmental Equivalency -->
            <div class="pt-2 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                <span class="font-semibold text-slate-800 block text-[11px]">Ekuivalen Kompensasi Emisi:</span>
                <p class="text-[11px] leading-relaxed text-slate-600">
                    Kapasitas serapan CO₂ pohon ini setara dengan menetralkan pembakaran bensin kendaraan bermotor sejauh 
                    <strong class="font-mono text-slate-900">~{{ number_format($carKmEquivalent) }} km</strong>, 
                    atau setara daya <strong class="font-mono text-slate-900">~{{ number_format($phoneChargesEquivalent) }} kali</strong> pengisian penuh baterai smartphone.
                </p>
            </div>
        </div>

        <!-- LOCATION & MAP CARD (IF AVAILABLE) -->
        @if($tree->hasCoordinates())
        <div class="bg-white rounded-lg border border-slate-300 shadow-xs overflow-hidden">
            <button type="button" onclick="toggleLocationMap()" class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-slate-50 transition-colors">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-map-location-dot text-slate-600 text-sm"></i>
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Titik Koordinat & Peta GPS</span>
                        <span class="text-[11px] text-slate-500 font-mono">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="badge-toggle-status" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Buka Peta</span>
                    <i id="icon-toggle-arrow" class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform"></i>
                </div>
            </button>

            <div id="location-map-container" class="hidden border-t border-slate-200 p-4 space-y-3 bg-slate-50/50">
                <div id="public-tree-map" class="w-full h-56 rounded border border-slate-300"></div>
                <a href="{{ $tree->google_maps_url }}" target="_blank"
                   class="w-full py-2 px-3 rounded bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs transition-colors flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-diamond-turn-right text-emerald-400"></i>
                    <span>Buka Panduan Rute di Google Maps</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Action Links -->
        <div class="grid grid-cols-2 gap-2 pt-1">
            <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Identitas Spesimen Pohon: ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') - ' . $tree->id_pohon . '. Serapan karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. ' . url()->current()) }}" 
               target="_blank"
               class="py-2.5 px-3 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs flex items-center justify-center space-x-1.5 transition-colors shadow-2xs">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Kirim ke WhatsApp</span>
            </a>
            <button onclick="shareTree()" type="button" class="py-2.5 px-3 rounded-md bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold text-xs flex items-center justify-center space-x-1.5 transition-colors shadow-2xs">
                <i class="fa-solid fa-copy text-xs"></i>
                <span>Salin Tautan</span>
            </button>
        </div>

    </main>

    <!-- Mobile Sticky Bottom Bar (Utilitarian) -->
    <aside aria-label="Aksi Cepat" class="sm:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 px-3 py-2 z-40 safe-bottom flex items-center justify-around gap-1 shadow-lg">
        <button onclick="toggleAudioNarration()" type="button" class="flex-1 flex flex-col items-center justify-center py-1 text-slate-700 active:bg-slate-100 rounded">
            <i id="bottom-audio-icon" class="fa-solid fa-volume-high text-xs text-emerald-700 mb-0.5"></i>
            <span id="bottom-audio-label" class="text-[10px] font-semibold">Dengar</span>
        </button>

        <a href="https://api.whatsapp.com/send?text={{ rawurlencode('Pohon ' . $tree->nama_pohon . ' (' . ($tree->nama_latin ?? '') . ') - No. Register ' . $tree->id_pohon . '. Karbon: ' . number_format($tree->estimasi_karbon, 1) . ' kg CO2e. ' . url()->current()) }}" 
           target="_blank" 
           class="flex-1 flex flex-col items-center justify-center py-1 text-emerald-800 active:bg-slate-100 rounded">
            <i class="fa-brands fa-whatsapp text-xs text-emerald-700 mb-0.5"></i>
            <span class="text-[10px] font-semibold">WhatsApp</span>
        </a>

        @if($tree->hasCoordinates())
        <a href="{{ $tree->google_maps_url }}" target="_blank" class="flex-1 flex flex-col items-center justify-center py-1 text-slate-700 active:bg-slate-100 rounded">
            <i class="fa-solid fa-location-arrow text-xs text-slate-700 mb-0.5"></i>
            <span class="text-[10px] font-semibold">Rute GPS</span>
        </a>
        @endif

        <button onclick="shareTree()" type="button" class="flex-1 flex flex-col items-center justify-center py-1 text-slate-700 active:bg-slate-100 rounded">
            <i class="fa-solid fa-copy text-xs text-slate-700 mb-0.5"></i>
            <span class="text-[10px] font-semibold">Salin Link</span>
        </button>
    </aside>

    <!-- Official Minimal Footer -->
    <footer class="w-full bg-white border-t border-slate-200 py-4 mt-6 text-center text-xs text-slate-500">
        <div class="max-w-2xl mx-auto px-4 space-y-1">
            <p class="font-semibold text-slate-700">SMK Kehutanan Negeri &bull; Sistem Inventarisasi Pohon</p>
            <p class="text-[11px] text-slate-400">Data hasil pengukuran silvikultur lapangan</p>
            <div class="pt-1">
                <a href="{{ route('login') }}" class="text-[11px] text-slate-500 hover:text-slate-800 underline">Akses Administrator</a>
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
                if (badge) badge.textContent = 'Tutup Peta';

                if (!mapInitialized) {
                    initLeafletMap();
                    mapInitialized = true;
                } else if (mapInstance) {
                    setTimeout(() => { mapInstance.invalidateSize(); }, 150);
                }
            } else {
                container.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');
                if (badge) badge.textContent = 'Buka Peta';
            }
        }

        @if($tree->hasCoordinates())
        function initLeafletMap() {
            const lat = {{ $tree->kordinat_lat ?? 0 }};
            const lng = {{ $tree->kordinat_lng ?? 0 }};

            setTimeout(() => {
                mapInstance = L.map('public-tree-map', { scrollWheelZoom: false }).setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapInstance);

                const marker = L.marker([lat, lng]).addTo(mapInstance);
                marker.bindPopup("<b>{{ addslashes($tree->nama_pohon) }}</b><br><span class='font-mono'>{{ $tree->id_pohon }}</span>").openPopup();
                mapInstance.invalidateSize();
            }, 100);
        }
        @else
        function initLeafletMap() {}
        @endif

        function shareTree() {
            const shareData = {
                title: "{{ addslashes($tree->nama_pohon) }} ({{ $tree->id_pohon }})",
                text: "Identitas Pohon: {{ addslashes($tree->nama_pohon) }} ({{ addslashes($tree->nama_latin ?? '') }}). Serapan karbon: {{ number_format($tree->estimasi_karbon, 1) }} kg CO2e.",
                url: window.location.href,
            };

            if (navigator.share) {
                navigator.share(shareData).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert("Tautan spesimen berhasil disalin!");
                }).catch(() => {
                    prompt("Salin tautan ini:", window.location.href);
                });
            }
        }

        // Fitur Audio Narasi Suara
        let isSpeaking = false;
        let speechSynth = window.speechSynthesis;
        let speechUtterance = null;

        function toggleAudioNarration() {
            if (!('speechSynthesis' in window)) {
                alert('Browser ini tidak mendukung narasi suara.');
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

            const textToSpeak = `Pohon ${treeName}. Nama ilmiah: ${latinName}. Nomor register ${idTree}. Berlokasi di ${location}. Taksiran umur ${age}. Estimasi kapasitas serapan karbon sebesar ${carbonKg} kilogram C O 2 e.`;

            speechUtterance = new SpeechSynthesisUtterance(textToSpeak);
            speechUtterance.lang = 'id-ID';
            speechUtterance.rate = 0.95;

            speechUtterance.onstart = function() { setSpeakingState(true); };
            speechUtterance.onend = function() { setSpeakingState(false); };
            speechUtterance.onerror = function() { setSpeakingState(false); };

            speechSynth.speak(speechUtterance);
        }

        function setSpeakingState(speaking) {
            isSpeaking = speaking;
            const btnText = document.getElementById('btn-audio-text');
            const audioDesc = document.getElementById('audio-desc');
            const audioIcon = document.getElementById('audio-icon');
            const bottomAudioIcon = document.getElementById('bottom-audio-icon');
            const bottomAudioLabel = document.getElementById('bottom-audio-label');

            if (speaking) {
                if (btnText) btnText.textContent = 'Hentikan';
                if (audioDesc) audioDesc.textContent = 'Sedang membacakan narasi...';
                if (audioIcon) audioIcon.className = 'fa-solid fa-pause';
                if (bottomAudioIcon) bottomAudioIcon.className = 'fa-solid fa-pause text-xs text-rose-600 mb-0.5';
                if (bottomAudioLabel) bottomAudioLabel.textContent = 'Stop';
            } else {
                if (btnText) btnText.textContent = 'Putar Suara';
                if (audioDesc) audioDesc.textContent = 'Dengarkan deskripsi botani pohon ini';
                if (audioIcon) audioIcon.className = 'fa-solid fa-volume-high';
                if (bottomAudioIcon) bottomAudioIcon.className = 'fa-solid fa-volume-high text-xs text-emerald-700 mb-0.5';
                if (bottomAudioLabel) bottomAudioLabel.textContent = 'Dengar';
            }
        }
    </script>
</body>
</html>
