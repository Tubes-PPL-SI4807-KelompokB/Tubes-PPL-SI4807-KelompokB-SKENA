@extends('layouts.app')
@section('title', 'Beranda – SKENA')

{{--
    TIM-C Dashboard Mahasiswa — persis Claude Design "Dashboard Page" (tc-1).
    Ringkasan, rekomendasi, dan aktivitas pesanan masih DATA CONTOH dari desain
    (tabel services/orders belum terisi; lihat DashboardController).
--}}

@php
    // Beranda = halaman awal untuk semua pengunjung; $user null untuk tamu.
    $firstName = $user ? \Illuminate\Support\Str::of($user->name)->before(' ') : null;
    $status = [
        'Dikerjakan'   => ['#E0ECF6', '#346284', '#3F749C'],
        'Revisi Klien' => ['#FEF3C7', '#B45309', '#F59E0B'],
        'Selesai'      => ['#DCFCE7', '#047857', '#10B981'],
        'Menunggu'     => ['#EEF4F9', '#475569', '#64748B'],
    ];
    $kicker = 'text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary';
    $h2 = 'mt-2 text-[24px] leading-[1.25] tracking-[-0.01em] font-bold';
@endphp

@section('content')
<div x-data="{ mode: @js($initialMode), cat: 'Semua', fav: {} }">

    {{-- Baris kontrol: menempel di bawah header saat scroll (.subbar) --}}
    <div class="subbar flex flex-wrap items-center gap-2.5" style="margin-bottom:10px">
        <a href="{{ route('home') }}#kategori" class="h-11 pl-5 pr-[18px] rounded-full border border-border bg-white flex items-center gap-2 text-[14px] font-semibold text-on-surface">Kategori<span class="msr">expand_more</span></a>
        <a href="{{ route('home', ['urut' => 'terbaru']) }}" class="h-11 pl-5 pr-[18px] rounded-full border border-border bg-white flex items-center gap-2 text-[14px] font-semibold text-on-surface">Terbaru<span class="msr">expand_more</span></a>
        <form method="GET" action="{{ route('home') }}" role="search" class="h-11 w-full sm:w-[300px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="dashSearch" class="sr-only">Cari jasa</label>
            <input id="dashSearch" type="search" name="q" placeholder="Cari jasa…" class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
        </form>
        <div class="xl:ml-auto hidden xl:flex gap-2 max-w-full overflow-x-auto">
            @foreach($categories as $c)
                <a href="{{ $c === 'Semua' ? route('home') : route('home', ['kategori' => $c]) }}"
                   class="h-11 px-5 rounded-full text-[14px] font-semibold border flex items-center whitespace-nowrap {{ $c === 'Semua' ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:text-on-surface' }}">{{ $c }}</a>
            @endforeach
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative rounded-[28px] overflow-hidden text-white h-auto lg:h-[520px] pb-[120px] lg:pb-0" style="background:linear-gradient(160deg,#7FB0D4 0%,#3F749C 100%)">
        <figure class="hidden xl:block absolute left-[92px] top-[70px] w-[210px] h-[296px] -rotate-[7deg] border-[5px] border-white rounded-[22px] overflow-hidden bg-tint" style="box-shadow:0 18px 30px -12px rgba(15,23,42,0.35)">
            <img src="{{ asset('images/dashboard/hero-tutor.jpg') }}" alt="Mahasiswa tutor Python dan SQL" class="w-full h-full object-cover" style="object-position:88% 20%">
            <figcaption class="absolute left-3 right-3 bottom-3.5 bg-white text-on-surface rounded-full px-3 py-2 text-[12px] leading-[1.3] font-semibold text-center">Tutor Python &amp; SQL, Mulai Rp40.000/jam</figcaption>
        </figure>
        <figure class="hidden xl:block absolute right-[92px] top-[70px] w-[210px] h-[296px] rotate-[7deg] border-[5px] border-white rounded-[22px] overflow-hidden bg-tint" style="box-shadow:0 18px 30px -12px rgba(15,23,42,0.35)">
            <img src="{{ asset('images/dashboard/hero-video.jpg') }}" alt="Mahasiswa mengedit video" class="w-full h-full object-cover" style="object-position:85% 50%">
            <figcaption class="absolute left-3 right-3 bottom-3.5 bg-white text-on-surface rounded-full px-3 py-2 text-[12px] leading-[1.3] font-semibold text-center">Video Editing, Mulai Rp35.000</figcaption>
        </figure>

        <div class="relative lg:absolute lg:left-0 lg:right-0 lg:top-[66px] pt-14 lg:pt-0 px-5 flex flex-col items-center text-center">
            <div class="flex items-center gap-3.5 text-[12px] leading-none font-bold tracking-[0.14em]">
                <span class="w-10 h-px bg-white/70"></span>SELAMAT DATANG DI SKENA<span class="w-10 h-px bg-white/70"></span>
            </div>
            <h1 class="mt-[22px] text-[38px] sm:text-[54px] leading-[1.04] tracking-[-0.035em] font-bold">Halo, {{ $firstName ?? 'Mahasiswa' }}!<br>Cari &amp; Tawarkan Jasa</h1>
            <p class="mt-[18px] max-w-[470px] text-[16px] leading-normal font-semibold">
                @auth
                    Mau cari atau tawarkan jasa hari ini? Kolaborasi antar mahasiswa, cepat dan ramah kantong kuliah.
                @else
                    Jasa dari mahasiswa terverifikasi untuk mahasiswa. Cepat, aman, dan ramah kantong kuliah.
                @endauth
            </p>
            <div class="flex flex-col sm:flex-row gap-3 mt-7 w-full sm:w-auto">
                <a href="{{ route('home') }}" class="h-14 pl-7 pr-2 rounded-full bg-white text-on-surface flex items-center justify-between gap-3.5 text-[15px] font-bold">Cari Jasa<span class="w-10 h-10 rounded-full bg-primary text-white grid place-items-center"><span class="msr">arrow_forward</span></span></a>
                @auth
                    <a href="{{ route('services.create') }}" class="h-14 px-7 rounded-full border-[1.5px] border-white text-white flex items-center justify-center text-[15px] font-bold hover:bg-white/10">Tawarkan Jasa</a>
                @else
                    <a href="{{ route('register') }}" class="h-14 px-7 rounded-full border-[1.5px] border-white text-white flex items-center justify-center text-[15px] font-bold hover:bg-white/10">Daftar Gratis</a>
                @endauth
            </div>
        </div>

        {{-- Tab fitur di dasar hero --}}
        <div class="absolute left-3 right-3 lg:left-[150px] lg:right-[150px] bottom-0 h-[110px] lg:h-24 bg-surface rounded-t-[28px] text-on-surface">
            <span class="absolute -left-7 bottom-0 w-7 h-7" style="background:radial-gradient(circle at 0 0,transparent 27.5px,#F8F9FB 28px)"></span>
            <span class="absolute -right-7 bottom-0 w-7 h-7" style="background:radial-gradient(circle at 100% 0,transparent 27.5px,#F8F9FB 28px)"></span>
            <div class="grid grid-cols-2 lg:grid-cols-4 h-full items-center px-1 lg:px-3 gap-y-2">
                @foreach($features as $i => [$ic, $t, $sub])
                    <div class="flex gap-3 items-center px-2 lg:px-5 min-w-0 {{ $i ? 'lg:border-l lg:border-border' : '' }}">
                        <span class="w-[42px] h-[42px] flex-none rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:21px">{{ $ic }}</span></span>
                        <div class="min-w-0">
                            <div class="text-[15px] leading-[1.2] font-semibold">{{ $t }}</div>
                            <div class="hidden sm:block text-[13px] leading-[1.4] text-on-surface-muted mt-0.5">{{ $sub }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @auth
    {{-- Ringkasan (hanya untuk pengguna yang sudah masuk) --}}
    <div id="ringkasan" class="mt-14 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div><div class="{{ $kicker }}">RINGKASAN</div><h2 class="{{ $h2 }}">Aktivitas Kamu</h2></div>
        <div class="flex p-1 rounded-full bg-white border border-border self-start sm:self-auto" role="group" aria-label="Mode peran">
            @foreach(['pencari' => 'Mode Pencari Jasa', 'penyedia' => 'Mode Penyedia Jasa'] as $k => $label)
                <button type="button" class="h-9 px-[18px] rounded-full text-[14px] font-semibold transition-colors"
                        :class="mode === '{{ $k }}' ? 'bg-primary text-white' : 'text-on-surface-muted'" :aria-pressed="(mode === '{{ $k }}').toString()"
                        @click="mode = '{{ $k }}'">{{ $label }}</button>
            @endforeach
        </div>
    </div>
    @foreach($modes as $k => $stats)
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-card-gap mt-6" x-show="mode === '{{ $k }}'" @if($k !== $initialMode) x-cloak @endif>
            @foreach($stats as [$label, $icon, $value, $note])
                <div class="bg-white border border-border rounded-3xl p-6 shadow-card">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-on-surface-muted">{{ $label }}</span>
                        <span class="w-[38px] h-[38px] rounded-full bg-tint text-primary grid place-items-center"><span class="msr">{{ $icon }}</span></span>
                    </div>
                    <div class="mt-[18px] text-[40px] leading-[1.05] tracking-[-0.02em] font-bold">{{ $value }}</div>
                    <div class="mt-1.5 text-[13px] leading-[1.45] text-on-surface-muted">{{ $note }}</div>
                </div>
            @endforeach
        </div>
    @endforeach
    @endauth

    {{-- Rekomendasi --}}
    <div class="mt-14 flex justify-between items-end gap-4">
        <div><div class="{{ $kicker }}">REKOMENDASI KAMPUS</div><h2 class="{{ $h2 }}">{{ $user ? 'Jasa Pilihan untuk Kamu' : 'Jasa Populer di Kampus' }}</h2></div>
        <a href="{{ route('home') }}" class="text-[14px] font-semibold text-primary hover:text-primary-hover whitespace-nowrap">Lihat Semua Jasa</a>
    </div>
    {{-- Grid ke bawah: 4 kolom x 3 baris di desktop (12 jasa), menyusut ke 3/2/1 kolom --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-card-gap">
        @forelse($services as $i => [$badge, $cat, $rating, $title, $by, $price, $img, $orderUrl])
            <article class="bg-white border border-border rounded-3xl p-3 shadow-card transition-[transform,box-shadow] duration-200 ease-out hover:-translate-y-[3px] hover:shadow-card-hover">
                <div class="relative h-[190px] rounded-[18px] overflow-hidden bg-tint">
                    <img src="{{ asset('images/dashboard/' . $img) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                    <span class="absolute top-3 left-3 h-[26px] px-3 rounded-full bg-primary text-white text-[11px] leading-[26px] font-bold tracking-[0.06em]">{{ $badge }}</span>
                    <button type="button" class="absolute top-2.5 right-2.5 w-9 h-9 rounded-full bg-white grid place-items-center" style="box-shadow:0 4px 10px -4px rgba(15,23,42,0.2)"
                            :style="fav[{{ $i }}] ? 'color:#EF4444' : 'color:#0F172A'" :aria-pressed="(!!fav[{{ $i }}]).toString()" aria-label="Simpan {{ $title }}"
                            @click="fav = { ...fav, {{ $i }}: !fav[{{ $i }}] }"><span class="msr" style="font-size:19px" :style="fav[{{ $i }}] ? `font-variation-settings:'FILL' 1` : ''">favorite</span></button>
                </div>
                <div class="px-1.5 pt-3.5 pb-1">
                    <div class="flex justify-between items-center">
                        <span class="h-6 px-2.5 rounded-full bg-tint text-[#346284] text-[13px] leading-6">{{ $cat }}</span>
                        @if($rating)
                            <span class="flex items-center gap-[3px] text-[13px] font-semibold"><span class="msr text-warning" style="font-size:16px">star</span>{{ $rating }}</span>
                        @else
                            <span class="text-[13px] text-on-surface-muted">Belum ada ulasan</span>
                        @endif
                    </div>
                    <h3 class="mt-3 h-11 overflow-hidden text-[17px] leading-[1.3] font-semibold">{{ $title }}</h3>
                    <div class="mt-1.5 text-[13px] leading-[1.4] text-on-surface-muted">{{ $by }}</div>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-[18px] leading-none font-bold">{{ $price }}</span>
                        <a href="{{ $orderUrl }}" aria-label="Pesan {{ $title }}" class="h-10 px-[18px] rounded-full bg-tint text-primary text-[14px] leading-10 font-semibold hover:bg-primary hover:text-white transition-colors">Pesan</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white border border-border rounded-3xl p-8 text-center">
                <div class="text-[16px] font-semibold">Belum ada jasa yang tersedia</div>
                <p class="mt-1 text-[14px] text-on-surface-muted">Jasa dari mahasiswa lain akan tampil di sini. Kamu juga bisa menawarkan jasamu sendiri.</p>
            </div>
        @endforelse
    </div>

    @auth
    {{-- Status terkini (hanya untuk pengguna yang sudah masuk) --}}
    <div class="mt-14 flex justify-between items-end gap-4">
        <div><div class="{{ $kicker }}">STATUS TERKINI</div><h2 class="{{ $h2 }}">Aktivitas Pesanan Kamu</h2></div>
        <a href="{{ route('orders.index') }}" class="text-[14px] font-semibold text-primary hover:text-primary-hover whitespace-nowrap">Lihat Semua Pesanan</a>
    </div>
    <div class="mt-6 bg-white border border-border rounded-3xl shadow-card overflow-hidden">
        @foreach($orders as $i => [$icon, $title, $meta, $st])
            @php([$bg, $fg, $dot] = $status[$st])
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-x-[18px] gap-y-3 px-6 py-[18px] {{ $i ? 'border-t border-border' : '' }}">
                <span class="w-12 h-12 flex-none rounded-[14px] bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:22px">{{ $icon }}</span></span>
                <div class="flex-1 min-w-0">
                    <div class="text-[16px] leading-[1.3] font-semibold">{{ $title }}</div>
                    <div class="mt-0.5 text-[13px] leading-[1.45] text-on-surface-muted">{{ $meta }}</div>
                </div>
                <span class="h-8 px-3.5 rounded-full flex items-center gap-2 text-[14px] font-semibold whitespace-nowrap" style="background:{{ $bg }};color:{{ $fg }}"><span class="w-[7px] h-[7px] rounded-full" style="background:{{ $dot }}"></span>{{ $st }}</span>
                <a href="{{ route('orders.index') }}" class="circ" aria-label="Buka pesanan {{ $title }}"><span class="msr">arrow_outward</span></a>
            </div>
        @endforeach
    </div>
    @endauth
</div>
@endsection
