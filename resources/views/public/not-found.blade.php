<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pohon Tidak Ditemukan &bull; EcoTree SMKKN</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col items-center justify-center p-4 py-8 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-6 sm:p-8 text-center space-y-6 border border-slate-200/80">
        <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center text-2xl mx-auto shadow-sm">
            <i class="fa-solid fa-tree"></i>
        </div>

        <div>
            <span class="inline-block font-mono text-xs px-3 py-1 rounded-xl bg-slate-100 text-slate-700 font-bold border border-slate-200">
                {{ $id_pohon }}
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2.5 tracking-tight">Data Pohon Tidak Ditemukan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                Kode ID <span class="font-mono font-bold text-slate-700">{{ $id_pohon }}</span> belum terdaftar di basis data. Data pohon di lingkungan SMKKN menggunakan format kode identitas seperti <span class="font-mono font-bold text-emerald-700">SMKKN-001</span> s/d <span class="font-mono font-bold text-emerald-700">SMKKN-038</span>.
            </p>
        </div>

        @if(isset($suggestedTrees) && $suggestedTrees->isNotEmpty())
            <div class="text-left pt-2 border-t border-slate-100 space-y-2.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block text-center">
                    Atau pilih salah satu pohon terdaftar di bawah ini:
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($suggestedTrees as $sugTree)
                        <a href="{{ route('pohon.public', $sugTree->id_pohon) }}" class="p-2.5 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-200/80 hover:border-emerald-300 text-left transition-all flex items-center space-x-2.5 group">
                            <span class="font-mono font-bold text-[10px] px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 flex-shrink-0">
                                {{ $sugTree->id_pohon }}
                            </span>
                            <div class="truncate flex-1">
                                <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 block truncate">{{ $sugTree->nama_pohon }}</span>
                                <span class="text-[10px] text-slate-400 block truncate">{{ number_format($sugTree->estimasi_karbon, 1) }} kg CO₂e</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pt-4 border-t border-slate-100 space-y-2.5">
            <a href="{{ route('home') }}" class="w-full py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-compass"></i>
                <span>Buka Katalog Lengkap Pohon SMKKN</span>
            </a>
            <a href="{{ route('login') }}" class="w-full py-2 text-slate-400 hover:text-slate-700 font-semibold text-xs transition-colors block">
                Masuk Administrator SMKKN
            </a>
        </div>
    </div>

</body>
</html>