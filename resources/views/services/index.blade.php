@extends('layouts.app')

@section('title', 'Kelola Jasa Saya - SKENA')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">

    <!-- Sidebar Navigasi Profil Mahasiswa (Sesuai Mockup Gambar 4.5.5 - 4.5.7) -->
    <aside class="w-full lg:w-56 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-slate-200 p-3 shadow-sm space-y-1">
            <a href="{{ Route::has('profile.index') ? route('profile.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Profil
            </a>
            <a href="{{ Route::has('skills.index') ? route('skills.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Skill
            </a>
            <a href="{{ route('services.index') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold text-blue-600 bg-blue-50 rounded-xl transition">
                Jasa saya
            </a>
            <a href="{{ route('portfolios.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Portfolio
            </a>
            <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 transition">
                Pesanan masuk
            </a>
        </div>
    </aside>

    <!-- Main Content Area: Kelola Jasa -->
    <div class="flex-1 space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Jasa Saya</h1>
            <p class="text-sm text-slate-500 mt-1">
                PBI-04: Buat, ubah, hapus, dan aktifkan/nonaktifkan jasa keterampilan yang kamu tawarkan ke sesama mahasiswa.
            </p>
        </div>
        <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Jasa Baru
        </a>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Jasa Terdaftar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalServices }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Jasa Aktif (Tampil di Katalog)</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeServices }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Jasa Nonaktif (Ditutup Sementara)</p>
                <p class="text-2xl font-bold text-slate-500 mt-1">{{ $inactiveServices }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('services.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex-1 flex flex-wrap items-center gap-3">
                <!-- Search input -->
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi jasa..." 
                           class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Category dropdown -->
                <select name="category_id" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->category_id }}" {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                <!-- Status filter pills -->
                <select name="status" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                    Terapkan Filter
                </button>
                @if (request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('services.index') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-slate-800 rounded-xl hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Services Grid -->
    @if ($services->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <h3 class="font-semibold text-slate-800 text-base">Belum ada jasa yang ditemukan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Silakan buat jasa baru untuk mulai menawarkan keterampilanmu kepada mahasiswa lain di kampus.
            </p>
            <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                + Buat Jasa Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($services as $service)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:border-slate-300 transition">
                    <div>
                        <!-- Header Card: Category & Status Badge -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                {{ $service->category?->nama_kategori ?? 'Umum' }}
                            </span>

                            @if ($service->status === 'aktif')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>

                        <!-- Service Title -->
                        <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-1">
                            {{ $service->judul }}
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                            {{ $service->deskripsi }}
                        </p>

                        <!-- Key Specs: Price & Duration -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Harga Jasa</span>
                                <span class="font-bold text-sm text-blue-600">{{ $service->formatted_harga }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 block font-medium">Durasi Pengerjaan</span>
                                <span class="font-semibold text-slate-700">{{ $service->durasi }} hari</span>
                            </div>
                        </div>

                        <!-- Associated Portfolios Count -->
                        <div class="mt-2.5 flex items-center gap-1.5 text-[11px] text-slate-400">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span>{{ $service->portfolios->count() }} karya tertaut di portofolio</span>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                        <!-- Toggle Status Button (Operasi 'aktifkan()') -->
                        <form action="{{ route('services.toggle-status', $service) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-1.5 rounded-lg border text-[11px] font-semibold transition {{ $service->status === 'aktif' ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                {{ $service->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('services.show', $service) }}" class="text-slate-600 hover:text-slate-900 font-medium px-2 py-1 rounded hover:bg-slate-100">
                                Detail
                            </a>
                            <a href="{{ route('services.edit', $service) }}" class="text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50">
                                Edit
                            </a>
                            <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus jasa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium px-2 py-1 rounded hover:bg-rose-50">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @endif

    </div>
</div>
@endsection
