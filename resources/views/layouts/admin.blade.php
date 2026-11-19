{{--
    Layout panel admin = layout lembar (DESIGN.md: tanpa sidebar tetap, navigasi lewat drawer)
    + utilitas Tailwind. Halaman admin tetap memakai @section('page-title'),
    @section('page-subtitle'), dan @section('content').
--}}
@extends('layouts.sheet')

@section('title')@yield('page-title', 'Dashboard Admin') — Admin SKENA @endsection

@push('styles')
    @include('layouts.partials.design-tokens')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@endpush

@section('drawer-nav')
    <span class="grp">Utama</span>
    <a class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}" href="{{ route('admin.dashboard') }}"><span><svg class="ic"><use href="#i-grid"/></svg>Dashboard</span></a>

    <span class="grp">Manajemen</span>
    <a class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}" href="{{ route('admin.users.index') }}"><span><svg class="ic"><use href="#i-user"/></svg>Pengguna</span></a>
    <a class="{{ request()->routeIs('admin.roles.*') ? 'on' : '' }}" href="{{ route('admin.roles.index') }}"><span><svg class="ic"><use href="#i-shield"/></svg>Role &amp; Hak Akses</span></a>
    <a class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}" href="{{ route('admin.categories.index') }}"><span><svg class="ic"><use href="#i-folder"/></svg>Kategori Jasa</span></a>
    <a class="{{ request()->routeIs('admin.vouchers.*') ? 'on' : '' }}" href="{{ route('admin.vouchers.index') }}"><span><svg class="ic"><use href="#i-receipt"/></svg>Voucher</span></a>

    <span class="grp">Moderasi</span>
    <a class="{{ request()->routeIs('admin.moderasi.*') ? 'on' : '' }}" href="{{ route('admin.moderasi.index') }}"><span><svg class="ic"><use href="#i-verified"/></svg>Moderasi Konten</span></a>
    <a class="{{ request()->routeIs('admin.logs.*') ? 'on' : '' }}" href="{{ route('admin.logs.index') }}"><span><svg class="ic"><use href="#i-clip"/></svg>Log Aktivitas</span></a>

    <span class="grp">Situs</span>
    <a href="{{ route('home') }}"><span><svg class="ic"><use href="#i-compass"/></svg>Lihat Situs</span></a>
@endsection

@section('page-head')
    <div class="page-head">
        <div>
            <div class="kicker">Panel Admin</div>
            <h1>@yield('page-title', 'Dashboard Admin')</h1>
            <p>@yield('page-subtitle', 'Kelola dan pantau ekosistem jasa mahasiswa SKENA.')</p>
        </div>
        <a class="btn-line" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat Situs <svg class="ic sm"><use href="#i-upright"/></svg></a>
    </div>
@endsection
