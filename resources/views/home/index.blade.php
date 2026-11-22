@extends('layouts.app')
@section('title', 'Jelajahi Jasa – SKENA')

{{-- FR-25 Jelajahi Jasa: halaman katalog, isinya fokus ke daftar jasa --}}
@section('content')
@php
    $authUser = auth()->user();
    $link = fn (array $over = []) => route('home', array_filter(array_merge(
        ['q' => $q ?: null, 'kategori' => $cat, 'urut' => $sort !== 'terbaru' ? $sort : null], $over
    )));
    $filtered = $q !== '' || $cat;
@endphp

{{-- JUDUL --}}
<div class="browse-head">
    <div>
        <div class="kicker">Jelajahi Jasa</div>
        <h1>Semua Jasa Mahasiswa</h1>
        <p>{{ $total }} jasa dari mahasiswa terverifikasi, siap membantu tugas dan kebutuhan kuliahmu.</p>
    </div>
</div>

{{-- BARIS KONTROL (menempel di bawah header) --}}
<form class="browse-bar subbar" method="GET" action="{{ route('home') }}" role="search">
    @if($cat)<input type="hidden" name="kategori" value="{{ $cat }}">@endif
    <label class="search">
        <span class="sr-only">Cari jasa</span>
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari jasa… (misal: PPT Skripsi)" enterkeyhint="search">
        <svg class="ic"><use href="#i-search"/></svg>
    </label>
    <label class="pill sort">
        <span class="sr-only">Urutkan</span>
        <select name="urut" onchange="this.form.requestSubmit()">
            @foreach($sorts as $key => $label)
                <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <span class="chev"><svg class="ic sm"><use href="#i-down"/></svg></span>
    </label>
    <nav class="chips" aria-label="Kategori jasa">
        <a class="tab" href="{{ $link(['kategori' => null]) }}" @if(!$cat) aria-current="true" @endif>Semua <span>{{ $total }}</span></a>
        @foreach($categories as $slug => $label)
            <a class="tab" href="{{ $link(['kategori' => $slug]) }}" @if($cat === $slug) aria-current="true" @endif>{{ $label }} <span>{{ $counts[$slug] ?? 0 }}</span></a>
        @endforeach
    </nav>
</form>

{{-- HASIL --}}
<section class="browse-results" aria-labelledby="resultTitle">
    <div class="result-line">
        <h2 id="resultTitle">
            @if($q !== '')
                {{ $services->count() }} hasil untuk “{{ $q }}”@if($cat) di {{ $categories[$cat] }}@endif
            @elseif($cat)
                {{ $services->count() }} jasa {{ $categories[$cat] }}
            @else
                {{ $services->count() }} jasa tersedia
            @endif
        </h2>
        @if($filtered)
            <a href="{{ route('home') }}" class="clear"><span class="msr">close</span>Hapus filter</a>
        @endif
    </div>

    @if($services->isEmpty())
        <div class="empty">
            <b>Belum ada jasa yang cocok</b>
            Coba kata kunci lain atau lihat semua kategori.
            <div><a class="btn-primary" href="{{ route('home') }}">Lihat Semua Jasa</a></div>
        </div>
    @else
        <div class="grid-cards">
            @foreach($services as $s)
                <article class="card">
                    <div class="thumb">
                        <img src="{{ asset('images/dashboard/' . $s['img']) }}" alt="{{ $s['title'] }}" loading="lazy">
                        <span class="badge-pill {{ $s['hot'] ? 'hot' : '' }}">{{ $s['badge'] }}</span>
                        <button class="heart" aria-pressed="false" aria-label="Simpan ke wishlist"><svg class="ic sm"><use href="#i-heart"/></svg></button>
                    </div>
                    <div class="meta">
                        <span class="cat">{{ $s['cat'] }}</span>
                        @if($s['rating'])
                            <span class="rate"><svg class="ic xs fill"><use href="#i-star"/></svg>{{ $s['rating'] }} <span>({{ $s['reviews'] }})</span></span>
                        @else
                            <span class="rate"><span>Belum ada ulasan</span></span>
                        @endif
                    </div>
                    <h3>{{ $s['title'] }}</h3>
                    <div class="prov"><i>{{ mb_substr($s['provider'], 0, 1) }}</i>{{ $s['provider'] }}</div>
                    <div class="buy">
                        <div><small>{{ $s['priceLabel'] }}</small><b>{{ $s['price'] }}@if($s['unit'])<em>{{ $s['unit'] }}</em>@endif</b></div>
                        @if($authUser && $authUser->id === $s['providerId'])
                            <span class="btn-tint" aria-disabled="true" title="Ini jasa milikmu">Jasa Kamu</span>
                        @else
                            {{-- Tamu diarahkan ke login lalu kembali ke form request --}}
                            <a class="btn-tint" href="{{ $s['orderUrl'] }}" aria-label="Pesan {{ $s['title'] }}">Pesan <svg class="ic xs"><use href="#i-right"/></svg></a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $services->links() }}</div>
    @endif
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
