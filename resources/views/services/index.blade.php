@extends('layouts.app')
@section('title', 'Jasa Saya — SKENA')

{{--
    PBI-04 Manajemen Jasa — persis Claude Design "PBI-04 Jasa":
    p04-1 daftar jasa saya (cari, filter status, switch aktif), p04-3 modal hapus + keadaan kosong.
    Form tambah/ubah (p04-2) ada di services/create & services/edit.
--}}

@php
    $filters = \App\Http\Controllers\ServiceController::FILTERS;
    $kicker  = 'text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary';
    $ibtn    = 'w-9 h-9 rounded-full border border-border bg-white grid place-items-center';
@endphp

@section('content')
<div x-data="{ del: null }" @keydown.escape.window="del = null">

    {{-- Toolbar: cari, filter, tambah --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <form method="GET" action="{{ route('services.index') }}" role="search"
              class="h-11 w-full md:w-80 px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="serviceSearch" class="sr-only">Cari jasa saya</label>
            <input id="serviceSearch" type="search" name="q" value="{{ $search }}" placeholder="Cari jasa saya…"
                   class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
            @if($filter !== 'semua')<input type="hidden" name="status" value="{{ $filter }}">@endif
        </form>
        <nav class="flex gap-2 md:ml-auto max-w-full overflow-x-auto" aria-label="Filter status jasa">
            @foreach($filters as $key => $label)
                <a href="{{ route('services.index', array_filter(['status' => $key === 'semua' ? null : $key, 'q' => $search ?: null])) }}"
                   class="h-11 px-5 rounded-full border text-[14px] font-semibold whitespace-nowrap flex items-center {{ $filter === $key ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:text-on-surface' }}"
                   @if($filter === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <a href="{{ route('services.create') }}"
           class="h-11 px-[22px] w-full md:w-auto justify-center rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold whitespace-nowrap">
            <span class="msr">add</span>Tambah Jasa
        </a>
    </div>

    {{-- Judul --}}
    <div class="pt-10 pb-8">
        <div class="{{ $kicker }}">JASA SAYA</div>
        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
            <span class="h-[30px] px-3.5 rounded-full bg-primary text-white text-[13px] leading-[30px] font-bold">PBI-04</span>
            <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Manajemen Jasa</h1>
            <span class="text-[14px] font-semibold text-on-surface-muted">Sprint 1</span>
        </div>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">{{ $total }} jasa · {{ $active }} aktif, {{ $total - $active }} nonaktif.</p>
    </div>

    @if($services->isEmpty())
        {{-- p04-3: keadaan kosong --}}
        <div class="min-h-[420px] rounded-3xl bg-white border border-border flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">{{ $total ? 'search_off' : 'storefront' }}</span></span>
            @if($total)
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Tidak ada jasa yang cocok</div>
                <p class="max-w-[300px] text-[14px] leading-normal text-on-surface-muted">Coba kata kunci lain atau tampilkan semua status jasa.</p>
                <a href="{{ route('services.index') }}" class="h-11 px-[22px] mt-2.5 rounded-full border border-border bg-white text-on-surface flex items-center text-[14px] font-semibold">Tampilkan Semua Jasa</a>
            @else
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Kamu belum punya jasa</div>
                <p class="max-w-[280px] text-[14px] leading-normal text-on-surface-muted">Jasa yang kamu tawarkan akan tampil di sini dan bisa ditemukan mahasiswa lain.</p>
                <a href="{{ route('services.create') }}" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">add</span>Tambah Jasa</a>
            @endif
        </div>
    @else
        {{-- p04-1: kartu jasa --}}
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-[18px]" role="list">
            @foreach($services as $s)
                @php($on = $s->status === 'aktif')
                <li class="bg-white border border-border rounded-3xl p-3 shadow-card transition duration-[250ms] ease-out hover:-translate-y-[3px] hover:shadow-card-hover flex flex-col {{ $on ? '' : 'opacity-[.72]' }}">
                    <div class="relative h-[170px] rounded-[18px] grid place-items-center text-primary"
                         style="background:repeating-linear-gradient(135deg,#E3ECF4 0 8px,#EDF3F8 8px 16px)">
                        <span class="msr" style="font-size:40px" aria-hidden="true">{{ $s->category?->icon ?? 'storefront' }}</span>
                        @unless($on)<span class="absolute top-3 left-3 h-[26px] px-3 rounded-full bg-white text-on-surface-muted text-[11px] leading-[26px] font-bold tracking-[0.06em]">NONAKTIF</span>@endunless
                    </div>
                    <div class="pt-3.5 px-1.5 pb-1 flex flex-col gap-2.5 flex-1">
                        <div class="flex justify-between items-center gap-2">
                            <span class="h-6 px-2.5 rounded-full bg-tint text-[#346284] text-[13px] leading-6 truncate">{{ $s->category?->nama_kategori ?? 'Tanpa kategori' }}</span>
                            <span class="flex items-center gap-1 text-[13px] text-on-surface-muted whitespace-nowrap"><span class="msr" style="font-size:16px">schedule</span>{{ $s->durasi_label }}</span>
                        </div>
                        <h2 class="text-[17px] leading-[1.3] font-semibold min-h-[44px] line-clamp-2">{{ $s->judul }}</h2>
                        <div class="text-[18px] leading-none font-bold">{{ $s->harga_label }}</div>
                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-border">
                            <form method="POST" action="{{ route('services.status', $s) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <button type="submit" class="switch" role="switch" aria-checked="{{ $on ? 'true' : 'false' }}" aria-label="Status jasa {{ $s->judul }}"></button>
                                <span class="text-[13px] font-semibold text-on-surface-variant">{{ $on ? 'Aktif' : 'Nonaktif' }}</span>
                            </form>
                            <div class="flex gap-1.5">
                                <a href="{{ route('services.edit', $s) }}" class="{{ $ibtn }} text-on-surface hover:border-secondary" aria-label="Ubah {{ $s->judul }}"><span class="msr" style="font-size:18px">edit</span></a>
                                <button type="button" class="{{ $ibtn }} text-[#B91C1C] hover:bg-error-container" aria-label="Hapus {{ $s->judul }}"
                                        @click="del = @js(['name' => $s->judul, 'url' => route('services.destroy', $s)])"><span class="msr" style="font-size:18px">delete</span></button>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        @if($services->hasPages())
            <nav class="flex flex-wrap justify-between items-center gap-3 mt-8 text-[13px] text-on-surface-muted" aria-label="Halaman">
                <span>Menampilkan {{ $services->count() }} dari {{ $services->total() }} jasa</span>
                <div class="flex gap-1.5">
                    @foreach(range(1, $services->lastPage()) as $p)
                        @if($p === $services->currentPage())
                            <span class="w-11 h-11 rounded-full bg-primary text-white grid place-items-center font-semibold" aria-current="page">{{ $p }}</span>
                        @else
                            <a href="{{ $services->url($p) }}" class="w-11 h-11 rounded-full border border-border bg-white grid place-items-center hover:border-secondary">{{ $p }}</a>
                        @endif
                    @endforeach
                </div>
            </nav>
        @endif
    @endif

    {{-- p04-3: modal konfirmasi hapus --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="del = null">
        <div x-trap.noscroll.inert="del" role="alertdialog" aria-modal="true" aria-labelledby="delSvcTitle" aria-describedby="delSvcDesc"
             class="w-full max-w-[420px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-[#B91C1C] grid place-items-center"><span class="msr" style="font-size:26px">delete</span></span>
            <h3 id="delSvcTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Hapus jasa "<span x-text="del?.name"></span>"?</h3>
            <p id="delSvcDesc" class="text-[14px] leading-normal text-on-surface-muted">Jasa akan hilang dari pencarian dan profilmu. Pesanan yang sudah berjalan tetap tercatat.</p>
            <form method="POST" :action="del?.url" class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="del = null">Batal</button>
                <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold" data-loading="Menghapus…">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection
