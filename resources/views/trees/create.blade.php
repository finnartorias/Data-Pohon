@extends('layouts.app')

@section('title', 'Tambah Data Pohon Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('trees.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pohon
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-subtle overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 via-white to-transparent">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-lg shadow-emerald-600/20">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Tambah Data Pohon Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sistem akan otomatis menghasilkan barcode QR dan menghitung umur pohon.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('trees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- Section 1: Identitas & Spesies -->
            <div class="space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 1. Identitas & Taksonomi Botani
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- ID Pohon -->
                    <div>
                        <label for="id_pohon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kode ID Pohon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="id_pohon" id="id_pohon" required
                            value="{{ old('id_pohon', $suggestedCode) }}"
                            placeholder="e.g. TREE-001"
                            class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('id_pohon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm font-mono font-bold transition-all">
                        @error('id_pohon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Target scan: <span class="font-mono text-emerald-700">/pohon/public/{id_pohon}</span></p>
                    </div>

                    <!-- Nama Pohon -->
                    <div>
                        <label for="nama_pohon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Umum Pohon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pohon" id="nama_pohon" required
                            value="{{ old('nama_pohon') }}"
                            placeholder="Contoh: Trembesi, Meranti Merah, Ketapang Kencana"
                            class="w-full px-4 py-2.5 rounded-2xl border {{ $errors->has('nama_pohon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all font-semibold">
                        @error('nama_pohon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Latin / Ilmiah -->
                    <div>
                        <label for="nama_latin" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Ilmiah / Spesies Latin
                        </label>
                        <input type="text" name="nama_latin" id="nama_latin"
                            value="{{ old('nama_latin') }}"
                            placeholder="Contoh: Samanea saman, Swietenia mahagoni"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm italic font-serif transition-all">
                        @error('nama_latin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Tanam -->
                    <div>
                        <label for="tanggal_tanam" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Penanaman (Untuk Hitung Umur Otomatis)
                        </label>
                        <input type="date" name="tanggal_tanam" id="tanggal_tanam"
                            value="{{ old('tanggal_tanam') }}"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Umur akan terhitung dinamis secara real-time dari tanggal ini.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Dimensi & Karbon -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 2. Dimensi Pohon & Estimasi Karbon
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Diameter -->
                    <div>
                        <label for="diameter" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Diameter (cm)
                        </label>
                        <input type="number" step="0.01" name="diameter" id="diameter"
                            value="{{ old('diameter') }}"
                            placeholder="e.g. 45.5"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all">
                    </div>

                    <!-- Tinggi -->
                    <div>
                        <label for="tinggi" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tinggi Pohon (m)
                        </label>
                        <input type="number" step="0.1" name="tinggi" id="tinggi"
                            value="{{ old('tinggi') }}"
                            placeholder="e.g. 12.0"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all">
                    </div>

                    <!-- Berat Jenis -->
                    <div>
                        <label for="berat_jenis" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Berat Jenis Kayu (BJ)
                        </label>
                        <input type="number" step="0.01" name="berat_jenis" id="berat_jenis"
                            value="{{ old('berat_jenis', '0.6') }}"
                            placeholder="e.g. 0.65"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all">
                    </div>

                    <!-- Estimasi Karbon -->
                    <div>
                        <label for="estimasi_karbon" class="block text-xs font-bold text-emerald-800 mb-1.5">
                            Serapan Karbon (kg CO₂e) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="estimasi_karbon" id="estimasi_karbon" required
                            value="{{ old('estimasi_karbon', '150.00') }}"
                            placeholder="e.g. 350.5"
                            class="w-full px-4 py-2.5 rounded-2xl border-2 border-emerald-400 bg-emerald-50/50 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm font-bold text-emerald-900 transition-all">
                        @error('estimasi_karbon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Lokasi & Geospasial -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 3. Lokasi & Titik GPS
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Lokasi Taman -->
                    <div>
                        <label for="lokasi_taman" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Zona / Area SMKKN
                        </label>
                        <input type="text" name="lokasi_taman" id="lokasi_taman"
                            value="{{ old('lokasi_taman', 'Arboretum SMKKN') }}"
                            placeholder="Contoh: Blok A - Arboretum Barat"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all">
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label for="kordinat_lat" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Latitude (GPS)
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lat" id="kordinat_lat"
                            value="{{ old('kordinat_lat') }}"
                            placeholder="Contoh: -0.494819"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm font-mono transition-all">
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="kordinat_lng" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Longitude (GPS)
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lng" id="kordinat_lng"
                            value="{{ old('kordinat_lng') }}"
                            placeholder="Contoh: 117.143615"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm font-mono transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 4: Foto Pohon & Deskripsi -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> 4. Dokumentasi Foto & Deskripsi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Pohon (Opsional)</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-3xl p-5 text-center hover:border-emerald-500 transition-colors bg-slate-50/50">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-emerald-600 mb-2"></i>
                            <input type="file" name="foto" id="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="text-[10px] text-slate-400 mt-2">Format: JPG, PNG, WEBP (Maksimal 3MB)</p>
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Manfaat Ekologis</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            placeholder="Catatan morfologi pohon, manfaat bagi lingkungan SMKKN, atau keunikan..."
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm leading-relaxed transition-all">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('trees.index') }}" class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/30 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Simpan Data & Generate QR Code</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection