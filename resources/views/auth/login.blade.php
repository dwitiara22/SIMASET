<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMASET</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-3 bg-teal-600 rounded-xl shadow-lg shadow-teal-500/20 mb-4">
                <img src="{{ asset('images/simaset-logo.png') }}" alt="Logo" class="h-12 w-auto">
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">SIMASET</h1>
            <p class="text-slate-400 mt-2">Sistem Informasi Manajemen Aset</p>
        </div>

        <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 p-8">
            <h2 class="text-xl font-semibold text-white mb-6">Silakan Login</h2>
            <div class="mb-6">
            {{-- Alert Error (Jika validasi gagal atau login salah) --}}
            @if ($errors->any())
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="flex items-center p-4 rounded-lg bg-red-500/10 border border-red-500/50 text-red-400" role="alert">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <div class="text-sm font-medium">
                        {{ $errors->first() }}
                    </div>
                    <button @click="show = false" class="ml-auto -mx-1.5 p-1.5 inline-flex h-8 w-8 text-red-400 hover:bg-red-500/20 rounded-md transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            {{-- Alert Success (Misal setelah logout atau reset password) --}}
            @if (session('status'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex items-center p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/50 text-emerald-400" role="alert">
                    <i class="fas fa-check-circle mr-3"></i>
                    <div class="text-sm font-medium">
                        {{ session('status') }}
                    </div>
                    <button @click="show = false" class="ml-auto -mx-1.5 p-1.5 inline-flex h-8 w-8 text-emerald-400 hover:bg-emerald-500/20 rounded-md transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>

            <form action="{{route('login')}}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-500 group-focus-within:text-teal-400 transition-colors"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                            placeholder="nama@perusahaan.com">
                    </div>
                </div>

               <div x-data="{ show: false }">
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <a href="#" class="text-xs text-teal-400 hover:text-teal-300 transition-colors">Lupa Password?</a>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-500 group-focus-within:text-teal-400 transition-colors"></i>
                    </div>

                    <input :type="show ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        class="block w-full pl-10 pr-10 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                        placeholder="••••••••">

                    <button type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-teal-400 transition-colors focus:outline-none">
                        <i x-show="show" class="fas fa-eye"></i>
                        <i x-show="!show" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-teal-500 focus:ring-teal-500 focus:ring-offset-slate-800 transition">
                    <label for="remember" class="ml-2 block text-sm text-slate-400">Ingat perangkat ini</label>
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-teal-600 hover:bg-teal-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus:ring-offset-slate-900 transition-all active:scale-[0.98]">
                    MASUK KE SISTEM
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-700 text-center text-sm">
                <p class="text-slate-500">
                    &copy; 2026 SIMASET Team. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
