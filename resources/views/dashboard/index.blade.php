@extends('layouts.sheet')
@section('title', 'Dashboard – SKENA')

@section('content')
@php($firstName = \Illuminate\Support\Str::of($user->name)->before(' '))

{{-- BARIS KONTROL --}}
<form class="filters" method="GET" action="{{ route('home') }}" role="search">
    <button type="button" class="pill">Kategori<span class="chev"><svg class="ic sm"><use href="#i-down"/></svg></span></button>
    <button type="button" class="pill">Terbaru<span class="chev"><svg class="ic sm"><use href="#i-down"/></svg></span></button>
    <label class="search">
        <span class="sr-only">Cari jasa</span>
        <input type="search" name="q" placeholder="Cari jasa… (misal: PPT Skripsi)">
        <svg class="ic"><use href="#i-search"/></svg>
    </label>
    <div class="tabs" id="tabs" role="group" aria-label="Kategori jasa">
        @foreach($categories as $i => $cat)
            <button type="button" class="tab" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}">{{ $cat }}</button>
        @endforeach
    </div>
</form>

{{-- VERIFIKASI MAHASISWA --}}
@unless($user->isVerifiedStudent())
    @php($vs = $user->student_verification_status)
    <div class="notice {{ $vs === 'pending' ? 'warn' : ($vs === 'rejected' ? 'err' : '') }}">
        <span class="ni"><svg class="ic"><use href="#i-verified"/></svg></span>
        <div class="nt">
            @if($vs === 'pending')
                <b>KTM kamu sedang diperiksa</b>
                <span>Tim verifikator SKENA meninjau data kemahasiswaanmu. Biasanya selesai dalam 1–2 hari kerja.</span>
            @elseif($vs === 'rejected')
                <b>Verifikasi belum diterima</b>
                <span>{{ $user->verification_notes ?? 'Foto KTM tidak terbaca. Unggah ulang KTM yang jelas agar bisa diverifikasi.' }}</span>
            @else
                <b>Verifikasi status mahasiswamu</b>
                <span>Akun terverifikasi bisa memesan dan menawarkan jasa di SKENA.</span>
            @endif
        </div>
        @if($vs !== 'pending')
            <a class="btn-primary" href="{{ route('profile.edit', $user->id) }}">Lengkapi Data</a>
        @endif
    </div>
@endunless

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
        <h1>Halo, {{ $firstName }}!<br>Mau cari atau<br>tawarkan jasa?</h1>
        <p class="sub">Kolaborasi antar mahasiswa seluruh Indonesia. Cepat, berkualitas, dan harga ramah kantong kuliah.</p>
        <div class="hero-actions">
            <a class="btn-white" href="{{ route('home') }}">Cari Jasa <span class="go"><svg class="ic sm"><use href="#i-upright"/></svg></span></a>
            <a class="btn-ghost" href="{{ route('services.create') }}"><svg class="ic"><use href="#i-plus"/></svg>Tawarkan Jasa</a>
        </div>
    </div>

    <svg class="curve" viewBox="0 0 1200 96" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0 96V80C70 76 105 34 205 34H995C1095 34 1130 76 1200 80V96Z" fill="#F8F9FB"/>
    </svg>
</section>

{{-- STRIP FITUR --}}
<div class="features">
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-cash"/></svg></span><div><b>Harga Terjangkau</b><span>Khusus dompet mahasiswa</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-shield"/></svg></span><div><b>Pembayaran Tercatat</b><span>Bukti bayar dikonfirmasi</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-chat"/></svg></span><div><b>Negosiasi Harga</b><span>Chat langsung mahasiswa</span></div></div>
    <div class="feat"><span class="fi"><svg class="ic"><use href="#i-award"/></svg></span><div><b>Ulasan &amp; Rating</b><span>Dari mahasiswa terverifikasi</span></div></div>
</div>

