{{--
    Layout "lembar" SKENA (DESIGN.md): lembar putih mengambang di atas kanvas gradien,
    header dengan logo di tengah, navigasi lewat drawer kiri dari hamburger.
    Acuan: PPL DESIGN CONSEP/skena-dashboard-v2.html

    Dipakai langsung oleh dashboard, dan lewat layouts.app (halaman PBI, beranda)
    serta layouts.admin (panel admin).

    Slot:
      @section('drawer-nav')  isi menu drawer (bawaan: menu mahasiswa / tamu)
      @section('page-head')   judul halaman di atas konten (opsional)
      @section('content')     isi halaman
--}}
@php($authUser = auth()->user())
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SKENA')</title>
    <link rel="preload" href="{{ asset('fonts/outfit-400.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/outfit-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/skena-sheet.css') }}">
    @stack('styles')
</head>
<body>

@include('layouts.partials.sheet-icons')

@if(session('success') || session('error'))
    <div class="toast {{ session('error') ? 'err' : '' }}" role="status" data-toast>
        <svg class="ic"><use href="#{{ session('error') ? 'i-x' : 'i-check' }}"/></svg>
        <span>{{ session('success') ?? session('error') }}</span>
    </div>
@endif

{{-- DRAWER NAVIGASI --}}
<div class="scrim" id="scrim"></div>
<aside class="drawer" id="drawer" aria-label="Menu utama" aria-hidden="true">
    <div>
        <div class="dh">
            <a class="logo" href="{{ $authUser ? route('dashboard') : route('home') }}">SKENA</a>
            <button class="circ" id="closeDrawer" aria-label="Tutup menu"><svg class="ic"><use href="#i-x"/></svg></button>
        </div>
        <nav>
            @section('drawer-nav')
                @auth
                    <a class="{{ request()->routeIs('dashboard') ? 'on' : '' }}" href="{{ route('dashboard') }}"><span><svg class="ic"><use href="#i-grid"/></svg>Dashboard</span></a>
                    <a class="{{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}"><span><svg class="ic"><use href="#i-compass"/></svg>Jelajahi Jasa</span></a>
                    <a class="{{ request()->routeIs('orders.*') ? 'on' : '' }}" href="{{ route('orders.index') }}"><span><svg class="ic"><use href="#i-receipt"/></svg>Pesanan</span></a>
                    <a class="{{ request()->routeIs('requests.*') ? 'on' : '' }}" href="{{ route('requests.index') }}"><span><svg class="ic"><use href="#i-clip"/></svg>Request Jasa</span></a>
                    <a class="{{ request()->routeIs('services.*') ? 'on' : '' }}" href="{{ route('services.index') }}"><span><svg class="ic"><use href="#i-store"/></svg>Jasa Saya</span></a>
                    <a class="{{ request()->routeIs('payments.*') ? 'on' : '' }}" href="{{ route('payments.index') }}"><span><svg class="ic"><use href="#i-wallet"/></svg>Pembayaran</span></a>
                    <a class="{{ request()->routeIs('notifications.*') ? 'on' : '' }}" href="{{ route('notifications.index') }}"><span><svg class="ic"><use href="#i-bell"/></svg>Notifikasi</span></a>
                    <a class="{{ request()->routeIs('profile.*') ? 'on' : '' }}" href="{{ route('profile.index') }}"><span><svg class="ic"><use href="#i-profile"/></svg>Profil</span></a>
                    @if($authUser->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"><span><svg class="ic"><use href="#i-shield"/></svg>Panel Admin</span></a>
                    @endif
                @else
                    <a class="{{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}"><span><svg class="ic"><use href="#i-compass"/></svg>Jelajahi Jasa</span></a>
                    <a href="{{ route('login') }}"><span><svg class="ic"><use href="#i-user"/></svg>Masuk</span></a>
                    <a href="{{ route('register') }}"><span><svg class="ic"><use href="#i-plus"/></svg>Daftar Akun</span></a>
                @endauth
            @show
        </nav>
    </div>
    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="out"><svg class="ic"><use href="#i-out"/></svg>Keluar</button>
        </form>
    @endauth
</aside>

<div class="sheet">

    {{-- TOP BAR --}}
    <header class="topbar">
        <div>
            <button class="circ plain" id="openDrawer" aria-label="Buka menu" aria-controls="drawer" aria-expanded="false">
                <svg class="ic" style="width:26px;height:26px"><use href="#i-menu"/></svg>
            </button>
        </div>
        <a class="logo" href="{{ $authUser ? route('dashboard') : route('home') }}" aria-label="SKENA">SKENA</a>
        <div class="top-right">
            @auth
                <a class="lnk" href="#">Bantuan</a>
                <a class="lnk" href="{{ route('notifications.index') }}">Notifikasi</a>
                <a class="circ" href="{{ route('notifications.index') }}" aria-label="Notifikasi">
                    <svg class="ic"><use href="#i-bell"/></svg>
                    @if(($unreadNotifications ?? 0) > 0)
                        <span class="badge">{{ $unreadNotifications }}</span>
                    @endif
                </a>
                <div class="menu-wrap">
                    <button class="circ" id="profileBtn" aria-label="Menu profil" aria-haspopup="true" aria-expanded="false" aria-controls="profileMenu">
                        <img src="{{ $authUser->avatar_url }}" alt="">
                    </button>
                    <div class="dropdown" id="profileMenu">
                        <div class="who">
                            <b>{{ $authUser->name }}</b>
                            <span>{{ $authUser->email }}</span>
                            @if($authUser->isVerifiedStudent())
                                <span class="verified"><svg class="ic xs"><use href="#i-verified"/></svg>Mahasiswa Terverifikasi</span>
                            @endif
                        </div>
                        <a href="{{ route('profile.index') }}"><svg class="ic sm"><use href="#i-user"/></svg>Profil Saya</a>
                        @if($authUser->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"><svg class="ic sm"><use href="#i-shield"/></svg>Panel Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="danger"><svg class="ic sm"><use href="#i-out"/></svg>Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a class="lnk" href="#">Bantuan</a>
                <a class="btn-line" href="{{ route('login') }}">Masuk</a>
                <a class="btn-primary" href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </header>

    <main class="sheet-main">
        @yield('page-head')
        @yield('content')
    </main>

    <footer>
        <span class="logo">SKENA</span>
        <nav>
            <a href="{{ route('home') }}">Eksplorasi</a>
            <a href="#">Pusat Bantuan</a>
            <a href="#">Privasi &amp; Keamanan Kampus</a>
        </nav>
        <span>&copy; {{ date('Y') }} SKENA. Eksklusif mahasiswa kampus terverifikasi.</span>
    </footer>
</div>

<script src="{{ asset('js/skena-sheet.js') }}" defer></script>
@stack('scripts')
</body>
</html>
