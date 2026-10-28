@extends('layouts.app')

@section('title', 'Dashboard Monitoring & Analitik Pohon')

@section('content')
<div class="space-y-8">

    <!-- Top Greeting & Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200/80 mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Sistem Monitoring Aktif &bull; SMKKN Arboretum</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Dashboard Monitoring Pohon</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Analitik populasi pohon, cadangan biomassa, dan pengelolaan barcode QR fisik.</p>
        </div>
        
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('trees.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl font-bold text-xs shadow-md shadow-emerald-600/30 flex items-center space-x-2 transition-all transform active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Pohon</span>
            </a>
            <a href="{{ route('trees.index') }}" class="px-4 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-2xl font-bold text-xs shadow-subtle transition-all flex items-center space-x-2">
                <i class="fa-solid fa-table-list text-slate-400"></i>
                <span>Kelola Data</span>
            </a>
        </div>
    </div>

    <!-- 4 Primary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Stat 1: Total Pohon -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Pohon Terdata</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($totalTrees) }}</h3>
                    <p class="text-xs text-emerald-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-qrcode mr-1.5"></i> Seluruhnya Ber-QR
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-tree"></i>
                </div>
            </div>
        </div>

        <!-- Stat 2: Total Serapan Karbon -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Serapan Karbon</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">
                        @if($totalCarbon >= 1000)
                            {{ number_format($totalCarbon / 1000, 2) }} <span class="text-base font-bold text-slate-400">Ton</span>
                        @else
                            {{ number_format($totalCarbon, 1) }} <span class="text-base font-bold text-slate-400">kg</span>
                        @endif
                    </h3>
                    <p class="text-xs text-teal-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> {{ number_format($totalCarbon, 0) }} kg CO2e
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-leaf"></i>
                </div>
            </div>
        </div>

        <!-- Stat 3: Total Cadangan Karbon (Carbon Stock) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Cadangan Karbon</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">
                        {{ number_format($totalCarbonStock, 2) }} <span class="text-base font-bold text-slate-400">Ton C</span>
                    </h3>
                    <p class="text-xs text-indigo-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-shield-halved mr-1.5"></i> Carbon Stock IPCC
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-seedling"></i>
                </div>
            </div>
        </div>

        <!-- Stat 4: Total Biomassa AGB -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Total Biomassa AGB</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">
                        @if($totalBiomass >= 1000)
                            {{ number_format($totalBiomass / 1000, 2) }} <span class="text-base font-bold text-slate-400">Ton</span>
                        @else
                            {{ number_format($totalBiomass, 1) }} <span class="text-base font-bold text-slate-400">kg</span>
                        @endif
                    </h3>
                    <p class="text-xs text-amber-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-weight-hanging mr-1.5"></i> {{ number_format($totalBiomass, 0) }} kg Biomassa
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Secondary Detailed Metrics: Silvikultur & Wilayah -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-white text-emerald-600 shadow-2xs flex items-center justify-center text-base flex-shrink-0 border border-slate-200/60">
                <i class="fa-solid fa-ruler-horizontal"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Rata-rata Diameter</span>
                <span class="text-lg font-black text-slate-900 block">{{ $avgDiameter }} <span class="text-xs text-slate-400 font-normal">cm</span></span>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-white text-teal-600 shadow-2xs flex items-center justify-center text-base flex-shrink-0 border border-slate-200/60">
                <i class="fa-solid fa-up-long"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Rata-rata Tinggi</span>
                <span class="text-lg font-black text-slate-900 block">{{ $avgTinggi }} <span class="text-xs text-slate-400 font-normal">m</span></span>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-white text-blue-600 shadow-2xs flex items-center justify-center text-base flex-shrink-0 border border-slate-200/60">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Ditanam Bulan Ini</span>
                <span class="text-lg font-black text-slate-900 block">{{ $treesThisMonth }} <span class="text-xs text-slate-400 font-normal">pohon</span></span>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-white text-purple-600 shadow-2xs flex items-center justify-center text-base flex-shrink-0 border border-slate-200/60">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase text-slate-400 block">Zona Penanaman</span>
                <span class="text-lg font-black text-slate-900 block">{{ $locationsCount }} <span class="text-xs text-slate-400 font-normal">kawasan</span></span>
            </div>
        </div>
    </div>

    <!-- Analitik Tingkat Pertumbuhan & 5 Spesies Terbanyak -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Kolom Kiri: Distribusi Fase Pertumbuhan Pohon -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-subtle space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Distribusi Tingkat Pertumbuhan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Komposisi silvikultur tegakan pohon di lingkungan sekolah</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                    Silvikultur
                </span>
            </div>

            <div class="space-y-4">
                @php
                    $kategoriTingkat = ['Pohon' => 'bg-emerald-500', 'Tiang' => 'bg-teal-500', 'Pancang' => 'bg-blue-500'];
                @endphp

                @foreach($kategoriTingkat as $namaTingkat => $warnaBg)
                    @php
                        $jumlah = $tingkatCounts[$namaTingkat] ?? 0;
                        $persen = $totalTrees > 0 ? round(($jumlah / $totalTrees) * 100, 1) : 0;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700 flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full {{ $warnaBg }} mr-2"></span>
                                Tingkat {{ $namaTingkat }}
                            </span>
                            <span class="font-mono text-slate-600 font-semibold">
                                {{ $jumlah }} pohon <span class="text-slate-400">({{ $persen }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $warnaBg }} rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Ekuivalensi Serapan Karbon Kampus -->
            <div class="pt-4 border-t border-slate-100 space-y-2.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Dampak Netralisasi Emisi Kolektif:</span>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900 block">~{{ number_format($totalCarKm) }} km</span>
                            <span class="text-[10px] text-slate-400">Mobil bensin dinetralkan</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-slate-900 block">~{{ number_format($totalPhoneCharges) }} kali</span>
                            <span class="text-[10px] text-slate-400">Pengisian daya HP penuh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Top 5 Spesies Pohon Dominan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-subtle space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Spesies Pohon Dominan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Peringkat spesies teratas berdasarkan populasi dan serapan karbon</p>
                </div>
                <a href="{{ route('trees.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($topSpecies as $index => $species)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                #{{ $index + 1 }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 text-xs block">{{ $species->nama_pohon }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $species->total }} pohon tercatat</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-emerald-600 block">{{ number_format($species->total_karbon, 1) }} kg</span>
                            <span class="text-[10px] text-slate-400">Total CO2e</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada data pohon.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Quick Action / Status Banner -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 rounded-3xl p-6 sm:p-8 text-white border border-white/10 shadow-card flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold mb-1">
                <i class="fa-solid fa-qrcode text-emerald-400"></i>
                <span>Label QR Code Fisik Siap Cetak</span>
            </div>
            <h3 class="text-xl font-black text-white tracking-tight">Cetak Label QR & Pasang Pada Pohon</h3>
            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                Setiap pohon dalam database memiliki kode QR unik beresolusi tinggi. Siswa atau tamu sekolah cukup memindai dengan kamera smartphone untuk melihat data silvikultur dan serapan karbon secara langsung.
            </p>
        </div>
        <div class="flex items-center space-x-3 flex-shrink-0">
            <a href="{{ route('trees.index') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition-all shadow-lg flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label QR Pohon</span>
            </a>
            <a href="{{ route('trees.create') }}" class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all flex items-center space-x-2 active:scale-95">
                <i class="fa-solid fa-plus text-emerald-400"></i>
                <span>Tambah Pohon</span>
            </a>
        </div>
    </div>

    <!-- Recent Trees Section (Tanpa Foto Sesuai Permintaan) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-subtle overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Pohon Yang Baru Ditambahkan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar pohon terakhir yang tercatat dalam basis data.</p>
            </div>
            <a href="{{ route('trees.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center space-x-1">
                <span>Lihat Seluruh Pohon</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3.5 px-6">Kode ID</th>
                        <th class="py-3.5 px-6">Nama Spesies</th>
                        <th class="py-3.5 px-6">Tingkat & Diameter</th>
                        <th class="py-3.5 px-6">Biomassa & Karbon</th>
                        <th class="py-3.5 px-6">Lokasi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTrees as $tree)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-mono font-black text-xs px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/80 inline-flex items-center">
                                    <i class="fa-solid fa-qrcode mr-1 text-[10px] text-emerald-600"></i>
                                    {{ $tree->id_pohon }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <a href="{{ route('trees.show', $tree) }}" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors block">
                                    {{ $tree->nama_pohon }}
                                </a>
                                @if($tree->nama_latin)
                                    <span class="text-[11px] text-emerald-700 italic font-serif">{{ $tree->nama_latin }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-semibold text-slate-800">{{ $tree->tingkat ?? 'Pohon' }}</span>
                                <span class="text-slate-400 block text-[11px]">D: {{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-extrabold text-emerald-600">{{ number_format($tree->estimasi_karbon, 1) }} kg CO2e</span>
                                <span class="text-[11px] text-slate-400 block">Biomassa: {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="text-slate-600 truncate max-w-[150px] block">
                                    {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" title="Cetak Label QR" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    <a href="{{ route('trees.show', $tree) }}" title="Lihat Detail" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada data pohon yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
