@extends('layouts.app')

@section('title', 'Spesimen ' . $tree->id_pohon . ' - ' . $tree->nama_pohon)

@section('content')
<div class="space-y-4">

    <!-- Top Action Breadcrumb Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
        <a href="{{ route('trees.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1.5 text-xs"></i> Kembali ke Database
        </a>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md text-xs font-semibold shadow-xs transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label QR</span>
            </a>

            <a href="{{ route('trees.download-qr', $tree) }}" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-download text-slate-400"></i>
                <span>Unduh SVG</span>
            </a>

            <a href="{{ route('trees.edit', $tree) }}" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-pen text-slate-400"></i>
                <span>Edit Data</span>
            </a>

            <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-md text-xs font-semibold transition-colors flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-up-right-from-square text-emerald-400"></i>
                <span>Lihat Scan Publik</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Left Specimen Dossier, Right QR Code -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- Column 1 & 2: Informasi Spesimen -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Specimen Dossier Card -->
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
                
                <!-- Header Dossier -->
                <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="font-mono font-bold text-xs px-2.5 py-0.5 rounded bg-slate-200 text-slate-800">
                            {{ $tree->id_pohon }}
                        </span>
                        @if($tree->tingkat)
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Tingkat {{ $tree->tingkat }}
                            </span>
                        @endif
                        <span class="text-xs text-slate-500 font-mono">
                            Tanam: {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d M Y') : 'Tercatat' }}
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $tree->nama_pohon }}</h1>
                    @if($tree->nama_latin)
                        <p class="text-base text-emerald-800 font-serif italic mt-0.5">{{ $tree->nama_latin }}</p>
                    @endif
                    <p class="text-xs text-slate-500 mt-2">
                        <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>
                        {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                    </p>
                </div>

                <!-- Silvicultural & Carbon Table Data -->
                <div class="p-5 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Hasil Pengukuran Silvikultur Lapangan</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Diameter Batang</span>
                            <span class="font-bold font-mono text-slate-900 text-sm mt-0.5 block">{{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Tinggi Pohon</span>
                            <span class="font-bold font-mono text-slate-900 text-sm mt-0.5 block">{{ $tree->tinggi ? $tree->tinggi . ' m' : '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Berat Jenis Kayu</span>
                            <span class="font-bold font-mono text-slate-900 text-sm mt-0.5 block">{{ $tree->berat_jenis ?? '-' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Taksiran Umur</span>
                            <span class="font-bold font-mono text-slate-900 text-sm mt-0.5 block">{{ $tree->umur }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-2">
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Biomassa AGB</span>
                            <span class="font-bold font-mono text-slate-900 text-base mt-0.5 block">
                                {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}
                            </span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded border border-slate-200">
                            <span class="text-slate-500 block text-[11px]">Cadangan Karbon (C-Stock)</span>
                            <span class="font-bold font-mono text-slate-900 text-base mt-0.5 block">
                                {{ $tree->cadangan_karbon ? number_format($tree->cadangan_karbon, 3) . ' Ton C' : '-' }}
                            </span>
                        </div>
                        <div class="bg-emerald-50/70 p-3 rounded border border-emerald-200">
                            <span class="text-emerald-800 font-semibold block text-[11px]">Estimasi Serapan CO₂e</span>
                            <span class="font-bold font-mono text-emerald-900 text-base mt-0.5 block">
                                {{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e
                            </span>
                            <span class="text-[10px] text-emerald-700 font-mono">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</span>
                        </div>
                    </div>

                    @if($tree->deskripsi)
                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-700 block mb-1">Catatan Botani & Deskripsi:</span>
                            <p class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-3.5 rounded border border-slate-200">
                                {{ $tree->deskripsi }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- GPS Map Section -->
                @if($tree->hasCoordinates())
                    <div class="p-5 border-t border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-700">
                                Titik Koordinat: <span class="font-mono text-slate-900">{{ round($tree->kordinat_lat, 6) }}, {{ round($tree->kordinat_lng, 6) }}</span>
                            </span>
                            <a href="{{ $tree->google_maps_url }}" target="_blank" class="text-emerald-700 hover:underline font-semibold">
                                Buka di Google Maps
                            </a>
                        </div>
                        <div id="tree-map" class="w-full h-56 rounded border border-slate-300"></div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Column 3: QR Code & Status -->
        <div class="space-y-4">
            
            <div class="bg-white rounded-lg border border-slate-200 p-4 space-y-4 text-center shadow-2xs">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Barcode QR Fisik Pohon</h3>
                    <p class="text-[11px] text-slate-500">Label identitas untuk ditempelkan pada fisik pohon.</p>
                </div>

                <!-- QR Frame -->
                <div class="w-48 h-48 mx-auto bg-slate-50 p-3 rounded border border-slate-200 flex items-center justify-center">
                    <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
                </div>

                <div class="font-mono text-[11px] text-slate-600 bg-slate-50 py-1.5 px-2.5 rounded border border-slate-200 truncate">
                    {{ route('pohon.public', $tree->id_pohon) }}
                </div>

                <!-- Actions -->
                <div class="space-y-2 pt-1">
                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank"
                        class="w-full py-2 px-3 rounded bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs transition-colors flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Label Tag Fisik</span>
                    </a>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('trees.download-qr', $tree) }}"
                            class="py-1.5 px-2 rounded bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors flex items-center justify-center space-x-1">
                            <i class="fa-solid fa-download text-slate-400"></i>
                            <span>Unduh SVG</span>
                        </a>

                        <form action="{{ route('trees.regenerate-qr', $tree) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full py-1.5 px-2 rounded bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors flex items-center justify-center space-x-1"
                                title="Buat ulang QR">
                                <i class="fa-solid fa-rotate text-slate-400"></i>
                                <span>Regenerate</span>
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank"
                        class="w-full py-2 px-3 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-500"></i>
                        <span>Buka Halaman Scan Publik</span>
                    </a>
                </div>

            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg border border-rose-200 p-4 space-y-2">
                <span class="text-xs font-bold text-rose-800 block">Hapus Data Spesimen</span>
                <p class="text-[11px] text-slate-600 leading-normal">
                    Menghapus spesimen ini akan mencabut arsip pohon dan QR code dari sistem.
                </p>
                <form action="{{ route('trees.destroy', $tree) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pohon {{ $tree->nama_pohon }} ({{ $tree->id_pohon }}) secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-1.5 px-3 rounded bg-rose-700 hover:bg-rose-800 text-white font-semibold text-xs transition-colors flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Hapus Permanen</span>
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>

@if($tree->hasCoordinates())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $tree->kordinat_lat }};
        const lng = {{ $tree->kordinat_lng }};
        const map = L.map('tree-map', { scrollWheelZoom: false }).setView([lat, lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<b>{{ addslashes($tree->nama_pohon) }}</b><br><span class='font-mono'>{{ $tree->id_pohon }}</span>").openPopup();
    });
</script>
@endpush
@endif
@endsection
