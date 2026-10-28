@extends('layouts.app')

@section('title', 'Daftar Pohon & QR Code')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Data Pohon & QR Code</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data seluruh pohon, cetak identitas barcode QR, dan pantau lokasi.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('trees.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 flex items-center space-x-2 transition-all transform active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Pohon Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('trees.index') }}" class="w-full sm:max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pohon, kode ID, spesies, atau lokasi..."
                class="w-full pl-10 pr-24 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm bg-slate-50/50 focus:bg-white transition-all">
            @if(request('q'))
                <a href="{{ route('trees.index') }}" class="absolute right-14 top-2 text-xs text-slate-400 hover:text-slate-600 px-2 py-1">Reset</a>
            @endif
            <button type="submit" class="absolute right-1.5 top-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors">
                Cari
            </button>
        </form>

        <div class="text-xs text-slate-500 flex items-center space-x-2 self-end sm:self-center">
            <span>Menampilkan <strong>{{ $trees->total() }}</strong> total pohon</span>
        </div>
    </div>

    <!-- Main Trees Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">ID & Foto</th>
                        <th class="py-4 px-6">Nama & Spesies</th>
                        <th class="py-4 px-6">Tanggal & Umur</th>
                        <th class="py-4 px-6">Lokasi & Area</th>
                        <th class="py-4 px-6">Est. Karbon</th>
                        <th class="py-4 px-6">QR Code</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trees as $tree)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- ID & Foto -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $tree->foto_url }}" alt="{{ $tree->nama_pohon }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <div>
                                        <span class="inline-block font-mono font-bold text-xs px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $tree->id_pohon }}
                                        </span>
                                        @if($tree->tingkat)
                                            <span class="block text-[10px] text-emerald-700 font-semibold mt-0.5">
                                                {{ $tree->tingkat }} &bull; D: {{ $tree->diameter }} cm
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Nama & Spesies -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-base">{{ $tree->nama_pohon }}</div>
                                @if($tree->nama_latin)
                                    <div class="text-xs text-slate-500 italic font-serif mt-0.5">{{ $tree->nama_latin }}</div>
                                @else
                                    <div class="text-xs text-slate-400 italic">Nama ilmiah belum diisi</div>
                                @endif
                            </td>

                            <!-- Tanggal Tanam & Umur -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-xs text-slate-500">
                                    <i class="fa-regular fa-calendar text-[11px] mr-1 text-slate-400"></i>
                                    {{ $tree->tanggal_tanam ? $tree->tanggal_tanam->translatedFormat('d M Y') : '-' }}
                                </div>
                                <div class="mt-1">
                                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="fa-solid fa-hourglass-half mr-1 text-[10px]"></i>
                                        {{ $tree->umur }}
                                    </span>
                                </div>
                            </td>

                            <!-- Lokasi & Koordinat -->
                            <td class="py-4 px-6">
                                <div class="text-xs font-semibold text-slate-700">{{ $tree->lokasi_taman ?? 'Area Sekolah' }}</div>
                                @if($tree->hasCoordinates())
                                    <a href="{{ $tree->google_maps_url }}" target="_blank" class="inline-flex items-center text-[11px] text-emerald-600 hover:text-emerald-800 hover:underline mt-0.5 font-mono">
                                        <i class="fa-solid fa-map-pin mr-1 text-emerald-500"></i>
                                        {{ round($tree->kordinat_lat, 5) }}, {{ round($tree->kordinat_lng, 5) }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-[11px] text-slate-400 mt-0.5">
                                        <i class="fa-solid fa-location-dot mr-1 text-emerald-500"></i> Area Sekolah
                                    </span>
                                @endif
                            </td>

                            <!-- Estimasi Karbon -->
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="font-extrabold text-slate-900 text-base">{{ $tree->estimasi_karbon }}</span>
                                <span class="text-xs text-slate-500 font-medium">kg CO₂e</span>
                            </td>

                            <!-- QR Code & Quick Action -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ $tree->qr_code_url }}" target="_blank" class="w-10 h-10 rounded-xl bg-white border border-slate-200 shadow-sm p-1 hover:border-emerald-500 transition-colors flex items-center justify-center group" title="Lihat Gambar QR">
                                        <img src="{{ $tree->qr_code_url }}" alt="QR {{ $tree->id_pohon }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                                    </a>
                                    <div class="flex flex-col space-y-1">
                                        <a href="{{ route('trees.print-qr', $tree) }}" target="_blank" class="text-[11px] font-semibold text-slate-600 hover:text-emerald-600 flex items-center" title="Cetak Kartu Signage">
                                            <i class="fa-solid fa-print mr-1 text-slate-400"></i> Cetak
                                        </a>
                                        <a href="{{ route('trees.download-qr', $tree) }}" class="text-[11px] font-semibold text-slate-600 hover:text-emerald-600 flex items-center" title="Unduh File SVG">
                                            <i class="fa-solid fa-download mr-1 text-slate-400"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <!-- Detail -->
                                    <a href="{{ route('trees.show', $tree) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 flex items-center justify-center transition-colors" title="Lihat Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    
                                    <!-- Edit -->
                                    <a href="{{ route('trees.edit', $tree) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 flex items-center justify-center transition-colors" title="Edit Pohon">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Public Scan View -->
                                    <a href="{{ route('pohon.public', $tree->id_pohon) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-teal-50 text-slate-600 hover:text-teal-700 flex items-center justify-center transition-colors" title="Buka Halaman Scan Publik">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('trees.destroy', $tree) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pohon {{ $tree->nama_pohon }} ({{ $tree->id_pohon }})? File foto dan QR code terkait juga akan dihapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-600 flex items-center justify-center transition-colors" title="Hapus Data">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-tree"></i>
                                </div>
                                <h4 class="font-bold text-slate-700 text-base">Tidak ada data pohon ditemukan</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                    @if(request('q'))
                                        Pencarian dengan kata kunci "<strong>{{ request('q') }}</strong>" tidak menghasilkan apapun.
                                    @else
                                        Mulai tambahkan pohon pertama Anda ke dalam sistem manajemen.
                                    @endif
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('trees.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Tambah Pohon Sekarang</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trees->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $trees->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
