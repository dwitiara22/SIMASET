<header
    /* Logika margin kiri hanya berlaku di layar besar (lg) */
    :class="sidebarOpen ? 'lg:ml-64 w-full lg:w-[calc(100%-16rem)]' : 'lg:ml-16 w-full lg:w-[calc(100%-4rem)]'"
    class="fixed top-0 left-0 right-0 h-16 bg-teal-700 border-b border-teal-800 shadow-sm z-40 transition-all duration-300"
>
    <div class="flex items-center justify-between h-full px-4">

        {{-- LEFT: HAMBURGER --}}
        <div class="flex items-center gap-4">
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-md text-teal-50 hover:bg-teal-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- RIGHT AREA --}}
        <div class="flex items-center gap-2 sm:gap-4">

            @auth
                {{-- Notification --}}
                <button class="relative p-2 rounded-md text-teal-100 hover:bg-teal-800 transition">
                    <i class="far fa-bell text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-teal-700"></span>
                    </span>
                </button>

                {{-- Avatar & Dropdown Section --}}
                <div class="relative flex items-center gap-3 border-l border-teal-600 pl-4" x-data="{ open: false }">

                    {{-- Info Nama: Hidden di mobile, flex di small screen (sm) ke atas --}}
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs font-semibold text-white leading-tight">
                            {{ auth()->user()->nama }}
                        </span>
                        <span class="text-[10px] text-teal-200 leading-tight">
                            @if(auth()->user()->role == 1)
                                Super Admin
                            @elseif(auth()->user()->role == 3)
                                Pengaju
                            @else
                                Administrator
                            @endif
                        </span>
                    </div>

                    {{-- Tombol Profil --}}
                    <button @click="open = !open" @click.away="open = false" class="focus:outline-none">
                        <div class="h-9 w-9 rounded-full overflow-hidden border-2 border-teal-500 flex items-center justify-center text-white font-semibold text-sm shadow-md cursor-pointer hover:border-white transition">
                            @if(auth()->user()->foto_profil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                            @else
                                <div class="bg-teal-900 w-full h-full flex items-center justify-center">
                                    {{ substr(auth()->user()->nama, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </button>

                    {{-- Dropdown Menu (Z-index 50 agar di atas konten) --}}
                    <div x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 top-12 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 text-slate-700">

                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Akun</p>
                        </div>

                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-600 transition">
                            <i class="fas fa-user-circle w-4"></i>
                            <span>Profil Saya</span>
                        </a>

                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-600 transition">
                            <i class="fas fa-key w-4"></i>
                            <span>Ubah Password</span>
                        </a>

                        <hr class="my-1 border-slate-50">

                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>

                        <button type="button"
                            onclick="confirmLogout()"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt w-4"></i>
                            <span>Keluar (Logout)</span>
                        </button>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg bg-teal-800 border border-teal-500 text-white text-sm font-bold hover:bg-teal-600 transition-all active:scale-95">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>LOGIN</span>
                </a>
            @endguest
        </div>
    </div>
</header>

<div class="fixed top-20 right-4 left-4 sm:left-auto z-50 space-y-3 w-auto sm:w-[340px] max-w-full">

    @if (session('login_success'))
        <x-alert type="success" :message="session('login_success')" />
    @endif

    @if (session('logout_success'))
        <x-alert type="info" :message="session('logout_success')" />
    @endif

    @if ($errors->any())
        <x-alert type="error" :message="$errors->first()" />
    @endif

</div>

