
@extends('layouts.app')

@section('title', 'Request Jasa Saya | SKENA')

@push('styles')
<style>
    .request-page {
        padding: 28px 0 56px;
        color: #0F172A;
    }

    .request-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
    }

    .request-kicker {
        color: #3F749C;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .request-heading h1 {
        margin-top: 8px;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
    }

    .request-heading p {
        margin-top: 8px;
        color: #64748B;
        font-size: 14px;
    }

    .request-toolbar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 26px;
    }

    .request-toolbar .search {
        flex: 1 1 260px;
    }

    .request-select {
        min-height: 48px;
        padding: 0 18px;
        border: 1px solid #E2E8F0;
        border-radius: 999px;
        background: #fff;
        color: #475569;
        font: inherit;
        outline: none;
    }

    .request-search-btn {
        min-height: 48px;
        padding: 0 22px;
        border-radius: 999px;
        background: #3F749C;
        color: #fff;
        font-weight: 600;
    }

    .request-search-btn:hover {
        background: #346284;
    }

    .request-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .request-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 24px;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(63,116,156,.10);
    }

    .request-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .request-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .request-status::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pending {
        background: #EEF4F9;
        color: #475569;
    }

    .status-accepted {
        background: #DCFCE7;
        color: #047857;
    }

    .status-rejected {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .status-cancelled {
        background: #F1F5F9;
        color: #64748B;
    }

    .request-date {
        color: #64748B;
        font-size: 12px;
        white-space: nowrap;
    }

    .request-card h3 {
        font-size: 18px;
        font-weight: 600;
        line-height: 1.35;
    }

    .request-provider {
        margin-top: 4px;
        color: #64748B;
        font-size: 13px;
    }

    .request-detail {
        padding: 13px 15px;
        border-radius: 16px;
        background: #F8F9FB;
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .request-detail strong {
        color: #0F172A;
    }

    .request-note {
        color: #64748B;
        font-size: 12px;
        line-height: 1.5;
    }

    .request-empty {
        grid-column: 1 / -1;
        padding: 55px 24px;
        text-align: center;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
    }

    .request-empty h3 {
        margin-top: 12px;
        font-size: 18px;
        font-weight: 600;
    }

    .request-empty p {
        margin-top: 6px;
        color: #64748B;
        font-size: 14px;
    }

    .request-pagination {
        margin-top: 28px;
    }

    @media (max-width: 1000px) {
        .request-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .request-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .request-heading h1 {
            font-size: 26px;
        }

        .request-grid {
            grid-template-columns: 1fr;
        }

        .request-toolbar > * {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

@php
    $statusLabels = [
        'pending' => ['label' => 'Menunggu', 'class' => 'status-pending'],
        'accepted' => ['label' => 'Diterima', 'class' => 'status-accepted'],
        'rejected' => ['label' => 'Ditolak', 'class' => 'status-rejected'],
        'cancelled' => ['label' => 'Dibatalkan', 'class' => 'status-cancelled'],
    ];
@endphp

<section class="request-page">

    {{-- JUDUL HALAMAN --}}
    <div class="request-heading">
        <div>
            <div class="request-kicker">Request Saya · PBI-10</div>
            <h1>Manajemen Request Jasa</h1>
            <p>Request yang masih menunggu bisa diubah atau dibatalkan.</p>
        </div>

        <a href="{{ route('home') }}" class="btn-primary">
            Cari Jasa
            <svg class="ic sm"><use href="#i-right"/></svg>
        </a>
    </div>

    {{-- PENCARIAN DAN FILTER --}}
    <form class="request-toolbar" method="GET" action="{{ route('requests.index') }}">

        <label class="search">
            <span class="sr-only">Cari request</span>
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Cari request atau nama jasa..."
            >
            <svg class="ic"><use href="#i-search"/></svg>
        </label>

        <select name="status" class="request-select">
            <option value="">Semua Status</option>
            @foreach($statusLabels as $value => $statusInfo)
                <option value="{{ $value }}" @selected($status === $value)>
                    {{ $statusInfo['label'] }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="request-search-btn">
            Cari
        </button>

        @if($search || $status)
            <a href="{{ route('requests.index') }}" class="btn-tint">
                Reset Filter
            </a>
        @endif

    </form>

    {{-- DAFTAR REQUEST --}}
    <div class="request-grid">

        @forelse($requests as $item)

            @php
                $currentStatus = $statusLabels[$item->status] ?? [
                    'label' => ucfirst($item->status),
                    'class' => 'status-cancelled',
                ];
            @endphp

            <article class="request-card">

                <div class="request-card-top">
                    <span class="request-status {{ $currentStatus['class'] }}">
                        {{ $currentStatus['label'] }}
                    </span>

                    <span class="request-date">
                        {{ $item->created_at?->format('d M Y') }}
                    </span>
                </div>

                <div>
                    <h3>{{ $item->service?->judul ?? 'Jasa tidak ditemukan' }}</h3>

                    <div class="request-provider">
                        Request jasa dari {{ $item->service?->provider?->name ?? 'Penyedia jasa' }}
                    </div>
                </div>

                <div class="request-detail">
                    <strong>Kebutuhan:</strong>
                    {{ $item->kebutuhan }}
                </div>

                <div class="request-note">
                    <strong>Tanggal dibutuhkan:</strong>
                    {{ $item->tanggal?->format('d M Y') ?? '-' }}
                </div>

                @if($item->catatan)
                    <div class="request-note">
                        <strong>Catatan:</strong>
                        {{ $item->catatan }}
                    </div>
                @endif

            </article>

        @empty

            <div class="request-empty">
                <svg class="ic" style="width:42px;height:42px;color:#3F749C">
                    <use href="#i-clip"/>
                </svg>

                <h3>Belum ada request jasa</h3>

                <p>
                    Request jasa yang kamu ajukan akan muncul di halaman ini.
                </p>

                <a href="{{ route('home') }}" class="btn-primary" style="margin-top:18px">
                    Cari Jasa
                </a>
            </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if($requests->hasPages())
        <div class="request-pagination">
            {{ $requests->links() }}
        </div>
    @endif

</section>

@endsection
