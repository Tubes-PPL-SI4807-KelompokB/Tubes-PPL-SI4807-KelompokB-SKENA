<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — SKENA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: { blue: '#2C99D6', red: '#EB2E33', light: '#F8FAFC', dark: '#0F2942' }
            }
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="font-sans antialiased" style="background:#F8FAFC; font-family:'Plus Jakarta Sans',sans-serif">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-60 shrink-0 flex flex-col shadow-sm border-r border-gray-100" style="background:#0F2942">

        {{-- Logo --}}
        <div class="flex items-center gap-2 px-5 py-5 border-b border-white/10">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#2C99D6">
                <span class="text-white font-black text-sm">S</span>
            </div>
            <div>
                <span class="text-white font-black text-base tracking-tight">SKENA</span>
                <p class="text-blue-300 text-xs font-medium leading-none">Admin Panel</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            <p class="text-xs font-bold text-blue-400 uppercase tracking-wider px-3 mb-2">Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                Dashboard
            </a>

            <p class="text-xs font-bold text-blue-400 uppercase tracking-wider px-3 mb-2 mt-4">Manajemen</p>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.users.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Pengguna
            </a>

            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.roles.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Role & Hak Akses
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.categories.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Kategori Jasa
            </a>

            <a href="{{ route('admin.vouchers.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.vouchers.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Voucher
            </a>

            <p class="text-xs font-bold text-blue-400 uppercase tracking-wider px-3 mb-2 mt-4">Moderasi</p>

            <a href="{{ route('admin.moderasi.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.moderasi.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Moderasi Konten
            </a>

            <a href="{{ route('admin.logs.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all
                      {{ request()->routeIs('admin.logs.*') ? 'bg-white/15 text-white' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Log Aktivitas
            </a>
        </nav>

        {{-- User Info --}}
        <div class="border-t border-white/10 p-4 flex items-center gap-3">
            <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-blue-300 text-xs truncate">Administrator</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-300 hover:text-white transition-colors" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Area --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-base font-black text-gray-900">@yield('page-title', 'Dashboard Admin')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', 'Platform SKENA — Kelola dan pantau ekosistem jasa kreatif mahasiswa')</p>
            </div>
            <div class="flex items-center gap-3">
                @if(session('success'))
                <div class="text-xs text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-1.5">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="text-xs text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-1.5">
                    {{ session('error') }}
                </div>
                @endif
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-gray-400 hover:text-brand-blue font-medium">Lihat Situs ↗</a>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
