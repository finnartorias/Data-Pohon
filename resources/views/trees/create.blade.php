@extends('layouts.app')

@section('title', 'Input Pohon Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    <!-- Header Breadcrumb -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Formulir Input Pohon Baru</h2>
            <p class="text-xs text-slate-500 mt-0.5">Catat data silvikultur pohon dan otomatis hasilkan barcode QR.</p>
        </div>
        <a href="{{ route('trees.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Kembali ke Database
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-2xs">
        <form action="{{ route('trees.store') }}" method="POST" class="p-5 sm:p-6 space-y-6">
            @csrf

            <!-- Section 1: Identitas & Taksonomi -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 pb-1.5 border-b border-slate-100">
                    1. Identitas & Taksonomi Botani
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_pohon" class="block text-xs font-semibold text-slate-700 mb-1">
                            Kode Register / ID Pohon <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="id_pohon" id="id_pohon" value="{{ old('id_pohon', $suggestedId ?? '') }}" required
                            placeholder="Contoh: TREE-039"
                            class="w-full px-3 py-1.5 rounded-md border {{ $errors->has('id_pohon') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-300' }} focus:outline-none focus:ring-1 focus:ring-emerald-700 font-mono text-xs">
                        @error('id_pohon')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_pohon" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Pohon (Lokal) <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="nama_pohon" id="nama_pohon" value="{{ old('nama_pohon') }}" required
                            placeholder="Contoh: Bengkirai, Ulin, Meranti"
                            class="w-full px-3 py-1.5 rounded-md border {{ $errors->has('nama_pohon') ? 'border-rose-400 bg-rose-50/50' : 'border-slate-300' }} focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs">
                        @error('nama_pohon')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_latin" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nama Ilmiah / Latin
                        </label>
                        <input type="text" name="nama_latin" id="nama_latin" value="{{ old('nama_latin') }}"
                            placeholder="Contoh: Shorea laevis"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs italic">
                    </div>

                    <div>
                        <label for="tanggal_tanam" class="block text-xs font-semibold text-slate-700 mb-1">
                            Tanggal Penanaman
                        </label>
                        <input type="date" name="tanggal_tanam" id="tanggal_tanam" value="{{ old('tanggal_tanam') }}"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs">
                    </div>
                </div>
            </div>

            <!-- Section 2: Parameter Silvikultur & Karbon -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 pb-1.5 border-b border-slate-100">
                    2. Pengukuran Silvikultur & Karbon
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="tingkat" class="block text-xs font-semibold text-slate-700 mb-1">Fase Pertumbuhan</label>
                        <select name="tingkat" id="tingkat" class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs">
                            <option value="">Pilih Fase</option>
                            <option value="Pohon" {{ old('tingkat') == 'Pohon' ? 'selected' : '' }}>Pohon (D > 20 cm)</option>
                            <option value="Tiang" {{ old('tingkat') == 'Tiang' ? 'selected' : '' }}>Tiang (D 10 - 20 cm)</option>
                            <option value="Pancang" {{ old('tingkat') == 'Pancang' ? 'selected' : '' }}>Pancang (D < 10 cm)</option>
                        </select>
                    </div>

                    <div>
                        <label for="diameter" class="block text-xs font-semibold text-slate-700 mb-1">Diameter Batang (cm)</label>
                        <input type="number" step="0.01" name="diameter" id="diameter" value="{{ old('diameter') }}"
                            placeholder="Contoh: 28.5"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>

                    <div>
                        <label for="tinggi" class="block text-xs font-semibold text-slate-700 mb-1">Tinggi Total (meter)</label>
                        <input type="number" step="0.01" name="tinggi" id="tinggi" value="{{ old('tinggi') }}"
                            placeholder="Contoh: 14.2"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>

                    <div>
                        <label for="berat_jenis" class="block text-xs font-semibold text-slate-700 mb-1">Berat Jenis Kayu (BJ)</label>
                        <input type="number" step="0.001" name="berat_jenis" id="berat_jenis" value="{{ old('berat_jenis') }}"
                            placeholder="Contoh: 0.650"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label for="biomassa_agb" class="block text-xs font-semibold text-slate-700 mb-1">Biomassa AGB (kg)</label>
                        <input type="number" step="0.01" name="biomassa_agb" id="biomassa_agb" value="{{ old('biomassa_agb') }}"
                            placeholder="Opsional / Otomatis"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>

                    <div>
                        <label for="cadangan_karbon" class="block text-xs font-semibold text-slate-700 mb-1">Cadangan Karbon (Ton C)</label>
                        <input type="number" step="0.001" name="cadangan_karbon" id="cadangan_karbon" value="{{ old('cadangan_karbon') }}"
                            placeholder="Opsional / Otomatis"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>

                    <div>
                        <label for="estimasi_karbon" class="block text-xs font-semibold text-slate-700 mb-1">
                            Estimasi Serapan Karbon (kg CO₂e) <span class="text-rose-600">*</span>
                        </label>
                        <input type="number" step="0.01" name="estimasi_karbon" id="estimasi_karbon" value="{{ old('estimasi_karbon', '0') }}" required
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono font-bold text-slate-900 bg-slate-50">
                    </div>
                </div>
            </div>

            <!-- Section 3: Lokasi & Deskripsi -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 pb-1.5 border-b border-slate-100">
                    3. Lokasi & Catatan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="lokasi_taman" class="block text-xs font-semibold text-slate-700 mb-1">Lokasi / Blok Taman</label>
                        <input type="text" name="lokasi_taman" id="lokasi_taman" value="{{ old('lokasi_taman', 'Arboretum SMKKN') }}"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs">
                    </div>

                    <div>
                        <label for="kordinat_lat" class="block text-xs font-semibold text-slate-700 mb-1">Latitude GPS</label>
                        <input type="number" step="0.0000001" name="kordinat_lat" id="kordinat_lat" value="{{ old('kordinat_lat') }}"
                            placeholder="Contoh: -1.234567"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>

                    <div>
                        <label for="kordinat_lng" class="block text-xs font-semibold text-slate-700 mb-1">Longitude GPS</label>
                        <input type="number" step="0.0000001" name="kordinat_lng" id="kordinat_lng" value="{{ old('kordinat_lng') }}"
                            placeholder="Contoh: 116.890123"
                            class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs font-mono">
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi & Catatan Silvikultur</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        placeholder="Catatan morfologi pohon, manfaat ekologis, atau kondisi kesehatan tanaman..."
                        class="w-full px-3 py-2 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 text-xs leading-relaxed">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-2">
                <a href="{{ route('trees.index') }}" class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition-colors flex items-center space-x-1.5">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan & Buat QR</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
