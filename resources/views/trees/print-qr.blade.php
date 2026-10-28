<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Tag Pohon - {{ $tree->id_pohon }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: 2px solid #052e16 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 flex flex-col items-center justify-center antialiased text-slate-800">

    <!-- Action Bar for Screen -->
    <div class="no-print max-w-md w-full mb-6 flex items-center justify-between">
        <button onclick="window.close()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Tutup Jendela</span>
        </button>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/30 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Label Sekarang</span>
            </button>
        </div>
    </div>

    <!-- Printable Tree Signage Tag (Standard Plaque / Card Ratio) -->
    <div class="print-card max-w-md w-full bg-white rounded-3xl border-2 border-emerald-900 shadow-2xl overflow-hidden relative">
        
        <!-- Header Plaque Bar -->
        <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-green-950 text-white p-5 text-center relative">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-tree text-white"></i>
                </div>
                <h1 class="font-extrabold text-base tracking-tight text-white uppercase">Sistem Monitoring Pohon SMKKN</h1>
            </div>
            <p class="text-[10px] text-emerald-300 font-medium tracking-widest uppercase mt-0.5">Kawasan Hutan Arboretum SMK Kehutanan Negeri</p>
        </div>

        <div class="p-6 text-center space-y-4">
            
            <!-- Tree Common & Latin Name -->
            <div>
                <div class="flex items-center justify-center space-x-2">
                    <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-mono font-bold text-xs uppercase tracking-wider border border-emerald-200">
                        {{ $tree->id_pohon }}
                    </span>
                    @if($tree->tingkat)
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[11px]">
                            {{ $tree->tingkat }} (D: {{ $tree->diameter }} cm)
                        </span>
                    @endif
                </div>
                <h2 class="text-2xl font-black text-slate-900 mt-2">{{ $tree->nama_pohon }}</h2>
                @if($tree->nama_latin)
                    <p class="text-sm text-emerald-700 font-serif italic mt-0.5">{{ $tree->nama_latin }}</p>
                @endif
            </div>

            <!-- High-Res QR Code -->
            <div class="w-56 h-56 mx-auto bg-white p-3 rounded-2xl border-2 border-slate-200 shadow-inner flex items-center justify-center">
                <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
            </div>

            <!-- Call to Action -->
            <div class="bg-emerald-50/80 rounded-2xl p-3 border border-emerald-100">
                <p class="text-xs font-bold text-emerald-950 flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-camera text-emerald-600"></i>
                    <span>SCAN KODE QR DI ATAS</span>
                </p>
                <p class="text-[11px] text-emerald-800/80 mt-0.5">Gunakan kamera smartphone untuk melihat data lengkap, peta lokasi, dan edukasi karbon pohon ini.</p>
            </div>

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-2 gap-3 text-left pt-2 border-t border-slate-100">
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Umur Pohon</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tree->umur }}</span>
                    <span class="block text-[9px] text-slate-400 mt-0.5">Tanam: {{ $tree->tanggal_tanam->format('d/m/Y') }}</span>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <span class="block text-[10px] uppercase font-bold text-slate-400">Serapan Karbon</span>
                    <span class="text-xs font-bold text-emerald-700">{{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e</span>
                    <span class="block text-[9px] text-slate-400 mt-0.5">≈ {{ number_format($tree->estimasi_karbon_ton, 3) }} Ton CO₂e</span>
                </div>
            </div>

            <!-- Footer Location -->
            <div class="text-[10px] text-slate-400 pt-1 flex items-center justify-between">
                <span>{{ $tree->lokasi_taman ?? 'Ruang Terbuka Hijau' }}</span>
                <span class="font-mono">Lat: {{ round($tree->kordinat_lat, 4) }}, Lng: {{ round($tree->kordinat_lng, 4) }}</span>
            </div>
        </div>

        <!-- Plaque Bottom Accent Strip -->
        <div class="h-2 bg-gradient-to-r from-emerald-600 via-green-500 to-teal-600"></div>
    </div>

</body>
</html>
