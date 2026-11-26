@extends('layouts.app')
@section('title', 'Daftar Pesanan — SKENA')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="mb-8">
        <div class="text-sm font-bold tracking-wide text-primary">
            MANAJEMEN TRANSAKSI
        </div>

        <h1 class="mt-2 text-2xl sm:text-3xl font-bold">
            Daftar Pesanan
        </h1>

        <p class="mt-2 text-sm text-on-surface-muted">
            Lihat dan pantau pesanan jasa yang berkaitan dengan akunmu.
        </p>
    </div>

    {{-- Pencarian --}}
    <form method="GET"
          action="{{ route('orders.index') }}"
          class="mb-6 flex flex-col sm:flex-row gap-3">

        <input
            type="search"
            name="q"
            value="{{ $search }}"
            placeholder="Cari nama jasa atau kebutuhan..."
            class="w-full sm:max-w-md rounded-xl border border-border bg-white px-4 py-3 text-sm focus:outline-none focus:border-primary"
        >

        @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif

        <button
            type="submit"
            class="rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-hover"
        >
            Cari
        </button>
    </form>

    {{-- Daftar pesanan --}}
    @forelse($orders as $order)

        @php
            $statusLabels = [
                'pending' => 'Menunggu',
                'in_progress' => 'Sedang Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];

            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'in_progress' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-green-100 text-green-800',
                'cancelled' => 'bg-red-100 text-red-800',
            ];

            $statusLabel = $statusLabels[$order->status]
                ?? ucfirst(str_replace('_', ' ', $order->status));

            $statusColor = $statusColors[$order->status]
                ?? 'bg-gray-100 text-gray-700';
        @endphp

        <div class="mb-5 rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

            {{-- Informasi utama --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                <div>
                    <h2 class="text-lg font-bold">
                        {{ $order->service->judul ?? 'Jasa tidak tersedia' }}
                    </h2>

                    <p class="mt-1 text-sm text-on-surface-muted">
                        Nomor Pesanan: #{{ $order->id }}
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>

            </div>

            {{-- Detail pesanan --}}
            <div class="mt-5 rounded-xl bg-surface p-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="font-semibold">Pencari Jasa</p>
                        <p class="mt-1 text-on-surface-muted">
                            {{ $order->buyer->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold">Penyedia Jasa</p>
                        <p class="mt-1 text-on-surface-muted">
                            {{ $order->service->provider->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold">Harga Akhir</p>
                        <p class="mt-1 text-on-surface-muted">
                            @if($order->harga_final !== null)
                                Rp{{ number_format((float) $order->harga_final, 0, ',', '.') }}
                            @else
                                Belum ditentukan
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold">Tanggal Pesanan</p>
                        <p class="mt-1 text-on-surface-muted">
                            {{ $order->created_at?->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>

                </div>

                @if($order->serviceRequest)
                    <div class="mt-4 border-t border-border pt-4">
                        <p class="font-semibold text-sm">Kebutuhan</p>
                        <p class="mt-1 text-sm leading-relaxed text-on-surface-muted">
                            {{ \Illuminate\Support\Str::limit($order->serviceRequest->kebutuhan, 180) }}
                        </p>
                    </div>
                @endif

            </div>

            {{-- Aksi --}}
            <div class="mt-5">
                <a
                    href="{{ route('orders.show', $order->id) }}"
                    class="inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white hover:bg-primary-hover"
                >
                    Lihat Detail Pesanan
                </a>
            </div>

        </div>

    @empty

        {{-- Tampilan kosong --}}
        <div class="rounded-2xl border border-border bg-white px-6 py-16 text-center">

            <div class="mb-4 text-4xl">
                📦
            </div>

            <h2 class="text-lg font-bold">
                Belum Ada Pesanan
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm text-on-surface-muted">
                Pesanan yang berhasil dibuat akan muncul di halaman ini.
            </p>

        </div>

    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
