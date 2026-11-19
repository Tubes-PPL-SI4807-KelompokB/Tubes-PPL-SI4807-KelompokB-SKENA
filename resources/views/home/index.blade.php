@extends('layouts.app')
@section('title', 'SKENA – Marketplace Jasa Mahasiswa')

@section('content')
@php
    $authUser   = auth()->user();
    $firstName  = $authUser ? \Illuminate\Support\Str::of($authUser->name)->before(' ') : null;
    $categories = ['Semua', 'Desain', 'Tutor', 'Video', 'Web', 'Dokumen'];

    // Data contoh sampai penelusuran jasa (FR-25) tersambung ke tabel services.
    $services = [
        ['img' => 'jasa-ppt.jpg',          'badge' => 'BARU',       'hot' => false, 'cat' => 'Desain PPT',    'rating' => '5.0', 'reviews' => 19, 'title' => 'Desain PPT Sidang & Seminar Skripsi Cepat',      'provider' => 'Nadia S. (DKV ITB)',              'priceLabel' => 'Mulai dari', 'price' => 'Rp20.000', 'unit' => null],
        ['img' => 'jasa-olah-data.jpg',    'badge' => 'POPULER',    'hot' => true,  'cat' => 'Olah Data',     'rating' => '4.9', 'reviews' => 42, 'title' => 'Olah Data Skripsi SPSS & Interpretasi Excel',    'provider' => 'Rian Pratama (Statistika UNPAD)', 'priceLabel' => 'Mulai dari', 'price' => 'Rp50.000', 'unit' => null],
        ['img' => 'jasa-video.jpg',        'badge' => 'BARU',       'hot' => false, 'cat' => 'Video Editing', 'rating' => '4.8', 'reviews' => 11, 'title' => 'Editing Video Reels / TikTok Tugas Kampus',      'provider' => 'Dimas K. (Ilkom UI)',             'priceLabel' => 'Mulai dari', 'price' => 'Rp35.000', 'unit' => null],
        ['img' => 'jasa-tutor-koding.jpg', 'badge' => 'TOP SELLER', 'hot' => true,  'cat' => 'Tutor Koding',  'rating' => '5.0', 'reviews' => 34, 'title' => 'Tutor Privat Koding Python & SQL Tugas Kuliah', 'provider' => 'Fathir A. (Informatika)',         'priceLabel' => 'Biaya sesi', 'price' => 'Rp40.000', 'unit' => '/jam'],
        ['img' => 'jasa-dokumen.jpg',      'badge' => 'BARU',       'hot' => false, 'cat' => 'Dokumen',       'rating' => '4.7', 'reviews' => 8,  'title' => 'Rapikan Format & Daftar Pustaka Skripsi',        'provider' => 'Salsa M. (Sastra UGM)',           'priceLabel' => 'Mulai dari', 'price' => 'Rp25.000', 'unit' => null],
        ['img' => 'jasa-web.jpg',          'badge' => 'BARU',       'hot' => false, 'cat' => 'Web',           'rating' => '4.9', 'reviews' => 15, 'title' => 'Bikin Landing Page Organisasi Kampus',           'provider' => 'Arga P. (Sistem Informasi)',      'priceLabel' => 'Mulai dari', 'price' => 'Rp75.000', 'unit' => null],
    ];
    $orderUrl = $authUser ? '#' : route('login');
    $offerUrl = $authUser ? route('services.create') : route('register');
@endphp

{{-- BARIS KONTROL --}}
<form class="filters" method="GET" action="{{ route('home') }}" role="search">
    <button type="button" class="pill">Kategori<span class="chev"><svg class="ic sm"><use href="#i-down"/></svg></span></button>
    <button type="button" class="pill">Terbaru<span class="chev"><svg class="ic sm"><use href="#i-down"/></svg></span></button>
    <label class="search">
        <span class="sr-only">Cari jasa</span>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari jasa… (misal: PPT Skripsi)">
        <svg class="ic"><use href="#i-search"/></svg>
    </label>
    <div class="tabs" id="tabs" role="group" aria-label="Kategori jasa">
        @foreach($categories as $i => $cat)
            <button type="button" class="tab" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">{{ $cat }}</button>
        @endforeach
    </div>
</form>

