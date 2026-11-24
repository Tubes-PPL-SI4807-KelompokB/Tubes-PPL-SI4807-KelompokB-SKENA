@extends('layouts.app')
@section('title', 'Request Jasa Saya — SKENA')

{{--
    PBI-10 Manajemen Request Jasa — Claude Design "PBI-10 Request Jasa":
    p10-1 daftar request saya (cari + filter status), p10-3 modal batal, toast, keadaan kosong.
    Form ajukan/ubah (p10-2) ada di requests/create & requests/edit.
--}}

@php
    $filters = \App\Http\Controllers\ServiceRequestController::FILTERS;
    $tone = [
        'pending'   => ['#EEF4F9', '#475569', '#64748B'],
        'accepted'  => ['#DCFCE7', '#047857', '#10B981'],
        'rejected'  => ['#FEE2E2', '#B91C1C', '#EF4444'],
        'cancelled' => ['#F1F5F9', '#475569', '#94A3B8'],
    ];
    $tgl = fn ($d) => $d?->translatedFormat('j M Y');
@endphp

@section('content')
<div x-data="{ cancel: null }" @keydown.escape.window="cancel = null">

    {{-- Toolbar: cari + filter status --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <form method="GET" action="{{ route('requests.index') }}" role="search"
              class="h-11 w-full md:w-80 px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="requestSearch" class="sr-only">Cari request</label>
            <input id="requestSearch" type="search" name="q" value="{{ $search }}" placeholder="Cari request…"
                   class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
            @if($filter !== 'semua')<input type="hidden" name="status" value="{{ $filter }}">@endif
        </form>
        <nav class="flex gap-2 md:ml-auto max-w-full overflow-x-auto" aria-label="Filter status request">
            @foreach($filters as $key => $label)
                <a href="{{ route('requests.index', array_filter(['status' => $key === 'semua' ? null : $key, 'q' => $search ?: null])) }}"
                   class="h-11 px-5 rounded-full border text-[14px] font-semibold whitespace-nowrap flex items-center {{ $filter === $key ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:text-on-surface' }}"
                   @if($filter === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
    </div>

    {{-- Judul --}}
    <div class="pt-10 pb-8">
        <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">REQUEST SAYA</div>
        <div class="flex flex-wrap items-center gap-x-3.5 gap-y-2 mt-2.5">
            <h1 class="m-0 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Request Jasa Saya</h1>
        </div>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">Request yang masih menunggu bisa diubah atau dibatalkan.</p>
    </div>

    @if($requests->isEmpty())
        {{-- p10-3: keadaan kosong --}}
        <div class="min-h-[420px] rounded-3xl bg-white border border-border flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">{{ $total ? 'search_off' : 'assignment' }}</span></span>
            @if($total)
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Tidak ada request yang cocok</div>
                <p class="max-w-[300px] text-[14px] leading-normal text-on-surface-muted">Coba kata kunci lain atau tampilkan semua status request.</p>
                <a href="{{ route('requests.index') }}" class="h-11 px-[22px] mt-2.5 rounded-full border border-border bg-white text-on-surface flex items-center text-[14px] font-semibold">Tampilkan Semua Request</a>
            @else
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada request</div>
                <p class="max-w-[280px] text-[14px] leading-normal text-on-surface-muted">Request yang kamu ajukan ke penyedia jasa akan muncul di sini.</p>
                <a href="{{ route('home') }}" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">search</span>Cari Jasa</a>
            @endif
        </div>
    @else
        {{-- p10-1: kartu request --}}
        <ul class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[18px]" role="list">
            @foreach($requests as $r)
                @php
                    [$bg, $fg, $dot] = $tone[$r->status] ?? $tone['cancelled'];
                    $title = $r->service?->judul ?? 'Jasa sudah dihapus';
                    $prov  = $r->service?->provider?->name ?? 'Penyedia SKENA';
                @endphp
                <li class="bg-white border border-border rounded-3xl shadow-card p-6 flex flex-col gap-3.5">
                    <div class="flex justify-between items-center gap-3">
                        <span class="h-8 px-3.5 rounded-full inline-flex items-center gap-2 text-[13px] font-semibold whitespace-nowrap" style="background:{{ $bg }};color:{{ $fg }}">
                            <span class="w-[7px] h-[7px] rounded-full" style="background:{{ $dot }}"></span>{{ $r->status_label }}
                        </span>
                        <span class="text-[13px] text-on-surface-muted whitespace-nowrap">{{ $tgl($r->created_at) }}</span>
                    </div>
                    <div>
                        <h2 class="text-[18px] leading-[1.28] font-semibold">{{ $title }}</h2>
                        <div class="mt-1 text-[14px] leading-[1.4] text-on-surface-muted">Penyedia: {{ $prov }}</div>
                    </div>
                    <div class="px-3.5 py-3 rounded-[18px] bg-surface text-[14px] leading-normal text-on-surface-variant [overflow-wrap:anywhere]">
                        <span class="font-semibold text-on-surface">Kebutuhan:</span> {{ \Illuminate\Support\Str::limit($r->kebutuhan, 160) }}
                    </div>
                    <div class="text-[13px] leading-[1.45] text-on-surface-muted">
                        Dibutuhkan {{ $tgl($r->tanggal) }}@if($r->catatan) · {{ \Illuminate\Support\Str::limit($r->catatan, 80) }}@endif
                    </div>

                    @if($r->isPending())
                        <div class="grid grid-cols-2 gap-2.5 mt-auto">
                            <a href="{{ route('requests.edit', $r) }}" class="h-11 rounded-full bg-tint text-primary hover:bg-primary hover:text-white flex items-center justify-center text-[14px] font-semibold transition-colors" aria-label="Ubah request {{ $title }}">Ubah</a>
                            <button type="button" class="h-11 rounded-full border border-border bg-white text-[#B91C1C] hover:bg-error-container text-[14px] font-semibold transition-colors"
                                    aria-label="Batalkan request {{ $title }}"
                                    @click="cancel = @js(['name' => $title, 'prov' => $prov, 'url' => route('requests.destroy', $r)])">Batalkan</button>
                        </div>
                    @else
                        <div class="mt-auto text-[13px] leading-[1.45] text-on-surface-muted">
                            @switch($r->status)
                                @case('accepted') Diterima {{ $tgl($r->updated_at) }} · <a href="{{ route('orders.index') }}" class="font-semibold">lanjut ke Pesanan</a> @break
                                @case('rejected') Ditolak penyedia pada {{ $tgl($r->updated_at) }} @break
                                @default Kamu membatalkan pada {{ $tgl($r->updated_at) }}
                            @endswitch
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        @if($requests->hasPages())
            <nav class="flex flex-wrap justify-between items-center gap-3 mt-8 text-[13px] text-on-surface-muted" aria-label="Halaman">
                <span>Menampilkan {{ $requests->count() }} dari {{ $requests->total() }} request</span>
                <div class="flex gap-1.5">
                    @foreach(range(1, $requests->lastPage()) as $p)
                        @if($p === $requests->currentPage())
                            <span class="w-11 h-11 rounded-full bg-primary text-white grid place-items-center font-semibold" aria-current="page">{{ $p }}</span>
                        @else
                            <a href="{{ $requests->url($p) }}" class="w-11 h-11 rounded-full border border-border bg-white grid place-items-center hover:border-secondary">{{ $p }}</a>
                        @endif
                    @endforeach
                </div>
            </nav>
        @endif
    @endif

    {{-- p10-3: modal konfirmasi batal --}}
    <div x-show="cancel" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="cancel = null">
        <div x-trap.noscroll.inert="cancel" role="alertdialog" aria-modal="true" aria-labelledby="cancelReqTitle" aria-describedby="cancelReqDesc"
             class="w-full max-w-[420px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <span class="w-[52px] h-[52px] rounded-full bg-error-container text-[#B91C1C] grid place-items-center"><span class="msr" style="font-size:26px">cancel</span></span>
            <h3 id="cancelReqTitle" class="mt-2.5 text-[20px] leading-[1.3] font-bold">Batalkan request ini?</h3>
            <p id="cancelReqDesc" class="text-[14px] leading-normal text-on-surface-muted">Request "<span x-text="cancel?.name"></span>" ke <span x-text="cancel?.prov"></span> akan dibatalkan dan penyedia diberi tahu.</p>
            <form method="POST" :action="cancel?.url" class="grid grid-cols-1 sm:grid-cols-[1fr_1.3fr] gap-2.5 mt-3.5">
                @csrf @method('DELETE')
                <button type="button" class="h-[46px] rounded-full border border-border bg-white text-on-surface text-[14px] font-bold" @click="cancel = null">Kembali</button>
                <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold" data-loading="Membatalkan…">Batalkan Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
