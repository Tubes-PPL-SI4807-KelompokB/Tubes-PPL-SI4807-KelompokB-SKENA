<nav class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center border-2 border-slate-900 bg-white shadow-[2px_2px_0px_0px_#0F2942]">
                        <span class="text-slate-950 font-black text-sm">S</span>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-lg font-black tracking-tight text-slate-950">SKENA</span>
                        <span class="hidden sm:inline text-[10px] font-mono text-slate-400 uppercase tracking-widest">Marketplace</span>
                    </div>
                </a>

                {{-- Left Navigation Links --}}
                <div class="hidden md:flex items-center gap-5 text-xs font-bold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-slate-950 transition-colors {{ request()->routeIs('home') ? 'text-slate-950' : '' }}">
                        Katalog Jasa
                    </a>
                    @auth
                        @if(auth()->user()->isFreelancer())
                            <a href="{{ route('services.index') }}" class="hover:text-slate-950 transition-colors">Jasa Saya</a>
                            <a href="{{ route('portfolios.index') }}" class="hover:text-slate-950 transition-colors">Portofolio</a>
                        @endif
                        @if(auth()->user()->isClient())
                            <a href="{{ route('orders.index') }}" class="hover:text-slate-950 transition-colors">Pesanan Aktif</a>
                            <a href="{{ route('favorites.index') }}" class="hover:text-slate-950 transition-colors">Tersimpan</a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right Navigation / Auth Area --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Notification Bell --}}
                    <a href="{{ route('notifications.index') }}" class="relative p-2 text-slate-400 hover:text-slate-950 transition-colors rounded-xl hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 rounded-xl border border-slate-200 pl-2 pr-3 py-1.5 hover:border-slate-900 transition-colors bg-white">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-7 h-7 rounded-lg object-cover border border-slate-200">
                            <span class="text-xs font-bold text-slate-900 max-w-28 truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-60 bg-white border-2 border-slate-900 rounded-2xl shadow-[4px_4px_0px_0px_#0F2942] py-1.5 z-50">

                            {{-- User Info Header --}}
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs font-black text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isVerifiedStudent())
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-white px-2 py-0.5 rounded-full mt-1.5" style="background:#2C99D6">
                                        ✓ Mahasiswa Terverifikasi
                                    </span>
                                @elseif(auth()->user()->student_verification_status === 'pending')
                                    <span class="inline-flex items-center text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full mt-1.5">
                                        ⏳ Verifikasi Pending
                                    </span>
                                @endif
                            </div>

                            <div class="py-1 text-xs font-medium text-slate-700">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 hover:text-slate-950 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 hover:text-slate-950 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil & KTM
                                </a>

                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-brand-blue font-bold hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Panel Admin
                                </a>
                                @endif
                            </div>

                            <div class="border-t border-slate-100 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-slate-950 transition-colors px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-xs font-bold text-white px-4 py-2 rounded-xl transition-transform active:translate-y-0.5 shadow-sm"
                       style="background:#2C99D6">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
