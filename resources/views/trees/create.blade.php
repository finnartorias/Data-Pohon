@extends('layouts.app')

@section('title', 'Tambah Pohon Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('trees.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Pohon
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-white to-transparent">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shadow-lg shadow-emerald-600/20">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900">Tambah Data Pohon Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Sistem akan otomatis menghasilkan barcode QR dan menghitung umur pohon.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('trees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8">
            @csrf

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
                            value="{{ old('id_pohon', $suggestedCode) }}"
                            placeholder="e.g. TREE-001"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('id_pohon') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                        @error('id_pohon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Kode ini akan menjadi target URL saat QR di-scan: <span class="font-mono text-emerald-700">/pohon/public/{id_pohon}</span></p>
                    </div>

                    <!-- Nama Pohon -->
                    <div>
                        <label for="nama_pohon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Umum Pohon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pohon" id="nama_pohon" required
                            value="{{ old('nama_pohon') }}"
                            placeholder="Contoh: Pohon Trembesi, Tabebuya Kuning"
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
                            value="{{ old('nama_latin') }}"
                            placeholder="Contoh: Samanea saman, Swietenia macrophylla"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm italic font-serif transition-all">
                        @error('nama_latin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Tanam -->
                    <div>
                        <label for="tanggal_tanam" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Penanaman <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_tanam" id="tanggal_tanam" required
                            value="{{ old('tanggal_tanam', date('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('tanggal_tanam') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                        @error('tanggal_tanam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Umur pohon akan terhitung secara otomatis dari tanggal ini.</p>
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
                                value="{{ old('estimasi_karbon', '25.00') }}"
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
                            value="{{ old('lokasi_taman') }}"
                            placeholder="Contoh: Zona Hutan Kota Blok A, Jalur Hijau Sisi Timur"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 3: Koordinat Lokasi Peta (GIS) -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Titik Koordinat GPS & Peta Interaktif
                    </h3>
                    <button type="button" onclick="getCurrentLocation()" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <i class="fa-solid fa-location-crosshairs mr-1.5"></i> Gunakan Lokasi GPS Saya
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                    <!-- Latitude -->
                    <div>
                        <label for="kordinat_lat" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Latitude (Lintang) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lat" id="kordinat_lat" required
                            value="{{ old('kordinat_lat', '-6.1753924') }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('kordinat_lat') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                        @error('kordinat_lat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="kordinat_lng" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Longitude (Bujur) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.0000001" name="kordinat_lng" id="kordinat_lng" required
                            value="{{ old('kordinat_lng', '106.8271528') }}"
                            class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('kordinat_lng') ? 'border-red-400 bg-red-50/50' : 'border-slate-200' }} focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono font-semibold transition-all">
                        @error('kordinat_lng')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Leaflet Interactive Coordinate Picker Map -->
                <div>
                    <p class="text-xs text-slate-500 mb-2">
                        <i class="fa-solid fa-hand-pointer mr-1 text-emerald-600"></i> Klik di mana saja pada peta di bawah untuk memindahkan pin lokasi dan mengisi koordinat secara otomatis:
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
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Upload Foto Pohon</label>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-4 text-center cursor-pointer transition-colors relative bg-slate-50/50 hover:bg-emerald-50/20">
                            <input type="file" name="foto" id="foto-input" accept="image/jpeg,image/png,image/webp" onchange="previewImage(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div id="preview-placeholder">
                                <div class="w-12 h-12 rounded-2xl bg-white text-emerald-600 shadow-sm flex items-center justify-center mx-auto mb-2 text-xl">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Pilih atau seret foto pohon di sini</p>
                                <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, JPEG atau WEBP (Maksimal 3MB)</p>
                            </div>
                            <img id="image-preview" src="#" alt="Preview" class="hidden max-h-48 rounded-xl mx-auto object-cover border border-slate-200">
                        </div>
                        @error('foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Deskripsi Pohon</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                            placeholder="Tuliskan karakteristik pohon, manfaat ekologis, atau catatan perawatan khusus di sini..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm transition-all">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Submit Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('trees.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Simpan & Buat QR Code</span>
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

        map = L.map('picker-map').setView([initialLat, initialLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        // Event drag marker
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            updateCoordinateInputs(position.lat, position.lng);
        });

        // Event klik pada peta
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoordinateInputs(e.latlng.lat, e.latlng.lng);
        });

        // Event jika user mengubah input text secara manual
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
        } else {
            alert("Browser Anda tidak mendukung Geolocation.");
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('preview-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
