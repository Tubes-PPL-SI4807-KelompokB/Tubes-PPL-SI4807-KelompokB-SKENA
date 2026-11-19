<nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#2C99D6">
                    <span class="text-white font-black text-sm">S</span>
                </div>
                <span class="text-lg font-black tracking-tight" style="color:#0F2942">SKENA</span>
                <span class="hidden sm:inline text-xs text-gray-400 font-medium border border-gray-200 rounded-full px-2 py-0.5">Beta</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-brand-blue transition-colors">Jelajahi</a>
                @auth
                    @if(auth()->user()->isFreelancer())
                        <a href="{{ route('services.index') }}" class="hover:text-brand-blue transition-colors">Jasa Saya</a>
                    @endif
                    @if(auth()->user()->isClient())
                        <a href="{{ route('orders.index') }}" class="hover:text-brand-blue transition-colors">Pesanan Saya</a>
                    @endif
                @endauth
            </div>

            {{-- Auth Buttons --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Notification Bell --}}
                    <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400 hover:text-brand-blue transition-colors rounded-lg hover:bg-blue-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 rounded-full border border-gray-200 pl-2 pr-3 py-1.5 hover:border-brand-blue transition-colors">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-7 h-7 rounded-full object-cover">
                            <span class="text-sm font-medium text-gray-700 max-w-24 truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-50">

                            {{-- User Info --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isVerifiedStudent())
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 rounded-full px-2 py-0.5 mt-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Mahasiswa Terverifikasi
                                    </span>
                                @elseif(auth()->user()->student_verification_status === 'pending')
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 rounded-full px-2 py-0.5 mt-1">
                                        ⏳ Verifikasi Pending
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-brand-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-brand-blue transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil Saya
                            </a>

                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-brand-blue hover:bg-blue-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Panel Admin
                            </a>
                            @endif

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-brand-red hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-brand-blue transition-colors px-3 py-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white px-4 py-2 rounded-lg transition-all hover:opacity-90" style="background:#2C99D6">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
