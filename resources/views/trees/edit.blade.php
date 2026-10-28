@extends('layouts.app')

@section('title', 'Edit Pohon: ' . $tree->nama_pohon)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('trees.show', $tree) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Detail Pohon
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 bg-gradient-to-r from-blue-50/50 via-white to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-600/20">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Perbarui Data Pohon</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $tree->nama_pohon }} (Kode: <span class="font-mono font-bold">{{ $tree->id_pohon }}</span>)</p>
                    </div>
                </div>
                <span class="font-mono text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">
                    {{ $tree->id_pohon }}
                </span>
            </div>
        </div>

        <form action="{{ route('trees.update', $tree) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Identitas Pohon -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Identitas & Spesies Pohon
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- ID Pohon -->
                    <div>
                        <label for="id_pohon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kode Unik Pohon (ID) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="id_pohon" id="id_pohon" required
                            value="{{ old('id_pohon', $tree->id_pohon) }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('id_pohon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                        @error('id_pohon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-amber-600 mt-1">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> Perhatian: Jika ID diubah, sistem akan otomatis membuat ulang file QR code baru.
                        </p>
                    </div>

                    <!-- Nama Pohon -->
                    <div>
                        <label for="nama_pohon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Umum Pohon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pohon" id="nama_pohon" required
                            value="{{ old('nama_pohon', $tree->nama_pohon) }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('nama_pohon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                        @error('nama_pohon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Latin / Ilmiah -->
                    <div>
                        <label for="nama_latin" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Latin / Spesies Botani
                        </label>
                        <input type="text" name="nama_latin" id="nama_latin"
                            value="{{ old('nama_latin', $tree->nama_latin) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm italic font-serif transition-all">
                        @error('nama_latin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Tanam -->
                    <div>
                        <label for="tanggal_tanam" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Penanaman <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <input type="date" name="tanggal_tanam" id="tanggal_tanam"
                            value="{{ old('tanggal_tanam', $tree->tanggal_tanam ? $tree->tanggal_tanam->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('tanggal_tanam') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                        @error('tanggal_tanam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-blue-600 mt-1">Umur saat ini: <strong>{{ $tree->umur }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Ekologi & Karbon -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Serapan Karbon & Area
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Estimasi Karbon -->
                    <div>
                        <label for="estimasi_karbon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Estimasi Karbon Terserap (kg CO₂e) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" name="estimasi_karbon" id="estimasi_karbon" required
                                value="{{ old('estimasi_karbon', $tree->estimasi_karbon) }}"
                                class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('estimasi_karbon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-semibold transition-all">
                            <span class="absolute right-4 top-2.5 text-xs text-slate-400 font-bold">kg</span>
                        </div>
                        @error('estimasi_karbon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi Taman / Zona -->
                    <div>
                        <label for="lokasi_taman" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Lokasi Taman / Blok Penanaman
                        </label>
                        <input type="text" name="lokasi_taman" id="lokasi_taman"
                            value="{{ old('lokasi_taman', $tree->lokasi_taman) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 3: Koordinat Lokasi Peta (Opsional) -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-slate-400 mr-2"></span> Titik Koordinat GPS (Opsional)
                    </h3>
                    <button type="button" onclick="getCurrentLocation()" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <i class="fa-solid fa-location-crosshairs mr-1.5"></i> Gunakan Lokasi GPS Saya
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                    <!-- Latitude -->
                    <div>
                        <label for="kordinat_lat" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Latitude (Lintang) <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lat" id="kordinat_lat"
                            value="{{ old('kordinat_lat', $tree->kordinat_lat) }}" placeholder="Contoh: -0.4862000"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="kordinat_lng" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Longitude (Bujur) <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lng" id="kordinat_lng"
                            value="{{ old('kordinat_lng', $tree->kordinat_lng) }}" placeholder="Contoh: 117.1578000"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                    </div>
                </div>

                <!-- Leaflet Interactive Coordinate Picker Map -->
                <div>
                    <p class="text-xs text-slate-500 mb-2">
                        <i class="fa-solid fa-hand-pointer mr-1 text-emerald-600"></i> Klik atau geser marker di bawah untuk mengubah koordinat pohon:
                    </p>
                    <div id="picker-map" class="w-full h-72 rounded-2xl border border-slate-200 z-10 shadow-inner"></div>
                </div>
            </div>

            <!-- Section 4: Foto Pohon & Deskripsi -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 mb-4 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Foto & Deskripsi Pohon
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Foto Pohon</label>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-4 text-center cursor-pointer transition-colors relative bg-slate-50/50 hover:bg-emerald-50/20">
                            <input type="file" name="foto" id="foto-input" accept="image/jpeg,image/png,image/webp" onchange="previewImage(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div id="current-image-container" class="space-y-2">
                                <img id="image-preview" src="{{ $tree->foto_url }}" alt="Preview" class="max-h-40 rounded-xl mx-auto object-cover border border-slate-200 shadow-sm">
                                <p class="text-[11px] text-slate-500 font-semibold">Klik atau seret file baru untuk mengganti foto ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Deskripsi Pohon</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">{{ old('deskripsi', $tree->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Submit Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('trees.show', $tree) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let map, marker;

    document.addEventListener("DOMContentLoaded", function () {
        const initialLat = parseFloat(document.getElementById('kordinat_lat').value) || -6.1753924;
        const initialLng = parseFloat(document.getElementById('kordinat_lng').value) || 106.8271528;

        map = L.map('picker-map').setView([initialLat, initialLng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            updateCoordinateInputs(position.lat, position.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoordinateInputs(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('kordinat_lat').addEventListener('change', updateMarkerFromInputs);
        document.getElementById('kordinat_lng').addEventListener('change', updateMarkerFromInputs);
    });

    function updateCoordinateInputs(lat, lng) {
        document.getElementById('kordinat_lat').value = lat.toFixed(7);
        document.getElementById('kordinat_lng').value = lng.toFixed(7);
    }

    function updateMarkerFromInputs() {
        const lat = parseFloat(document.getElementById('kordinat_lat').value);
        const lng = parseFloat(document.getElementById('kordinat_lng').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    }

    function getCurrentLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                updateCoordinateInputs(lat, lng);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 17);
            }, function (error) {
                alert("Gagal membaca lokasi GPS: " + error.message);
            });
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
