<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spesimen Tidak Ditemukan &bull; SI-Pohon SMKKN</title>
    
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
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
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-lg border border-slate-300 shadow-sm p-6 text-center space-y-4">
        <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center text-xl mx-auto">
            <i class="fa-solid fa-tree"></i>
        </div>

        <div>
            <span class="inline-block font-mono text-xs px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 font-bold border border-slate-200">
                {{ $id_pohon }}
            </span>
            <h1 class="text-lg font-bold text-slate-900 mt-2">Data Spesimen Tidak Ditemukan</h1>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Nomor register <span class="font-mono font-bold text-slate-700">{{ $id_pohon }}</span> belum tercatat dalam database pohon arboretum. Pastikan kode QR fisik dalam kondisi bersih dan terbaca dengan baik.
            </p>
        </div>

        @if(isset($suggestedTrees) && $suggestedTrees->isNotEmpty())
            <div class="text-left pt-3 border-t border-slate-200 space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block text-center">
                    Contoh Spesimen Terdaftar:
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($suggestedTrees as $sugTree)
                        <a href="{{ route('pohon.public', $sugTree->id_pohon) }}" class="p-2 rounded bg-slate-50 hover:bg-slate-100 border border-slate-200 text-left transition-colors flex items-center space-x-2">
                            <span class="font-mono font-bold text-[10px] px-1.5 py-0.5 rounded bg-slate-200 text-slate-700">
                                {{ $sugTree->id_pohon }}
                            </span>
                            <span class="text-xs font-semibold text-slate-800 truncate">{{ $sugTree->nama_pohon }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pt-3 border-t border-slate-200 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-emerald-800 hover:underline">
                Akses Masuk Administrator
            </a>
        </div>
    </div>

</body>
</html>
