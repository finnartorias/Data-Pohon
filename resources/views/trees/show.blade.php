@extends('layouts.app')

@section('title', 'Detail Pohon ' . $tree->id_pohon . ' - ' . $tree->nama_pohon)

@section('content')
<div class="space-y-6">

    <!-- Top Action Breadcrumb Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('trees.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pohon
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Cetak QR -->
            <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label QR</span>
            </a>

            <!-- Unduh QR -->
            <a href="{{ route('trees.download-qr', $tree) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-2xl text-xs font-bold shadow-subtle transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-download text-slate-400"></i>
                <span>Unduh SVG</span>
            </a>

            <!-- Edit Data -->
            <a href="{{ route('trees.edit', $tree) }}" class="px-4 py-2 bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100 rounded-2xl text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-pen"></i>
                <span>Edit</span>
            </a>

            <!-- Scan Publik Preview -->
            <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square text-emerald-400"></i>
                <span>Buka Scan Publik</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Tree Info, Right QR Code Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Detail Informasi Pohon -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Hero Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-subtle overflow-hidden">
                <div class="relative h-64 sm:h-80 w-full overflow-hidden bg-slate-900">
                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-xl bg-emerald-600 text-white font-mono font-bold text-xs uppercase tracking-wider shadow-sm">
                                {{ $tree->id_pohon }}
                            </span>
                            @if($tree->tingkat)
                                <span class="px-3 py-1 rounded-xl bg-white/20 backdrop-blur-md text-emerald-200 text-xs font-semibold">
                                    {{ $tree->tingkat }}
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-xl bg-white/20 backdrop-blur-md text-white text-xs font-semibold">
                                <i class="fa-regular fa-calendar mr-1"></i> Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $tree->nama_pohon }}</h1>
                        @if($tree->nama_latin)
                            <p class="text-sm sm:text-base text-emerald-200 font-serif italic mt-0.5">{{ $tree->nama_latin }}</p>
                        @endif
                    </div>
                </div>

                <!-- 3 Metric Highlights -->
                <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Umur Pohon (Otomatis)</span>
                        <div class="text-lg font-black text-slate-900 flex items-center">
                            <i class="fa-solid fa-hourglass-half text-blue-500 mr-2 text-sm"></i>
                            {{ $tree->umur }}
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Dihitung dari tanggal tanam</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Serapan Karbon (CO₂e)</span>
                        <div class="text-lg font-black text-emerald-600 flex items-center">
                            <i class="fa-solid fa-cloud-arrow-down text-emerald-500 mr-2 text-sm"></i>
                            {{ number_format($tree->estimasi_karbon, 1) }} <span class="text-xs text-slate-400 font-normal ml-1">kg</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</p>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Area / Zona SMKKN</span>
                        <div class="text-sm font-bold text-slate-800 flex items-center truncate">
                            <i class="fa-solid fa-location-dot text-amber-500 mr-2 text-sm flex-shrink-0"></i>
                            <span class="truncate">{{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Lokasi penanaman terdaftar</p>
                    </div>
                </div>

                <!-- Parameters Grid -->
                <div class="p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Parameter Silvikultur & Botani Lengkap</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Diameter Batang</span>
                            <span class="font-extrabold text-slate-900 text-sm mt-0.5 block">{{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Tinggi Pohon</span>
                            <span class="font-extrabold text-slate-900 text-sm mt-0.5 block">{{ $tree->tinggi ? $tree->tinggi . ' m' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Berat Jenis Kayu</span>
                            <span class="font-extrabold text-slate-900 text-sm mt-0.5 block">{{ $tree->berat_jenis ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Cadangan Karbon</span>
                            <span class="font-extrabold text-teal-700 text-sm mt-0.5 block">{{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) . ' Ton C' : '-' }}</span>
                        </div>
                    </div>

                    @if($tree->deskripsi)
                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Deskripsi Tambahan</span>
                            <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                {{ $tree->deskripsi }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- GPS Map Section if available -->
                @if($tree->hasCoordinates())
                    <div class="p-6 border-t border-slate-100 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700 flex items-center">
                                <i class="fa-solid fa-map-location-dot text-emerald-600 mr-2"></i>
                                Peta Sebaran Geospasial: <span class="font-mono text-slate-900 ml-1">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</span>
                            </span>
                            <a href="{{ $tree->google_maps_url }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold">
                                Buka di Google Maps &rarr;
                            </a>
                        </div>
                        <div id="tree-map" class="w-full h-64 rounded-2xl border border-slate-200 z-10"></div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Column 3: High-Res QR Code Card & Actions -->
        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-subtle p-6 space-y-6 text-center">
                
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Barcode Identitas Fisik</span>
                    <h3 class="text-lg font-black text-slate-900 mt-1">QR Code Pohon</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pindai dengan kamera untuk membuka detail publik.</p>
                </div>

                <!-- QR Frame -->
                <div class="w-56 h-56 mx-auto bg-slate-50 p-4 rounded-3xl border-2 border-slate-100 shadow-inner flex items-center justify-center">
                    <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
                </div>

                <div class="font-mono text-xs text-slate-500 bg-slate-50 py-2 px-3 rounded-xl border border-slate-100 truncate">
                    Target: <span class="text-emerald-700 font-bold">{{ route('pohon.public', $tree->id_pohon) }}</span>
                </div>

                <!-- Button Actions -->
                <div class="space-y-2 pt-2">
                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank"
                        class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Label Tag Fisik</span>
                    </a>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('trees.download-qr', $tree) }}"
                            class="py-2.5 px-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-download text-slate-400"></i>
                            <span>Unduh SVG</span>
                        </a>

                        <form action="{{ route('trees.regenerate-qr', $tree) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 px-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all flex items-center justify-center space-x-1.5"
                                title="Buat ulang berkas SVG QR jika terjadi perubahan domain">
                                <i class="fa-solid fa-rotate text-slate-400"></i>
                                <span>Regenerate</span>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank"
                        class="w-full py-2.5 px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-500"></i>
                        <span>Simulasi Scan Kamera</span>
                    </a>
                </div>

            </div>

            <!-- Danger Zone Delete -->
            <div class="bg-red-50/50 rounded-3xl border border-red-100 p-5 text-center space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-red-600 block">Tindakan Bahaya</span>
                <p class="text-xs text-red-700/80 leading-relaxed">
                    Menghapus data pohon ini akan menghapus QR code dan arsipnya secara permanen.
                </p>
                <form action="{{ route('trees.destroy', $tree) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pohon {{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 px-4 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Hapus Data Pohon</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

@if($tree->hasCoordinates())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $tree->kordinat_lat }};
        const lng = {{ $tree->kordinat_lng }};

        const map = L.map('tree-map').setView([lat, lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<b>{{ $tree->nama_pohon }}</b><br>{{ $tree->id_pohon }}").openPopup();
    });
</script>
@endpush
@endif
@endsection