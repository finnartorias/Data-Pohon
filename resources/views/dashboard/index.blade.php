@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-8">

    <!-- Top Greeting & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Monitoring Pohon</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau populasi pohon, serapan karbon, dan kode QR di seluruh area ruang terbuka hijau.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('trees.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 flex items-center space-x-2 transition-all transform active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Pohon Baru</span>
            </a>
            <a href="{{ route('trees.index') }}" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-semibold text-sm shadow-sm transition-all flex items-center space-x-2">
                <i class="fa-solid fa-table-list text-slate-400"></i>
                <span>Lihat Semua Pohon</span>
            </a>
        </div>
    </div>

    <!-- 4 Summary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Stat 1: Total Pohon -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pohon</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($totalTrees) }}</h3>
                    <p class="text-xs text-emerald-600 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-seedling mr-1.5"></i> Terdata & Ber-QR
                    </p>
                </div>
                <div class="w-13 h-13 w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-tree"></i>
                </div>
            </div>
        </div>

        <!-- Stat 2: Total Serapan Karbon -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-teal-50 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Estimasi Karbon</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">
                        @if($totalCarbon >= 1000)
                            {{ number_format($totalCarbon / 1000, 2) }} <span class="text-base font-bold text-slate-500">Ton CO₂e</span>
                        @else
                            {{ number_format($totalCarbon, 1) }} <span class="text-base font-bold text-slate-500">kg CO₂e</span>
                        @endif
                    </h3>
                    <p class="text-xs text-teal-600 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> Serapan Kumulatif ({{ number_format($totalCarbon, 0) }} kg)
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-leaf"></i>
                </div>
            </div>
        </div>

        <!-- Stat 3: Penanaman Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Bulan Ini</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($treesThisMonth) }}</h3>
                    <p class="text-xs text-blue-600 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-calendar-check mr-1.5"></i> Ditanam {{ now()->translatedFormat('F Y') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Stat 4: Zona/Lokasi Taman -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Zona / Lokasi</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($locationsCount) }}</h3>
                    <p class="text-xs text-amber-600 font-semibold mt-2 flex items-center">
                        <i class="fa-solid fa-map-pin mr-1.5"></i> Titik Sebaran Ruang Hijau
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Map Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-lg flex items-center">
                    <i class="fa-solid fa-map-location-dot text-emerald-600 mr-2"></i> Peta Sebaran Koordinat Pohon
                </h3>
                <p class="text-xs text-slate-500">Klik marker pohon untuk melihat info singkat, kode QR, dan estimasi karbon.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                {{ count($treeMarkers) }} Pohon Terpetakan
            </span>
        </div>
        <div id="dashboard-map" class="w-full h-80 md:h-96 rounded-xl border border-slate-200 z-10"></div>
    </div>

    <!-- Recent Trees Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Pohon Terakhir Ditambahkan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar pohon terbaru beserta perhitungan umur dan status QR code.</p>
            </div>
            <a href="{{ route('trees.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center">
                <span>Kelola Semua Pohon</span>
                <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-6">ID & Foto</th>
                        <th class="py-3.5 px-6">Nama Pohon</th>
                        <th class="py-3.5 px-6">Umur Otomatis</th>
                        <th class="py-3.5 px-6">Est. Karbon</th>
                        <th class="py-3.5 px-6">QR Code</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTrees as $tree)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-sm">
                                    <div>
                                        <span class="inline-block font-mono font-bold text-xs px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ $tree->id_pohon }}
                                        </span>
                                        <span class="block text-[11px] text-slate-400 mt-0.5">{{ $tree->lokasi_taman ?? 'Lokasi belum diset' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $tree->nama_pohon }}</div>
                                @if($tree->nama_latin)
                                    <div class="text-xs text-slate-500 italic">{{ $tree->nama_latin }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                    <i class="fa-regular fa-clock mr-1 text-[11px]"></i>
                                    {{ $tree->umur }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800">{{ $tree->estimasi_karbon }}</span>
                                <span class="text-xs text-slate-400">kg CO₂e</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $tree->qr_code_url }}" target="_blank" class="w-9 h-9 rounded-lg bg-slate-50 hover:bg-emerald-50 border border-slate-200 flex items-center justify-center p-1 transition-colors" title="Lihat QR Code">
                                        <img src="{{ $tree->qr_code_url }}" alt="QR" class="w-full h-full object-contain">
                                    </a>
                                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="text-xs text-slate-500 hover:text-emerald-600 p-1.5 rounded hover:bg-slate-100" title="Cetak Signage QR">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('trees.show', $tree) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-semibold transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-emerald-600 rounded-lg hover:bg-slate-100" title="Buka Halaman Scan Publik">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-tree text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">Belum ada data pohon yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const treeMarkers = @json($treeMarkers);

        let defaultLat = -6.1753924;
        let defaultLng = 106.8271528;

        if (treeMarkers.length > 0) {
            defaultLat = treeMarkers[0].kordinat_lat;
            defaultLng = treeMarkers[0].kordinat_lng;
        }

        const map = L.map('dashboard-map').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const bounds = [];

        treeMarkers.forEach(tree => {
            if (tree.kordinat_lat && tree.kordinat_lng) {
                const marker = L.marker([tree.kordinat_lat, tree.kordinat_lng]).addTo(map);
                
                const popupContent = `
                    <div class="p-1 text-slate-800">
                        <span class="inline-block text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 mb-1">${tree.id_pohon}</span>
                        <h4 class="font-bold text-sm text-slate-900">${tree.nama_pohon}</h4>
                        ${tree.nama_latin ? `<p class="text-xs text-slate-500 italic">${tree.nama_latin}</p>` : ''}
                        <p class="text-xs text-emerald-700 font-semibold mt-1">Serapan: ${tree.estimasi_karbon} kg CO₂</p>
                        <div class="mt-2 pt-2 border-t border-slate-200 flex justify-between gap-2">
                            <a href="/trees/${tree.id}" class="text-xs font-bold text-emerald-600 hover:underline">Detail Admin &rarr;</a>
                            <a href="/pohon/public/${tree.id_pohon}" target="_blank" class="text-xs font-bold text-slate-500 hover:text-slate-800">Scan QR &nearr;</a>
                        </div>
                    </div>
                `;
                marker.bindPopup(popupContent);
                bounds.push([tree.kordinat_lat, tree.kordinat_lng]);
            }
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    });
</script>
@endpush