{{-- RINGKASAN --}}
<section class="block">
    <div class="sec-head">
        <div>
            <div class="kicker">Ringkasan</div>
            <h2>Aktivitas Kamu</h2>
            <p>Pantau pesanan dan transaksi jasamu di satu tempat.</p>
        </div>
        <div class="seg" id="roleSeg" role="group" aria-label="Mode peran">
            <button type="button" aria-pressed="{{ $initialMode === 'seeker' ? 'true' : 'false' }}" data-mode="seeker">Mode Pencari Jasa</button>
            <button type="button" aria-pressed="{{ $initialMode === 'provider' ? 'true' : 'false' }}" data-mode="provider">Mode Penyedia Jasa</button>
        </div>
    </div>
    <div class="stats">
        <article class="stat">
            <div class="top"><span class="lbl" data-k="l1">{{ $summary['l1'] }}</span><span class="si"><svg class="ic sm"><use href="#i-clip"/></svg></span></div>
            <div class="num"><span data-k="n1">{{ $summary['n1'] }}</span><small data-k="s1">{{ $summary['s1'] }}</small></div>
            <div class="note" data-k="t1">{{ $summary['t1'] }}</div>
        </article>
        <article class="stat">
            <div class="top"><span class="lbl" data-k="l2">{{ $summary['l2'] }}</span><span class="si"><svg class="ic sm"><use href="#i-check"/></svg></span></div>
            <div class="num"><span data-k="n2">{{ $summary['n2'] }}</span><small class="ok" data-k="s2">{{ $summary['s2'] }}</small></div>
            <div class="note" data-k="t2">{{ $summary['t2'] }}</div>
        </article>
        <article class="stat">
            <div class="top"><span class="lbl" data-k="l3">{{ $summary['l3'] }}</span><span class="si"><svg class="ic sm"><use href="#i-wallet"/></svg></span></div>
            <div class="num" style="font-size:34px"><span data-k="n3">{{ $summary['n3'] }}</span></div>
            <div class="note" data-k="t3">{{ $summary['t3'] }}</div>
        </article>
        <article class="stat">
            <div class="top"><span class="lbl">Rating Rata-rata</span><span class="si gold"><svg class="ic sm fill"><use href="#i-star"/></svg></span></div>
            <div class="num">{{ $rating['value'] }}<small class="mut">/ 5.0</small></div>
            <div class="note">Dari {{ $rating['count'] }} ulasan mahasiswa</div>
        </article>
    </div>
    <script type="application/json" id="roleModes">@json($modes)</script>
</section>

{{-- REKOMENDASI JASA --}}
<section class="block">
    <div class="sec-head">
        <div><div class="kicker">Rekomendasi Kampus</div><h2>Jasa Pilihan untuk Kamu</h2></div>
        @if(count($recommendations) > 4)
            <div class="circ-row">
                <button type="button" class="circ" id="prev" aria-label="Jasa sebelumnya"><svg class="ic"><use href="#i-left"/></svg></button>
                <button type="button" class="circ" id="next" aria-label="Jasa berikutnya"><svg class="ic"><use href="#i-right"/></svg></button>
            </div>
        @endif
    </div>

    @if(count($recommendations))
        <div class="rail" id="rail">
            @foreach($recommendations as $svc)
                <article class="card">
                    <div class="thumb">
                        <img src="{{ asset('images/dashboard/' . $svc['img']) }}" alt="" loading="lazy">
                        <span class="badge-pill {{ $svc['hot'] ? 'hot' : '' }}">{{ $svc['badge'] }}</span>
                        <button type="button" class="heart" aria-pressed="false" aria-label="Simpan {{ $svc['title'] }} ke wishlist"><svg class="ic sm"><use href="#i-heart"/></svg></button>
                    </div>
                    <div class="meta">
                        <span class="cat">{{ $svc['cat'] }}</span>
                        <span class="rate"><svg class="ic xs fill"><use href="#i-star"/></svg>{{ $svc['rating'] }} <span>({{ $svc['reviews'] }})</span></span>
                    </div>
                    <h3>{{ $svc['title'] }}</h3>
                    <div class="prov"><i>{{ mb_substr($svc['provider'], 0, 1) }}</i>{{ $svc['provider'] }}</div>
                    <div class="buy">
                        <div><small>{{ $svc['priceLabel'] }}</small><b>{{ $svc['price'] }}@if($svc['unit'])<em>{{ $svc['unit'] }}</em>@endif</b></div>
                        <a class="btn-tint" href="{{ route('home') }}">Pesan <svg class="ic xs"><use href="#i-right"/></svg></a>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="list"><div class="empty"><b>Belum ada rekomendasi jasa</b>Jasa dari mahasiswa lain akan muncul di sini.</div></div>
    @endif
</section>

{{-- AKTIVITAS PESANAN --}}
<section class="block" style="margin-top:56px">
    <div class="sec-head">
        <div><div class="kicker">Status terkini</div><h2>Aktivitas Pesanan Kamu</h2></div>
        <a class="link-more" href="{{ route('orders.index') }}">Lihat Semua Pesanan <svg class="ic sm"><use href="#i-right"/></svg></a>
    </div>
    <div class="list">
        @forelse($orderActivities as $act)
            <div class="row">
                <span class="ri {{ $act['tone'] }}"><svg class="ic"><use href="#{{ $act['icon'] }}"/></svg></span>
                <div class="rt">
                    <b>{{ $act['title'] }}</b>
                    <span>{{ $act['who'] }}: <strong>{{ $act['name'] }}</strong> · {{ $act['note'] }}</span>
                </div>
                <span class="status s-{{ $act['status'] }}">{{ $act['label'] }}</span>
                <a class="circ" href="{{ route('orders.index') }}" aria-label="Buka pesanan {{ $act['title'] }}"><svg class="ic sm"><use href="#i-ext"/></svg></a>
            </div>
        @empty
            <div class="empty">
                <b>Belum ada pesanan</b>
                Pesanan yang kamu buat atau terima akan muncul di sini.
                <div><a class="btn-primary" href="{{ route('home') }}">Cari Jasa</a></div>
            </div>
        @endforelse
    </div>
</section>
@endsection
