{{-- OVERLAY (Mobile Only) --}}
<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-cloak
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300">
</div>

<aside
    :class="{
        'w-64': sidebarOpen,
        'w-16': !sidebarOpen,
        'left-0': sidebarOpen,
        '-left-full lg:left-0': !sidebarOpen
    }"
    class="fixed top-0 h-screen bg-slate-900 text-slate-300 border-r border-slate-800 shadow-2xl z-50 transition-all duration-300 ease-in-out flex flex-col"
>
    {{-- LOGO HEADER --}}
    <div class="h-16 flex items-center justify-between bg-slate-900 border-b border-slate-800 px-4 shrink-0">
        <a href="{{ route('dashboard') }}"
           @click="if(window.innerWidth < 1024) sidebarOpen = false"
           class="flex items-center group cursor-pointer">
            <img src="{{ asset('images/simaset-logo.png') }}" class="h-8 w-auto">
            <span x-show="sidebarOpen" class="ml-3 font-bold text-xl text-white whitespace-nowrap">SIMASET</span>
        </a>

        <button @click="sidebarOpen = false" x-show="sidebarOpen" class="lg:hidden p-2 text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- MENU AREA --}}
    <nav class="flex-1 overflow-y-auto overflow-x-visible py-4 custom-scrollbar">
        <ul class="space-y-1 px-3">

            {{-- DASHBOARD --}}
            <li x-data="{ hover: false }" class="relative flex items-center">
                <a href="{{ route('dashboard') }}"
                   @mouseenter="hover = true" @mouseleave="hover = false"
                   @click="if(window.innerWidth < 1024) sidebarOpen = false"
                   class="flex-1 flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 group
                        {{ $activePage == 'dashboard' ? 'bg-teal-600/20 text-teal-400' : 'hover:bg-teal-900/40 hover:text-teal-400 text-slate-400' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <i class="fas fa-home text-lg" :class="!sidebarOpen ? '' : 'mr-3'"></i>
                    <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Dashboard</span>
                </a>

                {{-- TOOLTIP DASHBOARD --}}
                <template x-if="!sidebarOpen">
                    <div x-show="hover" x-cloak
                         class="fixed left-14 px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-md shadow-xl z-[9999] whitespace-nowrap pointer-events-none hidden lg:block">
                        Dashboard
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-teal-600"></div>
                    </div>
                </template>
            </li>

            {{-- Cek apakah user sudah login --}}
            @auth
                {{-- DATA USER (Hanya untuk Super Admin / Role 1) --}}
                @if(auth()->user()->role == 1)
                    <li x-data="{
                        open: {{ in_array($activePage ?? '', ['admin', 'pengaju']) ? 'true' : 'false' }},
                        hover: false
                    }" class="relative flex flex-col">

                        <button
                            @click="if(!sidebarOpen) { sidebarOpen = true; open = true; } else { open = !open }"
                            @mouseenter="hover = true"
                            @mouseleave="hover = false"
                            class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all group
                                {{ in_array($activePage ?? '', ['admin', 'pengaju']) ? 'text-teal-400 bg-teal-900/20' : 'hover:bg-teal-900/40 hover:text-teal-400' }}"
                            :class="!sidebarOpen ? 'justify-center' : ''">

                            <div class="flex items-center">
                                <i class="fas fa-users text-lg" :class="!sidebarOpen ? '' : 'mr-3'"></i>
                                <span x-show="sidebarOpen" x-transition.opacity class="font-medium">Data User</span>
                            </div>

                            {{-- Icon Panah --}}
                            <i x-show="sidebarOpen" class="fas fa-chevron-right text-xs transition-transform" :class="open ? 'rotate-90' : ''"></i>
                        </button>

                        {{-- TOOLTIP (Muncul saat sidebar tertutup & di-hover) --}}
                        <template x-if="!sidebarOpen">
                            <div x-show="hover" x-cloak
                                class="fixed left-14 px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-md shadow-xl z-[9999] whitespace-nowrap pointer-events-none hidden lg:block">
                                Data User
                                <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-teal-600"></div>
                            </div>
                        </template>

                        {{-- Submenu --}}
                        <div x-show="open && sidebarOpen"
                            x-collapse
                            x-cloak
                            class="pl-9 pr-2 mt-1 space-y-1">

                            <a href="{{ route('Admin.index') }}"
                            @click="if(window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3 py-2 px-3 text-sm rounded-md transition-colors {{ ($activePage ?? '') == 'admin' ? 'text-teal-300 bg-white/10' : 'text-slate-400 hover:text-teal-300' }}">
                                <i class="fas fa-user-cog w-4 text-center"></i>
                                <span>Admin</span>
                            </a>

                            <a href="{{ route('Pengaju.index') }}"
                            @click="if(window.innerWidth < 1024) sidebarOpen = false"
                            class="flex items-center gap-3 py-2 px-3 text-sm rounded-md transition-colors {{ ($activePage ?? '') == 'pengaju' ? 'text-teal-300 bg-white/10' : 'text-slate-400 hover:text-teal-300' }}">
                                <i class="fas fa-user-pen w-4 text-center"></i>
                                <span>Pengaju</span>
                            </a>
                        </div>
                    </li>
                @endif
            @endauth

            {{-- DATA BARANG --}}

            <li x-data="{ hover: false }" class="relative flex items-center">
                <a href="{{ route('barang.index') }}"
                @mouseenter="hover = true"
                @mouseleave="hover = false"
                @click="if(window.innerWidth < 1024) sidebarOpen = false"
                {{-- Perbaikan: Menggabungkan logic class ke dalam satu blok --}}
                class="flex-1 flex items-center py-2.5 px-3 rounded-lg transition-colors
                        {{ ($activePage ?? '') == 'barang' ? 'bg-teal-600/20 text-teal-400' : 'text-slate-400 hover:bg-teal-900/40 hover:text-teal-400' }}"
                :class="!sidebarOpen ? 'justify-center' : ''">

                    <i class="fas fa-boxes text-lg" :class="!sidebarOpen ? '' : 'mr-3'"></i>

                    <span x-show="sidebarOpen"
                        x-transition.opacity
                        class="font-medium whitespace-nowrap">
                        Data Barang
                    </span>
                </a>

                {{-- TOOLTIP DATA BARANG (Hanya muncul saat sidebar tertutup) --}}
                <template x-if="!sidebarOpen">
                    <div x-show="hover"
                        x-cloak
                        class="fixed left-14 px-3 py-1.5 bg-teal-600 text-white text-xs font-bold rounded-md shadow-xl z-[9999] whitespace-nowrap pointer-events-none hidden lg:block">
                        Data Barang
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-teal-600"></div>
                    </div>
                </template>
            </li>
            
        </ul>
    </nav>
</aside>
