@extends('layouts.app')
@section('title', 'SKENA (Skill Exchange Network) — Marketplace Jasa Kreatif Mahasiswa')

@section('content')

{{-- Top Micro Announcement Bar --}}
<div class="border-b border-slate-200 bg-white py-2 px-4 text-center">
    <div class="max-w-7xl mx-auto flex items-center justify-center gap-2 text-xs text-slate-600">
        <span class="inline-block w-2 h-2 rounded-full" style="background:#EB2E33"></span>
        <span class="font-bold text-slate-900">SKENA (Skill Exchange Network)</span> — Platform jasa kreatif khusus mahasiswa terverifikasi KTM se-Indonesia.
        <a href="{{ route('register') }}?role=freelancer" class="hidden sm:inline font-bold underline hover:text-brand-blue ml-1" style="color:#2C99D6">Daftar jadi kreator →</a>
    </div>
</div>

{{-- 1. Hero Section (Sesuai Referensi SKENA: Blue Background, Pill Badge, Buttons & Bottom Wave Tanpa Foto) --}}
<section class="relative overflow-hidden pt-14 sm:pt-20 pb-0" style="background: linear-gradient(180deg, #2C99D6 0%, #207FA8 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">

        {{-- Top Pill Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest text-white uppercase bg-white/20 backdrop-blur-md border border-white/30 mb-8 shadow-sm">
            <span>•</span> SELAMAT DATANG DI SKENA <span>•</span>
        </div>

        {{-- Main Headline --}}
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.15] mb-6">
            @auth
                Halo, {{ auth()->user()->name }}!<br>
            @else
                SKENA: Skill Exchange Network.<br>
            @endauth
            Mau cari atau tawarkan jasa hari ini?
        </h1>

        {{-- Subtitle --}}
        <p class="text-base sm:text-lg text-blue-100 font-normal leading-relaxed max-w-2xl mx-auto mb-9">
            Kolaborasi antar mahasiswa seluruh Indonesia. Cepat, berkualitas, dan harga bersahabat ramah kantong kuliah.
        </p>

        {{-- Action Buttons (Dua Tombol Khas: Cari Jasa & Tawarkan Jasa) --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
            <a href="#katalog"
               class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm font-bold text-slate-900 bg-white hover:bg-slate-100 transition-all flex items-center justify-center gap-3 shadow-lg active:scale-95">
                <span>Cari Jasa</span>
                <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black">↗</span>
            </a>
            <a href="{{ route('register') }}?role=freelancer"
               class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm font-bold text-white bg-white/10 hover:bg-white/20 border border-white/40 transition-all flex items-center justify-center gap-2.5 backdrop-blur-sm active:scale-95">
                <span class="w-5 h-5 rounded-full border border-white/80 flex items-center justify-center text-xs font-bold">+</span>
                <span>Tawarkan Jasa</span>
            </a>
        </div>

        {{-- Embedded Search Bar --}}
        <div class="max-w-xl mx-auto bg-white/95 backdrop-blur-md p-2 rounded-2xl shadow-xl border border-white/40 mb-10">
            <div class="flex items-center gap-2">
                <div class="flex-1 flex items-center pl-3">
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text"
                           placeholder="Coba cari: Logo Minimalis, Reels Video, UI Figma..."
                           class="w-full px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none bg-transparent font-medium">
                </div>
                <button type="button"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90 active:scale-95 shrink-0"
                        style="background:#2C99D6">
                    Telusuri
                </button>
            </div>
        </div>

    </div>

    {{-- Seamless Organic Bottom Wave Ke Warna Canvas #F8FAFC --}}
    <div class="w-full overflow-hidden leading-none relative z-0">
        <svg class="relative block w-full h-12 sm:h-20 lg:h-24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" preserveAspectRatio="none">
            <path fill="#F8FAFC" d="M0,96L48,106.7C96,117,192,139,288,138.7C384,139,480,117,576,96C672,75,768,53,864,64C960,75,1056,117,1152,122.7C1248,128,1344,96,1392,80L1440,64L1440,160L1392,160C1344,160,1248,160,1152,160C1056,160,960,160,864,160C768,160,672,160,576,160C480,160,384,160,288,160C192,160,96,160,48,160L0,160Z"></path>
        </svg>
    </div>
</section>

{{-- 2. Category Live Pill Strip --}}
<section id="katalog" class="border-b border-slate-200 bg-slate-50 py-4 overflow-x-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2 whitespace-nowrap">
        <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-wider mr-2">Katalog:</span>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm" style="background:#2C99D6">
            Semua Jasa (850+)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            Logo & Branding (214)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            Video Reels & Motion (168)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            UI/UX Mobile & Web (142)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            Ilustrasi & Karakter (195)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            3D Render & Produk (88)
        </button>
        <button class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:border-slate-900 transition-colors">
            Poster & Editorial (94)
        </button>
    </div>
</section>

{{-- 3. Curated Marketplace Feed (Real Visual Portfolio Cards) --}}
<section class="py-16" style="background:#F8FAFC">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <p class="text-xs font-mono font-bold text-slate-400 uppercase tracking-wider mb-1">Karya Terkini</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-950 tracking-tight">Eksplorasi Jasa Kreatif Mahasiswa</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Portofolio diverifikasi berdasarkan karya asli mahasiswa bersangkutan</p>
        </div>

        {{-- Feed Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Service Card 1 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80"
                             alt="3D Abstract Visuals"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            3D & Render
                        </span>
                        <span class="absolute top-3 right-3 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#EB2E33">
                            ⚡ 2 Hari
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Dimas Aditya</span>
                            <span class="text-[10px] text-slate-400 font-mono">· ITB SRD</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Pembuatan Aset 3D Produk & Ilustrasi Komersial Menggunakan Blender
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 4.9</span>
                            <span class="text-slate-400 font-normal">(19 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 200.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

            {{-- Service Card 2 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=800&auto=format&fit=crop&q=80"
                             alt="UI UX Figma App"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            UI/UX Design
                        </span>
                        <span class="absolute top-3 right-3 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#2C99D6">
                            Verified Mahasiswa
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Anindya Putri</span>
                            <span class="text-[10px] text-slate-400 font-mono">· Telkom SI</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Desain Antarmuka Aplikasi Mobile iOS/Android Full Figma & Design System
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 5.0</span>
                            <span class="text-slate-400 font-normal">(14 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 250.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

            {{-- Service Card 3 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=800&auto=format&fit=crop&q=80"
                             alt="Video Editing Reels"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            Video & Motion
                        </span>
                        <span class="absolute top-3 right-3 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#EB2E33">
                            ⚡ 24 Jam
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Rizky Maulana</span>
                            <span class="text-[10px] text-slate-400 font-mono">· ISI Yogya</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Editing Video TikTok & Instagram Reels Kreatif dengan Subtitle Animasi
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 4.8</span>
                            <span class="text-slate-400 font-normal">(32 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 120.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

            {{-- Service Card 4 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=800&auto=format&fit=crop&q=80"
                             alt="Vector Illustration"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            Ilustrasi Digital
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Nabila Maharani</span>
                            <span class="text-[10px] text-slate-400 font-mono">· FSRD ITB</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Ilustrasi Maskot & Karakter Vektor untuk Kemasan Produk dan Merchandise
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 5.0</span>
                            <span class="text-slate-400 font-normal">(21 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 150.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

            {{-- Service Card 5 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1509281373149-e957c6296406?w=800&auto=format&fit=crop&q=80"
                             alt="Poster Design"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            Desain Grafis
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Earliyanza</span>
                            <span class="text-[10px] text-slate-400 font-mono">· Telkom DKV</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Desain Poster Event Kampus, Konser Musik, dan Feed Instagram Terkonsep
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 4.9</span>
                            <span class="text-slate-400 font-normal">(18 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 100.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

            {{-- Service Card 6 --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-900 hover:shadow-md transition-all group flex flex-col justify-between">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&auto=format&fit=crop&q=80"
                             alt="Branding Packaging"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded">
                            Kemasan & Label
                        </span>
                        <span class="absolute top-3 right-3 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" style="background:#2C99D6">
                            Bestseller
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=100&auto=format&fit=crop&q=80"
                                 class="w-6 h-6 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900">Farhan Pratama</span>
                            <span class="text-[10px] text-slate-400 font-mono">· UGM DKV</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug mb-2">
                            Desain Label Kemasan Kopi & Produk Makanan Siap Cetak (Die-cut Ready)
                        </h3>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <span>★ 4.9</span>
                            <span class="text-slate-400 font-normal">(41 ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-mono">Mulai Dari</span>
                        <span class="text-sm font-black text-slate-950">Rp 180.000</span>
                    </div>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        Lihat Portofolio →
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- 4. Why Skena: Real Difference Matrix (Not Generic Text) --}}
<section class="py-16 bg-white border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-xs font-mono font-bold uppercase tracking-wider text-slate-400 mb-1">Keamanan Transaksi</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-950 tracking-tight">Kenapa Transaksi di SKENA Lebih Aman?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            {{-- Cara Lama (Sosmed / DM) --}}
            <div class="p-6 rounded-3xl border border-red-200 bg-red-50/40">
                <div class="flex items-center gap-2 mb-4 text-red-700 font-bold text-sm">
                    <span class="w-6 h-6 rounded-full bg-red-200 flex items-center justify-center text-xs">✕</span>
                    Transaksi DM Sosmed / Personal
                </div>
                <ul class="space-y-3 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <span class="text-red-500 font-bold">•</span>
                        <span>Uang ditransfer langsung ke rekening pribadi, rawan dibawa kabur tanpa kabar.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-500 font-bold">•</span>
                        <span>Tidak tahu apakah freelancer benar-benar mahasiswa atau akun anonim fiktif.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-500 font-bold">•</span>
                        <span>Revisi tidak terkontrol, tidak ada batasan tenggat waktu yang mengikat.</span>
                    </li>
                </ul>
            </div>

            {{-- Cara SKENA --}}
            <div class="p-6 rounded-3xl border-2 border-slate-900 bg-white shadow-[4px_4px_0px_0px_#0F2942]">
                <div class="flex items-center gap-2 mb-4 text-slate-950 font-black text-sm">
                    <span class="w-6 h-6 rounded-full text-white flex items-center justify-center text-xs" style="background:#2C99D6">✓</span>
                    Menggunakan Platform SKENA
                </div>
                <ul class="space-y-3 text-xs text-slate-700 font-medium">
                    <li class="flex items-start gap-2">
                        <span class="font-bold" style="color:#2C99D6">•</span>
                        <span><strong>Rekening Bersama (Escrow):</strong> Dana hanya cair ke mahasiswa setelah file hasil pekerjaan disetujui klien.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold" style="color:#2C99D6">•</span>
                        <span><strong>Validasi KTM Resmi:</strong> Setiap kreator dicek nomor mahasiswa dan universitas asalnya oleh admin.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold" style="color:#2C99D6">•</span>
                        <span><strong>Fitur Negosiasi & Milestone:</strong> Atur tenggat waktu, harga kesepakatan, dan jumlah revisi secara transparan.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- 5. Real Editorial Call To Action --}}
<section class="border-t border-slate-200 bg-slate-950 text-white py-16 lg:py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 rounded-md text-[11px] font-mono uppercase tracking-widest text-blue-300 border border-blue-900 bg-blue-950/50 mb-4">
            Mulai Buka Komisi & Pekerjakan Talenta
        </span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
            Punya ide proyek? Atau ingin cari penghasilan sambil kuliah?
        </h2>
        <p class="text-sm sm:text-base text-slate-400 max-w-xl mx-auto mb-8 font-normal">
            Daftar gratis dalam 2 menit. Dapatkan karya kreatif profesional dengan mendukung kemandirian finansial mahasiswa Indonesia.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('register') }}?role=client"
               class="w-full sm:w-auto px-7 py-3.5 rounded-xl text-sm font-bold text-white transition-transform active:translate-y-0.5 shadow-md"
               style="background:#2C99D6">
                Cari Talenta Sekarang →
            </a>
            <a href="{{ route('register') }}?role=freelancer"
               class="w-full sm:w-auto px-7 py-3.5 rounded-xl text-sm font-bold text-slate-900 bg-white hover:bg-slate-100 transition-colors">
                Daftar Jadi Freelancer Mahasiswa
            </a>
        </div>
    </div>
</section>

@endsection
