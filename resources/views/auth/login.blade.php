<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin &bull; EcoTree QR SMKKN</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                            950: '#032311',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-pattern {
            background-color: #032311;
            background-image: radial-gradient(rgba(34, 197, 94, 0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="hero-pattern min-h-screen flex items-center justify-center p-4 antialiased text-slate-800">

    <div class="w-full max-w-md space-y-6">
        
        <!-- Brand Logo Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-emerald-400 items-center justify-center text-white text-2xl shadow-xl shadow-emerald-500/30 mb-3 transform hover:scale-105 transition-transform">
                <i class="fa-solid fa-tree"></i>
            </a>
            <h1 class="text-2xl font-black text-white tracking-tight">EcoTree <span class="text-emerald-400">QR</span></h1>
            <p class="text-xs text-emerald-200/70 mt-1 font-medium">Sistem Monitoring Pohon SMKKN</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/20 space-y-6">
            <div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Masuk Administrator</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan kredensial akun untuk mengelola data pohon & barcode.</p>
            </div>

            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-sm flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email Administrator</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </div>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email', 'admin@pohon.id') }}"
                            placeholder="admin@pohon.id"
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all bg-slate-50/50 focus:bg-white">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs sm:text-sm transition-all bg-slate-50/50 focus:bg-white">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer font-medium">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk ke Dashboard</span>
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-emerald-700 font-bold transition-colors inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Kembali ke Katalog Publik Tanpa Login</span>
                </a>
            </div>
        </div>

    </div>

</body>
</html>