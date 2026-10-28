@extends('layouts.app')

@section('title', 'Dashboard Ringkasan Eksekutif')

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

    <!-- 4 Summary Stat Cards with Modern Glow -->
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
                        <i class="fa-solid fa-cloud-arrow-down mr-1.5"></i> {{ number_format($totalCarbon, 0) }} kg CO₂e
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-leaf"></i>
                </div>
            </div>
        </div>

        <!-- Stat 3: Pohon Baru Bulan Ini -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Ditanam Bulan Ini</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($treesThisMonth) }}</h3>
                    <p class="text-xs text-blue-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-calendar-check mr-1.5"></i> {{ now()->translatedFormat('F Y') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Stat 4: Zona Konservasi -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Titik Sebaran Zona</span>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($locationsCount) }}</h3>
                    <p class="text-xs text-amber-600 font-bold mt-2 flex items-center">
                        <i class="fa-solid fa-map-pin mr-1.5"></i> Titik Lokasi Kampus
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Action / Status Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white border border-white/10 shadow-card flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold mb-1">
                <i class="fa-solid fa-bullhorn text-emerald-400"></i>
                <span>Portal Eksplorasi Publik Tersedia</span>
            </div>
            <h3 class="text-xl font-black text-white tracking-tight">Siap Untuk Dicetak & Dipasang Pada Pohon</h3>
            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                Setiap pohon memiliki label QR siap cetak beresolusi tinggi. Siapapun dapat memindai tanpa aplikasi tambahan dan langsung membaca data karbon secara transparan.
            </p>
        </div>
        <div class="flex items-center space-x-3 flex-shrink-0">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-emerald-400"></i>
                <span>Lihat Portal Publik</span>
            </a>
            <a href="{{ route('trees.index') }}" class="px-5 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition-all shadow-lg flex items-center space-x-2">
                <i class="fa-solid fa-qrcode"></i>
                <span>Cetak Label QR</span>
            </a>
        </div>
    </div>

    <!-- Recent Trees Section -->
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
                        <th class="py-3.5 px-6">ID & Foto</th>
                        <th class="py-3.5 px-6">Nama Pohon</th>
                        <th class="py-3.5 px-6">Tingkat & Diameter</th>
                        <th class="py-3.5 px-6">Serapan Karbon</th>
                        <th class="py-3.5 px-6">Lokasi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTrees as $tree)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $tree->id_pohon }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                <a href="{{ route('trees.show', $tree) }}" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors">
                                    {{ $tree->nama_pohon }}
                                </a>
                                @if($tree->nama_latin)
                                    <div class="text-[11px] text-slate-400 italic font-serif">{{ $tree->nama_latin }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-semibold text-slate-700">{{ $tree->tingkat ?? 'Pohon' }}</span>
                                <span class="text-slate-400 block text-[11px]">D: {{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-6 whitespace-nowrap">
                                <span class="font-extrabold text-emerald-600">{{ number_format($tree->estimasi_karbon, 1) }} kg</span>
                                <span class="text-[11px] text-slate-400 block">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton</span>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="text-slate-600 truncate max-w-[150px] block">
                                    {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" title="Cetak QR" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    <a href="{{ route('trees.show', $tree) }}" title="Detail" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors">
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