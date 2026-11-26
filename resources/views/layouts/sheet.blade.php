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
@php
    $authUser = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SKENA')</title>
    <link rel="preload" href="{{ asset('fonts/outfit-400.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/outfit-700.woff2') }}" as="font" type="font/woff2" crossorigin>
    {{-- ?v= berubah setiap file diubah, agar browser tidak memakai CSS/JS lama dari cache --}}
    <link rel="stylesheet" href="{{ asset('css/skena-sheet.css') }}?v={{ filemtime(public_path('css/skena-sheet.css')) }}">
    {{-- Ikon Material Symbols Rounded (sama dengan Claude Design) --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0&display=block">
    @stack('styles')
</head>
<body>

@include('layouts.partials.sheet-icons')

@if(session('success') || session('error'))
    @php
        $sticky = str_contains((string) session('success'), 'Kata sandi awal');
    @endphp
    <div class="toast {{ session('error') ? 'err' : '' }}" role="status" data-toast @if($sticky) data-sticky @endif>
        <span class="ti msr">{{ session('error') ? 'error' : 'check' }}</span>
        <span>{{ session('success') ?? session('error') }}</span>
        @if($sticky)<button type="button" class="msr text-on-surface-muted" style="font-size:20px" aria-label="Tutup" data-toast-close>close</button>@endif
    </div>
@endif

{{-- DRAWER NAVIGASI (Claude Design: Header.dc.html) --}}
@php
    $navItem = fn ($label, $icon, $href, $on = false) =>
        '<a class="'.($on ? 'on' : '').'" href="'.e($href).'"'.($on ? ' aria-current="page"' : '').'><span class="msr">'.$icon.'</span>'.e($label).'</a>';
@endphp
<div class="scrim" id="scrim"></div>
<aside class="drawer" id="drawer" aria-label="Menu utama" aria-hidden="true">
    <div class="dh">
        <a class="logo" href="{{ route('dashboard') }}">SKENA</a>
        <button class="circ" id="closeDrawer" aria-label="Tutup menu"><span class="msr">close</span></button>
    </div>
    <nav>
        @section('drawer-nav')
            @auth
                {!! $navItem('Beranda', 'home', route('dashboard'), request()->routeIs('dashboard')) !!}

                {{-- PBI-11: Menu Pesanan --}}
                {!! $navItem('Pesanan', 'receipt_long', route('orders.index'), request()->routeIs('orders.index', 'orders.show')) !!}

                {{-- PBI-11: Menu Request Masuk khusus penyedia jasa --}}
                @if($authUser->isFreelancer())
                    {!! $navItem('Request Masuk', 'inbox', route('orders.incoming'), request()->routeIs('orders.incoming')) !!}
                @endif

                {!! $navItem('Chat', 'chat_bubble', route('messages.index'), request()->routeIs('messages.*')) !!}
                {!! $navItem('Wishlist', 'favorite', route('favorites.index'), request()->routeIs('favorites.*')) !!}
                {!! $navItem('Jasa Saya', 'storefront', route('services.index'), request()->routeIs('services.*')) !!}
                {!! $navItem('Portofolio', 'folder_open', route('portfolios.index'), request()->routeIs('portfolios.*')) !!}
                {!! $navItem('Profil', 'person', route('profile.index'), request()->routeIs('profile.*')) !!}
            @else
                {!! $navItem('Beranda', 'home', route('dashboard'), request()->routeIs('dashboard')) !!}
                {!! $navItem('Jelajahi Jasa', 'search', route('home'), request()->routeIs('home')) !!}
                {!! $navItem('Masuk', 'login', route('login'), request()->routeIs('login')) !!}
                {!! $navItem('Daftar Akun', 'person_add', route('register'), request()->routeIs('register')) !!}
            @endauth
        @show
        @auth
            <form method="POST" action="{{ route('logout') }}" data-confirm-logout>
                @csrf
                <button type="submit"><span class="msr">logout</span>Keluar</button>
            </form>
        @endauth
    </nav>
</aside>

<div class="sheet">

    {{-- TOP BAR --}}
    <header class="topbar">
        <div>
            <button class="circ" id="openDrawer" aria-label="Buka menu" aria-controls="drawer" aria-expanded="false">
                <span class="msr" style="font-size:22px">menu</span>
            </button>
        </div>
        <a class="logo" href="{{ route('dashboard') }}" aria-label="SKENA">SKENA</a>
        <div class="top-right">
            <a class="lnk" href="#">Bantuan</a>
            @auth
                @php
                    $unread = (int) ($unreadNotifications ?? 0);
                    $initials = collect(preg_split('/\s+/', trim($authUser->name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                    $userMeta = $authUser->isAdmin()
                        ? $authUser->email
                        : (collect([$authUser->major, $authUser->angkatan])->filter()->implode(' · ') ?: $authUser->email);
                @endphp
                <a class="lnk" href="{{ route('notifications.index') }}">Notifikasi</a>
                <div class="menu-wrap">
                    <button class="circ" id="bellBtn" aria-label="Notifikasi" aria-haspopup="true" aria-expanded="false" aria-controls="bellMenu">
                        <span class="msr" style="font-size:21px">notifications</span>
                        @if($unread > 0)<span class="badge">{{ $unread }}</span>@endif
                    </button>
                    <div class="dropdown bell" id="bellMenu">
                        <div class="bh"><b>Notifikasi</b><span>{{ $unread }} belum dibaca</span></div>
                        <div class="bempty">Belum ada notifikasi baru.</div>
                        <a class="ball" href="{{ route('notifications.index') }}">Lihat Semua</a>
                    </div>
                </div>
                <div class="menu-wrap">
                    <button class="circ ava-btn" id="profileBtn" aria-label="Akun" aria-haspopup="true" aria-expanded="false" aria-controls="profileMenu">{{ $initials }}</button>
                    <div class="dropdown" id="profileMenu">
                        <div class="who">
                            <b>{{ $authUser->name }}</b>
                            <span>{{ $userMeta }}</span>
                        </div>
                        <a href="{{ route('profile.index') }}"><span class="msr">person</span>Profil Saya</a>
                        <a href="{{ route('dashboard') }}#ringkasan"><span class="msr">sync_alt</span>Ganti Peran</a>
                        <form method="POST" action="{{ route('logout') }}" data-confirm-logout>
                            @csrf
                            <button type="submit"><span class="msr">logout</span>Keluar</button>
                        </form>
                    </div>
                </div>
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
            <a href="{{ route('dashboard') }}">Tentang</a>
            <a href="#">Syarat &amp; Ketentuan</a>
            <a href="#">Bantuan</a>
        </nav>
        <span>&copy; {{ date('Y') }} SKENA · Skill Exchange Network</span>
    </footer>
</div>

{{-- TIM-B tb-3: konfirmasi keluar (dibuka oleh form[data-confirm-logout]) --}}
@auth
<div class="dialog-wrap" id="logoutDialog" hidden>
    <div class="dialog" role="alertdialog" aria-modal="true" aria-labelledby="logoutTitle" aria-describedby="logoutDesc">
        <span class="di"><svg class="ic" style="width:24px;height:24px"><use href="#i-out"/></svg></span>
        <h2 id="logoutTitle">Yakin ingin keluar?</h2>
        <p id="logoutDesc">Kamu bisa masuk lagi kapan saja.</p>
        <div class="acts">
            <button type="button" class="btn-line" data-cancel>Batal</button>
            <button type="button" class="btn-primary" data-ok>Keluar</button>
        </div>
    </div>
</div>
@endauth

<script src="{{ asset('js/skena-sheet.js') }}?v={{ filemtime(public_path('js/skena-sheet.js')) }}" defer></script>
@stack('scripts')
</body>
</html>
