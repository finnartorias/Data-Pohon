@extends('layouts.app')

@section('title', 'Database Inventarisasi Pohon')

@section('content')
<div class="space-y-4">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Database Inventarisasi Pohon</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar tegakan pohon, pencatatan silvikultur, dan pencetakan label QR fisik.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('trees.create') }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md font-semibold text-xs transition-colors flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Pohon Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('trees.index') }}" class="w-full sm:max-w-md relative flex items-center">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pohon, kode register, atau lokasi..."
                    class="w-full pl-9 pr-16 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 text-xs text-slate-900 placeholder:text-slate-400">
                @if(request('q'))
                    <a href="{{ route('trees.index') }}" class="absolute right-2 top-1.5 text-xs text-slate-400 hover:text-slate-600">Reset</a>
                @endif
            </div>
            <button type="submit" class="ml-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-md transition-colors">
                Cari
            </button>
        </form>

        <div class="text-xs text-slate-600 font-medium">
            Total Tercatat: <strong class="font-mono text-slate-900">{{ $trees->total() }}</strong> pohon
        </div>
    </div>

    <!-- Main Table: Professional Silvicultural Registry -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <th class="py-2.5 px-3.5">Kode Register</th>
                        <th class="py-2.5 px-3.5">Nama Pohon / Ilmiah</th>
                        <th class="py-2.5 px-3.5">Tingkat</th>
                        <th class="py-2.5 px-3.5 text-right">Diameter</th>
                        <th class="py-2.5 px-3.5 text-right">Tinggi</th>
                        <th class="py-2.5 px-3.5 text-right">Biomassa (AGB)</th>
                        <th class="py-2.5 px-3.5 text-right">Serapan Karbon</th>
                        <th class="py-2.5 px-3.5">Lokasi</th>
                        <th class="py-2.5 px-3.5 text-center">Label QR</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($trees as $tree)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Kode Register -->
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 text-xs">
                                    {{ $tree->id_pohon }}
                                </span>
                            </td>

                            <!-- Nama & Spesies -->
                            <td class="py-2.5 px-3.5">
                                <a href="{{ route('trees.show', $tree) }}" class="font-semibold text-slate-900 hover:text-emerald-700 transition-colors block">
                                    {{ $tree->nama_pohon }}
                                </a>
                                @if($tree->nama_latin)
                                    <span class="text-[11px] text-slate-500 italic block">{{ $tree->nama_latin }}</span>
                                @endif
                            </td>

                            <!-- Tingkat Silvikultur -->
                            <td class="py-2.5 px-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $tree->tingkat ?? 'Pohon' }}
                                </span>
                            </td>

                            <!-- Diameter -->
                            <td class="py-2.5 px-3.5 text-right font-mono text-slate-700 whitespace-nowrap">
                                {{ $tree->diameter ? number_format($tree->diameter, 1) . ' cm' : '-' }}
                            </td>

                            <!-- Tinggi -->
                            <td class="py-2.5 px-3.5 text-right font-mono text-slate-700 whitespace-nowrap">
                                {{ $tree->tinggi ? number_format($tree->tinggi, 1) . ' m' : '-' }}
                            </td>

                            <!-- Biomassa AGB -->
                            <td class="py-2.5 px-3.5 text-right font-mono text-slate-700 whitespace-nowrap">
                                {{ $tree->biomassa_agb ? number_format($tree->biomassa_agb, 1) . ' kg' : '-' }}
                            </td>

                            <!-- Serapan Karbon -->
                            <td class="py-2.5 px-3.5 text-right font-mono font-bold text-emerald-800 whitespace-nowrap">
                                {{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e
                            </td>

                            <!-- Lokasi -->
                            <td class="py-2.5 px-3.5 text-slate-600 truncate max-w-[130px]">
                                {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                            </td>

                            <!-- Label QR -->
                            <td class="py-2.5 px-3.5 text-center whitespace-nowrap">
                                <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" title="Cetak Label QR"
                                    class="inline-flex items-center space-x-1 px-2.5 py-1 rounded bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 text-[11px] font-semibold border border-slate-200 transition-colors">
                                    <i class="fa-solid fa-qrcode text-emerald-700"></i>
                                    <span>Cetak</span>
                                </a>
                            </td>

                            <!-- Aksi CRUD -->
                            <td class="py-2.5 px-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" title="Lihat Tampilan Scan Publik"
                                        class="p-1.5 rounded text-slate-500 hover:text-emerald-700 hover:bg-slate-100 border border-slate-200">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('trees.show', $tree) }}" title="Lihat Detail Rekaman"
                                        class="p-1.5 rounded text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('trees.edit', $tree) }}" title="Edit Data"
                                        class="p-1.5 rounded text-slate-500 hover:text-blue-700 hover:bg-slate-100 border border-slate-200">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('trees.destroy', $tree) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pohon {{ $tree->nama_pohon }} ({{ $tree->id_pohon }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Data"
                                            class="p-1.5 rounded text-slate-500 hover:text-rose-700 hover:bg-rose-50 border border-slate-200">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-slate-400">
                                Data pohon tidak ditemukan dengan kriteria pencarian tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trees->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">
                {{ $trees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
