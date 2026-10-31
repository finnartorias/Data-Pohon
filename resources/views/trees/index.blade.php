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

    <!-- Mobile Card View (Khusus Layar HP: Tampilkan QR Code & Identitas Jelas) -->
    <div class="block sm:hidden space-y-3">
        @forelse($trees as $tree)
            <div class="bg-white rounded-lg border border-slate-200 p-3.5 shadow-2xs space-y-3">
                <div class="flex items-start space-x-3">
                    <!-- QR Code Thumbnail -->
                    <a href="{{ route('trees.show', $tree) }}" class="w-16 h-16 bg-slate-50 p-1.5 rounded-md border border-slate-200 flex-shrink-0 flex items-center justify-center hover:bg-slate-100 transition-colors" title="Lihat Spesimen">
                        <img src="{{ $tree->qr_code_url }}" alt="QR {{ $tree->id_pohon }}" class="w-full h-full object-contain">
                    </a>

                    <!-- Detail Pohon -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-1.5 mb-1">
                            <span class="font-mono font-bold text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $tree->id_pohon }}
                            </span>
                            @if($tree->tingkat)
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    {{ $tree->tingkat }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('trees.show', $tree) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-700 block truncate">
                            {{ $tree->nama_pohon }}
                        </a>
                        @if($tree->nama_latin)
                            <span class="text-[11px] text-slate-500 italic block truncate -mt-0.5">{{ $tree->nama_latin }}</span>
                        @endif
                        <span class="text-[10px] text-slate-400 block truncate mt-1">
                            <i class="fa-solid fa-location-dot mr-1"></i>{{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                        </span>
                    </div>
                </div>

                <!-- Info Silvikultur Singkat -->
                <div class="grid grid-cols-2 gap-2 text-[11px] bg-slate-50 p-2 rounded border border-slate-100 font-mono">
                    <div>
                        <span class="text-slate-400 block text-[9px] uppercase font-sans">Diameter Batang</span>
                        <strong class="text-slate-800">{{ $tree->diameter ? number_format($tree->diameter, 1) . ' cm' : '-' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[9px] uppercase font-sans">Serapan Karbon</span>
                        <strong class="text-emerald-800">{{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e</strong>
                    </div>
                </div>

                <!-- Tombol Aksi Cepat Mobile -->
                <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-xs">
                    <div class="flex items-center space-x-1.5">
                        <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 font-semibold text-[11px] border border-slate-200 flex items-center space-x-1">
                            <i class="fa-solid fa-print text-emerald-700"></i>
                            <span>Cetak QR</span>
                        </a>
                        <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="p-1.5 rounded text-slate-500 hover:text-emerald-700 hover:bg-slate-100 border border-slate-200" title="Scan Publik">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <a href="{{ route('trees.show', $tree) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-900 text-white font-semibold text-[11px]">
                            Detail
                        </a>
                        <a href="{{ route('trees.edit', $tree) }}" class="p-1.5 rounded text-slate-500 hover:text-blue-700 hover:bg-slate-100 border border-slate-200" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg border border-slate-200 p-6 text-center text-xs text-slate-400">
                Data pohon tidak ditemukan.
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (Tabel Lengkap untuk Layar PC / Tablet) -->
    <div class="hidden sm:block bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
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
    </div>

    @if($trees->hasPages())
        <div class="px-4 py-3 bg-white rounded-lg border border-slate-200">
            {{ $trees->links() }}
        </div>
    @endif

</div>
@endsection
