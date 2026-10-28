@extends('layouts.app')

@section('title', 'Dashboard Ringkasan Eksekutif')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Ringkasan Inventarisasi Pohon & Karbon</h2>
            <p class="text-xs text-slate-500 mt-0.5">Arboretum & Lingkungan Kampus SMK Kehutanan Negeri (SMKKN)</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <a href="{{ route('trees.index') }}" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-md font-semibold text-xs transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-table-list text-slate-500"></i>
                <span>Kelola Database</span>
            </a>
            <a href="{{ route('trees.create') }}" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md font-semibold text-xs transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Input Pohon Baru</span>
            </a>
        </div>
    </div>

    <!-- 4 Primary Stat Cards: Clean, Professional, Tabular -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Stat 1: Total Pohon -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Populasi Pohon</div>
            <div class="text-2xl font-bold text-slate-900 mt-1 font-mono tracking-tight">{{ number_format($totalTrees) }} <span class="text-xs font-normal text-slate-500 font-sans">pohon</span></div>
            <div class="text-[11px] text-emerald-700 font-medium mt-2 flex items-center">
                <i class="fa-solid fa-qrcode mr-1.5 text-xs text-emerald-600"></i> Terdaftar dengan Label QR
            </div>
        </div>

        <!-- Stat 2: Total Serapan Karbon -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Serapan Karbon (CO₂e)</div>
            <div class="text-2xl font-bold text-slate-900 mt-1 font-mono tracking-tight">
                @if($totalCarbon >= 1000)
                    {{ number_format($totalCarbon / 1000, 2) }} <span class="text-xs font-normal text-slate-500 font-sans">Ton CO₂e</span>
                @else
                    {{ number_format($totalCarbon, 1) }} <span class="text-xs font-normal text-slate-500 font-sans">kg CO₂e</span>
                @endif
            </div>
            <div class="text-[11px] text-slate-500 mt-2 font-mono">
                Ekuivalen {{ number_format($totalCarbon, 0) }} kg CO₂
            </div>
        </div>

        <!-- Stat 3: Cadangan Karbon (Carbon Stock) -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Cadangan Karbon (C-Stock)</div>
            <div class="text-2xl font-bold text-slate-900 mt-1 font-mono tracking-tight">
                {{ number_format($totalCarbonStock, 2) }} <span class="text-xs font-normal text-slate-500 font-sans">Ton C</span>
            </div>
            <div class="text-[11px] text-slate-500 mt-2">
                Berdasarkan Standar IPCC
            </div>
        </div>

        <!-- Stat 4: Biomassa Atas Tanah (AGB) -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-2xs">
            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Biomassa (AGB)</div>
            <div class="text-2xl font-bold text-slate-900 mt-1 font-mono tracking-tight">
                @if($totalBiomass >= 1000)
                    {{ number_format($totalBiomass / 1000, 2) }} <span class="text-xs font-normal text-slate-500 font-sans">Ton</span>
                @else
                    {{ number_format($totalBiomass, 1) }} <span class="text-xs font-normal text-slate-500 font-sans">kg</span>
                @endif
            </div>
            <div class="text-[11px] text-slate-500 mt-2 font-mono">
                {{ number_format($totalBiomass, 0) }} kg biomassa kering
            </div>
        </div>

    </div>

    <!-- Parameter Silvikultur Standar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white p-3.5 rounded-lg border border-slate-200 text-xs">
        <div class="px-3 border-r border-slate-200">
            <span class="text-slate-500 block text-[11px]">Rata-rata Diameter Batang</span>
            <span class="text-sm font-bold font-mono text-slate-800">{{ $avgDiameter }} cm</span>
        </div>
        <div class="px-3 border-r border-slate-200">
            <span class="text-slate-500 block text-[11px]">Rata-rata Tinggi Pohon</span>
            <span class="text-sm font-bold font-mono text-slate-800">{{ $avgTinggi }} m</span>
        </div>
        <div class="px-3 border-r border-slate-200">
            <span class="text-slate-500 block text-[11px]">Penambahan Bulan Ini</span>
            <span class="text-sm font-bold font-mono text-slate-800">{{ $treesThisMonth }} pohon</span>
        </div>
        <div class="px-3">
            <span class="text-slate-500 block text-[11px]">Sebaran Area Lokasi</span>
            <span class="text-sm font-bold font-mono text-slate-800">{{ $locationsCount }} zona</span>
        </div>
    </div>

    <!-- Two Columns: Struktur Tegakan & Spesies Dominan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Struktur Tingkat Pertumbuhan -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Struktur Tingkat Pertumbuhan (Silvikultur)</h3>
                <span class="text-[11px] font-mono text-slate-500">{{ $totalTrees }} Total Individu</span>
            </div>

            <div class="space-y-3">
                @php
                    $kategoriTingkat = [
                        'Pohon' => ['color' => 'bg-emerald-700', 'desc' => 'Diameter > 20 cm'],
                        'Tiang' => ['color' => 'bg-emerald-500', 'desc' => 'Diameter 10 - 20 cm'],
                        'Pancang' => ['color' => 'bg-teal-600', 'desc' => 'Diameter < 10 cm, Tinggi > 1.5 m']
                    ];
                @endphp

                @foreach($kategoriTingkat as $namaTingkat => $info)
                    @php
                        $jumlah = $tingkatCounts[$namaTingkat] ?? 0;
                        $persen = $totalTrees > 0 ? round(($jumlah / $totalTrees) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div>
                                <span class="font-semibold text-slate-800">{{ $namaTingkat }}</span>
                                <span class="text-[10px] text-slate-500 ml-1.5">({{ $info['desc'] }})</span>
                            </div>
                            <span class="font-mono text-slate-700 font-bold">{{ $jumlah }} <span class="text-slate-400 font-normal">({{ $persen }}%)</span></span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-sm overflow-hidden">
                            <div class="h-full {{ $info['color'] }}" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Ekuivalensi Lingkungan -->
            <div class="pt-3 border-t border-slate-100 text-xs text-slate-600">
                <span class="font-semibold text-slate-800 block mb-1">Estimasi Dampak Lingkungan Kawasan:</span>
                <p class="text-[11px] leading-relaxed text-slate-600">
                    Akumulasi serapan karbon setara dengan menetralkan emisi pembakaran bahan bakar kendaraan bermotor sejauh 
                    <strong class="font-mono text-slate-900">~{{ number_format($totalCarKm) }} km</strong>, atau setara energi listrik 
                    <strong class="font-mono text-slate-900">~{{ number_format($totalPhoneCharges) }} kali</strong> pengisian perangkat ponsel.
                </p>
            </div>
        </div>

        <!-- Spesies Dominan -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Spesies Dominan di Kawasan</h3>
                <a href="{{ route('trees.index') }}" class="text-[11px] font-semibold text-emerald-700 hover:underline">Lihat Semua Data</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase text-slate-400 font-bold border-b border-slate-100">
                            <th class="py-1.5">No</th>
                            <th class="py-1.5">Nama Spesies</th>
                            <th class="py-1.5 text-right">Populasi</th>
                            <th class="py-1.5 text-right">Total Serapan CO₂e</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topSpecies as $index => $species)
                            <tr>
                                <td class="py-2 text-slate-400 font-mono text-[11px]">{{ $index + 1 }}</td>
                                <td class="py-2 font-medium text-slate-800">{{ $species->nama_pohon }}</td>
                                <td class="py-2 text-right font-mono font-semibold text-slate-700">{{ $species->total }}</td>
                                <td class="py-2 text-right font-mono text-emerald-800 font-bold">{{ number_format($species->total_karbon, 1) }} kg</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-3 text-center text-slate-400">Belum ada data pohon tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Tabel Data Terbaru -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Catatan Data Pohon Terbaru</h3>
                <p class="text-[11px] text-slate-500">Daftar rekaman silvikultur pohon terakhir yang terdaftar dalam sistem.</p>
            </div>
            <a href="{{ route('trees.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                <span>Buka Tabel Lengkap</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-2.5 px-4">No. Registrasi</th>
                        <th class="py-2.5 px-4">Nama Pohon / Spesies</th>
                        <th class="py-2.5 px-4">Tingkat</th>
                        <th class="py-2.5 px-4 text-right">Diameter</th>
                        <th class="py-2.5 px-4 text-right">Biomassa (AGB)</th>
                        <th class="py-2.5 px-4 text-right">Serapan CO₂e</th>
                        <th class="py-2.5 px-4">Lokasi</th>
                        <th class="py-2.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($recentTrees as $tree)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                {{ $tree->id_pohon }}
                            </td>
                            <td class="py-2.5 px-4">
                                <a href="{{ route('trees.show', $tree) }}" class="font-semibold text-slate-900 hover:text-emerald-700">
                                    {{ $tree->nama_pohon }}
                                </a>
                                @if($tree->nama_latin)
                                    <span class="block text-[11px] text-slate-500 italic">{{ $tree->nama_latin }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $tree->tingkat ?? 'Pohon' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono text-slate-700">
                                {{ $tree->diameter ? number_format($tree->diameter, 1) . ' cm' : '-' }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono text-slate-700">
                                {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-emerald-800">
                                {{ number_format($tree->estimasi_karbon, 1) }} kg
                            </td>
                            <td class="py-2.5 px-4 text-slate-600 truncate max-w-[140px]">
                                {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                            </td>
                            <td class="py-2.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" title="Cetak QR" class="p-1.5 rounded text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('trees.show', $tree) }}" title="Lihat Rekaman" class="p-1.5 rounded text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">
                                Belum ada rekaman data pohon dalam sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
