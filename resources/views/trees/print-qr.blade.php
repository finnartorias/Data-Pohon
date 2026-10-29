<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plakat Label QR Pohon &bull; {{ $tree->id_pohon }} - {{ $tree->nama_pohon }}</title>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: 2px solid #0f172a !important;
                box-shadow: none !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 flex flex-col items-center justify-center antialiased text-slate-800">

    <!-- Action Toolbar (Screen Only) -->
    <div class="no-print max-w-sm w-full mb-4 flex items-center justify-between">
        <button onclick="window.close()" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-md text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Tutup</span>
        </button>

        <button onclick="window.print()" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md text-xs font-semibold shadow-xs transition-colors flex items-center space-x-1.5">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Label Sekarang</span>
        </button>
    </div>

    <!-- Official Botanical Specimen Tag (Outdoor Signage Ratio) -->
    <div class="print-card max-w-sm w-full bg-white rounded-lg border-2 border-slate-900 shadow-md overflow-hidden relative">
        
        <!-- Header Institution Bar -->
        <div class="bg-slate-900 text-white p-3.5 text-center border-b-2 border-emerald-700">
            <div class="flex items-center justify-center space-x-2">
                <i class="fa-solid fa-tree text-emerald-400 text-sm"></i>
                <h1 class="font-bold text-xs tracking-wider text-white uppercase">SMK KEHUTANAN NEGERI</h1>
            </div>
            <p class="text-[9px] text-slate-400 font-medium tracking-wide uppercase mt-0.5">Inventarisasi Flora & Karbon Arboretum</p>
        </div>

        <div class="p-6 text-center space-y-4">
            
            <!-- Register Number & Species -->
            <div>
                <div class="inline-flex items-center space-x-1.5">
                    <span class="px-2.5 py-0.5 rounded bg-slate-900 text-white font-mono font-bold text-xs">
                        {{ $tree->id_pohon }}
                    </span>
                    @if($tree->tingkat)
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[11px] border border-slate-200">
                            {{ $tree->tingkat }}
                        </span>
                    @endif
                </div>

                <h2 class="text-2xl font-extrabold text-slate-900 mt-2 uppercase tracking-tight">{{ $tree->nama_pohon }}</h2>
                @if($tree->nama_latin)
                    <p class="text-sm text-slate-600 italic font-serif font-medium mt-0.5">{{ $tree->nama_latin }}</p>
                @endif
            </div>

            <!-- Enlarged High-Res QR Code Frame -->
            <div class="w-64 h-64 sm:w-72 sm:h-72 mx-auto bg-white p-2 rounded-md border-2 border-slate-300 flex items-center justify-center shadow-2xs">
                <img src="{{ $tree->qr_code_url }}" alt="QR Code {{ $tree->id_pohon }}" class="w-full h-full object-contain">
            </div>

            <!-- Scan Instruction -->
            <div class="pt-1">
                <p class="text-[11px] text-slate-900 font-bold uppercase tracking-wider flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-qrcode text-emerald-700 text-sm"></i>
                    <span>PINDAI DENGAN KAMERA HP</span>
                </p>
                <p class="text-[10px] text-slate-500 mt-0.5">Arahkan kamera smartphone untuk melihat data silvikultur & serapan karbon lengkap</p>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-slate-100 text-slate-600 py-2.5 px-3 text-center text-[9px] font-mono border-t border-slate-200">
            SI-Pohon SMKKN &bull; Konservasi & Silvikultur
        </div>

    </div>

</body>
</html>
