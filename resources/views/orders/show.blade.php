@extends('layouts.app')
@section('title', 'Detail Pesanan — SKENA')

@section('content')
<div class="container mx-auto px-4 py-6">

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

        $user = auth()->user();

        $isSeeker = (int) $order->seeker_id === (int) $user->id;
        $isProvider = (int) $order->service->provider_id === (int) $user->id;
    @endphp

    {{-- Tombol kembali --}}
    <div class="mb-6">
        <a
            href="{{ route('orders.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline"
        >
            ← Kembali ke Daftar Pesanan
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <div class="text-sm font-bold tracking-wide text-primary">
            MANAJEMEN TRANSAKSI
        </div>

        <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">
                    Detail Pesanan
                </h1>

                <p class="mt-1 text-sm text-on-surface-muted">
                    Nomor Pesanan: #{{ $order->id }}
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $statusColor }}">
                {{ $statusLabel }}
            </span>

        </div>
    </div>

    {{-- Informasi jasa --}}
    <div class="mb-6 rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

        <h2 class="text-lg font-bold mb-5">
            Informasi Jasa
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-on-surface-muted">
                    Nama Jasa
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->service->judul ?? 'Jasa tidak tersedia' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Penyedia Jasa
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->service->provider->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Harga Awal Jasa
                </p>

                <p class="mt-1 font-semibold">
                    Rp{{ number_format((float) ($order->service->harga ?? 0), 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Harga Akhir Pesanan
                </p>

                <p class="mt-1 font-semibold text-primary">
                    @if($order->harga_final !== null)
                        Rp{{ number_format((float) $order->harga_final, 0, ',', '.') }}
                    @else
                        Belum ditentukan
                    @endif
                </p>
            </div>

        </div>
    </div>

    {{-- Informasi pihak terkait --}}
    <div class="mb-6 rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

        <h2 class="text-lg font-bold mb-5">
            Pihak yang Terlibat
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="rounded-xl bg-surface p-4">
                <p class="text-sm text-on-surface-muted">
                    Pencari Jasa
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->buyer->name ?? '-' }}
                </p>

                @if($isSeeker)
                    <span class="mt-2 inline-block text-xs font-semibold text-primary">
                        Akun kamu
                    </span>
                @endif
            </div>

            <div class="rounded-xl bg-surface p-4">
                <p class="text-sm text-on-surface-muted">
                    Penyedia Jasa
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->service->provider->name ?? '-' }}
                </p>

                @if($isProvider)
                    <span class="mt-2 inline-block text-xs font-semibold text-primary">
                        Akun kamu
                    </span>
                @endif
            </div>

        </div>
    </div>

    {{-- Informasi request awal --}}
    <div class="mb-6 rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

        <h2 class="text-lg font-bold mb-5">
            Informasi Request Jasa
        </h2>

        @if($order->serviceRequest)

            <div class="space-y-4">

                <div>
                    <p class="text-sm font-semibold">
                        Kebutuhan
                    </p>

                    <p class="mt-1 text-sm leading-relaxed text-on-surface-muted whitespace-pre-line">
                        {{ $order->serviceRequest->kebutuhan }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold">
                        Tanggal Dibutuhkan
                    </p>

                    <p class="mt-1 text-sm text-on-surface-muted">
                        {{ $order->serviceRequest->tanggal?->format('d F Y') ?? '-' }}
                    </p>
                </div>

                @if($order->serviceRequest->catatan)
                    <div>
                        <p class="text-sm font-semibold">
                            Catatan Tambahan
                        </p>

                        <p class="mt-1 text-sm text-on-surface-muted whitespace-pre-line">
                            {{ $order->serviceRequest->catatan }}
                        </p>
                    </div>
                @endif

            </div>

        @else

            <p class="text-sm text-on-surface-muted">
                Informasi request awal tidak tersedia.
            </p>

        @endif

    </div>

    {{-- Informasi pesanan --}}
    <div class="rounded-2xl border border-border bg-white p-5 sm:p-6 shadow-sm">

        <h2 class="text-lg font-bold mb-5">
            Informasi Pesanan
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-on-surface-muted">
                    Nomor Pesanan
                </p>

                <p class="mt-1 font-semibold">
                    #{{ $order->id }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Status Pesanan
                </p>

                <p class="mt-1 font-semibold">
                    {{ $statusLabel }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Dibuat Pada
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-on-surface-muted">
                    Terakhir Diperbarui
                </p>

                <p class="mt-1 font-semibold">
                    {{ $order->updated_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

        </div>

        <div class="mt-6 rounded-xl bg-blue-50 p-4">
            <p class="text-sm leading-relaxed text-blue-800">
                Pesanan ini dibuat setelah request jasa diterima oleh penyedia.
                Harga akhir dapat ditentukan melalui proses negosiasi.
            </p>
        </div>

    </div>

</div>
@endsection
