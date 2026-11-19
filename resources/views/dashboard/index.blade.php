@extends('layouts.app')
@section('title', 'Dashboard — SKENA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <img src="{{ $user->avatar_url }}"
                 alt="{{ $user->name }}"
                 class="w-14 h-14 rounded-full object-cover ring-2 ring-white shadow-md">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-black text-gray-900">Halo, {{ $user->name }}!</h1>
                    @if($user->isVerifiedStudent())
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-white px-2 py-0.5 rounded-full" style="background:#2C99D6">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Mahasiswa Terverifikasi
                        </span>
                    @elseif($user->student_verification_status === 'pending')
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">
                            ⏳ Verifikasi Pending
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">
                    {{ $user->role?->display_name ?? 'Pengguna' }} &middot; {{ $user->email }}
                </p>
            </div>
        </div>

        @if($user->isFreelancer())
        <a href="{{ route('services.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 shadow-sm"
           style="background:#2C99D6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Jasa Baru
        </a>
        @else
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 shadow-sm"
           style="background:#EB2E33">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari Jasa Kreatif
        </a>
        @endif
    </div>

    {{-- Verifikasi Mahasiswa Banner (jika belum terverifikasi) --}}
    @if($user->isFreelancer() && !$user->isVerifiedStudent())
    <div class="rounded-2xl p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 border"
         style="background:linear-gradient(135deg,#EFF8FF,#DBEAFE); border-color:#BFDBFE">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#2C99D6">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div class="flex-1">
            @if($user->student_verification_status === 'pending')
                <p class="font-bold text-sm text-blue-900">KTM Anda Sedang Diperiksa ⏳</p>
                <p class="text-xs text-blue-700 mt-0.5">Tim verifikator SKENA sedang meninjau dokumen kemahasiswaan Anda. Proses 1–2 hari kerja.</p>
            @elseif($user->student_verification_status === 'rejected')
                <p class="font-bold text-sm text-red-900">Verifikasi Ditolak</p>
                <p class="text-xs text-red-700 mt-0.5">{{ $user->verification_notes ?? 'Mohon upload ulang KTM yang valid dan terbaca.' }}</p>
            @else
                <p class="font-bold text-sm text-blue-900">Dapatkan Badge Mahasiswa Terverifikasi! 🎓</p>
                <p class="text-xs text-blue-700 mt-0.5">Profil terverifikasi mendapatkan 3× lebih banyak klik dari klien. Lengkapi data kemahasiswaan Anda.</p>
            @endif
        </div>
        @if($user->student_verification_status !== 'pending')
        <a href="{{ route('profile.edit', $user->id) }}"
           class="shrink-0 px-4 py-2 text-xs font-bold text-white rounded-lg transition-all hover:opacity-90"
           style="background:#2C99D6">
            Lengkapi Data
        </a>
        @endif
    </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @if($user->isFreelancer())
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Jasa Aktif</p>
            <p class="text-3xl font-black" style="color:#2C99D6">{{ $stats['published_services'] }}</p>
            <a href="{{ route('services.index') }}" class="text-xs text-gray-400 hover:text-brand-blue mt-1 inline-block">Kelola jasa →</a>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Order Aktif</p>
            <p class="text-3xl font-black text-gray-900">{{ $stats['active_orders'] }}</p>
            <a href="{{ route('orders.index') }}" class="text-xs text-gray-400 hover:text-brand-blue mt-1 inline-block">Lihat pesanan →</a>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Selesai</p>
            <p class="text-3xl font-black text-green-600">{{ $stats['completed_orders'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Proyek berhasil</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Pendapatan</p>
            <p class="text-2xl font-black" style="color:#EB2E33">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Sepanjang waktu</p>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Order Aktif</p>
            <p class="text-3xl font-black" style="color:#2C99D6">{{ $stats['active_orders'] }}</p>
            <a href="{{ route('orders.index') }}" class="text-xs text-gray-400 hover:text-brand-blue mt-1 inline-block">Lihat pesanan →</a>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Selesai</p>
            <p class="text-3xl font-black text-green-600">{{ $stats['completed_orders'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Proyek berhasil</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Disimpan</p>
            <p class="text-3xl font-black text-gray-900">{{ $stats['saved_freelancers'] }}</p>
            <a href="{{ route('favorites.index') }}" class="text-xs text-gray-400 hover:text-brand-blue mt-1 inline-block">Lihat favorit →</a>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Dibelanjakan</p>
            <p class="text-2xl font-black" style="color:#EB2E33">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Sepanjang waktu</p>
        </div>
        @endif
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-4">
                @forelse($recentActivities as $act)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 hover:bg-blue-50 transition-colors">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5"
                         style="background:{{ $act['type'] === 'success' ? '#D1FAE5' : ($act['type'] === 'warning' ? '#FEF3C7' : '#DBEAFE') }}">
                        @if($act['type'] === 'success')
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $act['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $act['description'] }}</p>
                    </div>
                    <span class="text-xs text-gray-400 shrink-0">{{ $act['time'] }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide mb-4">Menu Cepat</h2>
            <div class="space-y-2">
                @if($user->isFreelancer())
                <a href="{{ route('services.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Tambah Jasa Baru</span>
                </a>
                <a href="{{ route('portfolios.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Kelola Portofolio</span>
                </a>
                @else
                <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#FFF5F5">
                        <svg class="w-4 h-4" style="color:#EB2E33" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-600">Cari Freelancer</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Freelancer Favorit</span>
                </a>
                @endif

                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Edit Profil</span>
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Riwayat Pesanan</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#EFF8FF">
                        <svg class="w-4 h-4" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-brand-blue">Notifikasi</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
