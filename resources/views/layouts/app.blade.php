<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aplikasi Laravel')</title>
    @vite('resources/css/app.css')

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar agar sidebar terlihat lebih rapi */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-100"
      x-data="{ sidebarOpen: window.innerWidth > 1024 }"
      @resize.window="sidebarOpen = window.innerWidth > 1024">

    @include('layouts.sidebar')

    <div
        :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'"
        class="transition-all duration-300 min-h-screen flex flex-col"
    >

        {{-- Pastikan di dalam layouts.header tidak ada class 'fixed' yang menutupi seluruh layar --}}
        @include('layouts.header')

        {{-- Main Content --}}
        <main class="pt-20 px-6 pb-12 flex-1">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

        @include('layouts.footer')
    </div>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Ingin keluar?',
            text: "Anda harus login kembali untuk mengakses sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488', // Warna teal-600
            cancelButtonColor: '#f43f5e', // Warna rose-500
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>

@stack('scripts')
</body>
</html>
