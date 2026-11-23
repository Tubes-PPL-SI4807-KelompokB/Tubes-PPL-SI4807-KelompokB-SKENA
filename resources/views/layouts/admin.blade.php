{{--
    Layout panel admin = layout lembar (DESIGN.md: tanpa sidebar tetap, navigasi lewat drawer)
    + utilitas Tailwind. Halaman admin tetap memakai @section('page-title'),
    @section('page-subtitle'), dan @section('content').
--}}
@extends('layouts.sheet')

@section('title')@yield('page-title', 'Dashboard Admin') — Admin SKENA @endsection

@push('styles')
    @include('layouts.partials.design-tokens')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.14.1/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@endpush

@section('drawer-nav')
    <a class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}" href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif><span class="msr">space_dashboard</span>Dashboard Admin</a>
    <a class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}" href="{{ route('admin.users.index') }}" @if(request()->routeIs('admin.users.*')) aria-current="page" @endif><span class="msr">group</span>Pengguna</a>
    <a class="{{ request()->routeIs('admin.roles.*') ? 'on' : '' }}" href="{{ route('admin.roles.index') }}" @if(request()->routeIs('admin.roles.*')) aria-current="page" @endif><span class="msr">admin_panel_settings</span>Role &amp; Hak Akses</a>
    <a class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}" href="{{ route('admin.categories.index') }}" @if(request()->routeIs('admin.categories.*')) aria-current="page" @endif><span class="msr">category</span>Kategori Jasa</a>
    <a class="{{ request()->routeIs('admin.vouchers.*') ? 'on' : '' }}" href="{{ route('admin.vouchers.index') }}" @if(request()->routeIs('admin.vouchers.*')) aria-current="page" @endif><span class="msr">confirmation_number</span>Voucher/Promo</a>
@endsection

@section('page-head')
    <div class="page-head">
        <div>
            <div class="kicker">Panel Admin</div>
            <h1>@yield('page-title', 'Dashboard Admin')</h1>
            <p>@yield('page-subtitle', 'Kelola dan pantau ekosistem jasa mahasiswa SKENA.')</p>
        </div>
        <a class="btn-line" href="{{ route('dashboard') }}" target="_blank" rel="noopener">Lihat Situs <svg class="ic sm"><use href="#i-upright"/></svg></a>
    </div>
@endsection
