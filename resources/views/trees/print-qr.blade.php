<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label QR Pohon &bull; {{ $tree->id_pohon }} - {{ $tree->nama_pohon }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: 2px solid #032311 !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 flex flex-col items-center justify-center antialiased text-slate-800">

    <!-- Action Bar for Screen -->
    <div class="no-print max-w-sm w-full mb-5 flex items-center justify-between">
        <button onclick="window.close()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-2xl text-xs font-bold shadow-sm transition-all flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Tutup</span>
        </button>

        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Label Sekarang</span>
        </button>
    </div>

    <!-- Printable Tree Signage Tag (Acrylic / Signboard Ratio) -->
    <div class="print-card max-w-sm w-full bg-white rounded-3xl border-2 border-emerald-950 shadow-2xl overflow-hidden relative">
        
        <!-- Header Plaque Bar -->
        <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-emerald-950 text-white p-5 text-center relative border-b-2 border-emerald-500">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-black text-sm">
                    <i class="fa-solid fa-tree text-white"></i>
                </div>
                <h1 class="font-black text-base tracking-tight text-white uppercase">EcoTree QR &bull; SMKKN</h1>
            </div>
            <p class="text-[9px] text-emerald-300 font-bold tracking-widest uppercase mt-1">Arboretum SMK Kehutanan Negeri</p>
        </div>

        <div class="p-6 text-center space-y-4">
            
            <!-- Tree Common & Latin Name -->
            <div>
                <div class="inline-flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-900 font-mono font-black text-xs uppercase tracking-wider border border-emerald-200">
                        {{ $tree->id_pohon }}
                    </span>
                    @if($tree->tingkat)
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                            {{ $tree->tingkat }}
                        </span>
                    @endif
                </div>

                <h2 class="text-xl font-black text-slate-900 mt-2.5 leading-tight">{{ $tree->nama_pohon }}</h2>
                @if($tree->nama_latin)
                    <p class="text-xs text-emerald-800 font-serif italic mt-0.5 font-bold">{{ $tree->nama_latin }}</p>
                @endif
            </div>

            <!-- High-Res QR Code Card -->
            <div class="w-52 h-52 mx-auto bg-white p-3 rounded-2xl border-2 border-slate-200 shadow-inner flex items-center justify-center">
                <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
            </div>

            <!-- Quick Carbon & Stats Pill -->
            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200/80 grid grid-cols-2 gap-2 text-center">
                <div>
                    <span class="text-[9px] font-bold uppercase text-slate-400 block">Serapan Karbon</span>
                    <span class="text-xs font-black text-emerald-700">{{ number_format($tree->estimasi_karbon, 1) }} kg CO₂e</span>
                </div>
                <div>
                    <span class="text-[9px] font-bold uppercase text-slate-400 block">Diameter Batang</span>
                    <span class="text-xs font-black text-slate-800">{{ $tree->diameter ? $tree->diameter . ' cm' : '-' }}</span>
                </div>
            </div>

            <!-- Instruction to Scan -->
            <div class="pt-1">
                <p class="text-[10px] text-slate-800 font-black uppercase tracking-wider flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-camera text-emerald-600"></i>
                    <span>SCAN KODE QR DI ATAS</span>
                </p>
                <p class="text-[9px] text-slate-400 mt-0.5">Pindai dengan kamera ponsel untuk membuka data pohon.</p>
            </div>

        </div>

        <!-- Footer Bar -->
        <div class="bg-slate-900 text-slate-300 py-2.5 px-4 text-center text-[9px] font-semibold border-t border-slate-800">
            Sistem Inventarisasi & Monitoring Karbon Hutan SMKKN
        </div>

    </div>

</body>
</html>