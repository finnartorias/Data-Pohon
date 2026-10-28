@extends('layouts.app')

@section('title', 'Daftar Pohon & QR Code')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Data Pohon & QR Code</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data seluruh pohon, cetak identitas barcode QR, dan pantau sebaran flora.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('trees.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl font-bold text-xs shadow-md shadow-emerald-600/30 flex items-center space-x-2 transition-all transform active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Pohon Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-subtle flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('trees.index') }}" class="w-full sm:max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pohon, kode ID, spesies, atau lokasi..."
                class="w-full pl-10 pr-24 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm bg-slate-50/50 focus:bg-white transition-all">
            @if(request('q'))
                <a href="{{ route('trees.index') }}" class="absolute right-14 top-2 text-xs text-slate-400 hover:text-slate-600 px-2 py-1">Reset</a>
            @endif
            <button type="submit" class="absolute right-1.5 top-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-colors">
                Cari
            </button>
        </form>

        <div class="text-xs text-slate-500 flex items-center space-x-2 self-end sm:self-center font-medium">
            <span>Menampilkan <strong>{{ $trees->total() }}</strong> total pohon</span>
        </div>
    </div>

    <!-- Main Trees Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-subtle overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">ID & Foto</th>
                        <th class="py-4 px-6">Nama & Spesies</th>
                        <th class="py-4 px-6">Tingkat & Umur</th>
                        <th class="py-4 px-6">Lokasi</th>
                        <th class="py-4 px-6">Est. Karbon</th>
                        <th class="py-4 px-6 text-center">Label QR</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trees as $tree)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- ID & Foto -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-200 shadow-2xs flex-shrink-0">
                                    <div>
                                        <span class="inline-block font-mono font-bold text-xs px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $tree->id_pohon }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Nama & Spesies -->
                            <td class="py-4 px-6">
                                <a href="{{ route('trees.show', $tree) }}" class="font-bold text-slate-900 hover:text-emerald-600 transition-colors block">
                                    {{ $tree->nama_pohon }}
                                </a>
                                @if($tree->nama_latin)
                                    <div class="text-xs text-slate-400 italic font-serif mt-0.5">{{ $tree->nama_latin }}</div>
                                @else
                                    <div class="text-xs text-slate-300 italic">Nama ilmiah -</div>
                                @endif
                            </td>

                            <!-- Tingkat & Umur -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-semibold text-slate-700">
                                    {{ $tree->tingkat ?? 'Pohon' }}
                                    @if($tree->diameter)
                                        <span class="text-xs text-slate-400">({{ $tree->diameter }} cm)</span>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center text-[11px] font-semibold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="fa-solid fa-hourglass-half mr-1 text-[9px]"></i>
                                        {{ $tree->umur }}
                                    </span>
                                </div>
                            </td>

                            <!-- Lokasi -->
                            <td class="py-4 px-6">
                                <span class="text-xs text-slate-600 block truncate max-w-[150px]">
                                    <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                                    {{ $tree->lokasi_taman ?? 'Arboretum SMKKN' }}
                                </span>
                            </td>

                            <!-- Serapan Karbon -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-extrabold text-emerald-600 text-sm">
                                    {{ number_format($tree->estimasi_karbon, 1) }} kg
                                </span>
                                <span class="text-[11px] text-slate-400 block">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</span>
                            </td>

                            <!-- QR Code Preview -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" title="Buka Label Siap Cetak"
                                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-bold transition-all border border-slate-200">
                                    <i class="fa-solid fa-qrcode text-emerald-600"></i>
                                    <span>Cetak</span>
                                </a>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" title="Pratinjau Publik"
                                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-500 flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('trees.show', $tree) }}" title="Detail"
                                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('trees.edit', $tree) }}" title="Edit Data"
                                        class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('trees.destroy', $tree) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pohon {{ $tree->nama_pohon }} ({{ $tree->id_pohon }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data pohon yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($trees->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $trees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection