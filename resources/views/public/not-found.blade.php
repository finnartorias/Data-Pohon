<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pohon Tidak Ditemukan - EcoTree QR</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased flex flex-col items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 text-center space-y-5 border border-slate-200">
        <div class="w-20 h-20 rounded-3xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mx-auto shadow-inner">
            <i class="fa-solid fa-tree-deciduous"></i>
        </div>

        <div>
            <span class="font-mono text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-bold">
                {{ $id_pohon }}
            </span>
            <h1 class="text-2xl font-black text-slate-900 mt-3">Data Pohon Tidak Ditemukan</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Kode barcode pohon yang Anda scan belum terdaftar atau mungkin data telah diperbarui oleh administrator ruang terbuka hijau.
            </p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-col space-y-2">
            <a href="{{ route('home') }}" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}" class="w-full py-2.5 px-4 text-slate-500 hover:text-slate-800 font-semibold text-xs transition-colors">
                Masuk Administrator
            </a>
        </div>
    </div>

</body>
</html>
