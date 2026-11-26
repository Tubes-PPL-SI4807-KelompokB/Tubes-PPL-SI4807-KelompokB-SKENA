@extends('layouts.app')
@section('title', 'Pesanan — SKENA')

{{--
    PBI-11 Manajemen Pesanan — Claude Design "PBI-11 Pesanan":
    p11-1 daftar pesanan (Sebagai Penyedia / Sebagai Pencari, cari, filter status),
    p11-3 modal tolak pesanan + keadaan kosong. Detail & ubah (p11-2) ada di orders/show.
    Kartu berasal dari dua sumber: request jasa (Baru/Menunggu/Ditolak) dan pesanan.
--}}

@php
    use App\Models\Order;
    use App\Models\ServiceRequest;

    $isProv = $peran === 'penyedia';
    $rp  = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
    $tgl = fn ($d) => $d?->translatedFormat('j M Y');
    $reqTone = [
        ServiceRequest::PENDING  => $isProv ? ['Baru', '#E0ECF6', '#346284', '#3F749C'] : ['Menunggu', '#EEF4F9', '#475569', '#64748B'],
        ServiceRequest::REJECTED => ['Ditolak', '#FEE2E2', '#B91C1C', '#EF4444'],
    ];
    $qs = fn (array $over) => array_filter(array_merge(['peran' => $peran, 'status' => $status === 'semua' ? null : $status, 'q' => $search ?: null], $over));
@endphp

