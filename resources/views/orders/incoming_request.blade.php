@extends('layouts.app')

@section('title', 'Request Masuk — SKENA')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="mb-8">
        <div class="text-sm font-bold tracking-wide text-primary">
            MANAJEMEN PESANAN
        </div>

        <h1 class="mt-2 text-2xl sm:text-3xl font-bold">
            Request Masuk
        </h1>

        <p class="mt-2 text-sm text-on-surface-muted">
            Lihat pengajuan jasa dari pengguna dan pilih untuk menerima atau menolaknya.
        </p>
    </div>

    {{-- Pencarian --}}
    <form method="GET"
          action="{{ route('orders.incoming') }}"
          class="mb-6 flex flex-col sm:flex-row gap-3">

        <input
            type="search"
            name="q"
            value="{{ $search }}"
            placeholder="Cari nama pengguna, jasa, atau kebutuhan..."
            class="w-full sm:max-w-md rounded-xl border border-border bg-white px-4 py-3 text-sm focus:outline-none focus:border-primary"
        >

        <button
            type="submit"
            class="rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-hover"
        >
            Cari
        </button>
    </form>

    {{-- Daftar request --}}
    @forelse ($requests as $item)

        <div class="mb-5 rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

            {{-- Informasi jasa --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                <div>
                    <h2 class="text-lg font-bold">
                        {{ $item->service->judul ?? 'Jasa tidak tersedia' }}
                    </h2>

                    <p class="mt-1 text-sm text-on-surface-muted">
                        Pemohon: {{ $item->seeker->name ?? 'Pengguna SKENA' }}
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                    Menunggu
                </span>

            </div>

            {{-- Detail kebutuhan --}}
            <div class="mt-5 rounded-xl bg-surface p-4">

                <p class="text-sm font-semibold mb-2">
                    Kebutuhan:
                </p>

                <p class="text-sm leading-relaxed text-on-surface-variant whitespace-pre-line">
                    {{ $item->kebutuhan }}
                </p>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">

                    <div>
                        <span class="font-semibold">Tanggal dibutuhkan:</span>
                        <p class="mt-1 text-on-surface-muted">
                            {{ $item->tanggal?->format('d M Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span class="font-semibold">Harga jasa:</span>
                        <p class="mt-1 text-on-surface-muted">
                            Rp{{ number_format((float) ($item->service->harga ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                </div>

                @if ($item->catatan)
                    <div class="mt-4">
                        <p class="text-sm font-semibold">Catatan tambahan:</p>
                        <p class="mt-1 text-sm text-on-surface-muted whitespace-pre-line">
                            {{ $item->catatan }}
                        </p>
                    </div>
                @endif

            </div>

            {{-- Informasi pengajuan --}}
            <p class="mt-4 text-xs text-on-surface-muted">
                Diajukan pada {{ $item->created_at?->format('d M Y, H:i') }}
            </p>

            {{-- Tombol aksi --}}
            <div class="mt-5 flex flex-col sm:flex-row gap-3">

                <form
                    method="POST"
                    action="{{ route('requests.accept', $item->id) }}"
                    class="w-full sm:w-auto"
                    onsubmit="return confirm('Apakah kamu yakin ingin menerima request ini? Pesanan akan dibuat secara otomatis.')"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-hover"
                    >
                        Terima Request
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('requests.reject', $item->id) }}"
                    class="w-full sm:w-auto"
                    onsubmit="return confirm('Apakah kamu yakin ingin menolak request ini?')"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-full border border-red-300 bg-white px-6 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                    >
                        Tolak Request
                    </button>
                </form>

            </div>

        </div>

    @empty

        {{-- Tampilan ketika belum ada request --}}
        <div class="rounded-2xl border border-border bg-white px-6 py-16 text-center">

            <div class="text-4xl mb-4">
                📩
            </div>

            <h2 class="text-lg font-bold">
                Belum Ada Request Masuk
            </h2>

            <p class="mt-2 text-sm text-on-surface-muted">
                Pengajuan jasa dari pengguna akan muncul di halaman ini.
            </p>

        </div>

    @endforelse

    {{-- Pagination --}}
    @if ($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif

</div>
@endsection
