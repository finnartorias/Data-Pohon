<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - Sistem Monitoring Pohon QR</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                        brand: {
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                            950: '#052e16',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-brand-950 via-slate-900 to-emerald-950 min-h-screen flex items-center justify-center p-4 antialiased text-slate-800">

    <div class="w-full max-w-md">
        <!-- Brand Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-3xl bg-gradient-to-tr from-emerald-500 to-green-400 items-center justify-center text-white text-3xl shadow-xl shadow-emerald-500/30 mb-3 transform hover:rotate-6 transition-transform">
                <i class="fa-solid fa-tree"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">EcoTree <span class="text-emerald-400">QR</span></h1>
            <p class="text-sm text-emerald-200/70 mt-1">Sistem Manajemen & Monitoring Pohon Kota</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">Masuk ke Dashboard</h2>
                <p class="text-xs text-slate-500 mt-1">Silakan masukkan kredensial akun administrator Anda.</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-sm flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-5 p-3.5 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-sm flex-shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Administrator</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email', 'admin@pohon.id') }}"
                            placeholder="admin@pohon.id"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            value="password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition-all bg-slate-50/50 hover:bg-white focus:bg-white">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat Sesi Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-semibold text-sm shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 transition-all transform active:scale-[0.98] flex items-center justify-center space-x-2">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Demo Account Info Box -->
            <div class="mt-6 pt-5 border-t border-slate-100 bg-emerald-50/60 -mx-8 -mb-8 p-6 rounded-b-3xl">
                <div class="flex items-start space-x-3 text-xs text-emerald-900">
                    <div class="w-6 h-6 rounded-lg bg-emerald-200/80 flex items-center justify-center text-emerald-800 flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-key text-[10px]"></i>
                    </div>
                    <div>
                        <span class="font-bold">Akun Demo Siap Pakai:</span>
                        <div class="mt-1 font-mono text-[11px] text-emerald-800">
                            Email: <span class="bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200">admin@pohon.id</span><br>
                            Password: <span class="bg-white/80 px-1.5 py-0.5 rounded border border-emerald-200 mt-1 inline-block">password</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Public Link -->
        <div class="text-center mt-6">
            <a href="{{ route('pohon.public', 'TREE-001') }}" class="text-xs text-emerald-300/80 hover:text-white transition-colors flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-qrcode text-emerald-400"></i>
                <span>Coba Scan Contoh QR Publik (TREE-001)</span>
            </a>
        </div>
    </div>

</body>
</html>
