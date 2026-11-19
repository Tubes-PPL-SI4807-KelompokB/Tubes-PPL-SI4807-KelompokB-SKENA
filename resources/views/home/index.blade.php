@extends('layouts.app')
@section('title', 'SKENA — Marketplace Jasa Kreatif Mahasiswa')

@section('content')

{{-- 1. Hero Section --}}
<section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28" style="background: radial-gradient(circle at 10% 20%, rgba(44,153,214,0.08) 0%, rgba(248,250,252,1) 90%);">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-10 right-10 w-72 h-72 rounded-full opacity-30 blur-3xl pointer-events-none" style="background:#2C99D6"></div>
    <div class="absolute bottom-10 left-5 w-60 h-60 rounded-full opacity-20 blur-3xl pointer-events-none" style="background:#EB2E33"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-10">

            {{-- Trust Badge --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold mb-6 border shadow-sm"
                 style="background:#FFFFFF; border-color:#BFDBFE; color:#0F2942;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#2C99D6"></span>
                <span class="text-brand-blue font-bold">100%</span> Terverifikasi Mahasiswa Kampus Indonesia
            </div>

            {{-- Main Title --}}
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-tight mb-6">
                Eksplorasi Jasa Kreatif dari
                <span class="relative inline-block text-white px-3 py-1 rounded-2xl mx-1" style="background:linear-gradient(135deg,#2C99D6 0%,#0F2942 100%)">
                    Mahasiswa Berbakat
                </span>
            </h1>

            <p class="text-base sm:text-lg text-gray-600 leading-relaxed mb-8 max-w-2xl mx-auto font-normal">
                Dapatkan logo profesional, konten media sosial, animasi 3D, hingga UI/UX website dari talenta muda kreatif dengan harga bersahabat dan garansi transaksi aman.
            </p>

            {{-- Search Bar --}}
            <div class="bg-white p-2.5 rounded-2xl shadow-lg border border-gray-100 max-w-2xl mx-auto flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text"
                           placeholder="Coba cari 'Desain Logo', 'Video Reels', 'Figma UI/UX'..."
                           class="w-full px-3 py-2 text-sm text-gray-900 placeholder-gray-400 outline-none bg-transparent">
                </div>
                <button type="button"
                        class="px-7 py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-sm flex items-center justify-center gap-2"
                        style="background:#2C99D6">
                    <span>Cari Jasa</span>
                </button>
            </div>

            {{-- Trending Tags --}}
            <div class="flex flex-wrap items-center justify-center gap-2 mt-5 text-xs text-gray-500">
                <span class="font-semibold text-gray-700">Populer:</span>
                <a href="#" class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:text-brand-blue transition-all">Desain Logo</a>
                <a href="#" class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:text-brand-blue transition-all">Video TikTok / Reels</a>
                <a href="#" class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:text-brand-blue transition-all">Figma UI/UX</a>
                <a href="#" class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:text-brand-blue transition-all">Ilustrasi Vektor</a>
                <a href="#" class="px-2.5 py-1 rounded-lg bg-white border border-gray-200 hover:border-blue-400 hover:text-brand-blue transition-all">Render 3D</a>
            </div>
        </div>

        {{-- Metrics Bar --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8 border-t border-gray-200/60">
            <div class="text-center p-3">
                <p class="text-2xl sm:text-3xl font-black" style="color:#2C99D6">1.500+</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Mahasiswa Terdaftar</p>
            </div>
            <div class="text-center p-3">
                <p class="text-2xl sm:text-3xl font-black text-gray-900">120+</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Kampus se-Indonesia</p>
            </div>
            <div class="text-center p-3">
                <p class="text-2xl sm:text-3xl font-black" style="color:#EB2E33">4.9 / 5.0</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Rating Kepuasan Klien</p>
            </div>
            <div class="text-center p-3">
                <p class="text-2xl sm:text-3xl font-black text-green-600">100%</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Escrow & Garansi Uang</p>
            </div>
        </div>
    </div>
</section>

{{-- 2. Kategori Jasa Kreatif --}}
<section class="py-16 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-brand-blue mb-1">Kategori Pilihan</p>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Keahlian Kreatif Siap Bantu Proyekmu</h2>
            </div>
            <p class="text-sm text-gray-500 mt-2 sm:mt-0">Dikerjakan langsung oleh mahasiswa program studi relevan</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            {{-- Category 1 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#EFF8FF">
                    <svg class="w-6 h-6" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition-colors">Desain Grafis</h3>
                <p class="text-[11px] text-gray-400 mt-1">Logo, Brand, Banner</p>
            </a>

            {{-- Category 2 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-red-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#FFF5F5">
                    <svg class="w-6 h-6" style="color:#EB2E33" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-red transition-colors">Video & Animasi</h3>
                <p class="text-[11px] text-gray-400 mt-1">Reels, Motion, Editing</p>
            </a>

            {{-- Category 3 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#EFF8FF">
                    <svg class="w-6 h-6" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition-colors">UI/UX & Web</h3>
                <p class="text-[11px] text-gray-400 mt-1">Figma, Mobile, Web</p>
            </a>

            {{-- Category 4 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#EFF8FF">
                    <svg class="w-6 h-6" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition-colors">Ilustrasi & Seni</h3>
                <p class="text-[11px] text-gray-400 mt-1">Vektor, Karakter, Komik</p>
            </a>

            {{-- Category 5 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-red-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#FFF5F5">
                    <svg class="w-6 h-6" style="color:#EB2E33" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-red transition-colors">Musik & Audio</h3>
                <p class="text-[11px] text-gray-400 mt-1">Jingle, Voice Over, BGM</p>
            </a>

            {{-- Category 6 --}}
            <a href="#" class="group p-5 rounded-2xl border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all text-center flex flex-col items-center" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform" style="background:#EFF8FF">
                    <svg class="w-6 h-6" style="color:#2C99D6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition-colors">3D & Render</h3>
                <p class="text-[11px] text-gray-400 mt-1">Blender, Produk, Aset</p>
            </a>
        </div>
    </div>
</section>

{{-- 3. Showcase Talenta Pilihan --}}
<section class="py-16" style="background:#F8FAFC">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-brand-blue mb-1">Talenta Pilihan</p>
                <h2 class="text-2xl font-black text-gray-900">Mahasiswa Kreatif Terverifikasi Minggu Ini</h2>
            </div>
            <a href="{{ route('register') }}" class="text-xs font-bold text-brand-blue hover:underline">
                Daftar Jadi Freelancer →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Card 1: Earliyanza --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:border-blue-300 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-start gap-4 mb-4">
                        <img src="https://ui-avatars.com/api/?name=Earliyanza&background=2C99D6&color=ffffff&bold=true"
                             class="w-14 h-14 rounded-full object-cover ring-2 ring-blue-100">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900 truncate">Earliyanza</h3>
                                <span class="inline-flex items-center text-[11px] font-bold text-white px-2 py-0.5 rounded-full" style="background:#2C99D6">
                                    ✓ Terverifikasi
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">Telkom University · Desain Komunikasi Visual</p>
                            <div class="flex items-center gap-1 mt-1 text-xs text-amber-500 font-bold">
                                <span>★ 4.9</span>
                                <span class="text-gray-400 font-normal">(18 ulasan)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Spesialis desain identitas visual, logo minimalis modern, dan packaging produk UMKM.
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Brand Identity</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Logo Design</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Illustrator</span>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Mulai dari</span>
                        <span class="text-sm font-black" style="color:#2C99D6">Rp 150.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white hover:opacity-90 transition-opacity" style="background:#2C99D6">
                        Pesan Jasa
                    </a>
                </div>
            </div>

            {{-- Card 2: Anindya Putri --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:border-blue-300 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-start gap-4 mb-4">
                        <img src="https://ui-avatars.com/api/?name=Anindya+Putri&background=EB2E33&color=ffffff&bold=true"
                             class="w-14 h-14 rounded-full object-cover ring-2 ring-red-100">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900 truncate">Anindya Putri</h3>
                                <span class="inline-flex items-center text-[11px] font-bold text-white px-2 py-0.5 rounded-full" style="background:#2C99D6">
                                    ✓ Terverifikasi
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">Telkom University · Sistem Informasi</p>
                            <div class="flex items-center gap-1 mt-1 text-xs text-amber-500 font-bold">
                                <span>★ 5.0</span>
                                <span class="text-gray-400 font-normal">(12 ulasan)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Desain UI/UX aplikasi mobile & web modern berstandar industri dengan Figma dan riset user flow.
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">UI/UX Mobile</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Figma</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Wireframing</span>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Mulai dari</span>
                        <span class="text-sm font-black" style="color:#2C99D6">Rp 250.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white hover:opacity-90 transition-opacity" style="background:#2C99D6">
                        Pesan Jasa
                    </a>
                </div>
            </div>

            {{-- Card 3: Dimas Pratama --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:border-blue-300 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-start gap-4 mb-4">
                        <img src="https://ui-avatars.com/api/?name=Dimas+Pratama&background=0F2942&color=ffffff&bold=true"
                             class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-100">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-base font-bold text-gray-900 truncate">Dimas Pratama</h3>
                                <span class="inline-flex items-center text-[11px] font-bold text-white px-2 py-0.5 rounded-full" style="background:#2C99D6">
                                    ✓ Terverifikasi
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">ITB · Seni Rupa & Desain</p>
                            <div class="flex items-center gap-1 mt-1 text-xs text-amber-500 font-bold">
                                <span>★ 4.8</span>
                                <span class="text-gray-400 font-normal">(9 ulasan)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Pembuatan video reels, short TikTok, dan color grading video promosi dengan Premiere Pro & After Effects.
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Video Editing</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Reels Viral</span>
                        <span class="text-[11px] bg-blue-50 text-brand-blue rounded px-2 py-0.5 font-medium">Motion Graphic</span>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Mulai dari</span>
                        <span class="text-sm font-black" style="color:#2C99D6">Rp 200.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white hover:opacity-90 transition-opacity" style="background:#2C99D6">
                        Pesan Jasa
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- 4. Mengapa Memilih SKENA --}}
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-xs font-black uppercase tracking-wider text-brand-blue mb-1">Keunggulan Platform</p>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kenapa Harus Menggunakan SKENA?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl border border-gray-100" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#2C99D6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Verifikasi Kampus Resmi</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Setiap mahasiswa melewati peninjauan KTM dan identitas kampus oleh administrator sebelum mendapatkan badge terverifikasi.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#0F2942">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Sistem Escrow Terlindungi</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Dana pembayaran aman disimpan di sistem SKENA dan baru diteruskan ke freelancer setelah klien menyetujui hasil akhir pekerjaan.
                </p>
            </div>

            <div class="p-6 rounded-2xl border border-gray-100" style="background:#F8FAFC">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background:#EB2E33">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Harga Kompetitif & Fleksibel</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Sangat terjangkau untuk UMKM, startup baru, maupun organisasi kampus tanpa mengorbankan kualitas dan orisinalitas karya.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- 5. Cara Kerja (Workflow) --}}
<section class="py-16" style="background:#F8FAFC">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-xs font-black uppercase tracking-wider text-brand-blue mb-1">Alur Kerja Praktis</p>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Mudah untuk Klien & Mahasiswa</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full mx-auto flex items-center justify-center text-sm font-black text-white mb-4" style="background:#2C99D6">1</div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Cari & Negosiasi</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Pilih kategori jasa, lihat portofolio mahasiswa, dan diskusikan kebutuhan serta tenggat waktu proyek.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full mx-auto flex items-center justify-center text-sm font-black text-white mb-4" style="background:#2C99D6">2</div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Bayar Aman via Escrow</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Dana diamankan di sistem penampungan sementara SKENA sampai mahasiswa menyelesaikan revisi dan tugasnya.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full mx-auto flex items-center justify-center text-sm font-black text-white mb-4" style="background:#2C99D6">3</div>
                <h3 class="text-sm font-bold text-gray-900 mb-2">Terima Hasil & Ulas</h3>
                <p class="text-xs text-gray-500 leading-relaxed">Unduh file hasil karya berkualitas, setujui penyelesaian, dan berikan review bintang 5 untuk mahasiswa!</p>
            </div>
        </div>
    </div>
</section>

{{-- 6. CTA Banner --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl p-8 sm:p-14 text-center relative overflow-hidden shadow-xl"
             style="background:linear-gradient(135deg,#0F2942 0%,#2C99D6 100%)">
            <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full opacity-20 pointer-events-none" style="background:#EB2E33"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="inline-block text-xs font-bold uppercase tracking-wider text-blue-200 mb-2">Mulai Sekarang</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-4">
                    Wujudkan Ide Kreatifmu Bersama Talenta Mahasiswa Terbaik
                </h2>
                <p class="text-sm text-blue-100 mb-8 leading-relaxed font-normal">
                    Daftar akun gratis sekarang dan nikmati kemudahan transaksi jasa kreatif yang aman, terpercaya, dan saling memberdayakan.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('register') }}?role=client"
                       class="w-full sm:w-auto px-7 py-3 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-[0.98] shadow-md"
                       style="background:#EB2E33">
                        Pekerjakan Talenta (Klien)
                    </a>
                    <a href="{{ route('register') }}?role=freelancer"
                       class="w-full sm:w-auto px-7 py-3 rounded-xl text-sm font-bold text-brand-dark bg-white hover:bg-gray-100 transition-all active:scale-[0.98] shadow-md">
                        Daftar Sebagai Mahasiswa Kreatif
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