@section('content')
<div x-data="{ reject: null }" @keydown.escape.window="reject = null">

    {{-- Toolbar: peran + cari + filter status --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <nav class="flex p-1 rounded-full bg-white border border-border" aria-label="Lihat pesanan sebagai">
            @foreach(['penyedia' => 'Sebagai Penyedia', 'pencari' => 'Sebagai Pencari'] as $key => $label)
                <a href="{{ route('orders.index', ['peran' => $key]) }}"
                   class="h-[34px] px-[18px] rounded-full text-[14px] font-semibold whitespace-nowrap flex items-center {{ $peran === $key ? 'bg-primary text-white' : 'text-on-surface-muted hover:text-on-surface' }}"
                   @if($peran === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
        <form method="GET" action="{{ route('orders.index') }}" role="search"
              class="h-11 w-full sm:w-[280px] px-5 rounded-full border border-border bg-white flex items-center gap-2.5 focus-within:border-primary">
            <span class="msr text-on-surface-muted">search</span>
            <label for="orderSearch" class="sr-only">Cari pesanan</label>
            <input id="orderSearch" type="search" name="q" value="{{ $search }}" placeholder="Cari pesanan…"
                   class="flex-1 min-w-0 border-0 outline-none bg-transparent text-[15px] text-on-surface placeholder:text-on-surface-muted">
            <input type="hidden" name="peran" value="{{ $peran }}">
            @if($status !== 'semua')<input type="hidden" name="status" value="{{ $status }}">@endif
        </form>
        <nav class="flex gap-2 lg:ml-auto max-w-full overflow-x-auto" aria-label="Filter status pesanan">
            @foreach($filters as $key => $label)
                <a href="{{ route('orders.index', $qs(['status' => $key === 'semua' ? null : $key])) }}"
                   class="h-11 px-5 rounded-full border text-[14px] font-semibold whitespace-nowrap flex items-center {{ $status === $key ? 'bg-primary border-primary text-white' : 'bg-white border-border text-on-surface-muted hover:text-on-surface' }}"
                   @if($status === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
    </div>

    @error('alasan_penolakan')
        <div class="mt-6 flex items-center gap-3 px-[18px] py-3.5 rounded-[18px] bg-error-container text-on-error-container text-[14px] font-semibold" role="alert">
            <span class="msr">error</span>{{ $message }} Klik Tolak lagi pada kartu pesanan.
        </div>
    @enderror

    {{-- Judul --}}
    <div class="pt-10 pb-8">
        <div class="text-[11px] leading-[1.2] font-bold tracking-[0.06em] text-primary">{{ $isProv ? 'PESANAN MASUK' : 'PESANAN SAYA' }}</div>
        <h1 class="mt-2.5 text-[26px] sm:text-[32px] leading-[1.15] tracking-[-0.02em] font-bold">Manajemen Pesanan</h1>
        <p class="mt-2.5 text-[15px] leading-normal text-on-surface-muted">
            {{ $isProv ? 'Terima atau tolak pesanan baru, lalu pantau yang sedang dikerjakan.' : 'Pesanan yang kamu buat ke penyedia jasa.' }}
        </p>
    </div>

    @if($items->isEmpty())
        {{-- p11-3: keadaan kosong --}}
        <div class="min-h-[420px] rounded-3xl bg-white border border-border flex flex-col items-center justify-center text-center gap-2.5 p-8">
            <span class="w-16 h-16 rounded-full bg-tint text-primary grid place-items-center"><span class="msr" style="font-size:30px">{{ $hasAny ? 'search_off' : 'receipt_long' }}</span></span>
            @if($hasAny)
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Tidak ada pesanan yang cocok</div>
                <p class="max-w-[300px] text-[14px] leading-normal text-on-surface-muted">Coba kata kunci lain atau tampilkan semua status pesanan.</p>
                <a href="{{ route('orders.index', ['peran' => $peran]) }}" class="h-11 px-[22px] mt-2.5 rounded-full border border-border bg-white text-on-surface flex items-center text-[14px] font-semibold">Tampilkan Semua Pesanan</a>
            @elseif($isProv)
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada pesanan</div>
                <p class="max-w-[300px] text-[14px] leading-normal text-on-surface-muted">Pesanan dari pencari jasa akan muncul di sini. Pastikan jasamu aktif agar mudah ditemukan.</p>
                <a href="{{ route('services.index') }}" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center text-[14px] font-semibold">Kelola Jasa Saya</a>
            @else
                <div class="mt-2 text-[18px] leading-[1.3] font-semibold">Belum ada pesanan</div>
                <p class="max-w-[300px] text-[14px] leading-normal text-on-surface-muted">Pesanan yang kamu buat ke penyedia jasa akan muncul di sini.</p>
                <a href="{{ route('home') }}" class="h-11 px-[22px] mt-2.5 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center gap-1.5 text-[14px] font-semibold"><span class="msr">search</span>Cari Jasa</a>
            @endif
        </div>
    @else
        {{-- p11-1: kartu pesanan --}}
        <ul class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[18px]" role="list">
            @foreach($items as $item)
                @php
                    $isOrder = $item instanceof Order;
                    $title   = $item->service?->judul ?? 'Jasa sudah dihapus';
                    $who     = $isProv ? ($item->seeker?->name ?? 'Pencari SKENA') : ($item->service?->provider?->name ?? 'Penyedia SKENA');
                    if ($isOrder) {
                        [$bg, $fg, $dot] = $item->status_tone;
                        $label = $item->status_label;
                        $code  = $item->code;
                        $price = $item->final_price;
                        $date  = match ($item->status) {
                            Order::SELESAI    => 'Selesai ' . $tgl($item->updated_at),
                            Order::DIBATALKAN => 'Dibatalkan ' . $tgl($item->updated_at),
                            default           => 'Tenggat ' . ($tgl($item->serviceRequest?->tanggal) ?? '-'),
                        };
                    } else {
                        [$label, $bg, $fg, $dot] = $reqTone[$item->status];
                        $code  = '#REQ-' . str_pad((string) $item->id, 4, '0', STR_PAD_LEFT);
                        $price = $item->service?->harga ?? 0;
                        $date  = $item->isPending()
                            ? ($isProv ? 'Masuk ' . $item->created_at->translatedFormat('j M Y, H.i') . ' WIB' : 'Diajukan ' . $tgl($item->created_at))
                            : 'Ditolak ' . $tgl($item->updated_at);
                    }
                @endphp
                <li class="bg-white border border-border rounded-3xl shadow-card p-6 flex flex-col gap-3.5">
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[13px] font-semibold font-mono text-on-surface-muted">{{ $code }}</span>
                        <span class="h-8 px-3.5 rounded-full inline-flex items-center gap-2 text-[13px] font-semibold whitespace-nowrap" style="background:{{ $bg }};color:{{ $fg }}">
                            <span class="w-[7px] h-[7px] rounded-full" style="background:{{ $dot }}"></span>{{ $label }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-[18px] leading-[1.28] font-semibold">{{ $title }}</h2>
                        <div class="mt-1 text-[14px] leading-[1.4] text-on-surface-muted">{{ $isProv ? 'Pencari' : 'Penyedia' }}: {{ $who }}</div>
                    </div>
                    @if(! $isOrder && $item->isPending())
                        {{-- Isi permintaan agar penyedia bisa memutuskan sebelum Terima/Tolak --}}
                        <div class="px-3.5 py-3 rounded-[18px] bg-surface text-[13px] leading-normal text-on-surface-variant [overflow-wrap:anywhere] flex flex-col gap-1">
                            <div><span class="font-semibold text-on-surface">Kebutuhan:</span> {{ \Illuminate\Support\Str::limit($item->kebutuhan, 160) }}</div>
                            <div><span class="font-semibold text-on-surface">Dibutuhkan:</span> {{ $tgl($item->tanggal) }}</div>
                            @if($item->catatan)<div><span class="font-semibold text-on-surface">Catatan:</span> {{ \Illuminate\Support\Str::limit($item->catatan, 100) }}</div>@endif
                        </div>
                    @endif
                    @if($isOrder && in_array($item->status, [Order::DIKERJAKAN, Order::REVISI], true))
                        @php
                            [$payLabel, $payCls] = match ($item->payment?->status) {
                                \App\Models\Payment::STATUS_LUNAS               => ['Sudah dibayar (Lunas)', 'bg-success-container text-on-success-container'],
                                \App\Models\Payment::STATUS_MENUNGGU_KONFIRMASI => ['Pembayaran menunggu konfirmasi', 'bg-tint text-[#346284]'],
                                \App\Models\Payment::STATUS_DITOLAK             => ['Bukti bayar ditolak — unggah ulang', 'bg-error-container text-on-error-container'],
                                default                                          => ['Belum dibayar', 'bg-[#FEF3C7] text-[#B45309]'],
                            };
                        @endphp
                        <div class="self-start h-7 px-3 rounded-full inline-flex items-center gap-1.5 text-[12px] font-semibold {{ $payCls }}">
                            <span class="msr" style="font-size:16px">payments</span>{{ $payLabel }}
                        </div>
                    @endif
                    @if(! $isOrder && $item->status === ServiceRequest::REJECTED && $item->alasan_penolakan)
                        <div class="px-3.5 py-3 rounded-[18px] bg-surface text-[13px] leading-normal text-on-surface-variant [overflow-wrap:anywhere]">
                            <span class="font-semibold text-on-surface">Alasan:</span> {{ \Illuminate\Support\Str::limit($item->alasan_penolakan, 140) }}
                        </div>
                    @endif
                    <div class="flex justify-between items-end gap-3 pt-3.5 border-t border-border mt-auto">
                        <div>
                            <div class="text-[13px] text-on-surface-muted">{{ $date }}</div>
                            <div class="mt-2 text-[20px] leading-none font-bold">{{ $rp($price) }}</div>
                        </div>
                        @if($isOrder)
                            <a href="{{ route('orders.show', $item) }}" class="w-11 h-11 rounded-full border border-border grid place-items-center text-on-surface hover:border-primary hover:text-primary" aria-label="Lihat detail pesanan {{ $code }} — {{ $title }}">
                                <span class="msr">arrow_outward</span>
                            </a>
                        @endif
                    </div>
                    @if(! $isOrder && $isProv && $item->isPending())
                        <div class="grid grid-cols-2 gap-2.5">
                            <form method="POST" action="{{ route('requests.accept', $item->id) }}">
                                @csrf
                                <button type="submit" class="w-full h-11 rounded-full bg-primary hover:bg-primary-hover text-white text-[14px] font-bold" data-loading="Menerima…" aria-label="Terima pesanan {{ $title }} dari {{ $who }}">Terima</button>
                            </form>
                            <button type="button" class="h-11 rounded-full border border-solid border-border bg-white text-on-surface text-[14px] font-bold hover:border-error hover:text-on-error-container"
                                    aria-label="Tolak pesanan {{ $title }} dari {{ $who }}"
                                    @click="reject = @js(['who' => $who, 'desc' => $title . ' · ' . $rp($price), 'url' => route('requests.reject', $item->id)])">Tolak</button>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        @if($items->hasPages())
            <nav class="flex flex-wrap justify-between items-center gap-3 mt-8 text-[13px] text-on-surface-muted" aria-label="Halaman">
                <span>Menampilkan {{ $items->count() }} dari {{ $items->total() }} pesanan</span>
                <div class="flex gap-1.5">
                    @foreach(range(1, $items->lastPage()) as $p)
                        @if($p === $items->currentPage())
                            <span class="w-11 h-11 rounded-full bg-primary text-white grid place-items-center font-semibold" aria-current="page">{{ $p }}</span>
                        @else
                            <a href="{{ $items->url($p) }}" class="w-11 h-11 rounded-full border border-border bg-white grid place-items-center hover:border-secondary">{{ $p }}</a>
                        @endif
                    @endforeach
                </div>
            </nav>
        @endif
    @endif

    {{-- p11-3: modal tolak pesanan --}}
    <div x-show="reject" x-cloak class="fixed inset-0 z-[52] grid place-items-center p-4" style="background:rgba(15,23,42,0.32)" @click.self="reject = null">
        <div x-trap.noscroll.inert="reject" role="dialog" aria-modal="true" aria-labelledby="rejectTitle" aria-describedby="rejectDesc"
             class="w-full max-w-[460px] bg-white rounded-3xl p-7 flex flex-col gap-2.5" style="box-shadow:0 20px 35px -8px rgba(63,116,156,0.16)">
            <h3 id="rejectTitle" class="text-[20px] leading-[1.3] font-bold">Tolak pesanan dari <span x-text="reject?.who"></span>?</h3>
            <p id="rejectDesc" class="text-[14px] leading-normal text-on-surface-muted"><span x-text="reject?.desc"></span>. Alasan akan dikirim ke pencari jasa.</p>
            <form method="POST" :action="reject?.url" class="flex flex-col gap-2.5">
                @csrf
                <label for="alasanPenolakan" class="mt-2 text-[14px] font-semibold">Alasan penolakan</label>
                <textarea id="alasanPenolakan" name="alasan_penolakan" rows="4" required minlength="5" maxlength="500"
                          placeholder="Contoh: Jadwal saya penuh sampai akhir bulan. Silakan ajukan lagi bulan depan."
                          class="px-[18px] py-3.5 rounded-[20px] border border-border bg-white text-[15px] leading-normal text-on-surface outline-none resize-none focus:border-primary focus:ring-[3px] focus:ring-tint-strong">{{ old('alasan_penolakan') }}</textarea>
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_1.3fr] gap-2.5 mt-2.5">
                    <button type="button" class="h-[46px] rounded-full border border-solid border-border bg-white text-on-surface text-[14px] font-bold" @click="reject = null">Batal</button>
                    <button type="submit" class="h-[46px] rounded-full bg-error text-white text-[14px] font-bold" data-loading="Menolak…">Tolak Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
