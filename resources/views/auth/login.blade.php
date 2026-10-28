<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Administrator &bull; SI-Pohon SMKKN</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4 text-slate-800 antialiased">

    <div class="w-full max-w-sm space-y-4">
        
        <!-- Institutional Header -->
        <div class="text-center space-y-1">
            <div class="inline-flex w-10 h-10 rounded-lg bg-emerald-700 items-center justify-center text-white text-base font-bold shadow-xs mb-1">
                <i class="fa-solid fa-tree"></i>
            </div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">SI-POHON SMKKN</h1>
            <p class="text-xs text-slate-500">Sistem Informasi Inventarisasi Pohon & Karbon</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg border border-slate-300 shadow-sm p-6 space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900">Autentikasi Administrator</h2>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan kredensial akun untuk mengakses panel kelola.</p>
            </div>

            @if($errors->any())
                <div class="p-3 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-3.5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email Administrator</label>
                    <input type="email" name="email" id="email" required autofocus
                        value="{{ old('email', 'admin@pohon.id') }}"
                        placeholder="admin@pohon.id"
                        class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 text-xs">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" id="password" required
                        placeholder="••••••••"
                        class="w-full px-3 py-1.5 rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 text-xs">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-600 pt-0.5">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-700">
                        <span>Ingat sesi masuk</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2 px-3 rounded-md bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition-colors flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Masuk ke Panel Admin</span>
                </button>
            </form>
        </div>

        <p class="text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} SMK Kehutanan Negeri &bull; Konservasi & Karbon
        </p>

    </div>

</body>
</html>
