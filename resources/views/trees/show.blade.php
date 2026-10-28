@extends('layouts.app')

@section('title', 'Detail Pohon ' . $tree->id_pohon . ' - ' . $tree->nama_pohon)

@section('content')
<div class="space-y-6">

    <!-- Top Action Breadcrumb Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('trees.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pohon
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Cetak QR -->
            <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-600/20 transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label QR</span>
            </a>

            <!-- Unduh QR -->
            <a href="{{ route('trees.download-qr', $tree) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-download text-slate-400"></i>
                <span>Unduh SVG</span>
            </a>

            <!-- Edit Data -->
            <a href="{{ route('trees.edit', $tree) }}" class="px-4 py-2 bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-pen"></i>
                <span>Edit</span>
            </a>

            <!-- Scan Publik Preview -->
            <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Buka Scan Publik</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Tree Info, Right QR Code Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Detail Informasi Pohon -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hero Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="relative h-64 md:h-80 w-full overflow-hidden bg-slate-100">
                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/30 to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/90 backdrop-blur-md text-white font-mono font-bold text-xs uppercase tracking-wider">
                                {{ $tree->id_pohon }}
                            </span>
                            @if($tree->tingkat)
                                <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-emerald-200 text-xs font-semibold">
                                    {{ $tree->tingkat }}
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-semibold">
                                <i class="fa-regular fa-calendar mr-1"></i> Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">{{ $tree->nama_pohon }}</h1>
                        @if($tree->nama_latin)
                            <p class="text-sm md:text-base text-emerald-200 font-serif italic mt-0.5">{{ $tree->nama_latin }}</p>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 bg-slate-50/50">
                    <!-- Umur Otomatis -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Umur Pohon (Otomatis)</span>
                        <div class="text-lg font-black text-slate-900 flex items-center">
                            <i class="fa-solid fa-hourglass-half text-blue-500 mr-2 text-base"></i>
                            {{ $tree->umur }}
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Dihitung otomatis dari tanggal tanam</p>
                    </div>

                    <!-- Estimasi Karbon -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Serapan Karbon (CO₂e)</span>
                        <div class="text-lg font-black text-emerald-600 flex items-center">
                            <i class="fa-solid fa-cloud-arrow-down text-emerald-500 mr-2 text-base"></i>
                            {{ number_format($tree->estimasi_karbon, 1) }} <span class="text-xs text-slate-500 font-normal ml-1">kg</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</p>
                    </div>

                    <!-- Lokasi Area -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Area / Zona SMKKN</span>
                        <div class="text-sm font-bold text-slate-800 flex items-center truncate">
                            <i class="fa-solid fa-location-dot text-amber-500 mr-2 text-base flex-shrink-0"></i>
                            <span class="truncate">{{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Hutan Konservasi</p>
                    </div>
                </div>

                <!-- Silvikultur & Parameter Kehutanan SMKKN -->
                <div class="p-6 md:p-8 border-b border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Data Silvikultur & Karbon Hutan (SMKKN)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Diameter Batang</span>
                            <span class="text-base font-extrabold text-slate-900">{{ $tree->diameter ?? '-' }} cm</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Berat Jenis (BJ)</span>
                            <span class="text-base font-extrabold text-slate-900">{{ $tree->berat_jenis ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Biomassa (AGB)</span>
                            <span class="text-base font-extrabold text-slate-900">{{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Cadangan Karbon</span>
                            <span class="text-base font-extrabold text-slate-900">{{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) . ' Ton C' : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi & Catatan -->
                <div class="p-6 md:p-8">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi & Catatan Konservasi</h3>
                    <p class="text-sm text-slate-700 leading-relaxed">
                        {{ $tree->deskripsi ?? 'Belum ada catatan deskripsi atau perawatan yang ditambahkan untuk pohon ini.' }}
                    </p>
                </div>
            </div>

            <!-- Lokasi & Peta (Jika memiliki GPS) atau Info Area Sekolah -->
            @if($tree->hasCoordinates())
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden transition-all">
                <button type="button" onclick="toggleAdminLocationMap()" id="btn-admin-map-toggle"
                    class="w-full p-6 flex items-center justify-between text-left hover:bg-slate-50 transition-colors focus:outline-none">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-base">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Titik Koordinat & Peta Penanaman</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Koordinat: <span class="font-mono text-emerald-700 font-semibold">{{ $tree->kordinat_lat }}, {{ $tree->kordinat_lng }}</span>
                                &bull; <span class="text-emerald-600 font-semibold">Klik untuk buka peta</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span id="admin-badge-map-status" class="text-[11px] font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                            Tersembunyi
                        </span>
                        <div id="admin-icon-map-arrow" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center transform transition-transform duration-300">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </button>

                <div id="admin-map-container" class="hidden border-t border-slate-100 p-6 space-y-4 bg-slate-50/50">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Peta Sebaran Titik Pohon di Kawasan Sekolah</span>
                        <a href="{{ $tree->google_maps_url }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold transition-colors">
                            <i class="fa-solid fa-map-location-dot"></i>
                            <span>Buka di Google Maps</span>
                        </a>
                    </div>
                    <div id="tree-map" class="w-full h-64 rounded-2xl border border-slate-200 z-10 shadow-inner"></div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Lokasi Penanaman</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $tree->lokasi_taman ?? 'Area Lingkungan Sekolah SMKKN' }}
                        </p>
                    </div>
                </div>
                <span class="text-xs font-medium px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                    Tanpa GPS
                </span>
            </div>
            @endif
        </div>

        <!-- Column 3: QR Code Card & Quick Print -->
        <div class="space-y-6">
            <!-- QR Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 md:p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-500 to-green-400"></div>

                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-mono font-bold text-xs uppercase tracking-wider mb-4">
                    QR Tag Pohon SMKKN
                </span>

                <!-- QR Code Display Box -->
                <div class="w-56 h-56 mx-auto bg-white p-4 rounded-3xl border-2 border-slate-100 shadow-inner flex items-center justify-center">
                    <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
                </div>

                <!-- Kode ID & URL -->
                <div class="mt-4">
                    <h4 class="font-mono text-lg font-extrabold text-slate-900">{{ $tree->id_pohon }}</h4>
                    <p class="text-xs text-slate-400 mt-1 truncate max-w-xs mx-auto" title="{{ $tree->public_url }}">
                        {{ $tree->public_url }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-2.5">
                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs shadow-md shadow-emerald-600/30 flex items-center justify-center space-x-2 transition-all">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Label Signage (Print)</span>
                    </a>

                    <a href="{{ route('trees.download-qr', $tree) }}" class="w-full py-2.5 px-4 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-semibold text-xs shadow-sm flex items-center justify-center space-x-2 transition-all">
                        <i class="fa-solid fa-download text-slate-400"></i>
                        <span>Unduh File SVG</span>
                    </a>

                    <form action="{{ route('trees.regenerate-qr', $tree) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 px-4 text-slate-500 hover:text-slate-800 text-[11px] font-semibold flex items-center justify-center space-x-1.5 transition-colors">
                            <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                            <span>Regenerasi File QR Code</span>
                        </button>
                    </form>
                </div>

                <!-- Info Box -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-left text-xs text-slate-500 space-y-2">
                    <div class="flex items-start space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i>
                        <span>Format SVG vektor jernih & tajam saat dicetak di media plakat akrilik, plat pohon, atau stiker laminasi.</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fa-solid fa-mobile-screen text-blue-500 mt-0.5"></i>
                        <span>Pengunjung cukup scan via kamera smartphone tanpa perlu download aplikasi apapun.</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone (Delete) -->
            <div class="bg-red-50/50 rounded-3xl border border-red-100 p-6">
                <h4 class="text-xs font-bold uppercase tracking-wider text-red-800 mb-1">Hapus Data</h4>
                <p class="text-xs text-red-600/80 mb-4">Menghapus data pohon ini akan menghapus seluruh file foto dan barcode QR terkait secara permanen.</p>
                <form action="{{ route('trees.destroy', $tree) }}" method="POST" onsubmit="return confirm('Apakah Anda benar-benar yakin ingin menghapus data pohon ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus Pohon Ini</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
@if($tree->hasCoordinates())
<script>
    let adminMap = null;
    let adminMapInitialized = false;

    function toggleAdminLocationMap() {
        const container = document.getElementById('admin-map-container');
        const arrow = document.getElementById('admin-icon-map-arrow');
        const badge = document.getElementById('admin-badge-map-status');

        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            arrow.classList.add('rotate-180');
            badge.textContent = 'Ditampilkan';
            badge.classList.remove('bg-slate-100', 'text-slate-600');
            badge.classList.add('bg-emerald-100', 'text-emerald-800');

            if (!adminMapInitialized) {
                initAdminMap();
                adminMapInitialized = true;
            } else if (adminMap) {
                setTimeout(() => adminMap.invalidateSize(), 150);
            }
        } else {
            container.classList.add('hidden');
            arrow.classList.remove('rotate-180');
            badge.textContent = 'Tersembunyi';
            badge.classList.remove('bg-emerald-100', 'text-emerald-800');
            badge.classList.add('bg-slate-100', 'text-slate-600');
        }
    }

    function initAdminMap() {
        const lat = {{ $tree->kordinat_lat ?? 0 }};
        const lng = {{ $tree->kordinat_lng ?? 0 }};

        setTimeout(() => {
            adminMap = L.map('tree-map', {
                scrollWheelZoom: false
            }).setView([lat, lng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(adminMap);

            const marker = L.marker([lat, lng]).addTo(adminMap);
            marker.bindPopup("<b>{{ $tree->nama_pohon }}</b><br>{{ $tree->id_pohon }}").openPopup();

            adminMap.invalidateSize();
        }, 100);
    }
</script>
@endif
@endpush