{{-- HERO --}}
<section class="hero">
    <figure class="shot l">
        <img src="{{ asset('images/dashboard/hero-tutor.jpg') }}" alt="Mahasiswa memberi tutor Python dan SQL" style="object-position:88% 20%">
        <figcaption class="tag"><b>Tutor Python &amp; SQL</b><span>Mulai Rp40.000/jam</span></figcaption>
    </figure>
    <figure class="shot r">
        <img src="{{ asset('images/dashboard/hero-video.jpg') }}" alt="Mahasiswa mengedit video" style="object-position:85% 50%">
        <figcaption class="tag"><b>Video Editing</b><span>Mulai Rp35.000</span></figcaption>
    </figure>

    <div class="hero-copy">
        <div class="eyebrow">Selamat datang di SKENA</div>
        @if($authUser)
            <h1>Halo, {{ $firstName }}!<br>Mau cari atau<br>tawarkan jasa?</h1>
        @else
            <h1>Jasa mahasiswa,<br>dikerjakan<br>mahasiswa.</h1>
        @endif
        <p class="sub">Kolaborasi antar mahasiswa seluruh Indonesia. Cepat, berkualitas, dan harga ramah kantong kuliah.</p>
        <div class="hero-actions">
            <a class="btn-white" href="#katalog">Cari Jasa <span class="go"><svg class="ic sm"><use href="#i-upright"/></svg></span></a>
            <a class="btn-ghost" href="{{ $offerUrl }}"><svg class="ic"><use href="#i-plus"/></svg>Tawarkan Jasa</a>
        </div>
    </div>

    <svg class="curve" viewBox="0 0 1200 96" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 96V80C70 76 105 34 205 34H995C1095 34 1130 76 1200 80V96Z" fill="#F8F9FB"/>
    </svg>
</section>

{{-- STRIP FITUR --}}
<div class="features">
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-cash"/></svg></span><div><b>Harga Terjangkau</b><span>Khusus dompet mahasiswa</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-verified"/></svg></span><div><b>Mahasiswa Terverifikasi</b><span>Email kampus dan NIM</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-shield"/></svg></span><div><b>Pembayaran Tercatat</b><span>Bukti bayar tersimpan rapi</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-award"/></svg></span><div><b>Ulasan &amp; Rating</b><span>Dari sesama mahasiswa</span></div></div>
</div>

{{-- JASA PILIHAN --}}
<section class="block" id="katalog">
    <div class="sec-head">
        <div><div class="kicker">Rekomendasi Kampus</div><h2>Jasa Pilihan untuk Kamu</h2></div>
        <div class="circ-row">
            <button class="circ" id="prev" aria-label="Sebelumnya"><svg class="ic"><use href="#i-left"/></svg></button>
            <button class="circ" id="next" aria-label="Berikutnya"><svg class="ic"><use href="#i-right"/></svg></button>
        </div>
    </div>
    <div class="rail" id="rail">
        @foreach($services as $s)
            <article class="card">
                <div class="thumb">
                    <img src="{{ asset('images/dashboard/' . $s['img']) }}" alt="{{ $s['title'] }}">
                    <span class="badge-pill {{ $s['hot'] ? 'hot' : '' }}">{{ $s['badge'] }}</span>
                    <button class="heart" aria-pressed="false" aria-label="Simpan ke wishlist"><svg class="ic sm"><use href="#i-heart"/></svg></button>
                </div>
                <div class="meta">
                    <span class="cat">{{ $s['cat'] }}</span>
                    <span class="rate"><svg class="ic xs fill"><use href="#i-star"/></svg>{{ $s['rating'] }} <span>({{ $s['reviews'] }})</span></span>
                </div>
                <h3>{{ $s['title'] }}</h3>
                <div class="prov"><i>{{ mb_substr($s['provider'], 0, 1) }}</i>{{ $s['provider'] }}</div>
                <div class="buy">
                    <div><small>{{ $s['priceLabel'] }}</small><b>{{ $s['price'] }}@if($s['unit'])<em>{{ $s['unit'] }}</em>@endif</b></div>
                    <a class="btn-tint" href="{{ $orderUrl }}">Pesan <svg class="ic xs"><use href="#i-right"/></svg></a>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- CARA KERJA --}}
<section class="block">
    <div class="sec-head">
        <div><div class="kicker">Cara Kerja</div><h2>Tiga Langkah di SKENA</h2><p>Satu akun bisa dipakai untuk mencari dan menawarkan jasa.</p></div>
    </div>
    <div class="steps">
        <div class="step">
            <span class="si"><svg class="ic"><use href="#i-verified"/></svg></span>
            <b>Daftar dengan Email Kampus</b>
            <p>Verifikasi email kampus dan NIM agar akunmu dikenali sebagai mahasiswa aktif.</p>
        </div>
        <div class="step">
            <span class="si"><svg class="ic"><use href="#i-compass"/></svg></span>
            <b>Pilih atau Tawarkan Jasa</b>
            <p>Cari jasa sesuai kebutuhan kuliahmu, atau buka jasa dari keahlian yang kamu punya.</p>
        </div>
        <div class="step">
            <span class="si"><svg class="ic"><use href="#i-receipt"/></svg></span>
            <b>Bayar dan Pantau Pesanan</b>
            <p>Unggah bukti pembayaran, lalu ikuti status pesanan sampai hasilnya kamu terima.</p>
        </div>
    </div>
</section>

@guest
    <section class="cta">
        <h2>Punya keahlian, atau butuh bantuan tugas?</h2>
        <p>Daftar gratis dengan email kampusmu dan mulai bertransaksi dengan sesama mahasiswa.</p>
        <div class="hero-actions">
            <a class="btn-white" href="{{ route('register') }}">Daftar Sekarang <span class="go"><svg class="ic sm"><use href="#i-right"/></svg></span></a>
            <a class="btn-ghost" href="{{ route('login') }}">Masuk</a>
        </div>
    </section>
@endguest

@endsection
